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
  'Opportunities' => 
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
        'javascript' => '{$PROBABILITY_SCRIPT}',
      ),
      'panels' => 
      array (
        'DEFAULT' => 
        array (
          array (
            array (
              'name' => 'name',
              'displayParams'=>array('required'=>true),
            ),
            array (
              'name' => 'account_name',
            ),
          ),
          array (
            array (
              'name' => 'currency_id',
            ),
            array (
              'name' => 'opportunity_type',
            ),            
          ),
          array (
            'amount',
            'date_closed'          
          ),
          array (
             'next_step',
             'sales_stage',
          ),
          array (
             'lead_source',
             'probability',
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
