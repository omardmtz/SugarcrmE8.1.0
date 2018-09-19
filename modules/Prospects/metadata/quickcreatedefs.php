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
  'Prospects' => 
  array (
    'QuickCreate' => 
    array (
      'templateMeta' => 
      array (
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
        'LBL_PROSPECT_INFORMATION' => 
        array (
          0 => 
          array (
            0 => 
            array (
              'name' => 'first_name',
            ),
            1 => 
            array (
              'name' => 'phone_work',
            ),
          ),
          1 => 
          array (
            0 => 
            array (
              'name' => 'last_name',
              'displayParams'=>array('required'=>true)
            ),
            1 => 
            array (
              'name' => 'phone_mobile',
            ),
          ),
          2 => 
          array (
            0 => 
            array (
              'name' => 'account_name',
            ),
            1 => 
            array (
              'name' => 'phone_fax',
            ),
          ),
          3 => 
          array (
            0 => 
            array (
              'name' => 'title',
            ),
            1 => 
            array (
              'name' => 'department',
            ),
          ),
          4 => 
          array (
            0 => 
            array (
              'name' => 'team_name',
            ),
            1 => 
            array (
              'name' => 'do_not_call',
            ),
          ),
          5 => 
          array (
            0 => 
            array (
              'name' => 'assigned_user_name',
            ),
          ),
        ),
        'lbl_email_addresses' => 
        array (
          0 => 
          array (
            0 => 
            array (
              'name' => 'email1',
            ),
          ),
        ),
        'LBL_ADDRESS_INFORMATION' => 
        array (
          0 => 
          array (
            0 => 
            array (
              'name' => 'primary_address_street',
            ),
            1 => 
            array (
              'name' => 'alt_address_street',
            ),
          ),
        ),
      ),
    ),
  ),
);
?>
