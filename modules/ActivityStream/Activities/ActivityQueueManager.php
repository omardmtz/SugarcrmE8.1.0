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

/**
 * Queue class for activity stream events.
 *
 * @api
 */
class ActivityQueueManager
{
    public static $linkBlacklist = array('user_sync', 'activities', 'contacts_sync', 'kbusefulness', 'usefulness');
    public static $linkModuleBlacklist = array('ActivityStream/Activities', 'ACLRoles', 'Teams',
        'KBArticles', 'KBDocuments', 'pmse_Project/pmse_BpmProcessDefinition',
    );
    public static $linkDupeCheck = array();
    public static $moduleBlacklist = array('OAuthTokens', 'SchedulersJobs',
        'Activities', 'vCals', 'KBArticles', 'KBDocuments',
        'Forecasts', 'ForecastWorksheets', 'ForecastManagerWorksheets', 'Notifications',
        'Quotes', //Quotes should not allow admin to enable activities until Quotes are converted to sidecar
    );
    public static $moduleWhitelist = array('Notes', 'Tasks', 'Meetings', 'Calls', 'Emails', 'Home');

    /**
     * Logic hook arbiter for activity streams.
     *
     * @param  SugarBean $bean
     * @param  string    $event
     * @param  array     $args
     */
    public function eventDispatcher(SugarBean $bean, $event, $args)
    {
        if (Activity::isEnabled()) {
            $activity       = BeanFactory::newBean('Activities');
            $eventTriggered = false;
            if ($event == 'after_save' && self::isEnabledForModule($bean->getModuleName())) {
                $eventTriggered = $this->createOrUpdate($bean, $args, $activity);
            } elseif ($event == 'after_relationship_add' && $this->isValidLink($args)) {
                $eventTriggered = $this->link($args, $activity);
            } elseif ($event == 'after_relationship_delete' && $this->isValidLink($args)) {
                $eventTriggered = $this->unlink($args, $activity);
            }

            // Add the job queue process to add rows to the activities_users
            // join table. This has been moved to the job queue as it's a
            // potentially slow operation.
            if ($eventTriggered) {
                $subscriptionsBeanName = BeanFactory::getBeanClass('Subscriptions');
                $subscriptionsBeanName::processSubscriptions($bean, $activity, $args, array('disable_row_level_security' => true));
            }
        }
    }

    /**
     * Checks whether a specific module is activity enabled
     * Does not verify whether activities are enabled across the app
     * Use Activity::isEnabled() to check activity status across the app
     *
     * @param $moduleName
     * @return bool returns true if bean exists and both audit and activity enabled
     */
    public static function isEnabledForModule($moduleName)
    {
        if (!Activity::isEnabled()) {
            return false;
        }

        if (in_array($moduleName, self::$moduleBlacklist)) {
            return false;
        }
        if (in_array($moduleName, self::$moduleWhitelist)) {
            return true;
        }

        $bean = BeanFactory::newBean($moduleName);
        return ($bean instanceof SugarBean) && $bean->isActivityEnabled() && $bean->is_AuditEnabled();
    }

