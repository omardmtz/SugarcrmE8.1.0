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
 * This class is here to provide functions to easily call in to the individual module api helpers
 */
class SugarBeanApiHelper
{
    /**
     * This is used when formatting records to do things like provide URI's for objects.
     */
    protected $api;

    public function __construct(\ServiceBase $api)
    {
        $this->api = $api;
    }

    /**
     * Formats the bean so it is ready to be handed back to the API's client. Certian fields will get extra processing
     * to make them easier to work with from the client end.
     *
     * @param $bean SugarBean The bean you want formatted
     * @param $fieldList array Which fields do you want formatted and returned (leave blank for all fields)
     * @param $options array Currently no options are supported
     * @return array The bean in array format, ready for passing out the API to clients.
     */
    public function formatForApi(\SugarBean $bean, array $fieldList = array(), array $options = array())
    {
        $sfh = new \SugarFieldHandler();

        // if you are listing something the action is list
        // if any other format is called its a view
        $action = (!empty($options['action']) && $options['action'] == 'list') ? 'list' : 'view';

        $data = array();
        $hasAccess = empty($bean->deleted) && $bean->ACLAccess($action);
        if ($hasAccess) {
            foreach ($bean->field_defs as $fieldName => $properties) {
                // Prune fields before ACL check because it can be expensive (Bug58133)
                if ( !empty($fieldList) && !in_array($fieldName,$fieldList) ) {
                    // They want to skip this field
                    continue;
                }

                if ( !$bean->ACLFieldAccess($fieldName,'read') ) {
                    // No read access to the field, eh?  Unset the field from the array of data returned
                    unset($data[$fieldName]);
                    continue;
                }

                $type = !empty($properties['custom_type']) ? $properties['custom_type'] : $properties['type'];
                $field = $sfh->getSugarField($type);

                if(empty($field)) continue;

                if (isset($bean->$fieldName)  || $type == 'relate') {
                     $field->apiFormatField($data, $bean, $options, $fieldName, $properties, $fieldList, $this->api);
                }
            }

            // mark if its a favorite
            if (in_array('my_favorite', $fieldList) || empty($fieldList)) {
                if (isset($bean->my_favorite)) {
                    $data['my_favorite'] = (bool)$bean->my_favorite;
                } else {
                    // If the module doesn't support favorites, set it to false
                    $data['my_favorite'] = false;
                }
            }
        } else {
            if (isset($bean->id)) {
                $data['id'] = $bean->id;
            }
            if (isset($bean->deleted) && $bean->deleted == true) {
                $data['deleted'] = (bool)$bean->deleted;
            } else {
                if (isset($bean->date_modified) && !empty($bean->field_defs['date_modified'])) {
                    $field = $sfh->getSugarField($bean->field_defs['date_modified']['type']);
                    $field->apiFormatField(
                        $data,
                        $bean,
                        array(),
                        'date_modified',
                        $bean->field_defs['date_modified'],
                        $fieldList,
                        $this->api
                    );
                }
            }
            if ($this->api->user->isAdmin()) {
                // BR-759 requests that assigned_user_id is returned on deleted records
                // to better sync some external systems
                if (isset($bean->assigned_user_id) && in_array('assigned_user_id', $fieldList)) {
                    $data['assigned_user_id'] = $bean->assigned_user_id;
                }
            }
        }

        // in some cases the ACL data should be displayed, even when the used doesn't have access to the bean
        // (e.g. after bean is assigned to a different user and thus is no more accessible)
        if ($hasAccess || !empty($options['display_acl'])) {
            // if not an admin and the hashes differ, send back bean specific acl's
            $data['_acl'] = $this->getBeanAcl($bean, $fieldList);
        }

        if (!empty($options['args']['erased_fields'])) {
            $data['_erased_fields'] = $bean->erased_fields ? $bean->erased_fields : [];
        }

        return $data;
    }

