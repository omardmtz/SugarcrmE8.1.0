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
  'Bugs' => 
  array (
    'QuickCreate' => 
    array (
      'templateMeta' => 
      array (
        'form' => 
        array (
          'hidden' => 
          array (
            0 => '<input type="hidden" name="account_id" value="{$smarty.request.account_id}">',
            1 => '<input type="hidden" name="contact_id" value="{$smarty.request.contact_id}">',
          ),
        ),
        'maxColumns' => '2',
        'widths' => 
        array (
          0 => 
          array (
            'label' => '10',
            'field' => '30',
          ),
          1 => 
          array (
            'label' => '10',
            'field' => '30',
          ),
        ),
      ),
      'panels' => 
      array (
        'DEFAULT' => 
        array (
          0 => 
          array (
            0 => 
            array (
              'name' => 'priority',
            ),
            1 => 
            array (
              'name' => 'assigned_user_name',
            ),
          ),
          1 => 
          array (
            0 => 
            array (
              'name' => 'source',
            ),
            1 => 
            array (
              'name' => 'team_name',
            ),
          ),
          2 => 
          array (
            0 => 
            array (
              'name' => 'type',
            ),
            1 => 
            array (
              'name' => 'status',
            ),
          ),
          3 => 
          array (
            0 => 
            array (
              'name' => 'product_category',
            ),
            1 => 
            array (
              'name' => 'found_in_release',
            ),
          ),
          4 => 
          array (
            0 => 
            array (
              'name' => 'name',
              'displayParams'=>array('required'=>true),
            ),
          ),
          5 => 
          array (
            0 => 
            array (
              'name' => 'description',
            ),
          ),
        ),
      ),
    ),
  ),
);
?>