    /**
     * Helper to determine whether an activity can be created for a link.
     *
     * @param array $args
     * @return boolean
     */
    protected function isValidLink(array $args)
    {
        if (SugarBean::inOperation('saving_related')) {
            return false;
        }
        $blacklist  = in_array($args['link'], self::$linkBlacklist);
        $lhs_module = in_array($args['module'], self::$linkModuleBlacklist);
        $rhs_module = in_array($args['related_module'], self::$linkModuleBlacklist);
        if ($blacklist || $lhs_module || $rhs_module) {
            return false;
        } else {
            foreach (self::$linkDupeCheck as $dupe_args) {
                if ($dupe_args['relationship'] == $args['relationship']) {
                    if (self::isLinkDupe($args, $dupe_args)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Helper to check if a link or unlink activity is a duplicate.
     * @param  array $args1
     * @param  array $args2
     * @return bool
     */
    protected static function isLinkDupe($args1, $args2)
    {
        if ($args1['module'] == $args2['related_module'] && $args1['id'] == $args2['related_id']) {
            return true;
        }
        if ($args1['module'] == $args2['module'] && $args1['id'] == $args2['id'] &&
            $args1['related_id'] == $args2['related_id']) {
            return true;
        }
        return false;
    }

    /**
     * Handler for create and update actions on a bean.
     *
     * @param SugarBean $bean
     * @param array     $args
     * @param Activity  $act
     * @return bool     eventProcessed
     */
    protected function createOrUpdate(SugarBean $bean, array $args, Activity $act)
    {
        if ($bean->deleted) {
            return false;
        }

        // Add Appropriate Subscriptions for this Bean
        $this->addRecordSubscriptions($args, $bean);

        $data = array(
            'object' => self::getBeanAttributes($bean),
        );
        if ($args['isUpdate']) {
            $act->activity_type = 'update';
            $data['changes']    = $args['dataChanges'];
            $this->prepareChanges($bean, $data);

            //if no field changes to report, do not create the activity
            if (empty($data['changes'])) {
                return false;
            }
        } else {
            $act->activity_type = 'create';
        }

        $act->parent_id   = $bean->id;
        $act->parent_type = $bean->module_name;
        $act->data        = $data;
        $act->save();
        $act->processRecord($bean);
        return true;
    }

    /**
     * Handler for link actions on two beans.
     *
     * @param  array    $args
     * @param  Activity $act
     * @return bool     eventProcessed
     */
    protected function link(array $args, Activity $act)
    {
        if (empty($args['id']) || empty($args['related_id'])) {
            return false;
        }
        $lhs                = BeanFactory::getBean($args['module'], $args['id']);
        $rhs                = BeanFactory::getBean($args['related_module'], $args['related_id']);

        // Submodules break this paradigm pretty badly, so until there is a mechanism
        // to handle $module_dir = $path/$sub_path this will have to do.
        // @see SugarRelationship::getCustomLogicArguments() as well
        if (!$lhs || !$rhs) {
            // Get the logger so we can inform the system
            $log = LoggerManager::getLogger();

            if (!$lhs) {
                $log->warn("Could not get LHS bean for {$args['module']}->id = {$args['id']}");
            }

            if (!$rhs) {
                $log->warn("Could not get RHS bean for {$args['related_module']}->id = {$args['related_id']}");
            }

            return false;
        }

        if (empty($lhs->name) && !empty($args['name'])) {
            $lhs->name = $args['name'];
        }
        if (empty($lhs->id) && !empty($args['id'])) {
            $lhs->id = $args['id'];
        }
        if (empty($rhs->name) && !empty($args['related_name'])) {
            $rhs->name = $args['related_name'];
        }
        if (empty($rhs->id) && !empty($args['related_id'])) {
            $rhs->id = $args['related_id'];
        }
        $data               = array(
            'object'       => self::getBeanAttributes($lhs),
            'subject'      => self::getBeanAttributes($rhs),
            'link'         => $args['link'],
            'relationship' => $args['relationship'],
        );
        $act->activity_type = 'link';
        $act->parent_id     = $lhs->id;
        $act->parent_type   = $lhs->module_name;
        $act->data          = $data;
        $act->save();

        self::$linkDupeCheck[] = $args;
        $act->processRecord($lhs);
        $act->processRecord($rhs);
        return true;
    }

    /**
     * Handler for unlink actions on two beans.
     *
     * @param  array    $args [description]
     * @param  Activity $act  [description]
     * @return bool     eventProcessed
     */
    protected function unlink(array $args, Activity $act)
    {
        if (empty($args['id']) || empty($args['related_id'])) {
            return false;
        }
        $lhs                = BeanFactory::getBean($args['module'], $args['id']);
        $rhs                = BeanFactory::getBean($args['related_module'], $args['related_id']);

        // Submodules break this paradigm pretty badly, so until there is a mechanism
        // to handle $module_dir = $path/$sub_path this will have to do.
        // @see SugarRelationship::getCustomLogicArguments() as well
        if (!$lhs || !$rhs) {
            // Get the logger so we can inform the system
            $log = LoggerManager::getLogger();

            if (!$lhs) {
                $log->warn("Could not get LHS bean for {$args['module']}->id = {$args['id']}");
            }

            if (!$rhs) {
                $log->warn("Could not get RHS bean for {$args['related_module']}->id = {$args['related_id']}");
            }

            return false;
        }

        $data               = array(
            'object'       => self::getBeanAttributes($lhs),
            'subject'      => self::getBeanAttributes($rhs),
            'link'         => $args['link'],
            'relationship' => $args['relationship'],
        );
        $act->activity_type = 'unlink';
        $act->parent_id     = $lhs->id;
        $act->parent_type   = $lhs->module_name;
        $act->data          = $data;
        $act->save();

        self::$linkDupeCheck[] = $args;
        $act->processRecord($lhs);
        $act->processRecord($rhs);
        return true;
    }

    /**
     * Helper to denormalize critical bean attributes.
     *
     * @param  SugarBean $bean
     *
     * @return array     Contains name, type, module and ID of the bean.
     */
    public static function getBeanAttributes(SugarBean $bean)
    {
        return array(
            'name'   => $bean->get_summary_text(),
            'type'   => $bean->object_name,
            'module' => $bean->module_name,
            'id'     => $bean->id,
        );
    }

    /**
     * Clears $linkDupeCheck property.
     * Used mainly for daemon scripts.
     */
    public static function resetDuplicateCheck()
    {
        self::$linkDupeCheck = array();
    }

    /**
     * Prepare the Change Data to be returned
     * Eliminates IDs and removes fields where activity_enabled is false
     * @param  $bean
     * @param  $data
     */
    protected function prepareChanges($bean, &$data)
    {
        if (!empty($data['changes']) && is_array($data['changes'])) {
            foreach ($data['changes'] as $fieldName => $changeInfo) {
                $def = $bean->getFieldDefinition($fieldName);

                //strip out changes where the field has activity_enabled is false
                // According to documentation the audited option is still supported and a field with
                // neither of the options should not be in activities.
                if (
                    empty($def['audited']) ||
                    (isset($def['audited']) && $def['audited'] === false) ||
                    (isset($def['activity_enabled']) && $def['activity_enabled'] === false)
                ) {
                    unset($data['changes'][$fieldName]);
                    continue;
                }

                if (isset($changeInfo['data_type']) &&
                    ($changeInfo['data_type'] === 'id' ||
                        in_array($changeInfo['data_type'], $bean::$relateFieldTypes) ||
                        $changeInfo['data_type'] === 'team_list')
                ) {
                    if ($fieldName == 'team_set_id') {
                        $this->resolveTeamSetReferences($data, $fieldName);
                    } else {
                        $referenceModule = null;
                        if ($fieldName == 'parent_id') {
                            $parentTypeDef = $bean->getFieldDefinition('parent_type');
                            if (empty($parentTypeDef)) {
                                $referenceModule = $data['object']['module'];
                            } elseif (!empty($parentTypeDef['module'])) {
                                $referenceModule = $parentTypeDef['module'];
                            }
                        } elseif ($fieldName == 'team_id') {
                            $teamNameDef = $bean->getFieldDefinition('team_name');
                            if (!empty($teamNameDef['module'])) {
                                $referenceModule = $teamNameDef['module'];
                            }
                        } else {
                            if (!empty($def['module'])) {
                                $referenceModule = $def['module'];
                            } elseif ($changeInfo['data_type'] === 'id') {
                                //find module from corresponding relate field
                                foreach($bean->field_defs as $fieldDef) {
                                    if (isset($fieldDef['type']) &&
                                        in_array($fieldDef['type'], $bean::$relateFieldTypes) &&
                                        isset($fieldDef['id_name']) &&
                                        $fieldDef['id_name'] === $fieldName &&
                                        isset($fieldDef['module'])
                                    ) {
                                        $referenceModule = $fieldDef['module'];
                                        break;
                                    }
                                }
                            }
                        }

                        if (!empty($referenceModule)) {
                            $this->resolveIdReferences($data, $fieldName, $referenceModule);
                        }
                    }
                }
            }
        }
    }

    /**
     * Resolve ID references in the change set to 'Name' field values
     *
     * @param  $data
     * @param  $fieldName
     * @param  $referenceModule
     */
    protected function resolveIdReferences(&$data, $fieldName, $referenceModule)
    {
        $data['changes'][$fieldName]['before'] = $this->getReferenceName(
            $referenceModule,
            $data['changes'][$fieldName]['before']
        );
        $data['changes'][$fieldName]['after']  = $this->getReferenceName(
            $referenceModule,
            $data['changes'][$fieldName]['after']
        );
    }

    /**
     * Get Name Field value for arbitrary Module/Id
     *
     * @param  $module
     * @param  $id
     *
     * @return mixed|null - Name field value
     */
    protected function getReferenceName($module, $id)
    {
        $val  = null;
        $bean = BeanFactory::retrieveBean($module, $id);
        if (!empty($bean)) {
            $val = $bean->name;
        }
        return $val;
    }

    /**
     * Resolve team_set_id references in the change set
     *
     * @param  $data
     * @param  $fieldName (team_set_id)
     */
    protected function resolveTeamSetReferences(&$data, $fieldName)
    {
        $data['changes'][$fieldName]['before'] =
            $this->getTeamSetInfo($data['changes'][$fieldName]['before']);
        $data['changes'][$fieldName]['after']  =
            $this->getTeamSetInfo($data['changes'][$fieldName]['after']);
    }

    /**
     * Get Team Ids for supplied Team Set
     *
     * @param  $teamSetId
     * @return string comma separated list of team names
     */
    protected function getTeamSetInfo($teamSetId)
    {
        $info = '';
        $teamSet = BeanFactory::retrieveBean('TeamSets', $teamSetId);
        if ($teamSet) {
            $teamSet->load_relationship('teams');
            $rows = $teamSet->getTeamIds($teamSetId);
            $teams = array();
            if (!empty($rows)) {
                foreach ($rows as $teamId) {
                    $teams[] = $this->getTeamNameFromId($teamId);
                }
            }
            $info = implode(", ", $teams);
        }
        return $info;
    }

    /**
     * Get Team Name given a team_id
     *
     * @param  $teamId
     * @return string name of the team or empty string if not found
     */
    protected function getTeamNameFromId($teamId)
    {
        $bean = BeanFactory::retrieveBean('Teams', $teamId);
        if (!empty($bean)) {
            return $bean->name;
        }
        return '';
    }

    /**
     * Check to see if the assigned user for the given bean has changed
     *
     * @param  $bean Bean that was created or updated
     * @param  $args Array containing audited data changes
     * @return bool  Was the assigned user changed
     */
    protected function assignmentChanged($bean, $args)
    {
        //first check the activity enabled data changes
        $assignmentChanged = isset($args['dataChanges']) && isset($args['dataChanges']['assigned_user_id']);

        //then check the full list of data changes in case the assigned_user_id field is not audited
        if (!$assignmentChanged) {
            $assignmentChanges = $bean->db->getDataChanges($bean, array('field_filter'=>array('assigned_user_id')));
            $assignmentChanged = isset($assignmentChanges['assigned_user_id']);
        }

        return $assignmentChanged;
    }

    /**
     * Add Record Subscriptions:
     *   (1) Assigned-To User
     *   (2) CreatedBy User if other than AssignedTo User and event is Not an Update
     *
     * @param  array $args
     * @param  SugarBean $bean
     * @return void
     */
    protected function addRecordSubscriptions($args, SugarBean $bean)
    {
        // Subscribe the user assigned to this record if an existing non-Portal User and action is create or assignment changed
        if (isset($bean->assigned_user_id) && (!$args['isUpdate'] || $this->assignmentChanged($bean, $args))) {
            $assigned_user = BeanFactory::getBean('Users', $bean->assigned_user_id, array('strict_retrieve' => true));
            if (!empty($assigned_user) && !$assigned_user->portal_only) {
                $this->subscribeUserToRecord($assigned_user, $bean);
            }
        }

        if (!$args['isUpdate']) {
            // Subscribe the user that created the record if an existing non-Portal User and
            // te user is different than the assigned-to user.
            if (isset($bean->created_by) &&
                (!isset($bean->assigned_user_id) || ($bean->created_by !== $bean->assigned_user_id))
            ) {
                $created_user = BeanFactory::getBean('Users', $bean->created_by, array('strict_retrieve' => true));
                if (!empty($created_user) && !$created_user->portal_only) {
                    $this->subscribeUserToRecord($created_user, $bean);
                }
            }
        }
    }

    /**
     * Subscribe supplied User to supplied Bean
     *
     * @param  User $user
     * @param  SugarBean $bean
     * @return void
     */
    protected function subscribeUserToRecord(User $user, SugarBean $bean)
    {
        $subs = BeanFactory::getBeanClass('Subscriptions');
        $subs::subscribeUserToRecord($user, $bean, array('disable_row_level_security' => true));
    }
}
