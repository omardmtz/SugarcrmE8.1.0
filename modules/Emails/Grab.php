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
// $Id: Grab.php 51719 2009-10-22 17:18:00Z mitani $

global $current_user;


$focus = BeanFactory::newBean('Emails');
// Get Group User IDs
$groupUserQuery = 'SELECT name, group_id FROM inbound_email ie INNER JOIN users u ON (ie.group_id = u.id AND u.is_group = 1)';
$groupUserQuery = 'SELECT group_id FROM inbound_email ie';
$groupUserQuery .= ' INNER JOIN team_memberships tm ON ie.team_id = tm.team_id';
$groupUserQuery .= ' INNER JOIN teams ON ie.team_id = teams.id AND teams.private = 0';
$groupUserQuery .= ' WHERE tm.user_id = \''.$current_user->id.'\'';
_pp($groupUserQuery);
$r = $focus->db->query($groupUserQuery);
$groupIds = '';
while($a = $focus->db->fetchByAssoc($r)) {
	$groupIds .= "'".$a['group_id']."', ";
}
$groupIds = substr($groupIds, 0, (strlen($groupIds) - 2));

$query = 'SELECT emails.id AS id FROM emails';
$query .= ' LEFT JOIN team_memberships ON emails.team_id = team_memberships.team_id';
$query .= ' LEFT JOIN teams ON emails.team_id = teams.id ';
$query .= " WHERE emails.deleted = 0 AND emails.status = 'unread' AND emails.assigned_user_id IN ({$groupIds})";  
if(!$current_user->is_admin) {
	$query .= "	AND team_memberships.user_id = '{$current_user->id}'";
}
//$query .= ' LIMIT 1';

//_ppd($query);
$r2 = $focus->db->query($query); 
$count = 0;
$a2 = $focus->db->fetchByAssoc($r2);

$focus->retrieve($a2['id']);
$focus->assigned_user_id = $current_user->id;
$focus->save();

if(!empty($a2['id'])) {
	header('Location: index.php?module=Emails&action=ListView&type=inbound&assigned_user_id='.$current_user->id);
} else {
	header('Location: index.php?module=Emails&action=ListView&show_error=true&type=inbound&assigned_user_id='.$current_user->id);
}

?>
