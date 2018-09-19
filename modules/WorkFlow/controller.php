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


class WorkflowController extends SugarController
{
    public function preProcess()
    {
        global $current_user;
        
        $workflow_modules = get_workflow_admin_modules_for_user($current_user);
        if (!is_admin($current_user) && empty($workflow_modules))
            sugar_die("Unauthorized access to WorkFlow.");
    }
}
