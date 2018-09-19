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



class PMSECaseWrapper //extends FilterApi
{
    protected static $mandatory_fields = array(
        'id',
        'cas_id',
        'cas_index',
        'cas_user_id',
        'cas_flow_status',
        'cas_sugar_module',
        'cas_delegate_date',
        'cas_due_date'
    );

    protected function parseArguments(ServiceBase $api, array $args, SugarBean $seed = null)
    {
        $options = array();

        // Set up the defaults
        $options['limit'] = 20;
        $options['offset'] = 0;
        $options['order_by'] = array(array('date_modified', 'DESC'));
        $options['add_deleted'] = true;

        if (!empty($args['max_num'])) {
            $options['limit'] = (int) $args['max_num'];
        }
        if (!empty($args['deleted'])) {
            $options['add_deleted'] = false;
        }

        if (!empty($args['offset'])) {
            if ($args['offset'] == 'end') {
                $options['offset'] = 'end';
            } else {
                $options['offset'] = (int) $args['offset'];
            }
        }
        if (!empty($args['order_by']) && !empty($seed)) {
            $orderBys = explode(',', $args['order_by']);
            $orderByArray = array();
            foreach ($orderBys as $order) {
                $orderSplit = explode(':', $order);

                if (!isset($orderSplit[1]) || strtolower($orderSplit[1]) == 'desc') {
                    $orderSplit[1] = 'DESC';
                } else {
                    $orderSplit[1] = 'ASC';
                }
                $orderByArray[] = $orderSplit;
            }
            $options['order_by'] = $orderByArray;
        }

        // Set $options['module'] so that runQuery can create beans of the right
        // type.
        if (!empty($seed)) {
            $options['module'] = $seed->module_name;
        }


        //Set the list of fields to be used in the select.
        $options['select'] =  array();

        return $options;
    }
    public function _filter_aux(array $_Filter_Dates)
    {
        $_query_filter="";
        if(empty($_Filter_Dates))
        {
            return $_query_filter;
        }
        else{
                foreach($_Filter_Dates as $uno => $dos){
                    foreach($dos as $tres => $cuatro){
                        $def_filter_name=$tres;
                        foreach($cuatro as $cinco => $seis){
                            $def_filter_conditional=$cinco;
                            $def_filter_value=$seis;
                        }
                    }
                    $_query_filter.=$this->_concat_filter($def_filter_value,$def_filter_conditional,$def_filter_name);
                    //break;
                }
                return $_query_filter;
        }
    }
    public function _concat_filter($def_filter_value,$def_filter_conditional,$def_filter_name)
    {
        $_query_filter="";
            switch($def_filter_conditional)
            {
                case '$equals':

                    if(is_array($def_filter_value))
                    {
                        
                        
                        $_query_filter.=" AND ( ";
                        for($i=0;$i<count($def_filter_value);$i++)
                        {
                            $staticAssignmentCondition = '';
                            if ($def_filter_name=='act_assignment_method' && ($def_filter_value[$i]=='BALANCED' || $def_filter_value[$i]=='static')) {
                                $staticAssignmentCondition = " OR (act_assignment_method='selfservice' AND cas_started=1)";
                            }
                            $_query_filter.=($i==0)?$def_filter_name."='".$def_filter_value[$i]."' ":" OR ".$def_filter_name."='".$def_filter_value[$i]."' ";
                        }
                        $_query_filter.=" $staticAssignmentCondition)";
                    }
                    else
                    {
                        $_query_filter= " AND ( ".$def_filter_name."='".$def_filter_value."' OR((act_assignment_method='selfservice' OR act_assignment_method='BALANCED') AND cas_started=1  ))";
                    }
                    break;
                case '$not_in':$_query_filter= " AND ".$def_filter_name."<>'".$def_filter_value."'";
                    break;
                case '$in':$_query_filter= " AND ".$def_filter_name." IN ".$def_filter_value;
                    break;
                case '$is_null':$_query_filter= " AND ".$def_filter_name." IS NULL";
                    break;
                case '$like':$_query_filter= " AND ".$def_filter_name." LIKE '%".$def_filter_value."%'";
                    break;
                case '$gte':$_query_filter= " AND ".$def_filter_name." > '".$def_filter_value."'";
                    break;
                default:
                    $_query_filter=" ";
                    break;
            }
            return $_query_filter;
    }
    public function _FilterTime($time)
    {
        foreach($time as $uno => $dos){
            foreach($dos as $tres => $cuatro){
                if ($tres == 'in_time') {
                    foreach($cuatro as $cinco => $seis){
                        $def_filter_value=$seis;
                    }
                }
            }
        }

        if($def_filter_value=="true"){
            return true;
        }
        else{
            return false;
        }
    }
    public function retrieveCases($api, $args, $custom = false)
    {
        $_idRows=array();
        //echo $args;
        global $current_user;
        //Current user
        $userLogged = $current_user->id;

        //Current teams
        $team = BeanFactory::newBean('Teams');
        $teamsForThisUser = $team->get_teams_for_user($userLogged);
        $inTeams = "(";
        foreach ($teamsForThisUser as $key => $teamRow) {
            if ($inTeams == "(") {
                $inTeams .= "'" . $teamRow->id . "'";
            } else {
                $inTeams .= ", '" . $teamRow->id . "'";
            }
        }
        $inTeams .= ")";

        // Init the pmse_BpmFlow bean
        $flowBean = BeanFactory::newBean('pmse_BpmFlow');
        $inboxBean = BeanFactory::newBean('pmse_Inbox');

        $options = self::parseArguments($api, $args, $inboxBean);
        if (empty($options['select'])) {
            $options['select'] = self::$mandatory_fields;
        }
        $queryOptions = array('add_deleted' => (!isset($options['add_deleted'])||$options['add_deleted'])?true:false);
        if ($queryOptions['add_deleted'] == false) {
            $options['select'][] = 'deleted';
        }

        $q = new SugarQuery();

        // $fields will store the fields required
        $fields = array();
        foreach ($options['select'] as $field) {
            $fields[] = $field;
        }

        $fields = array(
            'a.*'
        );

        //$q->from($flowBean, $queryOptions);
        $q->select($fields);
        $q->from($inboxBean, array('alias' => 'a'));

        // Add raw joins to combine other tables
        //TODO Update this way to declare joins when SugarQuery will accept them.
        //$q->joinRaw('INNER JOIN pmse_inbox ON pmse_inbox.cas_id=pmse_bpm_flow.cas_id', array('alias'=>'pmse_inbox'));
        //$q->joinRaw('INNER JOIN pmse_bpmn_activity ON pmse_bpm_flow.bpmn_id=pmse_bpmn_activity.id', array('alias'=>'pmse_bpmn_activity'));
        $q->joinTable('pmse_bpm_flow', array('joinType' => 'LEFT', 'alias' => 'b'))
            ->on()->equalsField('a.cas_id', 'b.cas_id');
        $q->joinTable('pmse_bpmn_activity', array('joinType' => 'LEFT', 'alias' => 'c'))
            ->on()->equalsField('b.bpmn_id', 'c.id')->equals('b.bpmn_type', 'bpmnActivity');
        $q->joinTable('pmse_bpm_activity_definition', array('alias' => 'd'))
            ->on()->equalsField('c.id', 'd.id');
        //$q->joinRaw("INNER JOIN pmse_bpmn_process ON(e.id = a.pro_id)", array('alias'=>'e'));

        // Add external fields using fieldRaw method
        //$q->select->fieldRaw('pmse_inbox.id','inbox_id');
        //$q->select->fieldRaw('pmse_inbox.name','cas_name');
        //$q->select->fieldRaw('pmse_inbox.pro_title','pro_title');
        //$q->select->fieldRaw('pmse_bpmn_activity.name','task_name');

        if($auxValue=$this->closeFieldFilter($args['filter']))
        {
            $data=array();
            $data['records']='';
            return $data;
        }

        $_filter_array = $this->preProcessFilters($args['filter']);

        if ($this->hasStaticFilter($args['filter'])) {
            $_filter_array[] = array('b.cas_user_id'=> array('$equals' => array($userLogged)));
            //$_filter_array[] = array('b.cas_started'=> array('$equals' => 1));
            //AND (b.cas_user_id='$userLogged'
        } else {
            // AND (d.act_assign_team IN $inTeams AND b.cas_start_date IS NULL))
            $_filter_array[] = array('d.act_assign_team'=> array('$in' => $inTeams));
            $_filter_array[] = array('b.cas_start_date'=> array('$is_null' => ''));
        }

        $q->where()->queryAnd()
            ->addRaw("b.cas_flow_status='FORM' AND a.cas_status <> 'DELETED' ".self::_filter_aux($_filter_array)."");
        //->addRaw("b.cas_flow_status='FORM' AND (b.cas_user_id='$userLogged' OR (d.act_assign_team IN $inTeams  ".self::_filter_aux($_filter_array)." AND b.cas_start_date IS NULL)) AND a.cas_status <> 'DELETED'");

        ////->addRaw("b.cas_flow_status='FORM' AND (b.cas_user_id='$userLogged' OR (d.act_assign_team IN $inTeams AND d.act_assignment_method='selfservice' AND b.cas_start_date IS NULL)) AND a.cas_status <> 'DELETED' ".self::_filtritos($_infoFiltro)."");
        //addRaw("b.cas_flow_status='FORM' AND (b.cas_user_id='$userLogged' OR (d.act_assign_team IN $inTeams AND b.cas_start_date IS NULL)) AND a.cas_status <> 'DELETED'");

        $q->select->fieldRaw('b.id','flow_id');
        $q->select->fieldRaw('b.cas_delegate_date', 'cas_delegate_date');
        $q->select->fieldRaw('b.cas_start_date', 'cas_start_date');
        $q->select->fieldRaw('b.cas_task_start_date', 'cas_task_start_date');
        $q->select->fieldRaw('b.cas_sugar_module', 'cas_sugar_module');
        $q->select->fieldRaw('c.name','task_name');
        $q->select->fieldRaw('d.act_assignment_method', 'act_assignment_method');
        $q->select->fieldRaw('d.act_expected_time', 'act_expected_time');
        $q->select->fieldRaw("'true' as", 'in_time');
        //$q->distinct(true);

        foreach ($options['order_by'] as $orderBy) {
            $q->orderBy($orderBy[0], $orderBy[1]);
        }

        // Add an extra record to the limit so we can detect if there are more records to be found
//        $q->limit($options['limit'] + 1);
//        $q->offset($options['offset']);
        //remove limit for test

        $data_aux = new stdClass();
        $idRows = $q->execute();
        $cont_aux=1;
        foreach ($idRows as $key => $value) {
            $data_aux->cas_task_start_date = $value['cas_task_start_date'];
//            $data_aux->cas_task_start_date = $value['cas_start_date'];
            $data_aux->cas_delegate_date = $value['cas_delegate_date'];
            //-----
            $idRows[$key]["id2"]=$value["id"];
            $idRows[$key]["id"]=$value["id"].'_'.$cont_aux++;
            //-----
            $expected = $this->expectedTime($value['act_expected_time'], $data_aux);
            $idRows[$key]["expected_time_warning"] = $expected["expected_time_warning"];
            $idRows[$key]["expected_time_message"] = $expected["expected_time_message"];
            $idRows[$key]["expected_time_view"] = $expected["expected_time_view"];
            $idRows[$key]["expected_time"] = $expected["expected_time"];
            //loading values
            unset($idRows[$key]["in_time"]);
            if($expected["expected_time_warning"]==self::_FilterTime($_filter_array))
            {
                $idRows[$key]["in_time"] = false;
                unset($idRows[$key]);
            }
            else
            {
                $idRows[$key]["in_time"] = true;
            }
        }
        //reorganizing the record
        if(count($idRows)>0)
        {
            foreach ($idRows as $key => $row) {
                $auxRows[$key] = $row['cas_delegate_date'];
            }
            array_multisort($auxRows,SORT_DESC,$idRows);
        }
//        sort($idRows);
        //loading record for limit
        if(!isset($args['offset']) || (int) $args['offset']==-1 || empty($args['offset']))
            $_offset=0;
        elseif((int) $args['offset']>0)
            $_offset=(int) $args['offset'];
        $_auxCont=0;
        $i=$_offset;
        while($i<$_offset+(int)$args['max_num'] && $i<count($idRows)){
            $_idRows[]=$idRows[$i++];
        }
//        }while($i<$_offset+(int)$args['max_num'] && $i<count($idRows));
//        for($i=$_offset;$i<$_offset+(int)$args['max_num'];$i++)
//        {
//            $_idRows[]=$idRows[$i];
//        }
        if(count($idRows)>(int) $_offset+(int) $args['max_num']){
            $_nextOffset=(int) $_offset + (int)$args['max_num'];
        }
        else{
            $_nextOffset=-1;
        }
        //TODO Count record to calculate next_offset value

        //reload $options['offset'] and $options['limit']
        $options['limit']=$args['max_num'];

        if (!empty($args['offset'])) {
            if ($args['offset'] == 'end') {
                $options['offset'] = 'end';
            } else {
                $options['offset']=$_nextOffset;
            }
        }

        $data = array();
        $data['next_offset'] = $_nextOffset;
        $data['records'] = $_idRows;
//        $data['records'] = $idRows;
        $data['options'] = $options;
        $data['args'] = $args;
        //$data['expected'] = $expected;

        return $data;
    }

