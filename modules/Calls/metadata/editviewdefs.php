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

$viewdefs ['Calls'] = array(
    'EditView' => array(
        'templateMeta' => array(
            'maxColumns' => 2,
            'form' => array(
                'hidden' => array(
                    '<input type="hidden" name="isSaveAndNew" value="false">',
                ),
                'buttons' => array(
                    array(
                        'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id="SAVE_HEADER" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="SUGAR.calls.fill_invitees();CAL.fillRepeatData();document.EditView.action.value=\'Save\'; document.EditView.return_action.value=\'DetailView\'; {if isset($smarty.request.isDuplicate) && $smarty.request.isDuplicate eq "true"}document.EditView.return_id.value=\'\'; {/if}formSubmitCheck();;" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
                    ),
                    'CANCEL',
                    array(
                        'customCode' => '<input title="{$MOD.LBL_SEND_BUTTON_TITLE}" id="SAVE_SEND_HEADER" class="button" onclick="document.EditView.send_invites.value=\'1\';SUGAR.calls.fill_invitees();CAL.fillRepeatData();document.EditView.action.value=\'Save\';document.EditView.return_action.value=\'EditView\';document.EditView.return_module.value=\'{$smarty.request.return_module}\';formSubmitCheck();;" type="button" name="button" value="{$MOD.LBL_SEND_BUTTON_LABEL}">',
                    ),
                    array(
                        'customCode' => '{if $fields.status.value != "Held"}<input title="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}" id="CLOSE_CREATE_HEADER" accessKey="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_KEY}" class="button" onclick="SUGAR.calls.fill_invitees();CAL.fillRepeatData(); document.EditView.status.value=\'Held\'; document.EditView.action.value=\'Save\'; document.EditView.return_module.value=\'Calls\'; document.EditView.isDuplicate.value=true; document.EditView.isSaveAndNew.value=true; document.EditView.return_action.value=\'EditView\'; document.EditView.return_id.value=\'{$fields.id.value}\'; formSubmitCheck();" type="button" name="button" value="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_LABEL}">{/if}',
                    ),
                ),
                'buttons_footer' => array(
                    array(
                        'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id="SAVE_FOOTER" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="SUGAR.calls.fill_invitees();CAL.fillRepeatData();document.EditView.action.value=\'Save\'; document.EditView.return_action.value=\'DetailView\'; {if isset($smarty.request.isDuplicate) && $smarty.request.isDuplicate eq "true"}document.EditView.return_id.value=\'\'; {/if}formSubmitCheck();;" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
                    ),
                    'CANCEL',
                    array(
                        'customCode' => '<input title="{$MOD.LBL_SEND_BUTTON_TITLE}" id="SAVE_SEND_FOOTER" class="button" onclick="document.EditView.send_invites.value=\'1\';SUGAR.calls.fill_invitees();CAL.fillRepeatData();document.EditView.action.value=\'Save\';document.EditView.return_action.value=\'EditView\';document.EditView.return_module.value=\'{$smarty.request.return_module}\';formSubmitCheck();;" type="button" name="button" value="{$MOD.LBL_SEND_BUTTON_LABEL}">',
                    ),
                    array(
                        'customCode' => '{if $fields.status.value != "Held"}<input title="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}" id="CLOSE_CREATE_FOOTER" accessKey="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_KEY}" class="button" onclick="SUGAR.calls.fill_invitees();CAL.fillRepeatData(); document.EditView.status.value=\'Held\'; document.EditView.action.value=\'Save\'; document.EditView.return_module.value=\'Calls\'; document.EditView.isDuplicate.value=true; document.EditView.isSaveAndNew.value=true; document.EditView.return_action.value=\'EditView\'; document.EditView.return_id.value=\'{$fields.id.value}\'; formSubmitCheck();" type="button" name="button" value="{$APP.LBL_CLOSE_AND_CREATE_BUTTON_LABEL}">{/if}',
                    ),
                ),
                'headerTpl' => 'modules/Calls/tpls/header.tpl',
                'footerTpl' => 'modules/Calls/tpls/footer.tpl',
            ),
            'widths' => array(
                array(
                    'label' => 10,
                    'field' => 30,
                ),
                array(
                    'label' => 10,
                    'field' => 30,
                ),
            ),
            'javascript' => '<script type="text/javascript">{$JSON_CONFIG_JAVASCRIPT}</script>
<script>toggle_portal_flag();function toggle_portal_flag()  {ldelim} {$TOGGLE_JS} {rdelim}
function disableSaveBtn() {ldelim}document.getElementById(\'SAVE_HEADER\').disabled=true; document.getElementById(\'SAVE_FOOTER\').disabled=true; document.getElementById(\'SAVE_SEND_HEADER\').disabled=true; document.getElementById(\'SAVE_SEND_FOOTER\').disabled=true;{rdelim}
function formSubmitCheck(){ldelim}var duration=true;if(typeof(isValidDuration)!="undefined"){ldelim}duration=isValidDuration();{rdelim}if(check_form(\'EditView\') && duration && CAL.checkRecurrenceForm()){ldelim}disableSaveBtn();SUGAR.ajaxUI.submitForm("EditView");{rdelim}{rdelim}</script>',
            'useTabs' => false,
        ),
        'panels' => array(
            'lbl_call_information' => array(
                array(
                    array(
                        'name' => 'name',
                    ),
                    array(
                        'name' => 'status',
                        'fields' => array(
                            array(
                                'name' => 'direction',
                            ),
                            array(
                                'name' => 'status',
                            ),
                        ),
                    ),
                ),
                array(
                    array(
                        'name' => 'date_start',
                        'displayParams' => array(
                            'updateCallback' => 'SugarWidgetScheduler.update_time();',
                        ),
                        'label' => 'LBL_DATE_TIME',
                    ),
                    array(
                        'name' => 'parent_name',
                        'label' => 'LBL_LIST_RELATED_TO',
                    ),
                ),
                array(
                    array(
                        'name' => 'duration_hours',
                        'label' => 'LBL_DURATION',
                        'customCode' => '{literal}<script type="text/javascript">function isValidDuration() { form = document.getElementById(\'EditView\'); if ( form.duration_hours.value + form.duration_minutes.value <= 0 ) { alert(\'{/literal}{$MOD.NOTICE_DURATION_TIME}{literal}\'); return false; } return true; }</script>{/literal}<input id="duration_hours" name="duration_hours" size="2" maxlength="2" type="text" value="{$fields.duration_hours.value}" onkeyup="SugarWidgetScheduler.update_time();"/>{$fields.duration_minutes.value}&nbsp;<span class="dateFormat">{$MOD.LBL_HOURS_MINUTES}</span>',

                    ),
                    array(
                        'name' => 'reminder_time',
                        'customCode' => '{include file="modules/Meetings/tpls/reminders.tpl"}',
                        'label' => 'LBL_REMINDER',
                    ),
                ),
                array(
                    array(
                        'name' => 'description',
                        'comment' => 'Full text of the note',
                        'label' => 'LBL_DESCRIPTION',
                    ),
                ),
            ),
            'LBL_PANEL_ASSIGNMENT' => array(
                array(
                    array(
                        'name' => 'assigned_user_name',
                        'label' => 'LBL_ASSIGNED_TO_NAME',
                    ),
                    array(
                        'name' => 'team_name',
                        'displayParams' => array('display' => true),
                    ),
                ),
            ),
        ),
    ),
);
