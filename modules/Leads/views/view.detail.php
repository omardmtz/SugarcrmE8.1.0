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


class LeadsViewDetail extends ViewDetail
{

    function display()
    {
        global $sugar_config;

        //If the convert lead action has been disabled for already converted leads, disable the action link.
        $disableConvert = ($this->bean->status == 'Converted' && !empty($sugar_config['disable_convert_lead'])) ? TRUE : FALSE;
        $this->ss->assign("DISABLE_CONVERT_ACTION", $disableConvert);
        parent::display();
    }
}
