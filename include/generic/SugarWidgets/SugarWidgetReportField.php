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

// $Id: SugarWidgetReportField.php 58357 2010-09-29 23:22:03Z kjing $



$used_aliases = array();
$alias_map = array();

class SugarWidgetReportField extends SugarWidgetField
{
    /**
     * Layout manager reporter attribute
     * @var SugarBean
     */
    protected $reporter;

    public function __construct(&$layout_manager)
    {
        parent::__construct($layout_manager);
        $this->reporter = $this->layout_manager->getAttribute("reporter");
    }

	function  getSubClass($layout_def)
	{
		if (! empty($layout_def['type']))
		{

			if ($layout_def['type'] == 'time') {
				$layout_def['widget_class'] = 'Fielddate';
			} else {
				$layout_def['widget_class'] = 'Field'.$layout_def['type'];
			}
			return $this->layout_manager->getClassFromWidgetDef($layout_def);
		} else {
			return $this;
		}
	}


    public function display(array $layout_def)
 {
        $obj = $this->getSubClass($layout_def);

        $context = $this->layout_manager->getAttribute('context');//_ppd($context);
        $func_name = 'display'.$context;


        if ( ! empty($context) && method_exists($obj,$func_name))
        {
                return  $obj->$func_name($layout_def);
        } else
        {
                return 'display not found:'.$func_name;
        }
 }

 function _get_column_select_special($layout_def)
 {
 		$alias = '';
		 if ( ! empty($layout_def['table_alias']))
		 {
			$alias = $layout_def['table_alias'];
		 }

    if ($layout_def['name'] == 'weighted_sum' )
    {
        return sprintf("SUM(%s * %s * 0.01)", $this->reporter->db->convert("$alias.probability","IFNULL", array(0)),
            $this->reporter->db->convert("$alias.amount_usdollar","IFNULL", array(0)));
	}
    if ($layout_def['name'] == 'weighted_amount' )
    {
        return sprintf("AVG(%s * %s * 0.01)", $this->reporter->db->convert("$alias.probability","IFNULL", array(0)),
            $this->reporter->db->convert("$alias.amount_usdollar","IFNULL", array(0)));
	}
 }

 function _get_column_select($layout_def)
 {
 	global $reportAlias;
 	if (!isset($reportAlias)) {
 		$reportAlias = array();
 	}

	if ( ! empty($layout_def['table_alias'])) {
		$alias = $layout_def['table_alias'].".".$layout_def['name'];
	} else if (! empty($layout_def['name'])) {
		$alias = $layout_def['name'];
	} else {
		$alias = "*";
	}

	if ( ! empty($layout_def['group_function']) )
	{
    	if ($layout_def['name'] == 'weighted_sum' || $layout_def['name'] == 'weighted_amount')
    	{
    			$alias = $this->_get_column_select_special($layout_def);
    			$reportAlias[$alias] = $layout_def;
				return $alias;
    	}

        // Use IFNULL only if it's not AVG aggregate
        // because it adds NULL rows to the count when it should not, thus getting wrong result
        if ($layout_def['group_function'] != 'avg') {
            $alias = $this->reporter->db->convert($alias, 'IFNULL', array(0));
        }

        // for a field with type='currency' conversion of values into a user-preferred currency
        if ($layout_def['type'] == 'currency' && strpos($layout_def['name'], '_usdoll') === false) {
            $currency = $this->reporter->currency_obj;
            $currency_alias = isset($layout_def['currency_alias'])
                ? $layout_def['currency_alias'] : $currency->table_name;
            $query = $this->reporter->db->convert($currency_alias.".conversion_rate", "IFNULL", array(1));
            // We need to use convert() for AVG because of Oracle
            if ($layout_def['group_function'] != 'avg') {
                $alias = "{$layout_def['group_function']}($alias/{$query})";
            } else {
                $alias = $this->reporter->db->convert("$alias/$query", "AVG");
            }
        } else {
            // We need to use convert() for AVG because of Oracle
            if ($layout_def['group_function'] != 'avg') {
                $alias = "{$layout_def['group_function']}($alias)";
            } else {
                $alias = $this->reporter->db->convert($alias, "AVG");
            }

        }
    } elseif (isset($layout_def['type']) && $layout_def['type'] == 'enum') {
        // NULL and '' are displayed as None at least for enum fields
        $db = $this->reporter->db;
        $alias = $db->convert($alias, 'IFNULL', array($db->emptyValue($layout_def['type'])));
    }

	$reportAlias[$alias] = $layout_def;
	return $alias;
 }

 function querySelect(&$layout_def)
 {
    return $this->_get_column_select($layout_def)." ".$this->_get_column_alias($layout_def)."\n";
 }

 function queryGroupBy($layout_def)
 {
	return $this->_get_column_select($layout_def)." \n";
 }


    /**
     * Returns ORDER BY for given layout_def
     *
     * @param $layout_def
     * @return string
     */
    public function queryOrderBy($layout_def)
    {
        if (!empty($this->reporter->all_fields[$layout_def['column_key']])) {
            $field_def = $this->reporter->all_fields[$layout_def['column_key']];
        }

        if (empty($layout_def['sort_dir']) || $layout_def['sort_dir'] == 'a') {
            $direction = " ASC";
        } else {
            $direction = " DESC";
        }

        $orderBy = array();

        if (!empty($field_def['sort_on'])) {
            $orderBy[] = $layout_def['table_alias'] . "." . $field_def['sort_on'];
            if (!empty($field_def['sort_on2'])) {
                $orderBy[] = $layout_def['table_alias'] . "." . $field_def['sort_on2'];
            }
        } else {
            $orderBy[] = $this->_get_column_alias($layout_def);
        }

        array_walk(
            $orderBy,
            function (&$order, $key, $direction) {
                $order = $order . $direction;
            },
            $direction
        );

        return implode(', ', $orderBy);
    }


