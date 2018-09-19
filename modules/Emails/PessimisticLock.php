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
global $mod_strings;
global $locale;
$userName = $mod_strings['LBL_UNKNOWN'];

if(isset($_REQUEST['user'])) {	
	$user = BeanFactory::getBean('Users', $_REQUEST['user']);
	$userName = $locale->formatName($user);
}

// NEXT FREE
if(isset($_REQUEST['next_free']) && $_REQUEST['next_free'] == true) {
	
	$next = BeanFactory::newBean('Emails');
	$rG = $next->db->query('SELECT count(id) AS c FROM users WHERE deleted = 0 AND users.is_group = 1');
	$aG = $next->db->fetchByAssoc($rG);
	if($rG['c'] > 0) {
		$rG = $next->db->query('SELECT id FROM users WHERE deleted = 0 AND users.is_group = 1');
		$aG = $next->db->fetchByAssoc($rG);
		while($aG = $next->db->fetchByAssoc($rG)) {
			$ids[] = $aG['id'];
		}
		$in = ' IN (';
		foreach($ids as $k => $id) {
			$in .= '"'.$id.'", ';
		}
		$in = substr($in, 0, (strlen($in) - 2));
		$in .= ') ';
		
		$team = '';
		$qT = 'SELECT count(team_id) AS c FROM team_memberships WHERE user_id'.$in;
		$rT = $next->db->query($qT);
		while($aT = $next->db->fetchByAssoc($rT)) {
			$tIds[] = $aT['team_id'];
		}

		if(count($tIds) > 0) {
			$team = ' AND team_id IN (';
			foreach($tIds as $k => $tid) {
				$team .= '"'.$tid.'", ';	
			}
			$team = substr($team, 0, (strlen($team) - 2));
			$team .= ') ';
		}
		
		$qE = 'SELECT count(id) AS c FROM emails WHERE deleted = 0 AND assigned_user_id'.$in.$team.'LIMIT 1';
		$rE = $next->db->query($qE);
		$aE = $next->db->fetchByAssoc($rE);

		if($aE['c'] > 0) {
			$qE = 'SELECT id FROM emails WHERE deleted = 0 AND assigned_user_id'.$in.$team.'LIMIT 1';
			$rE = $next->db->query($qE);
			$aE = $next->db->fetchByAssoc($rE);
			$next->retrieve($aE['id']);
			$next->assigned_user_id = $current_user->id;
			$next->save();
			
			header('Location: index.php?module=Emails&action=DetailView&record='.$next->id);
			
		} else {
			// no free items
			header('Location: index.php?module=Emails&action=ListView&type=inbound&group=true');
		}
	} else {
		// no groups
		header('Location: index.php?module=Emails&action=ListView&type=inbound&group=true');
	}
}
?>
<table width="100%" cellpadding="12" cellspacing="0" border="0">
	<tr>
		<td valign="middle" align="center" colspan="2">
			<?php echo $mod_strings['LBL_LOCK_FAIL_DESC']; ?>
			<br>
			<?php echo $userName.$mod_strings['LBL_LOCK_FAIL_USER']; ?>
		</td>
	</tr>
	<tr>
		<td valign="middle" align="right" width="50%">
			<a href="index.php?module=Emails&action=ListView&type=inbound&group=true"><?php echo $mod_strings['LBL_BACK_TO_GROUP']; ?></a>
		</td>
		<td valign="middle" align="left">
			<a href="index.php?module=Emails&action=PessimisticLock&next_free=true"><?php echo $mod_strings['LBL_NEXT_EMAIL']; ?></a>
		</td>
	</tr>
</table>
