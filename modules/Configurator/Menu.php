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

 // $Id: Menu.php 52272 2009-11-06 18:50:17Z jmertic $

global $mod_strings;
$module_menu[]=Array("index.php?module=Configurator&action=EditView",$mod_strings['LBL_CONFIGURE_SETTINGS_TITLE'], "Administration");
$module_menu[]=Array("index.php?module=Configurator&action=LogView",$mod_strings['LBL_LOGVIEW'], "Leads");
