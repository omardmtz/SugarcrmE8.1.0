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
 * Static ACL implementation - ACLs defined per-module
 * Uses ACLController and ACLAction
 */
class SugarACLSupportPortal extends SugarACLStatic
{
    /**
     * Is the current user a portal user?
     * @return bool Yes, the user is a portal user
     */
    protected function isPortalUser()
    {
        if (!empty($_SESSION['type']) && $_SESSION['type'] == 'support_portal' ) {
            return true;
        }
        return false;
    }

    /**
     * Fetch the list of account id's associated to this user
     * @return array List of account id's associated to this user
     */
    protected function getAccountIds($bean)
    {
        static $accounts;
        if ( !isset($accounts) ) {
            // If the portal user isn't linked to any accounts they can only do anything with Contacts and Bugs
            // Get the account_id list and make sure there is something on it.
            $vis = new SupportPortalVisibility($bean);
            $accounts = $vis->getAccountIds();
        }

        return $accounts;
    }

    /**
     * Determines if a portal user "owns" a record
     * @param SugarBean $bean
     */
    protected function isPortalOwner(SugarBean $bean) {
        if ( empty($bean->id) || $bean->new_with_id ) {
            // New record, they are the owner.
            $bean->portal_owner = true;
        }
        // Cache portal owner on bean so that we aren't loading Contacts for each ACL check
        // Performance Bug58133
        if(!isset($bean->portal_owner)){
            switch( $bean->module_dir ) {
                case 'Contacts':
                    $bean->portal_owner = $bean->id == $_SESSION['contact_id'];
                    break;
                    // Cases & Bugs work the same way, so handily enough we can share the code.
                case 'Cases':
                case 'Bugs':
                    $bean->load_relationship('contacts');
                    $rows = $bean->contacts->query(array(
                                                       'where'=>array(
                                                           // query adds the prefix so we don't need contact.id
                                                           'lhs_field'=>'id',
                                                           'operator'=>'=',
                                                           'rhs_value'=>$GLOBALS['db']->quote($_SESSION['contact_id']),
                                                           )));
                    $bean->portal_owner = count($rows) > 0;
                    break;
                default:
                    // Unless we know how to find the "owner", they can't own it.
                    $bean->portal_owner = false;
            }
        }
        return $bean->portal_owner;
    }

    /**
     * Handles the special access controls of the portal system, primarily disabling editing of records while allowing for record creation
     * @param string $module
     * @param string $action
     * @param array $context THIS IS MODIFIED, owner_override is modified and set according to if the portal user is the "owner" of this object
     */
    protected function portalAccess($module, $action, &$context) {
        // Leave this set to null to let the decision be handled by the parent
        $accessGranted = null;

        if ($this->isPortalUser() ) {
            $bean = isset($context['bean'])?$context['bean']:BeanFactory::newBean($module);
            if (!$bean) {
                // There is no bean, without a bean portal ACL's wont work
                // So for security we will deny the request
                return false;
            }

            $accounts = $this->getAccountIds($bean);

            //bug57022 : Retrieve of KB articles return 0 records when no account is associated to a portal contact
            if ( count($accounts) == 0 
                 && $bean->module_dir != 'Notes'
                 && $bean->module_dir != 'Contacts' 
                 && $bean->module_dir != 'Bugs'
                 && $bean->module_dir != 'KBContents'
                 && $bean->module_dir != 'Categories'
                 && $bean->module_dir != 'DocumentRevisions' ) {
                return false;
            }
            $context['owner_override'] = $this->isPortalOwner($bean);
            
            if(isset(self::$action_translate[$action])) {
                $action = self::$action_translate[$action];
            }

            // Only allow users to create records, never edit, for everything but Contacts
            if ($bean->module_dir != 'Contacts' ) {
                if ($action=='edit' && !empty($bean->id) && !$bean->new_with_id) {
                    $accessGranted = false;
                }
            } else {
                // Can't create new Contacts
                if ($action == 'edit' && (empty($bean->id) || $bean->new_with_id)) {
                    $accessGranted = false;
                }
            }
        }

        return $accessGranted;
    }

    static $action_translate = array(
        'listview' => 'list',
        'index' => 'list',
        'popupeditview' => 'edit',
        'editview' => 'edit',
        'detail' => 'view',
        'detailview' => 'view',
        'save' => 'edit',
        'create' => 'edit',
    );

    /**
     * Check access to fields
     * @param string $module
     * @param string $action
     * @param array $context
     */
    protected function fieldACL($module, $action, $context)
    {
        $accessGranted = $this->portalAccess($module, $action, $context);
        
        // Handle file and image type field checking here, specifically for creates
        if ($accessGranted === false && $action == 'create') {
            $bean = isset($context['bean']) ? $context['bean'] : null;
            
            // If there is a bean, and a field name and defs for that fieldname...
            if ($bean && isset($context['field']) && isset($bean->field_defs[$context['field']])) {
                $field = $context['field'];
                $def = $bean->field_defs[$field];
                
                // If the field type is an image or file
                if (isset($def['type']) && ($def['type'] == 'image' || $def['type'] == 'file')) {
                    // And the value for this field in the bean is empty, it is
                    // a create, which should make accessGranted = null
                    if (empty($bean->$field)) {
                        $accessGranted = null;
                    }
                }
            }
        }

        if( !isset($accessGranted) ) {
            $module = ($module == 'Categories') ? 'KBContents' : $module;
            $accessGranted = parent::fieldACL($module, $action, $context);
        }

        return $accessGranted;
    }

    /**
     * Check bean ACLs
     * @param string $module
     * @param string $action
     * @param array $context
     */
    protected function beanACL($module, $action, $context)
    {
        $accessGranted = $this->portalAccess($module, $action, $context);

        if( !isset($accessGranted) ) {
            $module = ($module == 'Categories') ? 'KBContents' : $module;
            $accessGranted = parent::beanACL($module, $action, $context);
        }

        return $accessGranted;

    }
}
