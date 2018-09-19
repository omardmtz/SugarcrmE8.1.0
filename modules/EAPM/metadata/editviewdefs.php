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
$module_name = 'EAPM';
$viewdefs[$module_name]['EditView'] = array(
    'templateMeta' => array('maxColumns' => '2',
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30')
                                            ),
                            'form' => array(
                                'hidden'=>array('<input name="assigned_user_id" type="hidden" value="{$fields.assigned_user_id.value}" autocomplete="off">'),
                                'buttons' =>
                                array (
                                    array (
                                        'customCode' => '{if $bean->aclAccess("save")}<input title="{$MOD.LBL_CONNECT_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="{if $isDuplicate}this.form.return_id.value=\'\'; {/if}this.form.action.value=\'Save\'; if(check_form(\'EditView\'))this.form.submit();else return false;" type="submit" name="button" value="{$MOD.LBL_CONNECT_BUTTON_TITLE}" id="EditViewSave">{/if} '
                                    ),
                                    array (
                                        'customCode' => '<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" id="cancel_button" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="window.location.href=\'{$cancelUrl}\'; return false;" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">',
                                    ),
                                    array (
                                        'customCode' => '{if $bean->aclAccess("delete") && !empty($smarty.request.record)}<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" id="delete_button" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="button" onclick="this.form.return_module.value=\'Users\'; this.form.return_action.value=\'EditView\'; this.form.action.value=\'Delete\'; this.form.return_id.value=\'{$return_id}\'; if (confirm(\'{$APP.NTC_DELETE_CONFIRMATION}\')){ldelim}return true;{rdelim}else{ldelim}return false;{rdelim};" type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">{/if} ',
                                    ),
                                ),
                                'headerTpl'=>'modules/EAPM/tpls/EditViewHeader.tpl',
                                'footerTpl'=>'modules/EAPM/tpls/EditViewFooter.tpl',),
                                            ),

 'panels' =>array (
  'default' =>
  array (
    array(
        array(
            'name' => 'application',
            'displayParams'=>array('required'=>true)
        ),
        array('name' => 'note', 
              'type'=>'text', 
              'customCode' => '{if $fields.validated.value}{$MOD.LBL_CONNECTED}<div id="eapm_notice_div" style="display: none;"></div>{else}<div id="eapm_notice_div">&nbsp;</div>{/if}',
              'label' => 'LBL_STATUS',
        ),
    ),
    array (
        array('name' => 'name', 'displayParams' => array('required' => true) ),
        array('name'=>'password', 'type'=>'password', 'displayParams' => array('required' => true) ),
    ),
    array (
        array('name' => 'url',
              'displayParams' => array('required' => true),
              'customCode' => '<input type=\'text\' name=\'url\' id=\'url\' size=\'30\' maxlength=\'255\' value=\'{$fields.url.value}\' title=\'\' tabindex=\'104\' ><br>{$MOD.LBL_OMIT_URL}',
            )
    ),
  ),

),

);
?>
