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
 * Render the quick compose frame needed by the UI.  The data is returned as a JSOn
 * object and consumed by the client in an ajax call.
 */

$em = new EmailUI();
$out = $em->displayQuickComposeEmailFrame();
		
@ob_end_clean();
ob_start();
echo $out;
ob_end_flush();