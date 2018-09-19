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

// $Id: Popup.php 13782 2006-06-06 17:58:55 +0000 (Tue, 06 Jun 2006) majed $

require_once('modules/Documents/Popup_picker.php');

$popup = new Popup_Picker();

echo $popup->process_page();

?>