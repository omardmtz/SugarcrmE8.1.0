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


class PMSEImageGenerator
{
    /**
     * Case ID variable
     * @var
     */
    private $cas_id;

    /**
     * Project ID variable
     * @var
     */
    private $prj_id;

    /**
     * Process ID variable
     * @var
     */
    private $pro_id;

    /**
     * Logger variable
     * @var
     */
    private $logger;

    private $pmse;

    private $running_elements;

    /**
     * Variable which checks if all elements on the diagram should be highlighted
     * @var boolean
     */
    private $allElements = false;

    public function __construct()
    {
        $this->pmse = PMSE::getInstance();
    }

    public function get_image($cas_id)
    {
        if (isset($cas_id)) {
            $this->cas_id = $cas_id;
            //Get running elements
            $this->running_elements = $this->get_running_elements();
            //Get prj_id from Bpmn_Process
            $processBean = BeanFactory::getBean('pmse_BpmnProcess', $this->pro_id, array(), false);
            $this->prj_id = $processBean->prj_id;

            //GET DIAGRAMS
            $diagrams = $this->get_project_diagrams($this->prj_id, $processBean->deleted == "1" ? 1 : 0);
            foreach ($diagrams as $diagram) {
                $files = $this->diagram_to_png($diagram);
            }

        }
        return $files;
    }

    public function getProjectImage($id)
    {
        $processBean = BeanFactory::newBean('pmse_BpmnProcess');
        $this->allElements = true;
        $q = new SugarQuery();
        $q->select(array('id', 'prj_id', 'deleted'));
        $q->from($processBean);
        $q->where()
            ->equals('prj_id', $id);

        $rows = $q->execute();
        foreach ($rows as $row) {
            $this->cas_id = $row['id'];
            $this->running_elements = array();
            $this->prj_id = $row['prj_id'];
            $diagrams = $this->get_project_diagrams($this->prj_id, $row['deleted'] == "1" ? 1 : 0);
            foreach ($diagrams as $diagram) {
                $files = $this->diagram_to_png($diagram);
            }
        }

        return $files;
    }

    private function get_running_elements()
    {
        $q = new SugarQuery();

        $fields = array(
            'id',
            'cas_id',
            'cas_index',
            'pro_id',
            'bpmn_id',
            'bpmn_type',
            'cas_user_id',
            'cas_thread',
            'cas_flow_status',
            'cas_sugar_module',
            'cas_sugar_object_id'
        );

        $flowBean = BeanFactory::newBean('pmse_BpmFlow');

        $q->select($fields);
        $q->from($flowBean);
        //$q->select()->fieldRaw('count(bpmn_id)', 'total');
        $q->where()
            ->equals('cas_id', $this->cas_id);
        //$q->groupBy('bpmn_id');
        $q->orderBy('cas_index');

        $rows = $q->execute();


        $arrElements = array();
        $auxArray = array();

        if (is_array($rows) && !empty($rows)) {
            $this->pro_id = $rows[0]['pro_id'];
            foreach ($rows as $key => $value) {
                if (!array_key_exists($value['bpmn_id'], $auxArray)) {
                    $element = new stdClass();
                    $element->usr_id = $value['cas_user_id'];
                    $element->bpmn_shape = $value['bpmn_type'];
                    $element->running = ($value['cas_flow_status'] == 'FORM' || $value['cas_flow_status'] == 'SLEEPING') ? true : false;;
                    $element->terminated = ($value['cas_flow_status'] == 'TERMINATED') ? true : false;
                    $element->count = 1;
                    $auxArray[$value['bpmn_id']] = $element;
                } else {
                    $element_aux = $auxArray[$value['bpmn_id']];
                    $element_aux->count++;
                    $auxArray[$value['bpmn_id']] = $element_aux;
                }
            }
            $arrElements = $auxArray;
        }

        return $arrElements;
    }

    private function get_project_diagrams($prj_id, $show_deleted = 0)
    {
        if (!empty($prj_id)) {
            $diagramBean = BeanFactory::newBean('pmse_BpmnDiagram');
            $rows = $diagramBean->get_full_list("", "prj_id = '{$prj_id}'", false, $show_deleted);
            $response = array();
            //Retrieve Shapes of diagrams
            foreach ($rows as $row) {
                $tmp = new stdClass();
                $tmp->activities = $this->get_project_elements($prj_id, $row->id, 'bpmn_activity');
                $tmp->events = $this->get_project_elements($prj_id, $row->id, 'bpmn_event');
                $tmp->gateways = $this->get_project_elements($prj_id, $row->id, 'bpmn_gateway');
                $tmp->artifacts = $this->get_project_elements($prj_id, $row->id, 'bpmn_artifact');
                $tmp->flows = $this->get_project_flow($prj_id, $row->id);
                $response[] = $tmp;
            }

            return $response;
        }
    }

