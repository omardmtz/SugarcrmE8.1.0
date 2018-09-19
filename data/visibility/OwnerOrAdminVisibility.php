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
 * Adds visibility for owner or admin only.
 */
class OwnerOrAdminVisibility extends OwnerVisibility
{
    /**
     * Allow admins to view all records, even if they are not the owner.
     * (non-PHPdoc)
     * @see SugarVisibility::addVisibilityWhere()
     */
    public function addVisibilityWhere(&$query)
    {
        global $current_user;
        $module =  $this->bean->module_name;
        if($current_user->isAdminForModule($module)) {
            return $query;
        }

        return parent::addVisibilityWhere($query);
    }
}
