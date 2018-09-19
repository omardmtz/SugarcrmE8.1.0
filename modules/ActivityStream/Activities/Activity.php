<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */


class Activity extends Basic
{
    public $table_name = 'activities';
    public $object_name = 'Activity';
    public $module_name = 'Activities';
    public $module_dir = 'ActivityStream/Activities';

    public $id;
    public $name = '';
    public $date_entered;
    public $date_modified;
    public $parent_id;
    public $parent_type;
    public $activity_type = 'post';
    public $data = '{}';
    public $last_comment = '{}';
    public $last_comment_bean;
    public $comment_count;
    public $created_by;
    public $created_by_name;

    /**
     * Disable Custom Field lookup since Activity Streams don't support them
     *
     * @var bool
     */
    public $disable_custom_fields = true;

    public static $enabled = true;

    private static $previousStates = [];

    /**
     * Constructor for the Activity bean.
     *
     * Override SugarBean's constructor so that we can create a comment bean as
     * a property of this object.
     */
    public function __construct()
    {
        parent::__construct();
        $this->last_comment_bean = BeanFactory::newBean('Comments');
    }

    /**
     * Retrieves the Activity specified.
     *
     * SugarBean's signature states that encode is true by default. However, as
     * we store JSON data, we want to modify that behaviour to be false so that
     * the JSON data does not have characters replaced by HTML entities.
     * @param  string  $id      GUID of the Activity record
     * @param  boolean $encode  Encode quotes and other special characters
     * @param  boolean $deleted Flag to allow retrieval of deleted records
     * @return Activity
     */
    public function retrieve($id = '-1', $encode = false, $deleted = true)
    {
        // TODO: Fix this after ENGRD-17 is resolved.
        $encode = false;
        parent::retrieve($id, $encode, $deleted);
        $this->last_comment_bean->populateFromRow(json_decode($this->last_comment, true));
        return $this;
    }