    private function get_project_elements($prj_id, $dia_id, $table)
    {
        $boundFields = array(
            //'bound.bou_uid',
            //'bound.prj_id',
            //'bound.dia_id',
            //'bound.element_id',
            'bound.bou_element',
            'bound.bou_element_type',
            'bound.bou_x',
            'bound.bou_y',
            'bound.bou_width',
            'bound.bou_height',
            'bound.bou_rel_position',
            'bound.bou_size_identical',
            'bound.bou_container'
        );
        $elementFields = array();
        switch ($table) {
            case 'bpmn_activity':
                $bean = BeanFactory::newBean('pmse_BpmnActivity');
                $elementFields = array(
                    'id',
                    'name',
                    //'act_uid',
                    //'prj_id',
                    //'pro_id',
                    'act_type',
                    'act_is_for_compensation',
                    'act_start_quantity',
                    'act_completion_quantity',
                    'act_task_type',
                    'act_implementation',
                    'act_instantiate',
                    'act_script_type',
                    'act_script',
                    'act_loop_type',
                    'act_test_before',
                    'act_loop_maximum',
                    'act_loop_condition',
                    'act_loop_cardinality',
                    'act_loop_behavior',
                    'act_is_adhoc',
                    'act_is_collapsed',
                    'act_completion_condition',
                    'act_ordering',
                    'act_cancel_remaining_instances',
                    'act_protocol',
                    'act_method',
                    'act_is_global',
                    'act_referer',
                    'act_default_flow',
                    'act_master_diagram'
                );
                break;
            case 'bpmn_event':
                $bean = BeanFactory::newBean('pmse_BpmnEvent');
                $elementFields = array(
                    'id',
                    'name',
                    //'evn_uid',
                    //'prj_id',
                    //'pro_id',
                    'evn_type',
                    'evn_marker',
                    'evn_is_interrupting',
                    'evn_attached_to',
                    'evn_cancel_activity',
                    'evn_activity_ref',
                    'evn_wait_for_completion',
                    'evn_error_name',
                    'evn_error_code',
                    'evn_escalation_name',
                    'evn_escalation_code',
                    'evn_condition',
                    'evn_message',
                    'evn_operation_name',
                    'evn_operation_implementation',
                    'evn_time_date',
                    'evn_time_cycle',
                    'evn_time_duration',
                    'evn_behavior'
                );
                break;
            case 'bpmn_gateway':
                $bean = BeanFactory::newBean('pmse_BpmnGateway');
                $elementFields = array(
                    'id',
                    'name',
                    //'gat_uid',
                    //'prj_id',
                    //'pro_id',
                    'gat_type',
                    'gat_direction',
                    'gat_instantiate',
                    'gat_event_gateway_type',
                    'gat_activation_count',
                    'gat_waiting_for_start',
                    'gat_default_flow'
                );
                break;
            case 'bpmn_artifact':
                $bean = BeanFactory::newBean('pmse_BpmnArtifact');
                $elementFields = array(
                    'id',
                    'name',
                    //'art_uid',
                    //'prj_id',
                    //'pro_id',
                    'art_type',
                    'art_category_ref'
                );

                break;
        }

        $q = new SugarQuery();
        //add fields to select
        $fields = array_merge($elementFields, $boundFields);
        $q->select($fields);
        $q->from($bean);
        $q->joinTable('pmse_bpmn_bound', array('alias' => 'bound', 'joinType' => 'INNER', 'linkingTable' => true))
            ->on()
            ->equalsField('bound.bou_element', 'id')
            ->equals('bound.deleted', 0);
        $q->select()->field($boundFields);
        $q->where()
            ->equals('prj_id', $prj_id);

        $rows = $q->execute();

        return $rows;
    }

    private function get_project_flow($prj_id, $dia_id)
    {
        $flowBean = BeanFactory::newBean('pmse_BpmnFlow');
        $rows = $flowBean->get_full_list("", "prj_id = '{$prj_id}' AND dia_id = '{$dia_id}'");
        $data = array();
        foreach ($rows as $row) {
            $field['id'] = $row->id;
            $field['name'] = $row->name;
            $field['flo_type'] = $row->flo_type;
            $field['flo_element_origin'] = $row->flo_element_origin;
            $field['flo_element_origin_type'] = $row->flo_element_origin_type;
            $field['flo_element_origin_port'] = $row->flo_element_origin_port;
            $field['flo_element_dest'] = $row->flo_element_dest;
            $field['flo_element_dest_type'] = $row->flo_element_dest_type;
            $field['flo_element_dest_port'] = $row->flo_element_dest_port;
            $field['flo_is_inmediate'] = $row->flo_is_inmediate;
            $field['flo_condition'] = $row->flo_condition;
            $field['flo_eval_priority'] = $row->flo_eval_priority;
            $field['flo_x1'] = $row->flo_x1;
            $field['flo_y1'] = $row->flo_y1;
            $field['flo_x2'] = $row->flo_x2;
            $field['flo_y2'] = $row->flo_y2;
            $field['flo_state'] = $row->flo_state;
            $data[] = $field;
        }
        return $data;
    }

    private function diagram_to_png($diagram)
    {
        //$serialize_data = serialize($diagram);
        //$data = unserialize($serialize_data);
        $png_data = $this->convert_png_array($diagram);
        //TODO: avoid hardcoded
        $sprite_filename = $this->pmse->getModulePath() . '/img/pmse_sprite_with_zoom.gif';
        //TODO: avoid hardcoded
        $sprite_filename_bw = $this->pmse->getModulePath() . '/img/adam_sprite_with_zoom_new_bw.gif';
        $image_sprite = imagecreatefromgif($sprite_filename);
        $image_sprite_bw = imagecreatefromgif($sprite_filename_bw);
        $sprite_map = $this->load_sprite_coords();

        $image = $this->allocate_diagram_image($png_data, $sprite_map, $image_sprite, $image_sprite_bw);

        return $image;
    }

