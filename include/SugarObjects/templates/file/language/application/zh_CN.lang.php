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
    'Marketing' => '市场营销',
    'Knowledege Base' => '知识库',
    'Sales' => '销售',
  ),

    strtolower($object_name).'_subcategory_dom' =>
    array (
    '' => '',
    'Marketing Collateral' => '营销资料',
    'Product Brochures' => '产品手册',
    'FAQ' => '常见问题',
  ),

    strtolower($object_name).'_status_dom' =>
    array (
    'Active' => '启用',
    'Draft' => '草稿',
    'FAQ' => '常见问题',
    'Expired' => '失效',
    'Under Review' => '审查中',
    'Pending' => '未决定',
  ),
  );
