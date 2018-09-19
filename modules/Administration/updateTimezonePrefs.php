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
?>
<form action='index.php' method="POST">
<input type='hidden' name='action' value='updateTimezonePrefs'>
<input type='hidden' name='module' value='Administration'>

<table>
<?php
global $mod_strings;
$prompt_users = 'checked';
if(isset($_POST['preview']) && !isset($_POST['prompt_users'])){
	$prompt_users = '';
}


$result = $db->query("SELECT id, user_preferences, user_name FROM users");
$execute = false;
// loop through user preferences and check for "bad" elements; rebuild preferences array and update database
if(isset($_POST['execute'])){
	$execute = true;
}
$serverTimeZone = lookupTimezone(0);
while ($row = $db->fetchByAssoc($result)) {
	$adjustment = 'none';

	if(isset($_POST[$row['id'].'adjust'])){
		$adjustment = $_POST[$row['id'].'adjust'];
	}
	
		$string = $mod_strings['LBL_UPDATE_TIMEZONE_PREVIEW'];
		if($execute)$string = $mod_strings['LBL_UPDATE_TIMEZONE_UPDATE'];
        echo "<tr><td> $string ".$mod_strings['LBL_UPDATE_TIMEZONE_PRE_USER']." <b>{$row['user_name']}</b>...</td><td>";
		
        
        $prefs = array();
        $newprefs = array();
	
        $prefs = unserialize(base64_decode($row['user_preferences']));
        $setTo = '';
        $alreadySet = '';
		if(!empty($prefs)){
			
	        foreach ($prefs as $key => $val) {
	               if ($key == 'timez') {
	                	if(empty($prefs['timezone']) && $val != ''){
		                	$hourAdjust = $adjustment;
		                	if($hourAdjust == 'none'){
								$hourAdjust = 0;
							}
	                		$selectedZone = lookupTimezone($prefs['timez'] + $hourAdjust);
	                	
	                	if(!empty($selectedZone)){
	                   	 	$newprefs['timezone'] = $selectedZone;   
	                   	 	$newprefs['timez']  = $val;
	                   	 	$setTo = $selectedZone;
	                   	 	if(empty($prompt_users)){
	                   			$newprefs['ut']=1;
	                   	 	}else{
	                   	 		$newprefs['ut']=0;
	                   	 	}
	                	}else{
	                		$newprefs['timezone'] = $serverTimeZone;   
	                   	 	$newprefs['timez']  = $val;
	                   	 	$setTo = $serverTimeZone;
	                   	 	if(empty($prompt_users)){
	                   			$newprefs['ut']=1;
	                   	 	}else{
	                   	 		$newprefs['ut']=0;
	                   	 	}
	                	}
	                }else{
	                	$newprefs[$key] = $val;
	                	if(!empty($prefs['timezone'])){
	                		$alreadySet = 'Previously Set - '. $prefs['timezone'];	
	                	}
	                }
	                
	         
	                }else{
	                	$newprefs[$key] = $val;
	                }
	        }
	        	if($execute){
                    $db->query(
                        sprintf(
                            'UPDATE users
                            SET user_preferences = %s
                            WHERE id = %s',
                            $db->quoted(base64_encode(serialize($newprefs))),
                            $db->quoted($row['id'])
                        )
                    );
	        	}
	        }
		if(!empty($setTo)){
			echo $setTo;
		}else{
			if(!empty($alreadySet)){
				echo $alreadySet;
			}else{
				echo $serverTimeZone;
				$prefs['timezone'] = $serverTimeZone;
			}
		}
        echo "</td><td>";
        if(!empty($setTo)){
        	echo $mod_strings['LBL_UPDATE_TIMEZONE_ADJUST'].": ";
        if($execute){
			if(isset($_POST[$row['id'].'adjust'])){
                    echo SugarCleaner::cleanHtml($adjustment, false);
			}
		}else{
			echo "<select name='{$row['id']}adjust'>";
			
			echo get_select_options_with_id(array('-1'=>'-1', 'none'=>'0', '1'=>'+1'), $adjustment.'');
			echo '</select>';

		}
		echo ' '.$mod_strings['LBL_UPDATE_TIMEZONE_HOUR'];
        }
		echo ' </td><td>';
        echo "</tr>";

		$the_old_prefs[] = $prefs;
		$the_new_prefs[] = $newprefs;

        unset($prefs);
        unset($newprefs);
        unset($newstr);
}

echo "</table>";

if($execute){
	echo "<br>".$mod_strings['LBL_UPDATE_TIMEZONE_UPDATED']."!<br><br>";
	
}else{
	echo $mod_strings['LBL_UPDATE_TIMEZONE_PROMPT_USERS_CONFIRM'].":<input type='checkbox' name='prompt_users' value='1' $prompt_users><br>";
	echo "<input class='button' type='submit' name='execute' value='Execute'>&nbsp; <input class='button' type='submit' name='preview' value='Preview'>";
	
}
echo "&nbsp;<input class='button' type='button' name='Done' value='Done' onclick='document.location.href=\"index.php?action=DstFix&module=Administration\"'>";
?>
</form>