    private function convert_png_array($data)
    {
        $pngArray = array();
        foreach ($data->activities as $activity) {
            $tmpData = array();
            $tmpData[0] = 'bpmnActivity';
            $tmpData[1] = $activity['bou_x'];
            $tmpData[2] = $activity['bou_y'];
            $tmpData[3] = $activity['bou_width'];
            $tmpData[4] = $activity['bou_height'];
            $tmpData[5] = $activity['act_type'];
            $tmpData[6] = $activity['name'];
            $tmpData[7] = $activity['act_task_type'] . '_' . $activity['act_loop_type'] . '_' . $activity['act_is_adhoc'] . '_' . $activity['act_is_collapsed'];
            $tmpData[8] = $activity['bou_element'];
            $tmpData[9] = $activity['act_script_type'];
            $tmpData[10] = $activity['id'];
            $pngArray[] = $tmpData;
        }

        foreach ($data->events as $event) {
            $tmpData = array();
            $tmpData[0] = 'bpmnEvent';
            $tmpData[1] = $event['bou_x'];
            $tmpData[2] = $event['bou_y'];
            $tmpData[3] = $event['bou_width'];
            $tmpData[4] = $event['bou_height'];
            if ($event['evn_type'] == 'BOUNDARY') {
                $tmpData[5] = $event['evn_is_interrupting'] . '_INTERMEDIATE_EVENT';
            } else {
                $tmpData[5] = $event['evn_is_interrupting'] . '_' . $event['evn_type'] . '_EVENT';
            }
            $tmpData[6] = $event['name'];
            if ($event['evn_type'] == 'BOUNDARY') {
                $tmpData[7] = 'INTERMEDIATE_' . $event['evn_marker'] . '_' . $event['evn_behavior'];
            } elseif ($event['evn_type'] == 'INTERMEDIATE') {
                if ($event['evn_marker'] == 'EMPTY') {
                    $tmpData[7] = 'EMPTY';
                } else {
                    if ($event['evn_behavior'] != '') {
                        $tmpData[7] = $event['evn_type'] . '_' . $event['evn_marker'] . '_' . $event['evn_behavior'];
                    } else {
                        $tmpData[7] = $event['evn_type'] . '_' . $event['evn_marker'];
                    }
                }
            } else {
                if ($event['evn_marker'] == 'EMPTY') {
                    $tmpData[7] = 'EMPTY';
                } else {
                    if ($event['evn_message'] != '') {
                        $tmpData[7] = $event['evn_type'] . '_' . $event['evn_marker'] . '_' . $event['evn_message'];
                    } else {
                        $tmpData[7] = $event['evn_type'] . '_' . $event['evn_marker'];
                    }
                }
            }
            $tmpData[8] = $event['bou_element'];
            $tmpData[9] = '';
            $tmpData[10] = $event['id'];
            $pngArray[] = $tmpData;
        }

        foreach ($data->gateways as $gateway) {
            $tmpData = array();
            $tmpData[0] = 'bpmnGateway';
            $tmpData[1] = $gateway['bou_x'];
            $tmpData[2] = $gateway['bou_y'];
            $tmpData[3] = $gateway['bou_width'];
            $tmpData[4] = $gateway['bou_height'];
            $tmpData[5] = $gateway['gat_type'] . '_GATEWAY';
            $tmpData[6] = $gateway['name'];
            $tmpData[7] = '';
            $tmpData[8] = $gateway['bou_element'];
            $tmpData[9] = '';
            $tmpData[10] = $gateway['id'];
            $pngArray[] = $tmpData;
        }
        foreach ($data->artifacts as $artifact) {
            $tmpData = array();
            $tmpData[0] = 'bpmnArtifact';
            $tmpData[1] = $artifact['bou_x'];
            $tmpData[2] = $artifact['bou_y'];
            $tmpData[3] = $artifact['bou_width'];
            $tmpData[4] = $artifact['bou_height'];
            $tmpData[5] = $artifact['name'];
            $tmpData[6] = $artifact['art_type'];
            $tmpData[7] = '';
            $tmpData[8] = $artifact['bou_element'];
            $tmpData[9] = '';
            $tmpData[10] = $artifact['id'];
            $pngArray[] = $tmpData;
        }
        foreach ($data->flows as $flow) {
            $tmpData = array();
            $tmpData[0] = 'bpmnFlow';
            $tmpData[1] = $flow['name'];
            $tmpData[2] = $flow['flo_type'];
            $tmpData[3] = $flow['flo_element_origin_type'];
            $tmpData[4] = $flow['flo_element_origin_port'];
            $tmpData[5] = $flow['flo_element_dest_type'];
            $tmpData[6] = $flow['flo_element_dest_port'];
            $tmpData[7] = $flow['flo_element_origin'];
            $tmpData[8] = $flow['flo_element_dest'];
            $tmpData[9] = $flow['flo_state'];
            $tmpData[10] = $flow['id'];
            $pngArray[] = $tmpData;
        }
        return $pngArray;
    }

