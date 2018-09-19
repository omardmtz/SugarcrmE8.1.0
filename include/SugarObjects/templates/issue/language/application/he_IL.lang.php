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
$object_name = strtolower($object_name);
 $app_list_strings = array (

  $object_name.'_type_dom' =>
  array (
  	'Administration' => 'Administration',
    'Product' => 'מוצר',
    'User' => 'משתמש',
  ),
   $object_name.'_status_dom' =>
  array (
    'New' => 'חדש',
    'Assigned' => 'הוקצו',
    'Closed' => 'נסגר',
    'Pending Input' => 'קלט תלוי ועומד',
    'Rejected' => 'נדחה',
    'Duplicate' => 'שכפל',
  ),
  $object_name.'_priority_dom' =>
  array (
    'P1' => 'גבוה',
    'P2' => 'בינוני',
    'P3' => 'נמוך',
  ),
  $object_name.'_resolution_dom' =>
  array (
  	'' => '',
  	'Accepted' => 'התקבל',
    'Duplicate' => 'שכפל',
    'Closed' => 'נסגר',
    'Out of Date' => 'לא רלוונטי',
    'Invalid' => 'לא תקין',
  ),
  );
?>