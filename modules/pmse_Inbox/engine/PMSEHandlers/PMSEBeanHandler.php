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

require_once 'modules/pmse_Inbox/engine/PMSEFieldsUtils.php';

use Sugarcrm\Sugarcrm\ProcessManager;

class PMSEBeanHandler
{
    /**
     * @var PMSELogger
     */
    protected $logger;

    /**
     * @var PMSEEvaluator
     */
    protected $evaluator;

    /**
     * @var PMSERelatedModule
     */
    protected $pmseRelatedModule;

    /**
     * @var PMSEExpressionEvaluator
     */
    protected $expressionEvaluator;

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->logger = PMSELogger::getInstance();
        $this->evaluator = ProcessManager\Factory::getPMSEObject('PMSEEvaluator');
        $this->pmseRelatedModule = ProcessManager\Factory::getPMSEObject('PMSERelatedModule');
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getEvaluator()
    {
        return $this->evaluator;
    }

    /**
     *
     * @param $evaluator
     * @codeCoverageIgnore
     */
    public function setEvaluator($evaluator)
    {
        $this->evaluator = $evaluator;
    }

    /**
     *
     * @param type $logger
     * @codeCoverageIgnore
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @param type $module
     * @param type $id
     * @return type
     * @codeCoverageIgnore
     */
    public function retrieveBean($module, $id = null)
    {
        return BeanFactory::getBean($module, $id);
    }

    /**
     * get the related modules of a determined bean passed as parameter
     * @global type $beanList
     * @global type $beanFiles
     * @global type $db
     * @param type $bean
     * @param type $flowBean
     * @param type $act_field_module
     * @return type
     */
    public function getRelatedModule($bean, $flowBean, $act_field_module)
    {
        global $beanList, $db;
        $moduleName = $flowBean['cas_sugar_module'];
        //$object_id = $flowBean->cas_sugar_object_id;

        $id_mainModule = $bean->id;

        $relatedNew = $this->getRelationshipData($act_field_module, $db);
        $left = $relatedNew['lhs_module'];
        $act_field_module = $related = $relatedNew['rhs_module'];

        if (!isset($beanList[$act_field_module])) {
            $this->logger->error("[][] $act_field_module module is not related to $moduleName, ain't appear in the bean list");
        } else {
            $this->logger->info("[][] $moduleName got a related module named: [$act_field_module]");
//            $moduleClassName = $beanList[$act_field_module];
//            $moduleDir = $beanFiles[$moduleClassName];
            $moduleName = $act_field_module;

            ///relationship
//            $relationship = "Relationship";
//            $RelationshipModuleDir = $beanFiles[$relationship];
//            require_once ($RelationshipModuleDir);
            $beanRelations = $this->retrieveBean("Relationships");
            $relation = $beanRelations->retrieve_by_sides($left, $related, $db);
            $ID_Related = $relation['rhs_key'];
            $this->logger->info("[][] $related $ID_Related field found.");

            // related module ID
//            $classRelatedBean = $beanList[$related];
//            $RelatedModuleDir = $beanFiles[$classRelatedBean];
//            require_once ($RelatedModuleDir);
            $beanRelated = $this->retrieveBean("$related");

            $singleCondition = $ID_Related . "='" . $id_mainModule . "'";
            $list_bean_related = $beanRelated->get_full_list('', $singleCondition);
            $len = sizeof($list_bean_related);
            if (isset($list_bean_related[$len - 1])) {
                $this->logger->info("[][] Getting the last related record of $len records.");
                $beanRelated = $list_bean_related[$len - 1];
            } else {
                $beanRelated->retrieve_by_string_fields(array($ID_Related => $id_mainModule), true);
            }
            if (!isset($beanRelated->id)) {
                $this->logger->info("[][] There is not a data relationship beetween $act_field_module and {$flowBean['cas_sugar_module']}");
                $bean = null;
            } else {
                $bean = $this->retrieveBean("{$related}", $beanRelated->id);
                $this->logger->info("[][] Related $act_field_module loaded using id: $beanRelated->id");
            }
        }
        return $bean;
    }

    /**
     * TODO: this function should move to utilities lib
     * Get the relationship data based on a relationship name.
     * @param string $relationName
     * @return array of fields
     */
    public function getRelationshipData($relationName, $db)
    {
        return SugarRelationshipFactory::getInstance()->getRelationshipDef($relationName);
    }