    /**
     * Get the beans ACL's to pass back any that differ
     * @param  SugarBean $bean
     * @param  array     $fieldList
     * @return array
     */
    public function getBeanAcl(\SugarBean $bean, array $fieldList)
    {
        $acl = array('fields' => (object) array());
        if (\SugarACL::moduleSupportsACL($bean->module_dir)) {
            $mm = \MetaDataManager::getManager($this->api->platform);
            $moduleAcl = $mm->getAclForModule($bean->module_dir, $this->api->user, false, true);

            $beanAcl = $mm->getAclForModule($bean->module_dir, $this->api->user, $bean, true);
            if ($beanAcl['_hash'] != $moduleAcl['_hash'] || !empty($fieldList)) {

                // diff the fields separately, they are usually empty anyway so we won't diff these often.
                $moduleAclFields = $moduleAcl['fields'];
                $beanAclFields = $beanAcl['fields'];
                // dont' need the fields here will append at the end
                unset($moduleAcl['fields']);
                unset($beanAcl['fields']);

                // don't need the hashes anymore
                unset($moduleAcl['_hash']);
                unset($beanAcl['_hash']);

                $acl = array_diff_assoc($beanAcl, $moduleAcl);
                $fieldAcls = array();

                /**
                 * Fields are different than module level acces
                 * if fields is empty that means all access is granted
                 * beanAclFields is empty and moduleAclFields is empty -> all access -> return empty
                 * beanAclFields is empty and moduleAclFields is !empty -> all access -> return yes's
                 * beanAclFields is !empty and moduleAclFields is empty -> beanAclFields access restrictions -> return beanAclFields
                 * beanAclFields is !empty and moduleAclFields is !empty -> return all access = "Yes" from moduleAcl and unset any in beanAcl that is in ModuleAcl [don't dupe data]
                 */

                if (!empty($beanAclFields) && empty($moduleAclFields)) {
                    $fieldAcls = $beanAclFields;
                } elseif (!empty($beanAclFields) && !empty($moduleAclFields)) {
                    // we need the ones that are moduleAclFields but not in beanAclFields
                    foreach ($moduleAclFields AS $field => $aclActions) {
                        foreach ($aclActions AS $action => $access) {
                            if (!isset($beanAclFields[$field][$action])) {
                                $beanAclFields[$field][$action] = "yes";
                            }
                            // if the bean action is set and it matches the access from module, we do not need to send it down
                            if (isset($beanAclFields[$field][$action]) && $beanAclFields[$field][$action] == $access) {
                                unset($beanAclFields[$field][$action]);
                            }
                        }
                    }

                    // cleanup BeanAclFields, we don't want to pass a field that doens't have actions
                    foreach ($beanAclFields AS $field => $actions) {
                        if (empty($actions)) {
                            unset($beanAclFields[$field]);
                        }
                    }

                    $fieldAcls = $beanAclFields;
                } elseif (empty($beanAclFields) && !empty($moduleAclFields)) {
                    // it is different because we now have access...
                    foreach ($moduleAclFields AS $field => $aclActions) {
                        foreach ($aclActions AS $action => $access) {
                            $fieldAcls[$field][$action] = "yes";
                        }
                    }
                }

                foreach ($fieldList AS $fieldName) {
                    if (empty($fieldAcls[$fieldName]) && isset($moduleAclFields[$fieldName])) {
                        $fieldAcls[$fieldName] = $moduleAclFields[$fieldName];
                    }
                }

                $acl['fields'] = (object) $fieldAcls;
            }

        }

        return $acl;
    }

    /**
     * This function
     *
     * @param $bean SugarBean The bean you want populated from the $submittedData array, this function will modify this
     *                        record
     * @param $submittedData array The data that was passed in from the client to update/create this record
     * @param $options array Options to pass in to the populateFromApi function, look at SugarBeanApiHelper:populateFromApi
     *                       for more information
     * @return array An array of validation errors, or true if the submitted data appeared to be correct
     */
    public function populateFromApi(\SugarBean $bean, array $submittedData, array $options = array())
    {
        if(!empty($bean->id) && !empty($options['optimistic_lock'])) {
            $this->checkOptimisticLocking($bean, $options['optimistic_lock']);
        }

        if (isset($submittedData['id']) && !empty($bean->id) && $submittedData['id'] != $bean->id) {
            $msg = "Not allowed to change record id '{$bean->id}' in the {$submittedData['module']} module";
            throw new \SugarApiExceptionInvalidParameter($msg);
        }

        // Some of the SugarFields require ID's, so lets set it up
        if (empty($bean->id)) {
            $bean->id = create_guid();
            $bean->new_with_id = true;
        }

        $sfh = new \SugarFieldHandler();

        $context = array();
        /**
         * We need to override because of order of fields.
         * For example, if we are changing ownership and a field that is owner read/owner write
         * The assigned_user_id could be set on the bean before we check the ACL of the field
         * Therefore we need to set the owner_override before we start manipulating the bean fields
         * so that the ACL returns correctly for owner
         */
        if (!empty($bean->assigned_user_id) && $bean->assigned_user_id == $this->api->user->id) {
            $context['owner_override'] = true;
        }

        // Sanitize the data if needed
        $submittedData = $this->sanitizeSubmittedData($submittedData);

        // check ACLs first
        $acl = !empty($options['acl']) ? $options['acl'] : 'save';
        foreach ($bean->field_defs as $fieldName => $properties) {
            if ( !isset($submittedData[$fieldName]) ) {
                // They aren't trying to modify this field
                continue;
            }
            if ( !$bean->ACLFieldAccess($fieldName, $acl, $context) ) {
                // No write access to this field, but they tried to edit it
                throw new \SugarApiExceptionNotAuthorized(
                    'Not allowed to edit field ' . $fieldName . ' in module: ' . $bean->module_name
                );
            }
        }

        // now update the fields
        foreach ($bean->field_defs as $fieldName => $properties) {
            if ( !isset($submittedData[$fieldName]) ) {
                // They aren't trying to modify this field
                continue;
            }

            $type = !empty($properties['custom_type']) ? $properties['custom_type'] : $properties['type'];
            $field = $sfh->getSugarField($type);
            $field->setOptions($options);

            if ($field != null) {
                // validate submitted data
                if (!$field->apiValidate($bean, $submittedData, $fieldName, $properties)) {
                    throw new \SugarApiExceptionInvalidParameter(
                        'Invalid field value: ' . $fieldName . ' in module: ' . $bean->module_name
                    );
                }

                if (!empty($options['massUpdate'])) {
                    $field->apiMassUpdate($bean, $submittedData, $fieldName, $properties);
                } else {
                    $field->apiSave($bean, $submittedData, $fieldName, $properties);
                }
            }
        }

        return true;
    }

