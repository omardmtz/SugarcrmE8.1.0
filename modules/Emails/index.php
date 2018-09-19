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


$focus = BeanFactory::newBean('Emails');
if (!$focus->ACLAccess('view')) {
      ACLController::displayNoAccess(true);
      sugar_cleanup(true);
  }
$focus->email2init();
$focus->et->preflightUser($current_user);
$out = $focus->et->displayEmailFrame();
echo $out;
echo "<script>var composePackage = null;</script>";

$skipFooters = true;