    private function load_sprite_coords()
    {
        $xMap = array();
        $xMap['1_START_EVENT'] = array(3, 3);
        $xMap['0_START_EVENT'] = array(3, 3);
        $xMap['1_INTERMEDIATE_EVENT'] = array(39, 3);
        $xMap['0_INTERMEDIATE_EVENT'] = array(39, 3);
        $xMap['1_END_EVENT'] = array(75, 3);

        $xMap['START_MESSAGE'] = array(39, 111);
        $xMap['START_MESSAGE_Leads'] = array(3, 39);
        $xMap['START_MESSAGE_Opportunities'] = array(3, 75);
        $xMap['START_MESSAGE_Documents'] = array(3, 111);
        $xMap['END_TERMINATE'] = array(388, 427);
        $xMap['END_MESSAGE'] = array(75, 75);
        $xMap['INTERMEDIATE_TIMER_THROW'] = array(39, 39);
        $xMap['INTERMEDIATE_MESSAGE_THROW'] = array(39, 75);
        $xMap['INTERMEDIATE_TIMER_CATCH'] = array(39, 39);
        $xMap['INTERMEDIATE_MESSAGE_CATCH'] = array(75, 39);

        $xMap['EXCLUSIVE_GATEWAY'] = array(111, 3);
        $xMap['PARALLEL_GATEWAY'] = array(111, 51);
        $xMap['EVENTBASED_GATEWAY'] = array(127, 403);
        $xMap['INCLUSIVE_GATEWAY'] = array(175, 403);

        $xMap['TASK_USERTASK'] = array(254, 75);
        $xMap['TASK_SCRIPTTASK'] = array(254, 92);

        $xMap['arrow_target_right'] = array(133, 111);
        $xMap['arrow_target_left'] = array(133, 99);
        $xMap['arrow_target_top'] = array(145, 126);
        $xMap['arrow_target_bottom'] = array(133, 126);

        $xMap['arrow_conditional_source_right'] = array(157, 99);
        $xMap['arrow_conditional_source_left'] = array(157, 99);
        $xMap['arrow_conditional_source_top'] = array(157, 111);
        $xMap['arrow_conditional_source_bottom'] = array(157, 111);

        $xMap['arrow_default_source_right'] = array(145, 111);
        $xMap['arrow_default_source_left'] = array(145, 111);
        $xMap['arrow_default_source_top'] = array(145, 99);
        $xMap['arrow_default_source_bottom'] = array(145, 99);

        $xMap['scripttask_none'] = array(475, 83);
        $xMap['scripttask_assign_user'] = array(475, 42);
        $xMap['scripttask_assign_team'] = array(475, 0);
        $xMap['scripttask_change_field'] = array(475, 125);
        $xMap['scripttask_add_related_record'] = array(475, 167);
        $xMap['scripttask_business_rule'] = array(514, 105);

        $xMap['text_now'] = array(446, 0);
        $xMap['icon_terminated'] = array(446, 10);
        return $xMap;
    }