    /**
     * Merge Bean data into an email template
     * @param type $bean
     * @param type $template
     * @param type $evaluate
     * @return type
     */
    public function mergeBeanInTemplate($bean, $template, $evaluate = false)
    {
        //Parse template and return a string mergin bean fields and the template
        $component_array = $this->parseString($template, $bean->module_dir);
        $parsed_template = $this->mergingTemplate($bean, $template, $component_array, $evaluate);
        return trim($parsed_template);
    }

    public function mergingTemplate ($bean, $template, $component_array, $evaluate)
    {
        global $beanList;
        $replace_array = array();

        foreach ($component_array as $module_name => $module_array) {
            foreach ($module_array as $field => $field_array) {
                if (!isset($field_array['filter']) || !isset($beanList[$field_array['filter']])) {
                    if (isset($field_array['type']) && $field_array['type'] === 'relate') {
                        $newBean = $this->pmseRelatedModule->getRelatedModule($bean, $field_array['rel_module']);
                    } else if (isset($field_array['filter'])) {
                        $newBean = $this->pmseRelatedModule->getRelatedModule($bean, $field_array['filter']);
                    } else {
                        $newBean = $bean;
                    }
                } else {
                    $newBean = $bean;
                }
                $field = $field_array['name'];
                if ($newBean instanceof SugarBean) {
                    $def = $newBean->field_defs[$field];
                    if ($def['type'] == 'datetime' || $def['type'] == 'datetimecombo') {
                        $value = (!empty($newBean->fetched_row[$field])) ? $newBean->fetched_row[$field] : $newBean->$field;
                    } else if ($def['type'] == 'bool') {
                        $value = ($newBean->$field==1) ? true : false;
                    } else {
                        $value = $newBean->$field;
                    }
                } else {
                     $value = !empty($newBean) ? array_pop($newBean)->{$field_array['name']} : null;
                }
                if (($field_array['value_type']) === 'href_link') {
                    $replace_array[$field_array['original']] = bpminbox_get_href($newBean, $field, $value);
                } else {
                    $replace_array[$field_array['original']] = bpminbox_get_display_text($newBean, $field, $value);
                }
            }
        }

        foreach ($replace_array as $name => $replacement_value) {
            $template = str_replace($name, $replacement_value, $template);
        }
        return $template;
    }

