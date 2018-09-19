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
$viewdefs['Users']['QuickCreate'] = array(
    'templateMeta' => array('maxColumns' => '2',
                            'widths' => array(
                                array('label' => '10', 'field' => '30'),
                                array('label' => '10', 'field' => '30')
                            ),
                            'form' => array(
                                'headerTpl'=>'modules/Users/tpls/EditViewHeader.tpl',
                                'footerTpl'=>'modules/Users/tpls/EditViewFooter.tpl',
                            ),
                            'javascript' => '<script type="text/javascript" src="modules/Users/UserEditView.js"></script>',
    ),
    'panels' => array (
        'LBL_USER_INFORMATION' => array (
            array(
                array(
                    'name'=>'user_name',
                    'displayParams' => array('required'=>true),
                ),
                'first_name'
            ),
            array(
                array(
                    'name' => 'status',
                    'customCode' => '{if $IS_ADMIN}@@FIELD@@{else}{$STATUS_READONLY}{/if}',
                    'displayParams' => array('required'=>true),
                ),
                array(
                    'name'=>'last_name',
                    'displayParams' => array('required'=>true),
                ),
            ),
            array(
                array(
                    'name'=>'email1',
                    'displayParams' => array('required'=>true),
                ),
                array(
                    'name'=>'UserType',
                    'customCode'=>'{if $IS_ADMIN}{$USER_TYPE_DROPDOWN}{else}{$USER_TYPE_READONLY}{/if}',
                ),
            ),
        ),
        'LBL_EMPLOYEE_INFORMATION' => array(
            array(
                array(
                    'name'=>'employee_status',
                    'customCode'=>'{if $IS_ADMIN}@@FIELD@@{else}{$EMPLOYEE_STATUS_READONLY}{/if}',
                ),
                'show_on_employees'
            ),
            array(
                array(
                    'name'=>'title',
                    'customCode'=>'{if $IS_ADMIN}@@FIELD@@{else}{$TITLE_READONLY}{/if}',
                ),
                'phone_work'
            ),
            array(
                array(
                    'name'=>'department',
                    'customCode'=>'{if $IS_ADMIN}@@FIELD@@{else}{$DEPT_READONLY}{/if}',
                ),
                array(
                    'name'=>'reports_to_name',
                    'customCode'=>'{if $IS_ADMIN}@@FIELD@@{else}{$REPORTS_TO_READONLY}{/if}',
                ),
            ),
        ),
    ),
);
