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
/*********************************************************************************
 * $Id$
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$viewdefs = array (
  'Contacts' => 
  array (
    'QuickCreate' => 
    array (
      'templateMeta' => 
      array (
        'form' => 
        array (
          'hidden' => 
          array (
            '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
            '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
            '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
            '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
            '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">',
            '{if !empty($smarty.request.contact_id)}<input type="hidden" name="reports_to_id" value="{$smarty.request.contact_id}">{/if}',
            '{if !empty($smarty.request.contact_name)}<input type="hidden" name="report_to_name" value="{$smarty.request.contact_name}">{/if}',
          ),
        ),
        'maxColumns' => '2',
        'widths' => 
        array (
          array (
            'label' => '10',
            'field' => '30',
          ),
          array (
            'label' => '10',
            'field' => '30',
          ),
        ),
      ),
      'panels' => 
      array (
        'default' => 
        array (

          array (

            array (
              'name' => 'first_name',
                'customCode' => '{html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}'
                . '&nbsp;<input name="first_name" id="first_name" size="25" maxlength="25" type="text" value="{$fields.first_name.value}">',
            ),

            array (
              'name' => 'account_name',
            ),
          ),

          array (

            array (
              'name' => 'last_name',
              'displayParams'=>array('required'=>true),
            ),

            array (
              'name' => 'phone_work',
            ),
          ),

          array (

            array (
              'name' => 'title',
            ),

            array (
              'name' => 'phone_mobile',
            ),
          ),

          array (

            array (
              'name' => 'phone_fax',
            ),

            array (
              'name' => 'do_not_call',
            ),
          ),

          array (
            array (
              'name' => 'email1',
            ),
            array (
              'name' => 'lead_source',
            ),
          ),

          array (

            array (
              'name' => 'assigned_user_name',
            ),
            array (
              'name' => 'team_name',
            ),
          ),
        ),
      ),
    ),
  ),
);
?>
