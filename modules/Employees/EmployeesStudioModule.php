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

class EmployeesStudioModule extends StudioModule {
    function getProvidedSubpanels ()
    {
        // Much like pointy haired bosses, other modules should not be able to relate to Employees.
        return false;
    }

    function getModule ()
    {
        $normalModules = parent::getModule();
        
        if(isset($normalModules[translate('LBL_RELATIONSHIPS')])) {
            unset($normalModules[translate('LBL_RELATIONSHIPS')]);
        }

        return $normalModules;
    }

}