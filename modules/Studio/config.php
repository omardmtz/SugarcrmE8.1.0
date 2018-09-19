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

 // $Id: config.php 19019 2007-01-04 03:05:01Z majed $

error_reporting(1);
//destroying global variables
$GLOBALS['studioConfig'] = array();
$GLOBALS['studioConfig']['parsers']['ListViewParser'] = 'modules/Studio/parsers/ListViewParser.php';
$GLOBALS['studioConfig']['parsers']['SlotParser'] = 'modules/Studio/parsers/SlotParser.php';
$GLOBALS['studioConfig']['parsers']['StudioParser'] = 'modules/Studio/parsers/StudioParser.php';
$GLOBALS['studioConfig']['parsers']['StudioRowParser'] = 'modules/Studio/parsers/StudioRowParser.php';
$GLOBALS['studioConfig']['parsers']['StudioUpgradeParser'] = 'modules/Studio/parsers/StudioUpgradeParser.php';
$GLOBALS['studioConfig']['parsers']['SubpanelColParser'] = 'modules/Studio/parsers/SubpanelColParser.php';
$GLOBALS['studioConfig']['parsers']['SubpanelParser'] = 'modules/Studio/parsers/SubpanelParser.php';
$GLOBALS['studioConfig']['parsers']['TabIndexParser'] = 'modules/Studio/parsers/TabIndexParser.php';
$GLOBALS['studioConfig']['parsers']['XTPLListViewParser'] = 'modules/Studio/parsers/XTPLListViewParser.php';
$GLOBALS['studioConfig']['ajax']['customfieldview'] = 'modules/Studio/ajax/customfieldview.php';
$GLOBALS['studioConfig']['ajax']['editcustomfield'] = 'modules/Studio/ajax/editcustomfield.php';
$GLOBALS['studioConfig']['ajax']['relatedfiles'] = 'modules/Studio/ajax/relatedfiles.php';
$GLOBALS['studioConfig']['dynamicFields']['bool'] = 'modules/DynamicFields/templates/Fields/Forms/bool.php';
$GLOBALS['studioConfig']['dynamicFields']['date'] = 'modules/DynamicFields/templates/Fields/Forms/date.php';
$GLOBALS['studioConfig']['dynamicFields']['email'] = 'modules/DynamicFields/templates/Fields/Forms/email.php';
$GLOBALS['studioConfig']['dynamicFields']['enum'] = 'modules/DynamicFields/templates/Fields/Forms/enum.php';
$GLOBALS['studioConfig']['dynamicFields']['float'] = 'modules/DynamicFields/templates/Fields/Forms/float.php';
$GLOBALS['studioConfig']['dynamicFields']['html'] = 'modules/DynamicFields/templates/Fields/Forms/html.php';
$GLOBALS['studioConfig']['dynamicFields']['int'] = 'modules/DynamicFields/templates/Fields/Forms/int.php';
$GLOBALS['studioConfig']['dynamicFields']['multienum'] = 'modules/DynamicFields/templates/Fields/Forms/multienum.php';
$GLOBALS['studioConfig']['dynamicFields']['radioenum'] = 'modules/DynamicFields/templates/Fields/Forms/radioenum.php';
$GLOBALS['studioConfig']['dynamicFields']['text'] = 'modules/DynamicFields/templates/Fields/Forms/text.php';
$GLOBALS['studioConfig']['dynamicFields']['url'] = 'modules/DynamicFields/templates/Fields/Forms/url.php';
$GLOBALS['studioConfig']['dynamicFields']['varchar'] = 'modules/DynamicFields/templates/Fields/Forms/varchar.php';

?>