    /**
     * Merge determined bean data into an determined text template, this could be
     * an email template, expression template, or another type of text with
     * bean variables in it.
     *
     * @global type $beanList
     * @param type $bean
     * @param type $template
     * @param type $component_array
     * @param type $evaluate
     * @return type
     */
    public function mergeTemplate($bean, $template, $component_array, $evaluate = false)
    {
        global $beanList;
        $replace_array = Array();
        $replace_type_array = array();


        foreach ($component_array as $module_name => $module_array) {
            //base module
            if ($module_name == $bean->module_dir) {
                foreach ($module_array as $field => $field_array) {
                    if ($field_array['value_type'] == 'href_link') {
                        //Create href link to target record
                        $replacement_value = $this->get_href_link($bean);
                    }

                    if ($field_array['value_type'] == 'future') {
                        if ($evaluate) {
                            $replacement_value = bpminbox_check_special_fields($field_array['name'], $bean, false,
                                array());
                        } else {
                            $replacement_value = bpminbox_check_special_fields($field_array['name'], $bean, false,
                                array());
                        }
                    }
                    if ($field_array['value_type'] == 'past') {
                        $replacement_value = bpminbox_check_special_fields($field_array['name'], $bean, true, array());
                    }

                    $replace_type_array[$field_array['original']] = get_bean_field_type($field_array['name'], $bean);
                    $replace_array[$field_array['original']] = implode(', ', unencodeMultienum($replacement_value));
                }
            } else {
                //Confirm this is an actual module in the beanlist
                if (isset($beanList[$module_name]) || isset($bean->field_defs[$module_name])) {
                    ///Build the relationship information using the Relationship handler
                    $rel_handler = $bean->call_relationship_handler("module_dir", true);
                    if (isset($bean->field_defs[$module_name])) {
                        $rel_handler->rel1_relationship_name = $bean->field_defs[$module_name]['relationship'];
                        $rel_module = get_rel_module_name($bean->module_dir, $rel_handler->rel1_relationship_name,
                            $bean->db);
                        $rel_handler->rel1_module = $rel_module;
                        $rel_handler->rel1_bean = get_module_info($rel_module);
                    } else {
                        $rel_handler->process_by_rel_bean($module_name);
                    }

                    foreach ($bean->field_defs as $field => $attribute_array) {
                        if (!empty($attribute_array['relationship']) && $attribute_array['relationship'] == $rel_handler->rel1_relationship_name) {
                            $rel_handler->base_vardef_field = $field;
                            break;
                        }
                    }
                    //obtain the rel_module object
                    $rel_list = $rel_handler->build_related_list("base");
                    if (!empty($rel_list[0])) {
                        $rel_object = $rel_list[0];
                        $rel_module_present = true;
                    } else {
                        $rel_module_present = false;
                    }

                    foreach ($module_array as $field => $field_array) {
                        if ($rel_module_present == true) {
                            if ($field_array['value_type'] == 'href_link') {
                                //Create href link to target record
                                $replacement_value = $this->get_href_link($rel_object);
                            } else {
                                //use future always for rel because fetched should always be the same
                                $replacement_value = bpminbox_check_special_fields($field_array['name'], $rel_object,
                                    false, array());
                            }
                        } else {
                            $replacement_value = "Invalid Value";
                        }
                        $replace_array[$field_array['original']] = implode(', ', unencodeMultienum($replacement_value));
                    }
                }
            }
        }

        foreach ($replace_array as $name => $replacement_value) {
            if ($evaluate) {
                $replacement_value = str_replace("\n", ' ', $replacement_value);
                $type = $replace_type_array[$name]['type'];
                $dbtype = $replace_type_array[$name]['db_type'];
                //TODO evaluate more types even Ids perhaps
                $this->logger->info("Field : $name , type: '$type',  DBtype: '$dbtype'");
                if (($dbtype == 'double' || $dbtype == 'int') && $type != 'currency') {
                    $replacement_value = trim($replacement_value);
                } elseif ($type == 'currency') {
                    //TODO hardcoded . , should use system currency format
                    $replacement_value = str_replace(",", '', $replacement_value);
                    $replacement_value = str_replace(".", ',', $replacement_value);
                    $replacement_value = floatval($replacement_value);
                } else {
                    //here $replacement_value must be datatime, time, string datatype values
                    $replacement_value = "'" . $replacement_value . "'";
                }
            } else {
                $replacement_value = nl2br($replacement_value);
            }
            $template = str_replace($name, $replacement_value, $template);
        }
        return $template;
    }

    /**
     * Executes a cast in order to process the value of a determined expression.
     * @param type $expression
     * @param type $bean
     * @return type
     */
    public function processValueExpression($expression, $bean)
    {
        global $timedate;
        $response = new stdClass();
        $dataEval = array();
        foreach ($expression as $value) {
            $expSubtype = PMSEEngineUtils::getExpressionSubtype($value);
            if ($value->expType != 'VARIABLE') {
                if (isset($expSubtype)) {
                    switch (strtoupper($expSubtype)) {
                        case 'INT':
                            $dataEval[] = (int)$value->expValue;
                            break;
                        case 'FLOAT':
                            $dataEval[] = (float)$value->expValue;
                            break;
                        case 'DOUBLE':
                            $dataEval[] = (double)$value->expValue;
                            break;
                        case 'NUMBER':
                            $dataEval[] = (float)$value->expValue;
                            break;
                        case 'CURRENCY':
                            $dataEval[] = json_encode($value);
                            break;
                        case 'BOOL':
                            $dataEval[] = $value->expValue == 'TRUE' ? true : false;
                            break;
                        default:
                            $dataEval[] = $value->expValue;
                            break;
                    }
                }
            } else {
                $fields = $value->expValue;
                $field_value = !empty($bean->fetched_row[$fields]) ? $bean->fetched_row[$fields] : $bean->$fields;
                switch (strtolower($expSubtype)) {
                    case 'currency':
                        $constantCurrency = new stdClass();
                        $constantCurrency->expType = 'CONSTANT';
                        $constantCurrency->expSubtype = 'currency';
                        $constantCurrency->expValue = $bean->$fields;
                        $constantCurrency->expField = $bean->currency_id;
                        $dataEval[] = json_encode($constantCurrency);
                        break;
                    case 'datetime':
                    case 'datetimecombo':
                        $dataEval[] = $timedate->asIso(new DateTime($field_value, new DateTimeZone('UTC')));
                        break;
                    default:
                        $dataEval[] = $field_value;
                }
            }
        }
        if (count($dataEval) > 1) {
            $response->value = $this->evaluator->evaluateExpression(json_encode($expression), $bean);
            $response->type = gettype($response->value);
        } else {
            $response->value = $dataEval[0];
            $response->type = $value->expSubtype;
        }
        if (strtolower($response->type) == 'timespan' ||
            (strtolower($response->type) == 'object' &&
                is_a($response->value, 'DateInterval'))) {
            if (!isset($this->expressionEvaluator)) {
                $this->expressionEvaluator = ProcessManager\Factory::getPMSEObject('PMSEExpressionEvaluator');
            }
            $now = new DateTime();
            $now->add($this->expressionEvaluator->processDateInterval($response->value));
            $response->value = $timedate->asIso($now);
        }

        if (strtolower($response->value) === 'now') {
            $response->value = $timedate->asIso(new DateTime());
        }
        return $response->value;
    }