 function queryFilter($layout_def)
 {
	$method_name = "queryFilter".$layout_def['qualifier_name'];
	return $this->$method_name($layout_def);
 }

	function displayHeaderCell($layout_def)
	{
				global $start_link_wrapper,$end_link_wrapper;


                // don't show sort links if name isn't defined
                $no_sort = $this->layout_manager->getAttribute('no_sort');
                if(empty($layout_def['name']) || ! empty($no_sort) || ! empty($layout_def['no_sort']))
                {
                        return $layout_def['label'];
                }



                $sort_by ='';
                if ( ! empty($layout_def['table_key']) && ! empty($layout_def['name']) ) {
                	if (! empty($layout_def['group_function']) && $layout_def['group_function'] == 'count') {
                            $sort_by = $layout_def['table_key'].":".'count';
                	} else {
                    	$sort_by = $layout_def['table_key'].":".$layout_def['name'];
                        if ( ! empty($layout_def['column_function'])) {
                        	$sort_by .= ':'.$layout_def['column_function'];
                		} else if ( ! empty($layout_def['group_function']) ) {
                        	$sort_by .= ':'.$layout_def['group_function'];
                		}
                	}
                } else {
                	return $this->displayHeaderCellPlain($layout_def);
                }

                $start = empty($start_link_wrapper) ? '': $start_link_wrapper;
				$end = empty($end_link_wrapper) ? '': $end_link_wrapper;

                // unable to retrieve the vardef here, exclude columns of type clob/text from being sortable

                if(!in_array($layout_def['name'], array('description', 'account_description', 'lead_source_description', 'status_description', 'to_addrs', 'cc_addrs', 'bcc_addrs', 'work_log', 'objective', 'resolution'))) {
                    $header_cell = "<a class=\"listViewThLinkS1\" href=\"".$start.$sort_by.$end."\">";
                    $header_cell .= $this->displayHeaderCellPlain($layout_def);
                    $header_cell .= ListView::getArrowUpDownStart(isset($layout_def['sort']) ? $layout_def['sort'] : '');
                    $header_cell .= ListView::getArrowUpDownEnd(isset($layout_def['sort']) ? $layout_def['sort'] : '');
		            $header_cell .= "</a>";
					return $header_cell;
				}

		        return $this->displayHeaderCellPlain($layout_def);
    }

	function query($layout_def)
 	{
       		 $obj = $this->getSubClass($layout_def);

        	$context = $this->layout_manager->getAttribute('context');
       	 	$func_name = 'query'.$context;

        	if ( ! empty($context) && method_exists($obj,$func_name))
       		 {
               		 return  $obj->$func_name($layout_def);
        	} else
        	{
                	return '';
		}
 	}

 function _get_column_alias($layout_def)
 {
        $alias_arr = array();

	if (!empty($layout_def['table_key']) && $layout_def['table_key'] == 'self' && !empty($layout_def['name']) && $layout_def['name'] == 'id')
	{
		return 'primaryid';
	}

     // Bug: 44605
     // this comment is being added to trigger the upgrade package
        if ( ! empty($layout_def['group_function']) && $layout_def['group_function']=='count')
        {
                return $layout_def['table_alias'] . '__count';
        }

        if ( ! empty($layout_def['table_alias']))
        {
                array_push($alias_arr,$layout_def['table_alias']);
        }

        if ( ! empty($layout_def['group_function']) && $layout_def['group_function'] != 'weighted_amount' && $layout_def['group_function'] != 'weighted_sum')
        {
                array_push($alias_arr,$layout_def['group_function']);
        } else if ( ! empty($layout_def['column_function']))
        {
                array_push($alias_arr,$layout_def['column_function']);
        } else if ( ! empty($layout_def['qualifier']))
        {
                array_push($alias_arr,$layout_def['qualifier']);
        }

        if ( ! empty($layout_def['name']))
        {
                array_push($alias_arr,$layout_def['name']);
        }

		global $used_aliases, $alias_map;

        $alias = strtolower(implode("_",$alias_arr));

        $short_alias = $this->getTruncatedColumnAlias($alias);

		if ( empty($used_aliases[$short_alias]))
		{
			$alias_map[$alias] = $short_alias;
		    $used_aliases[$short_alias] = 1;
          	return $short_alias;
		} else if ( ! empty($alias_map[$alias]) )
		{
			return $alias_map[$alias];
		} else {
			$alias_map[$alias] = $short_alias.'_'.$used_aliases[$short_alias];
		  $used_aliases[$short_alias]++;
			return $alias_map[$alias];
		}
 }

 function queryFilterEmpty($layout_def)
 {
     $column = $this->_get_column_select($layout_def);
        return "(coalesce({$this->reporter->db->convert($column, 'length')}, 0) = 0)";
 }

 function queryFilterIs($layout_def)
 {
 	return '( '.$this->_get_column_select($layout_def)."='".$GLOBALS['db']->quote($layout_def['input_name0'])."')\n";
 }

 function queryFilteris_not($layout_def)
 {
 	return '( '.$this->_get_column_select($layout_def)."<>'".$GLOBALS['db']->quote($layout_def['input_name0'])."')\n";
 }

 function queryFilterNot_Empty($layout_def)
 {
     $column = $this->_get_column_select($layout_def);
        return "(coalesce({$this->reporter->db->convert($column, 'length')}, 0) <> 0)";
 }

 protected function getInputValue($layout_def)
 {
     $input_name0 = $layout_def['input_name0'];
     if (is_array($layout_def['input_name0']))
     {
         $input_name0 = $layout_def['input_name0'][0];
     }
     return $input_name0;
 }

}
