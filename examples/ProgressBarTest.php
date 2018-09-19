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
ob_start();
require_once('../include/utils/progress_bar_utils.php');
display_flow_bar('myflow', 1);

display_progress_bar('myprogress',0, 10);
for($i = 0; $i <= 10; $i++){
update_progress_bar('myprogress',$i, 10);
sleep(1);
}
destroy_flow_bar('myflow');
?>