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

 // $Id: removeme.php 29835 2007-11-20 00:01:55Z ajay $

require_once('modules/Campaigns/utils.php');

if (!empty($_REQUEST['remove'])) clean_string($_REQUEST['remove'], "STANDARD");
if (!empty($_REQUEST['from'])) clean_string($_REQUEST['from'], "STANDARD");

if(!empty($_REQUEST['identifier'])) {
    global $beanFiles, $beanList, $current_user;

    //user is most likely not defined, retrieve admin user so that team queries are bypassed
    if(empty($current_user) || empty($current_user->id)){
            $current_user = BeanFactory::getBean('Users', '1');
    }

    $keys=log_campaign_activity($_REQUEST['identifier'],'removed');
    global $current_language;
    $mod_strings = return_module_language($current_language, 'Campaigns');


    if (!empty($keys) && $keys['target_type'] == 'Users'){
        //Users cannot opt out of receiving emails, print out warning message.
        echo $mod_strings['LBL_USERS_CANNOT_OPTOUT'];
     }elseif(!empty($keys) && isset($keys['campaign_id']) && !empty($keys['campaign_id'])){
        //we need to unsubscribe the user from this particular campaign
        $focus = BeanFactory::getBean($keys['target_type'], $keys['target_id']
        , array('disable_row_level_security' => true)
        );
        unsubscribe($keys['campaign_id'], $focus);

    }elseif(!empty($keys)){
		$id = $keys['target_id'];
        $module = empty($keys['target_type']) ? '' : $keys['target_type'];
        $db = DBManagerFactory::getInstance();

		//no opt out for users.
        if (!empty($module) && $module != 'Users') {
            //record this activity in the campaign log table..
            $status = true;
            $email = BeanFactory::newBean('EmailAddresses');
            $sql = 'SELECT ea.id FROM email_addresses ea' .
                ' INNER JOIN email_addr_bean_rel eabr ON eabr.email_address_id = ea.id' .
                ' WHERE  eabr.bean_module = ? AND eabr.bean_id = ? AND eabr.deleted = 0 AND ea.opt_out = 0';
            $conn = $db->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute([$module, $id]);
            while ($row = $stmt->fetch()) {
                $status = $status && (bool) $db->updateParams(
                    $email->getTableName(),
                    $email->field_defs,
                    array('opt_out' => 1),
                    array('id' => $row['id'])
                );
            }
			if($status){
				echo "*";
			}
		}
    }
		//Print Confirmation Message.
		echo $mod_strings['LBL_ELECTED_TO_OPTOUT'];
}
