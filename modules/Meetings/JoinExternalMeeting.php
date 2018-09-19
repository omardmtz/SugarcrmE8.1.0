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

/*
 * This file checks if you are invited to an external meeting, which is too expensive
 * to do per-row in lists so we direct them here and check before either forwarding
 * them along, or displaying an error message to the user.
 */

global $db, $current_user, $mod_strings, $app_strings, $app_list_strings;

$ret = $db->query("SELECT id FROM meetings_users WHERE meeting_id = '".$db->quote($_REQUEST['meeting_id'])."' AND user_id = '".$current_user->id."' AND deleted = 0",true);
$row = $db->fetchByAssoc($ret);

$meetingBean = BeanFactory::getBean('Meetings', $_REQUEST['meeting_id']);

if ( $_REQUEST['host_meeting'] == '1' ) {
    if($meetingBean->assigned_user_id == $GLOBALS['current_user']->id || is_admin($GLOBALS['current_user']) || is_admin_for_module($GLOBALS['current_user'],'Meetings')){
        SugarApplication::redirect($meetingBean->host_url);
    }else{
        //since they are now the owner of the meeting nor an Admin they cannot start the meeting.
        $ss = new Sugar_Smarty();
        $ss->assign('current_user',$current_user);
        $ss->assign('bean',$meetingBean->toArray());
        $ss->displayCustom('modules/Meetings/tpls/extMeetingNoStart.tpl');
    }
}else{
    if(isset($row['id']) || $meetingBean->assigned_user_id == $GLOBALS['current_user']->id || is_admin($GLOBALS['current_user']) || is_admin_for_module($GLOBALS['current_user'],'Meetings')){
      SugarApplication::redirect($meetingBean->join_url);
    }else{
        //if the user is not invited or the owner of the meeting or an admin then they cannot join the meeting.

        $ss = new Sugar_Smarty();
        $ss->assign('current_user',$current_user);
        $ss->assign('bean',$meetingBean->toArray());
        $ss->displayCustom('modules/Meetings/tpls/extMeetingNotInvited.tpl');
    }
}