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


global $mod_strings, $sugar_config, $app_strings;

if(SugarACL::checkAccess('PdfManager', 'edit', true))$module_menu[] =Array("index.php?module=PdfManager&action=EditView&return_module=PdfManager&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"CreatePdfManager");
if(SugarACL::checkAccess('PdfManager', 'list', true))$module_menu[] =Array("index.php?module=PdfManager&action=index&return_module=PdfManager&return_action=index", $mod_strings['LNK_LIST'],"PdfManager");

$module_menu[] =Array("index.php?return_module=PdfManager&return_action=index&module=Configurator&action=SugarpdfSettings", $mod_strings['LNK_EDIT_PDF_TEMPLATE'], "PdfManager");