    public function expectedTime($actExpected, $caseData)
    {
        global $timedate;
        global $current_user;
        $returnArray = array();
        $returnArray['expected_time_warning'] = false;
        $returnArray['expected_time_message'] = false;
        $returnArray['expected_time_view'] = false;
        $returnArray['expected_time'] = '';
        $expTime = json_decode(base64_decode($actExpected));
        if (!empty($expTime) && !empty($expTime->time)) {
            $expectedTime = PMSEEngineUtils::processExpectedTime($expTime, $caseData);
            $currentDate = new DateTime($timedate->nowDb());
            if ($currentDate > $expectedTime){
                $returnArray['expected_time_warning'] = true;
                $returnArray['expected_time_message'] = true;
            }
            $returnArray['expected_time'] = $timedate->to_display_date_time($expectedTime->format('Y-m-d H:i:s'), true, true, $current_user);
        }
        return $returnArray;
    }

    /*
     * Calculates if the due date on a process is greater or less than the current time.
     * Accordingly it sets a expected time message and warning
     * @param string - datetime in string format
     * @return array
     */
    public function processDueDateTime($dueDateTimeStr)
    {
        global $timedate;
        global $current_user;

        $currentDate = new DateTime($timedate->nowDb());
        $returnArray = array();
        $returnArray['expected_time_warning'] = false;
        $returnArray['expected_time_message'] = false;
        $returnArray['expected_time_view'] = false;
        $returnArray['expected_time'] = '';

        if (!empty($dueDateTimeStr)) {
            $dueDateTime = new DateTime($dueDateTimeStr);
            if ($currentDate > $dueDateTime) {
                $returnArray['expected_time_warning'] = true;
                $returnArray['expected_time_message'] = true;
            }
            $returnArray['expected_time'] = $timedate->to_display_date_time($dueDateTimeStr, true, true, $current_user);
        }
        return $returnArray;
    }
    
