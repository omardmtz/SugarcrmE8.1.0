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


/**
 * Used to evaluate related expressions on the front end for arbitrary (possibly unsaved) records.
 */
class RelatedValueApi extends SugarApi
{
    /**
     * Rest Api Registration Method
     *
     * @return array
     */
    public function registerApiRest()
    {
        $parentApi = array(
            'related_value' => array(
                'reqType' => 'GET',
                'path' => array('ExpressionEngine', '?', 'related'),
                'pathVars' => array('', 'record', ''),
                'method' => 'getRelatedValues',
                'shortHelp' => 'Retrieve the Chart data for the given data in the Forecast Module',
                'longHelp' => 'modules/Forecasts/clients/base/api/help/ForecastChartApi.html',
            ),
        );
        return $parentApi;
    }

    /**
     * Used by the dependency manager to pre-load all the related fields required
     * to load an entire view.
     */
    public function getRelatedValues(ServiceBase $api, array $args)
    {
        if (empty($args['module']) || empty($args['fields'])) {
            return;
        }
        $fields = json_decode(html_entity_decode($args['fields']), true);
        $focus = $this->loadBean($api, $args);
        $ret = array();
        foreach ($fields as $rfDef) {
            if (!isset($rfDef['link']) || !isset($rfDef['type'])) {
                continue;
            }
            $link = $rfDef['link'];
            $type = $rfDef['type'];
            $rField = '';
            if (!isset($ret[$link])) {
                $ret[$link] = array();
            }
            if (empty($ret[$link][$type])) {
                $ret[$link][$type] = array();
            }
            // count formulas don't have a relate attribute
            if (isset($rfDef['relate'])) {
                $rField = $rfDef['relate'];
            }

            // Switch the type to the correct name
            if ($type == 'rollupAvg') {
                $type = 'rollupAve';
            } else if ($type == 'rollupCurrencySum') {
                $type = 'rollupSum';
            }

            switch ($type) {
                //The Related function is used for pulling a sing field from a related record
                case "related":
                    //Default it to a blank value
                    $ret[$link]['related'][$rfDef['relate']] = "";

                    //If we have neither a focus id nor a related record id, we can't retrieve anything
                    $relBean = null;
                    if (empty($rfDef['relId']) || empty($rfDef['relModule'])) {
                        //If the relationship is invalid, just move onto another field
                        if (!$focus->load_relationship($link)) {
                            break;
                        }

                        $beans = $focus->$link->getBeans(array("enforce_teams" => true));
                        //No related beans means no value
                        if (empty($beans)) {
                            break;
                        }
                        //Grab the first bean on the list
                        reset($beans);
                        $relBean = current($beans);
                    } else {
                        $relBean = BeanFactory::getBean($rfDef['relModule'], $rfDef['relId']);
                    }
                    //If we found a bean and the current user has access to the related field, grab a value from it
                    if (!empty($relBean) && ACLField::hasAccess($rfDef['relate'], $relBean->module_dir, $GLOBALS['current_user']->id, true)) {
                        $validFields = FormulaHelper::cleanFields($relBean->field_defs, false, true, true);
                        if (isset($validFields[$rfDef['relate']])) {
                            $ret[$link]['relId'] = $relBean->id;
                            $ret[$link]['related'][$rfDef['relate']] =
                                FormulaHelper::getFieldValue($relBean, $rfDef['relate']);
                        }
                    }

                    break;
                case "count":
                    if ($focus->load_relationship($link)) {
                        $ret[$link][$type] = count($focus->$link->get());
                    } else {
                        $ret[$link][$type] = 0;
                    }
                    break;
                case "rollupSum":
                case "rollupAve":
                case "rollupMin":
                case "rollupMax":
                    //If we are going to calculate one rollup, calculate all the rollups since there is so little cost
                    if ($focus->load_relationship($link)) {
                        $relBeans = $focus->$link->getBeans(array("enforce_teams" => true));
                        $sum = 0;
                        $count = 0;
                        $min = false;
                        $max = false;
                        $values = array();
                        if (!empty($relBeans)) {
                            //Check if the related record vardef has banned this field from formulas
                            $relBean = reset($relBeans);
                            $validFields = FormulaHelper::cleanFields($relBean->field_defs, false, true, true);
                            if (!isset($validFields[$rField])) {
                                $ret[$link][$type][$rField] = 0;
                                break;
                            }
                        }

                        $isCurrency = null;

                        foreach ($relBeans as $bean) {
                            if (isset($bean->$rField) && is_numeric($bean->$rField) &&
                                //ensure the user can access the fields we are using.
                                ACLField::hasAccess($rField, $bean->module_dir, $GLOBALS['current_user']->id, true)
                            ) {
                                if(is_null($isCurrency)) {
                                    $isCurrency = $this->isFieldCurrency($bean, $rField);
                                }

                                $count++;

                                $value = $bean->$rField;
                                if ($isCurrency) {
                                    $value = SugarCurrency::convertWithRate($value, $bean->base_rate);
                                }

                                $sum = SugarMath::init($sum)->add($value)->result();
                                if ($min === false || floatval($value) < floatval($min)) {
                                    $min = $value;
                                }
                                if ($max === false || floatval($value) > floatval($max)) {
                                    $max = $value;
                                }
                                $values[$bean->id] = $value;
                            }
                        }
                        if ($type == "rollupSum") {
                            $ret[$link][$type][$rField] = $sum;
                            $ret[$link][$type][$rField . '_values'] = $values;
                        }
                        if ($type == "rollupAve") {
                            $ret[$link][$type][$rField] = $count == 0 ? 0 : SugarMath::init($sum)->div($count)->result();
                            $ret[$link][$type][$rField . '_values'] = $values;
                        }
                        if ($type == "rollupMin") {
                            $ret[$link][$type][$rField] = $min;
                            $ret[$link][$type][$rField . '_values'] = $values;
                        }
                        if ($type == "rollupMax") {
                            $ret[$link][$type][$rField] = $max;
                            $ret[$link][$type][$rField . '_values'] = $values;
                        }
                    } else {
                        $ret[$link][$type][$rField] = 0;
                    }
                    break;
                case "countConditional":
                    $sum = 0;
                    $values = [];

                    if ($focus->load_relationship($link)) {
                        $condition_values = Parser::evaluate($rfDef['condition_expr'])->evaluate();
                        $relBeans = $focus->$link->getBeans(array("enforce_teams" => true));

                        foreach ($relBeans as $bean) {
                            if (in_array($bean->{$rfDef['condition_field']}, $condition_values)) {
                                $sum++;
                                $values[$bean->id] = true;
                            }
                        }
                    }
                    // for countConditional, we use the target field, since there can have more than one
                    // on the same record.
                    if (isset($rfDef['target'])) {
                        $ret[$link][$type][$rfDef['target']] = $sum;
                        $ret[$link][$type][$rfDef['target'] . '_values'] = $values;
                    } else {
                        $ret[$link][$type] = $sum;
                    }
                    break;
                case "rollupConditionalSum":
                    $ret[$link][$type][$rField] = '0';
                    $values = [];

                    if ($focus->load_relationship($link)) {
                        if (preg_match('/^[a-zA-Z0-9_\-$]+\(.*\)$/', $rfDef['condition_expr'])) {
                            $condition_values = Parser::evaluate($rfDef['condition_expr'])->evaluate();
                        } else {
                            $condition_values = array($rfDef['condition_expr']);
                        }
                        $toRate = isset($focus->base_rate) ? $focus->base_rate : null;
                        $relBeans = $focus->$link->getBeans(array("enforce_teams" => true));
                        $sum = '0';
                        $isCurrency = null;
                        foreach ($relBeans as $bean) {
                            if (!empty($bean->$rField) && is_numeric($bean->$rField) &&
                                //ensure the user can access the fields we are using.
                                ACLField::hasAccess($rField, $bean->module_dir, $GLOBALS['current_user']->id, true)
                            ) {
                                if (in_array($bean->{$rfDef['condition_field']}, $condition_values)) {
                                    if (is_null($isCurrency)) {
                                        $isCurrency = $this->isFieldCurrency($bean, $rField);
                                    }
                                    $value = $bean->$rField;
                                    if ($isCurrency) {
                                        $value = SugarCurrency::convertWithRate($value, $bean->base_rate, $toRate);
                                    }
                                    $sum = SugarMath::init($sum)->add(
                                        $value
                                    )->result();
                                    $values[$bean->id] = $value;
                                }
                            }
                        }
                        $ret[$link][$type][$rField] = $sum;
                        $ret[$link][$type][$rField . '_values'] = $values;
                    }
                    break;
                case 'maxRelatedDate':
                    $ret[$link][$type][$rField] = "";
                    if ($focus->load_relationship($link)) {
                        $td = TimeDate::getInstance();
                        $isTimestamp = true;
                        $maxDate = 0;
                        $relBeans = $focus->$link->getBeans(array("enforce_teams" => true));
                        $valueMap = array();
                        foreach ($relBeans as $bean) {
                            if (ACLField::hasAccess($rField, $bean->module_dir, $GLOBALS['current_user']->id, true)
                            ) {
                                // we have to use the fetched_row as it's still in db format
                                // where as the $bean->$relfield is formatted into the users format.
                                if (isset($bean->fetched_row[$rField])) {
                                    $value = $bean->fetched_row[$rField];
                                } elseif (isset($bean->$rField)) {
                                    if (is_int($bean->$rField)) {
                                        // if we have a timestamp field, just set the value
                                        $value = $bean->relfield;
                                    } else {
                                        // more than likely this is a date field, so try and un-format based on the users preferences
                                        // we pass false to asDbDate as we want the value that would be stored in the DB
                                        $value = $td->fromString($bean->$rField)->asDbDate(false);
                                    }
                                } else {
                                    continue;
                                }

                                $valueMap[$bean->id] = $value;

                                //if it isn't a timestamp, mark the flag as such and convert it for comparison
                                if (!is_int($value)) {
                                    $isTimestamp = false;
                                    $value = strtotime($value);
                                }

                                //compare
                                if ($maxDate < $value) {
                                    $maxDate = $value;
                                }
                            }
                        }

                        //if nothing was done, return an empty string
                        if ($maxDate == 0 && $isTimestamp) {
                            $maxDate = "";
                        } else if ($isTimestamp === false) {
                            $date = new DateTime();
                            $date->setTimestamp($maxDate);

                            $maxDate = $date->format("Y-m-d");
                        }


                        $ret[$link][$type][$rField] = $maxDate;
                        $ret[$link][$type][$rField . '_values'] = $valueMap;
                    }
                    break;
            }
        }

        return $ret;
    }

    /**
     * Test if the current field is a currency field
     *
     * @param SugarBean $bean The Bean to which the Field Belongs
     * @param string $field The name of the field
     * @return bool
     */
    protected function isFieldCurrency(SugarBean $bean, $field)
    {
        $def = $bean->getFieldDefinition($field);
        // start by just using the type in the def
        $def_type = $def['type'];
        // but if custom_type is set, use it, when it's not set and dbType is, use dbType
        if (isset($def['custom_type']) && !empty($def['custom_type'])) {
            $def_type = $def['custom_type'];
        } elseif (isset($def['dbType']) && !empty($def['dbType'])) {
            $def_type = $def['dbType'];
        }
        // always lower case the type just to make sure.
        return (strtolower($def_type) === 'currency');
    }
}
