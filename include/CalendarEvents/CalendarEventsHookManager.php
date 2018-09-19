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
 * CalendarEvents hook handler class
 * contains hook configuration for CalendarEvents
 */
class CalendarEventsHookManager
{
    protected $inviteeRelationships = array(
        'meetings_users' => true,
        'meetings_contacts' => true,
        'meetings_leads' => true,
        'calls_users' => true,
        'calls_contacts' => true,
        'calls_leads' => true,
    );

    /**
     * @deprecated Since 7.8
     * CalendarEvents initialization hook
     *
     * Serve "before_relationship_update" hook handling
     */
    public function beforeRelationshipUpdate(SugarBean $bean, $event, $args)
    {
        $relationship = $args['relationship'];
        if (($bean->module_name === 'Meetings' || $bean->module_name === 'Calls') &&
            !empty($this->inviteeRelationships[$relationship]) &&
             empty($bean->updateAcceptStatus)
        ) {
            throw new BypassRelationshipUpdateException();
        }
    }
}
