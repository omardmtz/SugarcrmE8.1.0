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
// created: 2005-11-03 19:18:13
$acldefs['Quotes'] = array (
  'forms' => 
  array (
    'by_name' => 
    array (
      'Billing_account_button' => 
      array (
        'display_option' => 'disabled',
        'action_option' => 'list',
        'app_action' => 'EditView',
        'module' => 'Accounts',
      ),
      'Shipping_account_button' => 
      array (
        'display_option' => 'disabled',
        'action_option' => 'list',
        'app_action' => 'EditView',
        'module' => 'Accounts',
      ),
      'Billing_contact_button' => 
      array (
        'display_option' => 'disabled',
        'action_option' => 'list',
        'app_action' => 'EditView',
        'module' => 'Contacts',
      ),
      'Shipping_contact_button' => 
      array (
        'display_option' => 'disabled',
        'action_option' => 'list',
        'app_action' => 'EditView',
        'module' => 'Contacts',
      ),
      'Opportunity_button' => 
      array (
        'display_option' => 'disabled',
        'action_option' => 'list',
        'app_action' => 'EditView',
        'module' => 'Opportunities',
      ),
      'opp_to_quote_button' => 
      array (
        'display_option' => 'disabled',
        'action_option' => 'edit',
        'app_action' => 'DetailView',
        'module' => 'Opportunities',
      ),
    ),
  ),
  'form_names' => 
  array (
    'by_id' => 'by_id',
    'by_name' => 'by_name',
    'DetailView' => 'DetailView',
    'EditView' => 'EditView',
  ),
);
?>