    private function allocate_diagram_image(array $pngData, $xSpriteMap, $imgSprite, $imgSpriteBW = '')
    {
        global $current_user;
        $font = $this->pmse->getModulePath() . '/img/arial.ttf';
        $minX = 10000;
        $minY = 10000;
        $maxW = 0;
        $maxH = 0;
        $border = 40;

        foreach ($pngData as $coords) {
            if ($coords[0] !== 'bpmnFlow') {
                if ($minX > $coords[1]) {
                    $minX = $coords[1];
                }
                if ($minY > $coords[2]) {
                    $minY = $coords[2];
                }
                if ($maxW < ($coords[1] + $coords[3])) {
                    $maxW = $coords[1] + $coords[3];
                }
                if ($maxH < ($coords[2] + $coords[4])) {
                    $maxH = $coords[2] + $coords[4];
                }
            }
        }

        $x1 = $minX - $border;
        $y1 = $minY - $border;
        $x2 = $maxW + $border;
        $y2 = $maxH + $border;
        $cWidth = $x2 - $x1;
        $cHeight = $y2 - $y1;

        if ($cWidth < 0 && $cHeight < 0) {
            $cWidth = 100;
            $cHeight = 100;
        }

        $img = imagecreate($cWidth, $cHeight);

        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        $red = imagecolorallocate($img, 255, 0, 0);
        $red_1 = imagecolorallocate($img, 255, 235, 235);
        $orange = imagecolorallocate($img, 255, 80, 0);
        $orange_1 = imagecolorallocate($img, 255, 210, 150);
        $green = imagecolorallocate($img, 0, 160, 0);
        $green_1 = imagecolorallocate($img, 195, 220, 195);
        $gray = imagecolorallocate($img, 0xC0, 0xC0, 0xC0);
        $aTaskColor = imagecolorallocate($img, 0x33, 0x66, 0x99);
        $aTaskFillColor = imagecolorallocate($img, 0xF0, 0xF5, 0xFB);
        $aSubProcessColor = $black;
        $aSubProcessFillColor = $white;
        $aNotSupportedColor = imagecolorallocate($img, 0xC0, 0xC0, 0xC0);
        $aNotSupportedFillColor = $white;


        foreach ($pngData as $figure) {
            $element_running = $this->get_element_running($figure[10]);
            $shape_image = $element_running->in_flow ? $imgSprite : $imgSpriteBW;
            $aTaskColor = $element_running->in_flow ? imagecolorallocate($img, 0x33, 0x66,
                0x99) : imagecolorallocate($img, 0xC0, 0xC0, 0xC0);
            $aTaskFillColor = $element_running->in_flow ? imagecolorallocate($img, 0xF0, 0xF5,
                0xFB) : imagecolorallocate($img, 230, 230, 230);
            switch ($figure[0]) {
                case 'bpmnActivity':
                    $X1 = $figure[1] - $x1;
                    $Y1 = $figure[2] - $y1;
                    $X2 = $X1 + $figure[3];
                    $Y2 = $Y1 + $figure[4];
                    $properties = explode('_', $figure[7]);
                    $points = array(
                        $X1 + 3,
                        $Y1,
                        $X2 - 3,
                        $Y1,
                        $X2,
                        $Y1 + 3,
                        $X2,
                        $Y2 - 3,
                        $X2 - 3,
                        $Y2,
                        $X1 + 3,
                        $Y2,
                        $X1,
                        $Y2 - 3,
                        $X1,
                        $Y1 + 3
                    );
                    $points2 = array(
                        $X1 + 5,
                        $Y1 + 2,
                        $X2 - 5,
                        $Y1 + 2,
                        $X2 - 2,
                        $Y1 + 5,
                        $X2 - 2,
                        $Y2 - 5,
                        $X2 - 5,
                        $Y2 - 2,
                        $X1 + 5,
                        $Y2 - 2,
                        $X1 + 2,
                        $Y2 - 5,
                        $X1 + 2,
                        $Y1 + 5
                    );
                    switch ($figure[5]) {
                        case 'TASK':
                            $borderColor = $aTaskColor;
                            $fillColor = $aTaskFillColor;
                            imagesetthickness($img, 2);
                            break;
                        default:
                            $borderColor = $aNotSupportedColor;
                            $fillColor = $aNotSupportedFillColor;
                            imagesetthickness($img, 2);
                    }
                    //CURRENT CASE
                    if ($element_running->running) {
                        $points_active = array(
                            $X1 + 3,
                            $Y1,
                            $X2 - 3,
                            $Y1,
                            $X2,
                            $Y1 + 3,
                            $X2,
                            $Y2 - 3,
                            $X2 - 3,
                            $Y2,
                            $X1 + 3,
                            $Y2,
                            $X1,
                            $Y2 - 3,
                            $X1,
                            $Y1 + 3
                        );
                        if ($current_user->id == $element_running->usr_id) {
                            imagefilledpolygon($img, $points, 8, $green_1);
                            imagepolygon($img, $points_active, 8, $green);
                        } else {
                            imagefilledpolygon($img, $points, 8, $red_1);
                            imagepolygon($img, $points_active, 8, $red);
                        }
                    } else {
                        imagefilledpolygon($img, $points, 8, $fillColor);
                        imagepolygon($img, $points, 8, $borderColor);
                    }
                    if ($element_running->terminated) {
                        imagecopymerge($img, $shape_image, $X2 - 15, $figure[2] - $y1 + 3,
                            $xSpriteMap['icon_terminated'][0], $xSpriteMap['icon_terminated'][1], 12, 12, 100);
                    }
                    if ($figure[5] == 'TRANSACTION') {
                        imagepolygon($img, $points2, 8, $borderColor);
                    }
                    if ($figure[5] == 'TASK' || $figure[5] == 'TASKCALLACTIVITY') {
                        if (isset($figure[9]) && $figure[9] != '') {
                            $css = 'scripttask_' . strtolower($figure[9]);
                            $spriteCoords = $xSpriteMap[$css];
                            imagecopymerge($img, $shape_image, $figure[1] - $x1 - 2, $figure[2] - $y1 - 2,
                                $spriteCoords[0], $spriteCoords[1], 39, 39, 100);
                        } else {
                            $css = 'TASK_' . strtoupper($properties[0]);
                            $spriteCoords = $xSpriteMap[$css];
                            imagecopymerge($img, $shape_image, $figure[1] - $x1 + 4, $figure[2] - $y1 + 4,
                                $spriteCoords[0], $spriteCoords[1], 12, 12, 100);
                        }
                    }
                    if ($figure[5] == 'TASK' && $properties[1] != 'NONE') {
                        $css = 'LOOP_' . strtoupper($properties[1]);
                        $spriteCoords = $xSpriteMap[$css];
                        imagecopymerge($img, $shape_image, $figure[1] - $x1 + ($figure[3] - 12) / 2,
                            $figure[2] - $y1 + $figure[4] - 12, $spriteCoords[0], $spriteCoords[1], 12, 12, 100);
                    }
                    if ($figure[5] != 'TASK' && $figure[5] != 'TASKCALLACTIVITY' && $properties[3] == 1) {
                        if ($properties[1] == 'NONE' && $properties[2] == 0) {
                            $spriteCoords = $xSpriteMap['LOOP_NONE'];
                            imagecopymerge($img, $shape_image, $figure[1] - $x1 + ($figure[3] - 12) / 2,
                                $figure[2] - $y1 + $figure[4] - 12, $spriteCoords[0], $spriteCoords[1], 12, 12, 100);
                        } else {
                            $t = 1;
                            if ($properties[2] == 1) {
                                $t++;
                            }
                            if ($properties[1] != 'NONE') {
                                $t++;
                            }
                            if ($t == 3) {
                                $spriteCoords = $xSpriteMap["LOOP_" . $properties[1]];
                                imagecopymerge($img, $shape_image, $figure[1] - $x1 + ($figure[3] - 36) / 2,
                                    $figure[2] - $y1 + $figure[4] - 12, $spriteCoords[0], $spriteCoords[1], 12, 12,
                                    100);
                                $spriteCoords = $xSpriteMap['LOOP_NONE'];
                                imagecopymerge($img, $shape_image, $figure[1] - $x1 + ($figure[3] - 12) / 2,
                                    $figure[2] - $y1 + $figure[4] - 12, $spriteCoords[0], $spriteCoords[1], 12, 12,
                                    100);
                                $spriteCoords = $xSpriteMap['ACTIVITY_ADHOC'];
                                imagecopymerge($img, $shape_image, $figure[1] - $x1 + ($figure[3] - 12) / 2 + 13,
                                    $figure[2] - $y1 + $figure[4] - 12, $spriteCoords[0], $spriteCoords[1], 12, 12,
                                    100);
                            } else {
                                if ($properties[1] != 'NONE') {
                                    $spriteCoords = $xSpriteMap["LOOP_" . $properties[1]];
                                    imagecopymerge($img, $shape_image, $figure[1] - $x1 + ($figure[3] - 12) / 2 + 7,
                                        $figure[2] - $y1 + $figure[4] - 12, $spriteCoords[0], $spriteCoords[1], 12, 12,
                                        100);
                                    $spriteCoords = $xSpriteMap['LOOP_NONE'];
                                    imagecopymerge($img, $shape_image, $figure[1] - $x1 + ($figure[3] - 12) / 2 + 6,
                                        $figure[2] - $y1 + $figure[4] - 12, $spriteCoords[0], $spriteCoords[1], 12, 12,
                                        100);
                                } else {
                                    //unknow process ??
                                }
                            }
                        }
                    }
                    //Print Text
                    if (isset($figure[9]) && $figure[9] != '') {
                        $tt = explode('_', $figure[7]);
                        $this->print_text($img, $figure[6], 10, 0, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0],
                            $tt[0]);
                    } else {
                        $this->print_text($img, $figure[6], 10, 0, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0],
                            $figure[5]);
                    }
                    break;
                case 'bpmnEvent':
                    $X1 = $figure[1] - $x1;
                    $Y1 = $figure[2] - $y1 + $figure[4] - 10;
                    $X2 = $X1 + $figure[3];
                    $Y2 = $Y1 + $figure[4] + 5;
                    $css = $figure[5];
                    $marker = $figure[7];
                    $spriteCoords = $xSpriteMap[$css];
                    $mk = explode('_', $figure[7]);
                    //CURRENT CASE
                    if (isset($element_running->running) && $element_running->running) {
                        //                        imageline($img, $X2 + 5 , $figure[2] - $y1, $X2 + 5, $figure[2] - $y1 + $figure[4], $red);
                        //                        imageline($img, $X2 , $figure[2] - $y1 - 5, $X2 + 5, $figure[2] - $y1, $red);
                        //                        imageline($img, $X2 + 5 ,  $figure[2] - $y1 + $figure[4], $X2,  $figure[2] - $y1 + $figure[4] + 5, $red);
                        //                        imagecopymerge ($img, $shape_image, $X2 + 10, $figure[2] - $y1 + (int)(($figure[4]/2)-5) , $xSpriteMap['text_now'][0], $xSpriteMap['text_now'][1], 24, 10, 100);
                        $event_active_filename = $this->pmse->getModulePath() . '/img/event_active.gif';
                        $event_active = imagecreatefromgif($event_active_filename);
                        if ($mk['1'] == 'TIMER') {
                            imagecopymerge($img, $event_active, $figure[1] - $x1 - 4, $figure[2] - $y1 - 4, 0, 0,
                                $figure[3] + 8, $figure[4] + 8, 100);
                        } else {
                            imagecopymerge($img, $event_active, $figure[1] - $x1 - 4, $figure[2] - $y1 - 4, 0, 41,
                                $figure[3] + 8, $figure[4] + 8, 100);
                        }
                    } else {
                        imagecopymerge($img, $shape_image, $figure[1] - $x1, $figure[2] - $y1, $spriteCoords[0],
                            $spriteCoords[1], $figure[3] + 3, $figure[4] + 3, 100);
                    }

                    if ($marker != 'EMPTY') {
                        //END_CANCELTHROW???
                        if (isset($xSpriteMap[$marker])) {
                            // patch for an inexistent index
                            // echo '<br>MARKER: <br>&nbsp&nbsp&nbsp&nbsp' . $marker .'<br>'; print_r($xSpriteMap[$marker]) . '<br>';
                            $spriteCoords2 = $xSpriteMap[$marker];
                            if (!($element_running->running && $mk[1] == 'TIMER')) {
                                imagecopymerge($img, $shape_image, $figure[1] - $x1, $figure[2] - $y1,
                                    $spriteCoords2[0], $spriteCoords2[1], $figure[3], $figure[4], 100);
                            }
                        }
                    }

                    if (isset($element_running->terminated) && $element_running->terminated) {
                        imagecopymerge($img, $shape_image, $X2, $figure[2] - $y1, $xSpriteMap['icon_terminated'][0],
                            $xSpriteMap['icon_terminated'][1], 12, 12, 100);
                    }

                    $this->print_text($img, $figure[6], 10, 0, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0]);
                    break;
                case 'bpmnGateway':
                    //                    print_r($figure); echo '<br><br>';
                    $X1 = $figure[1] - $x1;
                    $Y1 = $figure[2] - $y1 + $figure[4] - 10;
                    $X2 = $X1 + $figure[3];
                    $Y2 = $Y1 + $figure[4] + 5;
                    $css = $figure[5];
                    $spriteCoords = $xSpriteMap[$css];
                    imagecopymerge($img, $shape_image, $figure[1] - $x1, $figure[2] - $y1, $spriteCoords[0],
                        $spriteCoords[1], $figure[3], $figure[4], 100);
                    $this->print_text($img, $figure[6], 10, 0, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0]);
                    break;
                case 'bpmnArtifact':
                    $xX1 = $figure[1] - $x1;
                    $xY1 = $figure[2] - $y1;
                    $xX2 = $xX1 + $figure[3];
                    $xY2 = $xY1 + $figure[4];
                    imagesetthickness($img, 1);
                    if ($figure[6] == 'GROUP') {
                        imagedashedline($img, $xX1, $xY1, $xX2, $xY1, $black);
                        imagedashedline($img, $xX2, $xY1, $xX2, $xY2, $black);
                        imagedashedline($img, $xX2, $xY2, $xX1, $xY2, $black);
                        imagedashedline($img, $xX1, $xY2, $xX1, $xY1, $black);
                    }
                    if ($figure[6] == 'TEXTANNOTATION') {
                        imageline($img, $xX1, $xY1, $xX1, $xY2, $black);
                        imageline($img, $xX1, $xY1, $xX1 + 15, $xY1, $black);
                        imageline($img, $xX1, $xY2, $xX1 + 15, $xY2, $black);
                    }
                    $this->print_text($img, $figure[5], 10, 0, $black, $font, $xX1, $xY1, $xX2, $xY2, $figure[0],
                        $figure[5]);
                    break; //this break wasn't here ...
                case 'bpmnFlow':
                    imagesetthickness($img, 1);
                    $lines = json_decode($figure[9]);

                    if ($element_running->in_flow) {
                        $line_color = $black;
                        $shape_image = $imgSprite;
                    } else {
                        $line_color = $gray;
                        $shape_image = $imgSpriteBW;
                    }
                    foreach ($lines as $key => $segment) {
                        // echo '<br>Point'. $key . ': (' . $lines[$key]->x . ', ' . $lines[$key]->y .')';
                        // echo '<br>Point'. ($key + 1) . ': (' . $lines[$key+1]->x . ', ' . $lines[$key+1]->y .')';
                        if (isset($lines[$key + 1]) && $lines[$key + 1]->x != '' && $lines[$key + 1]->y != '') {
                            if ($figure[2] == 'MESSAGE' || $figure[2] == 'ASSOCIATION' || $figure[2] == 'DATAASSOCIATION') {
                                imagedashedline($img, $lines[$key]->x - $x1, $lines[$key]->y - $y1,
                                    $lines[$key + 1]->x - $x1, $lines[$key + 1]->y - $y1, $line_color);
                            } else {
                                imageline($img, $lines[$key]->x - $x1, $lines[$key]->y - $y1, $lines[$key + 1]->x - $x1,
                                    $lines[$key + 1]->y - $y1, $line_color);
                            }
                            if ((int)((sizeof($lines) - 1) / 2) == $key) {
                                if (isset($element_running->count) && $element_running->count > 1) {
                                    $cf = ' (' . $element_running->count . ')';
                                } else {
                                    $cf = '';
                                }
                                $this->print_text($img, $figure[1] . $cf, 10, 0, $black, $font, $lines[$key]->x - $x1,
                                    $lines[$key]->y, $lines[$key + 1]->x - $x1, $lines[$key + 1]->y - $y1);
                            }
                        }

                    }

                    $decorator_width = 11;
                    $decorator_height = 11;
                    //END DECORATOR
                    $colorBackView = imagecolorallocate($img, 24, 124, 220);
                    if ($lines[sizeof($lines) - 1]->x == $lines[sizeof($lines) - 2]->x) {
                        if ($lines[sizeof($lines) - 1]->y < $lines[sizeof($lines) - 2]->y) {
                            $spriteCoords = $xSpriteMap['arrow_target_bottom'];
                            imagecopymerge($img, $shape_image,
                                $lines[sizeof($lines) - 1]->x - (int)($decorator_width / 2) - $x1,
                                $lines[sizeof($lines) - 1]->y - $y1, $spriteCoords[0], $spriteCoords[1],
                                $decorator_width, $decorator_height, 100);
                        } else {
                            $spriteCoords = $xSpriteMap['arrow_target_top'];
                            imagecopymerge($img, $shape_image,
                                $lines[sizeof($lines) - 1]->x - (int)($decorator_width / 2) - $x1,
                                $lines[sizeof($lines) - 1]->y - $decorator_height - $y1, $spriteCoords[0],
                                $spriteCoords[1], $decorator_width, $decorator_height, 100);
                        }
                    } elseif (($lines[sizeof($lines) - 1]->y == $lines[sizeof($lines) - 2]->y)) {
                        if ($lines[sizeof($lines) - 1]->x < $lines[sizeof($lines) - 2]->x) {
                            $spriteCoords = $xSpriteMap['arrow_target_right'];
                            imagecopymerge($img, $shape_image, $lines[sizeof($lines) - 1]->x - $x1,
                                $lines[sizeof($lines) - 1]->y - (int)($decorator_height / 2) - $y1, $spriteCoords[0],
                                $spriteCoords[1], $decorator_width, $decorator_height, 100);
                        } else {
                            $spriteCoords = $xSpriteMap['arrow_target_left'];
                            imagecopymerge($img, $shape_image, $lines[sizeof($lines) - 1]->x - $decorator_width - $x1,
                                $lines[sizeof($lines) - 1]->y - (int)($decorator_height / 2) - $y1, $spriteCoords[0],
                                $spriteCoords[1], $decorator_width, $decorator_height, 100);
                        }
                    }

                    //SOURCE DECORATOR
                    if ($figure[2] === 'DEFAULT' OR $figure[2] === 'CONDITIONAL') {
                        if ($figure[2] === 'DEFAULT') {
                            $source_decorator = '_default';
                        } elseif ($figure[2] === 'CONDITIONAL') {
                            $source_decorator = '_conditional';
                        }

                        if ($lines[0]->x == $lines[1]->x) {
                            if ($lines[0]->y < $lines[1]->y) {
                                $spriteCoords = $xSpriteMap['arrow' . $source_decorator . '_source_top'];
                                imagecopymerge($img, $shape_image, $lines[0]->x - (int)($decorator_width / 2) - $x1,
                                    $lines[0]->y - $y1, $spriteCoords[0], $spriteCoords[1], $decorator_width,
                                    $decorator_height, 100);
                            } else {
                                $spriteCoords = $xSpriteMap['arrow' . $source_decorator . '_source_bottom'];
                                imagecopymerge($img, $shape_image, $lines[0]->x - (int)($decorator_width / 2) - $x1,
                                    $lines[0]->y - $decorator_height - $y1, $spriteCoords[0], $spriteCoords[1],
                                    $decorator_width, $decorator_height, 100);
                            }
                        } elseif (($lines[0]->y == $lines[1]->y)) {
                            if ($lines[0]->x < $lines[1]->x) {
                                $spriteCoords = $xSpriteMap['arrow' . $source_decorator . '_source_right'];
                                imagecopymerge($img, $shape_image, $lines[0]->x - $x1,
                                    $lines[0]->y - (int)($decorator_height / 2) - $y1, $spriteCoords[0],
                                    $spriteCoords[1], $decorator_width, $decorator_height, 100);
                            } else {
                                $spriteCoords = $xSpriteMap['arrow' . $source_decorator . '_source_left'];
                                imagecopymerge($img, $shape_image, $lines[0]->x - $decorator_width - $x1,
                                    $lines[0]->y - (int)($decorator_height / 2) - $y1, $spriteCoords[0],
                                    $spriteCoords[1], $decorator_width, $decorator_height, 100);
                            }
                        }
                    }
                    break;
            }
        }
        return $img;
    }

    private function print_text($IMG, $txt, $size, $angle, $color, $font, $x1, $y1, $x2, $y2, $type = '', $stype = '')
    {
        //TODO Create a section to write multi-line text
        $yy = 6;
        switch ($type) {
            case 'bpmnActivity':
            case 'bpmnArtifact':
                if ($stype == 'SCRIPTTASK') {
                    $line = $this->wrap_text($size, $angle, $font, $txt, $x2 + 50 - $x1);
                    //$yy = -10;
                } else {
                    $line = $this->wrap_text($size, $angle, $font, $txt, $x2 - $x1);
                    //$lines = count($line);
                    //$yy = floor($lines / 2) * (-13);
                }
                break;
            case 'bpmnEvent':
                $line = $this->wrap_text($size, $angle, $font, $txt, $x2 + 40 - $x1);
                break;
            case 'bpmnGateway':
                $line = $this->wrap_text($size, $angle, $font, $txt, $x2 + 40 - $x1);
                //                $yy = -10;
                break;
            default:
                $line = $this->wrap_text($size, $angle, $font, $txt, $x2 - $x1);
        }
        $h = count($line) * 16;
        foreach ($line as $value) {
            $w = strlen(trim($value)) * 6;
            $X = ($x1 + ((($x2 - $x1) - $w) / 2)) - 3;
            if ($type == 'bpmnActivity' && $stype == 'TASK') {
                $Y = $y1 + (($y2 - $y1) / 2) - floor($h / 2) + $yy;
            } elseif ($type == 'bpmnActivity' && $stype == 'SCRIPTTASK') {
                $Y = $y2 + $yy + 10;
            } else {
                $Y = $y1 + $yy + 13;
            }
            // imagestring() only supports Latin-2, use imagettftext() to support UTF-8
            $ttfont = 'themes/default/font/OpenSans-Semibold.ttf';
            imagettftext($IMG, $size, $angle, $X, $Y, $color, $ttfont, $value);
            $yy += 16;
        }
    }

    private function wrap_text($fontSize, $angle, $fontFace, $string, $width)
    {
        $pattern = '[\n|\r|\n\r]';
        $string = preg_replace($pattern, ' ', trim($string));
        $arr = explode(' ', $string);
        $sa = '';
        $sf = array();
        foreach ($arr as $word) {
            $sa_ = $sa;
            $sa .= ' ' . $word;
            $w = strlen(trim($sa)) * 6;
            if ($w >= $width) {
                $sf[] = $sa_;
                $sa = $word;
            } //else {
            //echo 'string: ' . $sa . ' width: ' . $w . '<br>';
            //}
        }
        $sf[] = $sa;
        return $sf;
    }

    private function get_element_running($id)
    {
        if ($this->allElements) {
            $element = $this->getElementObject(true);
            return $element;
        }
        $element = isset($this->running_elements[$id]) ? $this->running_elements[$id] : $this->getElementObject();
        if (isset($this->running_elements[$id])) {
            $element->in_flow = true;
        }
        return $element;
    }

    /**
     * Method which return an element for diagram
     * @param boolean $inFlow Determines if element will be rendered in color or b&w
     * @param boolean $running Currently running element
     * @param boolean $terminated Adds Terminated icon to element
     * @param int $count Adds # of iteration of element
     * @return stdClass
     */
    protected function getElementObject($inFlow = false, $running = false, $terminated = false, $count = 0)
    {
        $element = new stdClass();
        $element->in_flow = $inFlow;
        $element->running = $running;
        $element->terminated = $terminated;
        $element->count = $count;

        return $element;
    }

}