    public function hasStaticFilter($filters)
    {
        $result = false;
        if (is_array($filters)){
            foreach ($filters as $filter) {
                $field = key($filter);
                $operator = key($filter[$field]);
                if ($field === 'act_assignment_method'
                    && $operator === '$equals'
                    && $filter[$field][$operator] === 'static') {
                    $result = true;
                }
            }
        }
        return $result;
    }
    
    public function preProcessFilters($filters = array())
    {
        if (is_array($filters)) {
            foreach ($filters as $filterKey => $filter) {
                $key = key($filter);
    //            if ($key == 'in_time') {
    //                unset($filters[$filterKey]);
    //            } else {
                $value = $this->parseFieldFilter($key, $filter[$key]);
                $result = array($key => $value);
                $filters[$filterKey] = $result;
    //            }
            }
        }
        return $filters;
    }
    
    public function parseFieldFilter($field, $filter)
    {
        $key = key($filter);
        if ($key != '$equals') {
            return $filter;
        }
        
        switch ($field) {
            case 'act_assignment_method':
                if ($filter[$key] == 'static') {
                    $filter[$key] = array('static', 'BALANCED');
                } else {
                    $filter[$key] = array('selfservice');
                }
                break;            
            default :
                $filter[$key] = array($filter[$key]);
                break;
        }
        return $filter;
    }
    public function closeFieldFilter($filters = array())
    {
        if (is_array($filters)) {
            foreach ($filters as $filterKey => $filter) {
                $key = key($filter);
                            if ($key == '$tracker' || $key == '$favorite' ) {
                                return true;
                            }
            }
        }
    }
}
