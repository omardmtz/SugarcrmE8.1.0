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

* Description: Bug 40166. Need for return right join for campaign's target list relations.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/


/**
 * @brief Bug #40166. Campaign Log Report will not display Contact/Account Names
 */
class ProspectLink extends Link2
{

    /**
     * This method changes join of any item to campaign through target list
     * if you want to use this join method you should add code below to your vardef.php
     * 'link_class' => 'ProspectLink',
     * 'link_file' => 'modules/Campaigns/ProspectLink.php'
     *
     * @see Link::getJoin method
     */
    public function getJoin($params, $return_array = false)
    {
        $join_type= ' INNER JOIN ';
        if (isset($params['join_type']))
        {
            $join_type = $params['join_type'];
        }
        $join = '';
        $bean_is_lhs=$this->_get_bean_position();

        if (
            $this->_relationship->relationship_type == 'one-to-many'
            && $bean_is_lhs
        )
        {
            $table_with_alias = $table = $this->_relationship->rhs_table;
            $key = $this->_relationship->rhs_key;
            $module = $this->_relationship->rhs_module;
            $other_table = (empty($params['left_join_table_alias']) ? $this->_relationship->lhs_table : $params['left_join_table_alias']);
            $other_key = $this->_relationship->lhs_key;
            $alias_prefix = $table;
            if (!empty($params['join_table_alias']))
            {
                $table_with_alias = $table. " ".$params['join_table_alias'];
                $table = $params['join_table_alias'];
                $alias_prefix = $params['join_table_alias'];
            }

            $join .= ' '.$join_type.' prospect_list_campaigns '.$alias_prefix.'_plc ON';
            $join .= ' '.$alias_prefix.'_plc.'.$key.' = '.$other_table.'.'.$other_key."\n";

            // join list targets
            $join .= ' '.$join_type.' prospect_lists_prospects '.$alias_prefix.'_plp ON';
            $join .= ' '.$alias_prefix.'_plp.prospect_list_id = '.$alias_prefix.'_plc.prospect_list_id AND';
            $join .= ' '.$alias_prefix.'_plp.related_type = '.$GLOBALS['db']->quoted($module)."\n";

            // join target
            $join .= ' '.$join_type.' '.$table_with_alias.' ON';
            $join .= ' '.$table.'.id = '.$alias_prefix.'_plp.related_id AND';
            $join .= ' '.$table.'.deleted=0'."\n";

            if ($return_array)
            {
                $ret_arr = array();
                $ret_arr['join'] = $join;
                $ret_arr['type'] = $this->_relationship->relationship_type;
                if ($bean_is_lhs)
                {
                    $ret_arr['rel_key'] = $this->_relationship->join_key_rhs;
                }
                else
                {
                    $ret_arr['rel_key'] = $this->_relationship->join_key_lhs;
                }
                return $ret_arr;
            }
            return $join;
        } else {
            return parent::getJoin($params, $return_array);
        }
    }
}
