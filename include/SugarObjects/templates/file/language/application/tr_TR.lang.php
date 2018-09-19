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
    'Marketing' => 'Pazarlama',
    'Knowledege Base' => 'Bilgi Tabanı',
    'Sales' => 'Satışlar',
  ),

    strtolower($object_name).'_subcategory_dom' =>
    array (
    '' => '',
    'Marketing Collateral' => 'Pazarlama Teminatı',
    'Product Brochures' => 'Ürün Broşürleri',
    'FAQ' => 'SSS',
  ),

    strtolower($object_name).'_status_dom' =>
    array (
    'Active' => 'Aktif',
    'Draft' => 'Taslak',
    'FAQ' => 'SSS',
    'Expired' => 'Süresi Geçmiş',
    'Under Review' => 'İncelemede',
    'Pending' => 'Beklemede',
  ),
  );