    /**
     * Adds a comment to the activity, handling the denormalized columns.
     * @param Comment $comment
     * @return bool
     */
    public function addComment(Comment $comment)
    {
        if ($this->id && $comment->id && $comment->parent_id == $this->id) {
            $this->comment_count++;
            $this->last_comment_bean = $comment;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Removes a comment from the activity, handling the denormalized columns.
     * @param  string $comment_id ID of the comment being deleted.
     * @return void
     */
    public function deleteComment($comment_id)
    {
        if ($comment_id && $this->id) {
            $comment = BeanFactory::getBean("Comments", $comment_id);
            if ($comment->parent_id == $this->id) {
                $comment->mark_deleted($comment_id);
                $this->comment_count--;
                $this->load_relationship('comments');
                $params = array('limit' => 1, 'orderby' => 'date_entered DESC');
                $linkResult = $this->comments->query($params);
                $last_comment_id = null;
                $linkResult = array_keys($linkResult['rows']);
                if (count($linkResult)) {
                    $last_comment_id = $linkResult[0];
                }
                $this->last_comment_bean = BeanFactory::getBean('Comments', $last_comment_id);
                $this->save();
            }
        }
    }

    /**
     * Saves the current activity.
     * @param  boolean $check_notify
     * @return string|bool ID of the new post or false
     */
    public function save($check_notify = false)
    {
        if (!Activity::isEnabled()) {
            return $this->id;
        }

        $isUpdate = !(empty($this->id) || $this->new_with_id);

        $this->data = $this->getDataArray();
        $this->data = $this->processDataWithHtmlPurifier($this->activity_type, $this->data);

        if ($this->activity_type == 'post' || $this->activity_type == 'attach') {
            if (!isset($this->data['object']) && !empty($this->parent_type)) {
                $parent = BeanFactory::retrieveBean($this->parent_type, $this->parent_id);
                if ($parent && !is_null($parent->id)) {
                    $this->data['object'] = ActivityQueueManager::getBeanAttributes($parent);
                } else {
                    $this->data['module'] = $this->parent_type;
                }
            }

            if (!$isUpdate) {
                $this->processEmbed();
            }
        }

        $this->data = $this->getDataString();
        $this->last_comment = $this->last_comment_bean->toJson();

        $return = parent::save($check_notify);

        if (($this->activity_type === 'post' || $this->activity_type === 'attach') && !$isUpdate) {
            $this->processPostSubscription();
            $this->processPostTags();
        }

        return $return;
    }

    /**
     * Helper to retrieve a representation of the data property in string format
     * @return string
     */
    protected function getDataString()
    {
        if (is_string($this->data)) {
            return $this->data;
        } else {
            return json_encode($this->data);
        }
    }

    /**
     * Helper to retrieve a representation of the data property in array format
     * @return array
     */
    protected function getDataArray()
    {
        if (is_array($this->data)) {
            return $this->data;
        } else {
            return json_decode($this->data, true);
        }
    }

    /**
     * Helper to retrieve the parent bean of this activity
     * @return null|SugarBean
     */
    protected function getParentBean()
    {
        if (empty($this->parent_type)) {
            return null;
        }

        if (empty($this->parent_id)) {
            return BeanFactory::newBean($this->parent_type);
        }

        $ignoreDeleted = true;
        $beanParams = array();
        if ($this->activity_type === 'delete') {
            $ignoreDeleted = false;
            $beanParams['disable_row_level_security'] = true;
        }
        return BeanFactory::retrieveBean($this->parent_type, $this->parent_id, $beanParams, $ignoreDeleted);
    }

    /**
     * Retrieve the list of changed fields for this activity that the user has ACL permissions to see
     * @param User $user
     * @param SugarBean $bean
     * @return array List of fields the user has ACL permission to see
     */
    protected function getChangedFieldsForUser(User $user, SugarBean $bean)
    {
        $fields = array();
        $activityData = $this->getDataArray();
        if ($this->activity_type === 'update' && isset($activityData['changes'])) {
            foreach ($activityData['changes'] as $field) {
                $fields[$field['field_name']] = 1;
            }
            $context = array('user' => $user);
            $bean->ACLFilterFieldList($fields, $context);
            $fields = array_keys($fields);
        }
        return $fields;
    }

    /**
     * Retrieve embed info about a link and merge it into the data array
     * Makes the assumption that data property is in array format
     */
    protected function processEmbed()
    {
        if (!empty($this->data['value'])) {
            $service = new EmbedLinkService();
            $val = $service->get($this->data['value']);
            if (!empty($val)) {
                $this->data = array_merge($this->data, $val);
            }
        }
    }

    /**
     * Helper for processing tags directly on a post
     * Links the activity to the appropriate tagged record(s)
     */
    protected function processPostTags()
    {
        $data = $this->getDataArray();
        if (isset($data['tags'])) {
            $this->processTags($data['tags']);
        }
    }

    /**
     * Helper for processing tags and linking the activity to the appropriate tagged record(s)
     * This is used for tags both directly on a post or in comments
     * @param array $tags
     */
    public function processTags(array $tags)
    {
        foreach ($tags as $tag) {
            //if tag is a User and the activity has a parent, we need to check access
            if ($tag['module'] === 'Users' && !empty($this->parent_id)) {
                $this->processUserRelationships(array($tag['id']));
            } elseif ($tag['module'] !== 'Users' || $this->userHasViewAccessToParentModule($tag['id'])) {
                $bean = BeanFactory::retrieveBean($tag['module'], $tag['id']);
                $this->processRecord($bean);
            }
        }
    }

    /**
     * Check if the user has view access to the parent module
     * Return true if no parent module
     * @param string $userId
     * @return boolean
     */
    protected function userHasViewAccessToParentModule($userId)
    {
        if (empty($this->parent_type)) {
            return true;
        }
        $aclActionBeanName = BeanFactory::getBeanClass('ACLActions');
        return $aclActionBeanName::userHasAccess($userId, $this->parent_type, 'view');
    }

    /**
     * Helper for processing record activities.
     * @param  SugarBean $bean
     */
    public function processRecord(SugarBean $bean)
    {
        if (ActivityQueueManager::isEnabledForModule($bean->module_name) && $bean->load_relationship('activities')) {
            $bean->activities->add($this);
        }
    }

    /**
     * Helper for creating the relationship between the activity and a given list of users
     * Checks access (module, record, and field level) first
     * @param array $userIds
     * @return boolean
     */
    public function processUserRelationships(array $userIds = array())
    {
        if (!$this->load_relationship('activities_users')) {
            $GLOBALS['log']->error('Could not load the activity/user relationship.');
            return false;
        }
        $deleteActivity = ($this->activity_type === 'delete');
        $bean = $this->getParentBean();
        foreach ($userIds as $userId) {
            $user = BeanFactory::retrieveBean('Users', $userId);
            if ($user && $bean) {
                $this->handleUserToBeanRelationship($bean, $user, $deleteActivity);
            }
        }
        return true;
    }

    /**
     * Associates the user to the activity, if it is a delete activity or the user can access the record. Otherwise, the
     * the user's subscription to the record is removed.
     *
     * We allow the user to see the activity on home and list views if the user has access to the bean.
     *
     * @param SugarBean $bean
     * @param User $user
     * @param bool $isADeleteActivity
     */
    protected function handleUserToBeanRelationship(SugarBean $bean, User $user, $isADeleteActivity = false)
    {
        if ($isADeleteActivity || $bean->checkUserAccess($user)) {
            $fields = $this->getChangedFieldsForUser($user, $bean);
            $this->activities_users->add($user, array('fields' => json_encode($fields)));
        } else {
            Subscription::unsubscribeUserFromRecord($user, $bean);
        }
    }

    /**
     * Helper for processing subscriptions on a post activity.
     */
    protected function processPostSubscription()
    {
        if (isset($this->parent_type) && isset($this->parent_id)) {
            $bean = BeanFactory::getBean($this->parent_type, $this->parent_id);
            $subscriptionsBeanName = BeanFactory::getBeanClass('Subscriptions');
            $this->processRecord($bean);
            $subscriptionsBeanName::processSubscriptions($bean, $this, array());
        } else {
            $globalTeam = BeanFactory::getBean('Teams', '1');
            if ($this->load_relationship('activities_teams')) {
                $this->activities_teams->add($globalTeam, array('fields' => '[]'));
            }
        }
    }

    /**
     * Removes harmful html tags from data using html purifier
     * @param $activityType string
     * @param $data array
     * @return array data
     */
    public function processDataWithHtmlPurifier($activityType, $data = array())
    {
        if ($activityType === 'post' && !empty($data['value'])) {
            $data['value'] = SugarCleaner::cleanHtml($data['value']);
        }

        return $data;
    }

    /**
     * Overwrite the notifications handler.
     */
    public function _sendNotifications($check_notify)
    {
        return false;
    }

    public function get_notification_recipients()
    {
        return array();
    }

    public function save_relationship_changes($is_update, $exclude = array())
    {
    }

    public static function enable()
    {
        static::$previousStates[] = static::$enabled;
        static::$enabled = true;
    }

    public static function disable()
    {
        static::$previousStates[] = static::$enabled;
        static::$enabled = false;
    }

    public static function isEnabled()
    {
        return self::$enabled;
    }

    /*
     * Resets the state of Activity Streams to the previous state.
     * If no previous state then it resets to the activity_streams_enabled configuration
     */
    public static function restoreToPreviousState()
    {
        $state = array_pop(static::$previousStates);

        if (!is_null($state)) {
            static::$enabled = $state;
        } else {
            static::$enabled = $GLOBALS['sugar_config']['activity_streams_enabled'];
        }
    }
}
