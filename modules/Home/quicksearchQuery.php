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

SugarAutoLoader::requireWithCustom('modules/Home/QuickSearch.php');
if(class_exists('quicksearchQueryCustom')) {
    $quicksearchQuery = new quicksearchQueryCustom();
}
else
{
    $quicksearchQuery = new QuickSearchQuery();
}

$json = getJSONobj();
$data = $json->decode(html_entity_decode($_REQUEST['data']));
if(isset($_REQUEST['query']) && !empty($_REQUEST['query'])){
    foreach($data['conditions'] as $k=>$v){
        if (empty($data['conditions'][$k]['value'])
            && ($data['conditions'][$k]['op'] != QuickSearchQuery::CONDITION_EQUAL)) {
            $data['conditions'][$k]['value']=urldecode($_REQUEST['query']);
        }
    }
}

$method = !empty($data['method']) ? $data['method'] : 'query';
if (is_callable(array($quicksearchQuery, $method))) {
   echo $quicksearchQuery->$method($data);
}
