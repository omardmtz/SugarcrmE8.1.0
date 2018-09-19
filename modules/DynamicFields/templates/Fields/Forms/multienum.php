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

 // $Id: multienum.php 35784 2008-05-20 21:31:40Z dwheeler $


$edit_mod_strings = return_module_language($GLOBALS['current_language'], 'EditCustomFields');
$edit_mod_strings['LBL_DROP_DOWN_LIST'] = $edit_mod_strings['LBL_MULTI_SELECT_LIST'];
$multi = 'true';
require_once('modules/DynamicFields/templates/Fields/Forms/enum2.php');
?>