    /**
     * This code replicates the behavior in Sugar_Controller::pre_save()
     * @param SugarBean $bean
     * @return boolean
     */
    public function checkNotify($bean)
    {
        $check_notify = TRUE;
        // check update
        // if Notifications are disabled for this module set check notify to false
        if (!empty($GLOBALS['sugar_config']['exclude_notifications'][$bean->module_dir]) && $GLOBALS['sugar_config']['exclude_notifications'][$bean->module_dir] == true) {
            $check_notify = FALSE;
        } else {
            // some modules, like Users don't have an assigned_user_id
            if (isset($bean->assigned_user_id)) {
                // if the assigned user hasn't changed, set check notify to false
                if (!empty($bean->fetched_row['assigned_user_id']) && $bean->fetched_row['assigned_user_id'] == $bean->assigned_user_id) {
                    $check_notify = FALSE;
                    // if its the same user, don't send
                } elseif ($bean->assigned_user_id == $GLOBALS['current_user']->id) {
                    $check_notify = FALSE;
                }
            }
        }

        return $check_notify;
    }

    /**
     * Handles cleaning up submitted data for child classes that need that functionality
     *
     * @param array $data Submitted data array
     * @return array
     */
    public function sanitizeSubmittedData($data)
    {
        return $data;
    }

    /**
     * Check optimistic lock on a bean against $timestamp
     * @param SugarBean $bean
     * @param string $timestamp ISO-formatted timestamp
     * @return bool
     * @throws SugarApiExceptionEditConflict
     */
    protected function checkOptimisticLocking($bean, $timestamp)
    {
        // only perform optimistic locking when the module vardef attribute has been set to true
        $objectName = \BeanFactory::getObjectName($bean->module_name);
        if (!isset($GLOBALS['dictionary'][$objectName]['optimistic_locking'])
            || ($GLOBALS['dictionary'][$objectName]['optimistic_locking'] !== true)
        ) {
            return true;
        }

        if(empty($timestamp)) {
            // no TS - no conflict
            return true;
        }
        if(empty($bean->id) || empty($bean->date_modified)) {
            // bean is either empty or new, no conflict
            return true;
        }

        $dateToCheck = (!empty($bean->fetched_row['date_modified'])) ? $bean->fetched_row['date_modified'] : $bean->date_modified;

        $timedate = \TimeDate::getInstance();
        $ts_client = $timedate->fromIso($timestamp);
        if(empty($ts_client)) {
            throw new \SugarApiExceptionInvalidParameter("Bad timestamp $timestamp");
        }
        $ts_server = $timedate->fromDb($dateToCheck);
        if(empty($ts_server)) {
            $ts_server = $timedate->fromUser($dateToCheck);
        }
        if(empty($ts_server)) {
            // Bean timestamp is incomprehensible, defaulting to no conflict
            return true;
        }
        if($ts_server->ts != $ts_client->ts) {
            // OOPS, edited after client TS, conflict!
            $cts = "client TS is {$timedate->asIso($ts_client)}";
            $sts = "server TS is {$timedate->asIso($ts_server)}";
            throw new \SugarApiExceptionEditConflict("Edit conflict - $cts, $sts");
        }
    }
}
