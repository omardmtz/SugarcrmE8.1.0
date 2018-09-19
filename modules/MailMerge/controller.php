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
require_once('soap/SoapHelperFunctions.php');
class MailMergeController extends SugarController{
    public function action_search(){
        //set ajax view
        $this->view = 'ajax';
        //get the module
        $module = !empty($_REQUEST['qModule']) ? $_REQUEST['qModule'] : '';
        //lowercase module name
        $lmodule = strtolower($module);
        //get the search term
        $term = !empty($_REQUEST['term']) ? $GLOBALS['db']->quote($_REQUEST['term']) : '';
        //in the case of Campaigns we need to use the related module
        $relModule = !empty($_REQUEST['rel_module']) ? $_REQUEST['rel_module'] : null;

        $max = !empty($_REQUEST['max']) ? $_REQUEST['max'] : 10;
        $order_by = !empty($_REQUEST['order_by']) ? $_REQUEST['order_by'] : $lmodule.".name";
        $offset = !empty($_REQUEST['offset']) ? $_REQUEST['offset'] : 0;
        $response = array();
        
        if(!empty($module)){
            $where = '';
            $deleted = '0';
            $using_cp = false;

            if(!empty($term))
            {
                if($module == 'Contacts' || $module == 'Leads')
                {
                    $where = $lmodule.".first_name like '%".$term."%' OR ".$lmodule.".last_name like '%".$term."%'";
                    $order_by = $lmodule.".last_name";
                }
                else
                {
                    $where = $lmodule.".name like '".$term."%'";
                }
            }

            if($module == 'CampaignProspects'){
                    $using_cp = true;
                    $module = 'Prospects';
                    $lmodule = strtolower($relModule);
                    $campign_where = $_SESSION['MAILMERGE_WHERE'];
                    $where = $lmodule.".first_name like '%".$term."%' OR ".$lmodule.".last_name like '%".$term."%'";
                    if($campign_where)
                        $where .= " AND ".$campign_where ;
                    $where .= " AND related_type = #".$lmodule."#";
            }

            $seed = BeanFactory::newBean($module);

            if($using_cp){
                $fields = array('id', 'first_name', 'last_name');
                $dataList = $seed->retrieveTargetList($where, $fields, $offset,-1,$max,$deleted);

            }else{
                $dataList = $seed->get_list($order_by, $where, $offset,-1,$max,$deleted);
            }

            $list = $dataList['list'];
            $row_count = $dataList['row_count'];

            $output_list = array();
            foreach($list as $value)
            {
                $output_list[] = get_return_value($value, $module);
            }

            $response['result'] = array('result_count'=>$row_count,'entry_list'=>$output_list);
        }
        
        $json = getJSONobj();
        $json_response = $json->encode($response, true);
        print $json_response;
    }
}

