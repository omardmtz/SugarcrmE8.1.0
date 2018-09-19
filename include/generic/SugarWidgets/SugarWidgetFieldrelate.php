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

class SugarWidgetFieldRelate extends SugarWidgetReportField
{
    /**
     * Method returns HTML of input on configure dashlet page
     *
     * @param array $layout_def definition of a field
     * @return string HTML of select for edit page
     */
    public function displayInput($layout_def)
    {
        $values = array();
        if (is_array($layout_def['input_name0']))
        {
            $values = $layout_def['input_name0'];
        }
        else
        {
            $values[] = $layout_def['input_name0'];
        }
        $html = '<select name="' . $layout_def['name'] . '[]" multiple="true">';

        $query = $this->displayInputQuery($layout_def);
        $result = $this->reporter->db->query($query);
        while ($row = $this->reporter->db->fetchByAssoc($result))
        {
            $html .= '<option value="' . $row['id'] . '"';
            if (in_array($row['id'], $values))
            {
                $html .= ' selected="selected"';
            }
            $html .= '>' . htmlspecialchars($row['title']) . '</option>';
        }

        $html .= '</select>';
        return $html;
    }

    /**
     * Method returns database query for generation HTML of input on configure dashlet page
     *
     * @param array $layout_def definition of a field
     * @return string database query HTML of select for edit page
     */
    private function displayInputQuery($layout_def)
    {
        $title = $layout_def['rname'];
        $bean = isset($layout_def['module']) ? BeanFactory::newBean($layout_def['module']) : NULL;
        $table = empty($bean) ? $layout_def['table'] : $bean->table_name;
        $concat_fields = isset($layout_def['db_concat_fields']) ? $layout_def['db_concat_fields'] : '';

        if (empty($concat_fields) && !empty($bean) && isset($bean->field_defs[$title]['db_concat_fields']))
        {
            $concat_fields = $bean->field_defs[$title]['db_concat_fields'];
        }
        if (!empty($concat_fields))
        {
            $title = $this->reporter->db->concat($table, $concat_fields);
        }

        $query = "SELECT
                id,
                $title title
            FROM $table
            WHERE deleted = 0
            ORDER BY title ASC";
        return $query;
    }

    /**
     * Method returns part of where in style table_alias.id IN (...) because we can't join of relation
     *
     * @param array $layout_def definition of a field
     * @param bool $rename_columns unused
     * @return string SQL where part
     */
    public function queryFilterStarts_With($layout_def, $rename_columns = true)
    {
        $ids = array();

        $relation = BeanFactory::newBean('Relationships');
        $relation->retrieve_by_name($layout_def['link']);
        $seed = BeanFactory::getBean($relation->lhs_module, $layout_def['input_name0']);

        $link = new Link2($layout_def['link'], $seed);
        $sql = $link->getQuery();
        $result = $this->reporter->db->query($sql);
        while ($row = $this->reporter->db->fetchByAssoc($result))
        {
            $ids[] = $row['id'];
        }
        $layout_def['name'] = 'id';
        return $this->_get_column_select($layout_def) . " IN ('" . implode("', '", $ids) . "')";
    }

    /**
     * Method returns part of where in style table_alias.id IN (...) because we can't join of relation
     *
     * @param array $layout_def definition of a field
     * @param bool $rename_columns unused
     * @return string SQL where part
     */
    public function queryFilterone_of($layout_def, $rename_columns = true)
    {
        $ids = array();
        $module = isset($layout_def['custom_module']) ? $layout_def['custom_module'] : $layout_def['module'];
        $seed = BeanFactory::newBean($module);

        foreach($layout_def['input_name0'] as $beanId)
        {
            $sq = new SugarQuery();
            $sq->select(array('id'));
            $sq->from($seed);
            if (isset($layout_def['link'])) {
                $linkName = $layout_def['link'];
                $relation = SugarRelationshipFactory::getInstance()->getRelationship($linkName);
                if (isset($seed->field_defs[$linkName]) && $seed->loadRelationship($linkName)) {
                    //Then the name of a link field was passed through, no need to guess at the link name.
                    $sq->join($linkName);
                } else if ($relation) {
                    //Valid relationship name passed through, time to guess on the side.
                    if ($layout_def['module'] == $relation->getRHSModule()) {
                        $sq->join($relation->getRHSLink());
                    } else {
                        $sq->join($relation->getLHSLink());
                    }
                }
            }
            $sq->where()
                ->equals($layout_def['id_name'], $beanId);

            $rows = $sq->execute();
            foreach($rows as $row) {
                $ids[] = $row['id'];
            }
        }
        $ids = array_unique($ids);
        $layout_def['name'] = 'id';
        return $this->_get_column_select($layout_def) . " IN ('" . implode("', '", $ids) . "')";
    }

