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
class Subscription extends Basic
{
    public $table_name = 'subscriptions';
    public $object_name = 'Subscription';
    public $module_name = 'Subscriptions';
    public $module_dir = 'ActivityStream/Subscriptions';

    public $id;
    public $name = '';
    public $parent_type;
    public $parent_id;
    public $deleted;
    public $active;
    public $date_entered;
    public $date_modified;
    public $created_by;
    public $created_by_name;

    /**
     * Disable Custom Field lookup since Activity Streams don't support them
     *
     * @var bool
     */
    public $disable_custom_fields = true;

    /**
     * Gets the subscribed users for the record specified.
     * @param  SugarBean $record
     * @param  string    $type   Return type for data
     * @param Array $params
     *        disable_row_level_security
     * @return mixed
     */
    public static function getSubscribedUsers(SugarBean $record, $type = 'array', $params = array())
    {
        $query = self::getQueryObject($params);
        $query->select(array('created_by'));
        $query->where()->equals('parent_id', $record->id);
        $query->where()->equals('parent_type', $record->module_name);

        return $query->execute($type);
    }

    /**
     * Gets the subscribed records for the user specified.
     * @param  User   $user
     * @param  string $type Return type for data
     * @param Array $params
     *        disable_row_level_security
     * @return mixed
     */
    public static function getSubscribedRecords(User $user, $type = 'array', $params = array())
    {
        $query = self::getQueryObject($params);
        $query->select(array('parent_id', 'parent_type'));
        $query->where()->equals('created_by', $user->id);

        return $query->execute($type);
    }

    /**
     * Checks whether the specified user is subscribed to the given record.
     * @param  User      $user
     * @param  SugarBean $record
     * @param Array $params
     *        disable_row_level_security
     * @return string|null       GUID of subscription or null
     */
    public static function checkSubscription(User $user, SugarBean $record, $params = array())
    {
        $query = self::getQueryObject($params);
        $query->select(array('id', 'deleted'));
        $query->where()->equals('parent_id', $record->id);
        $query->where()->equals('created_by', $user->id);
        $result = $query->execute();
        if (count($result)) {
            return $result[0];
        }
        return null;
    }

    /**
     * Checks which of the given records the specified user is subscribed to.
     * @param  User  $user
     * @param  array $records An array of associative arrays (not SugarBeans).
     * @param Array $params
     *        disable_row_level_security
     * @return array Associative array where keys are IDs of the record a user
     * is subscribed to.
     */
    public static function checkSubscriptionList(User $user, array $records, $params = array())
    {
        $return = array();
        // Plucks IDs of records passed in.
        $ids = array_map(
            function ($record) {
                if (!isset($record['id'])) {
                    $GLOBALS['log']->error("Attempting to check for a subscription with a null ID");
                    return;
                }
                return $record['id'];
            },
            $records
        );

        if (!empty($ids)) {
            $query = self::getQueryObject($params);
            $query->select(array('parent_id'));
            $query->where()->in('parent_id', $ids);
            $query->where()->equals('created_by', $user->id);
            $result = $query->execute();
            foreach ($result as $row) {
                $return[$row['parent_id']] = !empty($record['id']) ? $record['id'] : false;
            }
        }

        return $return;
    }

    /**
     * Retrieve the subscription bean for a user-record relationship.
     * @param  User      $user
     * @param  SugarBean $record
     * @param Array $params
     *        disable_row_level_security
     * @return Subscription|null
     */
    public static function getSubscription(User $user, SugarBean $record, $params = array())
    {
        $sub = self::checkSubscription($user, $record, $params);
        if (!empty($sub['id'])) {
            return BeanFactory::retrieveBean('Subscriptions', $sub['id']);
        }
        return null;
    }

    /**
     * Adds a user subscription to a record if one doesn't already exist.
     * @param  User      $user
     * @param  SugarBean $record
     * @param Array $params
     *        disable_row_level_security
     * @return string|bool       GUID of the subscription or false.
     */
    public static function subscribeUserToRecord(User $user, SugarBean $record, $params = array())
    {
        $params['add_deleted'] = false;
        $sub = self::checkSubscription($user, $record, $params);
        if (!$sub) {
            $seed = BeanFactory::newBean('Subscriptions');
            $seed->parent_type = $record->module_name;
            $seed->parent_id = $record->id;
            $seed->set_created_by = false;
            $seed->created_by = $user->id;
            return $seed->save();
        }
        else {
            if (!empty($sub['deleted'])) {
                $seed = BeanFactory::retrieveBean('Subscriptions', $sub['id'], array(), false);
                if ($seed) {
                    $seed->mark_undeleted($seed->id);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Removes a user subscription to a record.
     * @param  User      $user
     * @param  SugarBean $record
     * @return bool
     */
    public static function unsubscribeUserFromRecord(User $user, SugarBean $record)
    {
        $sub = self::getSubscription($user, $record);
        if ($sub) {
            $sub->mark_deleted($sub->id);
            return true;
        }
        return false;
    }

    /**
     * Returns a query object for subscription queries.
     * @param Array $params
     *        disable_row_level_security
     * @return SugarQuery
     */
    protected static function getQueryObject($params = array())
    {
        $subscription = BeanFactory::newBean('Subscriptions');
        // Pro+ versions able to override visibility on subscriptions (Portal)
        // to allow Contact change activity messages to be linked to subscribers
        if (!empty($params['disable_row_level_security'])) {
            $subscription->disable_row_level_security = true;
        }
        $query = new SugarQuery();
        $query->from($subscription, !isset($params['add_deleted']) ? array() : array('add_deleted' => $params['add_deleted']));
        if (!isset($params['add_deleted'])) {
            $query->where()->equals('deleted', '0');
        }
        if (!empty($params['limit'])) {
            $query->limit($params['limit'] + 1);
        }
        if (!empty($params['offset'])) {
            $query->offset($params['offset']);
        }
        return $query;
    }

    /**
     * Override mark_deleted().
     */
    public function mark_deleted($id)
    {
        $this->deleted = 1;
        $this->save();
    }

    /**
     * Adds an activities_users relationship between the activity and all users specified in the data array
     * @param array $data
     */
    public static function addActivitySubscriptions(array $data)
    {
        $activity = BeanFactory::retrieveBean('Activities', $data['act_id']);
        $userIds = array();
        foreach ($data['user_partials'] as $userPartial) {
            $userIds[] = $userPartial['created_by'];
        }
        $activity->processUserRelationships($userIds);
    }

    /**
     * Helper for processing subscriptions on a bean-related activity.
     *
     * @param  SugarBean $bean
     * @param  Activity  $act
     * @param  array     $args
     * @param  array     $params
     */
    public static function processSubscriptions(SugarBean $bean, Activity $act, array $args, $params = array())
    {
        $userPartials          = self::getSubscribedUsers($bean, 'array', $params);
        $data                  = array(
            'act_id'        => $act->id,
            'args'          => $args,
            'user_partials' => $userPartials,
        );
        if (count($userPartials) < 5) {
            self::addActivitySubscriptions($data);
        } else {
            $job                   = BeanFactory::newBean('SchedulersJobs');
            $job->requeue          = 1;
            $job->name             = "ActivityStream add";
            $job->data             = serialize($data);
            $job->target           = "class::SugarJobAddActivitySubscriptions";
            $job->assigned_user_id = $GLOBALS['current_user']->id;
            $queue                 = new SugarJobQueue();
            $queue->submitJob($job);
        }
    }
}