    /**
     * Parse the variables strings
     * @param type $template
     * @param type $base_module
     * @return type
     */
    public function parseString($template, $base_module)
    {
        $component_array = Array();
        $component_array[$base_module] = Array();

        preg_match_all("/(({::)[^>]*?)(.*?)((::})[^>]*?)/", $template, $matches, PREG_SET_ORDER);

        foreach ($matches as $val) {
            $matched_component = $val[0];
            $matched_component_core = $val[3];

            $split_array = preg_split('{::}', $matched_component_core);

            //related module
            //0 - future/past/href_link 1 - base_module 2 - rel_module 3 - field
            if (!empty($split_array[3])) {
                $component_array[$split_array[2]][$split_array[3]]['name'] = $split_array[3];
                $component_array[$split_array[2]][$split_array[3]]['value_type'] = $split_array[0];
                $component_array[$split_array[2]][$split_array[3]]['original'] = $matched_component;
                $component_array[$split_array[2]][$split_array[3]]['type'] = 'relate';
                $component_array[$split_array[2]][$split_array[3]]['rel_module'] = $split_array[2];
            } else {
                //base module
                //0 - future/past/href_link 1 - base_module 2 - field
                if (!empty($split_array[2])) {
                    $meta_name = $split_array[2] . "_" . $split_array[0];
                    $component_array[$base_module][$meta_name]['name'] = $split_array[2];
                    $component_array[$base_module][$meta_name]['value_type'] = $split_array[0];
                    $component_array[$base_module][$meta_name]['original'] = $matched_component;
                } else {
                    //0 - base_module 1 - field
                    $meta_name = $split_array[0] . '_' . $split_array[1] . "_" . 'future';
                    $component_array[$base_module][$meta_name]['filter'] = $split_array[0];
                    $component_array[$base_module][$meta_name]['name'] = $split_array[1];
                    $component_array[$base_module][$meta_name]['value_type'] = 'future';
                    $component_array[$base_module][$meta_name]['original'] = $matched_component;

                    // If the base_module has an alternate name which matches the filter then use the
                    // base_module name as the filter instead of the alternate name
                    if (translate($base_module) === $component_array[$base_module][$meta_name]['filter']) {
                        $component_array[$base_module][$meta_name]['filter'] = $base_module;
                    }
                }
            }
        }

        return $component_array;
    }

