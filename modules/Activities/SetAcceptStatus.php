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

// $Id: SetAcceptStatus.php 45763 2009-04-01 19:16:18Z majed $


    global $json,$current_user;
    
    
    if ($_REQUEST['object_type'] == "Meeting")
    {
        $focus = BeanFactory::newBean('Meetings');
        $focus->id = $_REQUEST['object_id'];
        $test = $focus->set_accept_status($current_user, $_REQUEST['accept_status']);
    }
    else if ($_REQUEST['object_type'] == "Call")
    {
        $focus = BeanFactory::newBean('Calls');
        $focus->id = $_REQUEST['object_id'];
        $test = $focus->set_accept_status($current_user, $_REQUEST['accept_status']);
    }
    print 1;
    exit;
?>
