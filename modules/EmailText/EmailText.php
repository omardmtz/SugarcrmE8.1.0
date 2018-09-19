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
 * Class for separate storage of Email texts
 */
class EmailText extends SugarBean
{
	var $disable_row_level_security = true;
    var $table_name = 'emails_text';
    var $module_name = "EmailText";
    var $module_dir = 'EmailText';
    var $object_name = 'EmailText';
    var $disable_custom_fields = true;
}