    /**
     * Method returns part of where in style table_alias.id IN (...) because we can't join of relation
     *
     * @param array $layout_def definition of a field
     * @param bool $rename_columns unused
     * @return string SQL where part
     */
    public function queryFilterEquals($layout_def, $rename_columns = true)
    {
        $reporter = $this->layout_manager->getAttribute("reporter");
        $field_def = $reporter->all_fields[$layout_def['column_key']];
        $module = isset($field_def['ext2']) ? $field_def['ext2'] : $field_def['module'];
        $seed = BeanFactory::newBean($module);
        $rvalue = $layout_def['input_name0'];
        $rname = isset($field_def['rname']) ? $field_def['rname'] : 'name';
        $ids = $this->getRelateIds($seed, $rname, $rvalue);
        if (!empty($ids)) {
            return $this->_get_column_select($layout_def) . " IN ('" . implode("', '", $ids) . "')";
        } else {
            // nothing found
            return '1=0';
        }
    }

    /**
     * Returns list of relate record ids by a field value
     * @param SugarBean $seed
     * @param string $rname
     * @param string $rvalue
     * @return string
     */
    protected function getRelateIds($seed, $rname, $rvalue)
    {
        $ids = array();
        if (isset($seed->field_defs[$rname]['db_concat_fields'])) {
            $rname = $seed->db->concat($seed->table_name, $seed->field_defs[$rname]['db_concat_fields']);
        }
        $query = new SugarQuery();
        $query->from($seed, ['add_deleted' => true, 'team_security' => false]);
        $query->select('id');
        $query->whereRaw("$rname = " . $seed->db->quoted($rvalue));
        $rows = $query->execute();
        if ($rows) {
            foreach ($rows as $row) {
                $ids[] = $row['id'];
            }
        }
        return array_unique($ids);
    }

	//for to_pdf/to_csv
	function displayListPlain($layout_def) {
	    $reporter = $this->layout_manager->getAttribute("reporter");
		$field_def = $reporter->all_fields[$layout_def['column_key']];
		$display = strtoupper($field_def['secondary_table'].'_name');
		//#31797  , we should get the table alias in a global registered array:selected_loaded_custom_links
		if(!empty($reporter->selected_loaded_custom_links) && !empty($reporter->selected_loaded_custom_links[$field_def['secondary_table']])){
			$display = strtoupper($reporter->selected_loaded_custom_links[$field_def['secondary_table']]['join_table_alias'].'_name');
		}
        elseif(isset($field_def['rep_rel_name']) && isset($reporter->selected_loaded_custom_links) && !empty($reporter->selected_loaded_custom_links[$field_def['secondary_table'].'_'.$field_def['rep_rel_name']]))
        {
            $display = strtoupper($reporter->selected_loaded_custom_links[$field_def['secondary_table'].'_'.$field_def['rep_rel_name']]['join_table_alias'].'_name');
        }
		elseif(!empty($reporter->selected_loaded_custom_links) && !empty($reporter->selected_loaded_custom_links[$field_def['secondary_table'].'_'.$field_def['name']])){
			$display = strtoupper($reporter->selected_loaded_custom_links[$field_def['secondary_table'].'_'.$field_def['name']]['join_table_alias'].'_name');
		}
		$cell = $layout_def['fields'][$display];
		return $cell;
	}

    function displayList($layout_def) {
        $reporter = $this->layout_manager->getAttribute("reporter");
        $field_def = $reporter->all_fields[$layout_def['column_key']];
        $display = strtoupper($field_def['secondary_table'].'_name');

        //#31797  , we should get the table alias in a global registered array:selected_loaded_custom_links
        if(!empty($reporter->selected_loaded_custom_links) && !empty($reporter->selected_loaded_custom_links[$field_def['secondary_table']])){
             $display = strtoupper($reporter->selected_loaded_custom_links[$field_def['secondary_table']]['join_table_alias'].'_name');
        }
        elseif(isset($field_def['rep_rel_name']) && isset($reporter->selected_loaded_custom_links) && !empty($reporter->selected_loaded_custom_links[$field_def['secondary_table'].'_'.$field_def['rep_rel_name']]))
        {
            $display = strtoupper($reporter->selected_loaded_custom_links[$field_def['secondary_table'].'_'.$field_def['rep_rel_name']]['join_table_alias'].'_name');
        }
        elseif(!empty($reporter->selected_loaded_custom_links) && !empty($reporter->selected_loaded_custom_links[$field_def['secondary_table'].'_'.$field_def['name']])){
            $display = strtoupper($reporter->selected_loaded_custom_links[$field_def['secondary_table'].'_'.$field_def['name']]['join_table_alias'].'_name');
        }
        $recordField = $this->getTruncatedColumnAlias(strtoupper($layout_def['table_alias']).'_'.strtoupper($layout_def['name']));

        $record = $layout_def['fields'][$recordField];
        $cell = "<a target='_blank' class=\"listViewTdLinkS1\" href=\"index.php?action=DetailView&module=".$field_def['ext2']."&record=$record\">";
        $cell .= $layout_def['fields'][$display];
        $cell .= "</a>";
        return $cell;
    }
}

?>
