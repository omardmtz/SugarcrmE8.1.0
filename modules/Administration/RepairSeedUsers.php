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
global $current_user, $mod_strings;

require_once 'include/SugarSmarty/plugins/function.sugar_csrf_form_token.php';

if(is_admin($current_user)){
    if(count($_POST)){
    	if(!empty($_POST['activate'])){

    		$status = '';
    		if($_POST['activate'] == 'false'){
    			$status = 'Inactive';
    		}else{
    			$status = 'Active';
    		}
    	}
    	$query = "UPDATE users SET status = '$status' WHERE id LIKE 'seed%'";
   		$GLOBALS['db']->query($query);
    }
    	$query = "SELECT status FROM users WHERE id LIKE 'seed%'";
    	$result = $GLOBALS['db']->query($query);
		$row = $GLOBALS['db']->fetchByAssoc($result);
		if(!empty($row['status'])){
			$activate = 'false';
			if($row['status'] == 'Inactive'){
				$activate = 'true';
			}
			?>
				<p>
				<form name="RepairSeedUsers" method="post" action="index.php">
                <?php echo smarty_function_sugar_csrf_form_token(array(), $smarty); ?>
				<input type="hidden" name="module" value="Administration">
				<input type="hidden" name="action" value="RepairSeedUsers">
				<input type="hidden" name="return_module" value="Administration">
				<input type="hidden" name="return_action" value="Upgrade">
				<input type="hidden" name="activate" value="<?php echo $activate; ?>">
				<table cellspacing="{CELLSPACING}" class="otherview">
					<tr>
					    <td scope="row" width="30%"><?php echo $mod_strings['LBL_REPAIR_SEED_USERS_TITLE']; ?></td>
					    <td><input type="submit" name="button" value="<?php if($row['status'] == 'Inactive'){echo $mod_strings['LBL_REPAIR_SEED_USERS_ACTIVATE'];}else{echo $mod_strings['LBL_REPAIR_SEED_USERS_DECACTIVATE'];} ?>"></td>
					</tr>
				</table>
				</form>
				</p>
			<?php

		}else{
			echo $mod_strings['LBL_REPAIR_SEED_USERS_NO_USER'];
		}
}
else{
	sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
}
?>
