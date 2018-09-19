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
 
$app_list_strings = array (
strtolower($object_name).'_category_dom' =>
    array (
    '' => '',
    'Marketing' => 'Marketing',
    'Knowledege Base' => 'Tudásbázis',
    'Sales' => 'Eladások',
  ),

    strtolower($object_name).'_subcategory_dom' =>
    array (
    '' => '',
    'Marketing Collateral' => 'Marketinganyagok',
    'Product Brochures' => 'Termékismertetők',
    'FAQ' => 'GYIK',
  ),

    strtolower($object_name).'_status_dom' =>
    array (
    'Active' => 'Aktív',
    'Draft' => 'Piszkozat',
    'FAQ' => 'GYIK',
    'Expired' => 'Lejárt',
    'Under Review' => 'Felülvizsgálat alatt',
    'Pending' => 'Függőben lévő',
  ),
  );