    /**
     * Method to evaluate the activation date for any flow
     * @param $expre Date +/- unit time (minutes,hours,days, month, year) adding or substracting
     * @param $bean this value is send in cse the date a native Sugar Date
     * @return array with two values  $today and $dueDate
     */
    public function calculateDueDate($expre, $bean)
    {
        $isDate = false;
        $date = '';
        $arrayUnitPos = array();
        $arrayUnitNeg = array();
        foreach ($expre as $keyevn => $evn) {
            switch ($evn->expType) {
                case 'FIXED_DATE':
                    $isDate = true;
                    $date = $evn->expValue;
                    break;
                case 'SUGAR_DATE':
                    $string = $evn->expValue;
                    $isDate = true;
                    $date = $bean->$string;
                    break;
                case 'UNIT_TIME':
                    switch ($evn->expUnit) {
                        case 'minutes':
                            $arrayUnitPos['minutes'] = isset($arrayUnitPos['minutes']) ? $arrayUnitPos['minutes'] : 0;
                            $arrayUnitNeg['minutes'] = isset($arrayUnitNeg['minutes']) ? $arrayUnitNeg['minutes'] : 0;
                            if ($expre[$keyevn - 1]->expValue == '+') {
                                $arrayUnitPos['minutes'] = $arrayUnitPos['minutes'] + $evn->expValue;
                            } else {
                                $arrayUnitNeg['minutes'] = $arrayUnitNeg['minutes'] - $evn->expValue;
                            }
                            break;
                        case 'hours':
                            $arrayUnitPos['hours'] = isset($arrayUnitPos['hours']) ? $arrayUnitPos['hours'] : 0;
                            $arrayUnitNeg['hours'] = isset($arrayUnitNeg['hours']) ? $arrayUnitNeg['hours'] : 0;
                            if ($expre[$keyevn - 1]->expValue == '+') {
                                $arrayUnitPos['hours'] = $arrayUnitPos['hours'] + $evn->expValue;
                            } else {
                                $arrayUnitNeg['hours'] = $arrayUnitNeg['hours'] - $evn->expValue;
                            }
                            break;
                        case 'days':
                            $arrayUnitPos['days'] = isset($arrayUnitPos['days']) ? $arrayUnitPos['days'] : 0;
                            $arrayUnitNeg['days'] = isset($arrayUnitNeg['days']) ? $arrayUnitNeg['days'] : 0;
                            if ($expre[$keyevn - 1]->expValue == '+') {
                                $arrayUnitPos['days'] = $arrayUnitPos['days'] + $evn->expValue;
                            } else {
                                $arrayUnitNeg['days'] = $arrayUnitNeg['days'] - $evn->expValue;
                            }
                            break;
                        case 'months':
                            $arrayUnitPos['months'] = isset($arrayUnitPos['months']) ? $arrayUnitPos['months'] : 0;
                            $arrayUnitNeg['months'] = isset($arrayUnitNeg['months']) ? $arrayUnitNeg['months'] : 0;
                            if ($expre[$keyevn - 1]->expValue == '+') {
                                $arrayUnitPos['months'] = $arrayUnitPos['months'] + $evn->expValue;
                            } else {
                                $arrayUnitNeg['months'] = $arrayUnitNeg['months'] - $evn->expValue;
                            }
                            break;
                        case 'years':
                            $arrayUnitPos['year'] = isset($arrayUnitPos['year']) ? $arrayUnitPos['year'] : 0;
                            $arrayUnitNeg['year'] = isset($arrayUnitNeg['year']) ? $arrayUnitNeg['year'] : 0;
                            if ($expre[$keyevn - 1]->expValue == '+') {
                                $arrayUnitPos['year'] = $arrayUnitPos['year'] + $evn->expValue;
                            } else {
                                $arrayUnitNeg['year'] = $arrayUnitNeg['year'] - $evn->expValue;
                            }
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    //default
                    break;
            }
        }
        if ($isDate) {
            $dateInt = strtotime($date);
            $date_evn = date("Y-m-d H:i:s", $dateInt);
            if (!empty($arrayUnitPos) || !empty($arrayUnitNeg)) {
                foreach ($arrayUnitPos as $unit => $value) {
                    $duration = $value . ' ' . $unit;
                    $dueDate = date("Y-m-d H:i:s", strtotime("+$duration", $dateInt));
                    $dateInt = strtotime($dueDate);
                }
                foreach ($arrayUnitNeg as $unit => $value) {
                    $duration = $value . ' ' . $unit;
                    $dueDate = date("Y-m-d H:i:s", strtotime("$duration", $dateInt));
                    $dateInt = strtotime($dueDate);
                }
                if ($dueDate > $date_evn) {
                    $today = $date_evn;
                } else {
                    $today = $dueDate;
                    $dueDate = date("Y-m-d H:i:s", strtotime("+10 seconds", $dateInt));
                }
            } else {
                $today = $date_evn;
                $dueDate = date("Y-m-d H:i:s", strtotime("+1 day", $dateInt));
            }
        }
        return array($today, $dueDate);
    }

    /**
     *
     * @param type $module
     * @return \DeployedRelationships
     * @codeCoverageIgnore
     */
    public function getDeployedRelationships($module)
    {
        return new DeployedRelationships($module);
    }

    /**
     *
     * @global type $app_list_strings
     * @global type $sugar_config
     * @param type $bean
     * @return type
     */
    private function get_href_link($bean)
    {
        global $app_list_strings;
        global $sugar_config;
        $link = "{$sugar_config['site_url']}/index.php?module={$bean->module_dir}&action=DetailView&record={$bean->id}";
        return "<a href=\"$link\">Click Here</a>";
    }
}
