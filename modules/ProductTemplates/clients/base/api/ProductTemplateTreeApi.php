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

class ProductTemplateTreeApi extends SugarApi
{

    public function registerApiRest()
    {
        return array(
            'tree' => array(
                'reqType' => 'GET',
                'path' => array('ProductTemplates', 'tree',),
                'pathVars' => array('module', 'type',),
                'method' => 'getTemplateTree',
                'shortHelp' => 'Returns a filterable tree structure of all Product Templates and Product Categories',
                'longHelp' => 'modules/ProductTemplates/clients/base/api/help/tree.html',
            ),
            'filterTree' => array(
                'reqType' => 'POST',
                'path' => array('ProductTemplates', 'tree',),
                'pathVars' => array('module', 'type',),
                'method' => 'getTemplateTree',
                'shortHelp' => 'Returns a filterable tree structure of all Product Templates and Product Categories',
                'longHelp' => 'modules/ProductTemplates/clients/base/api/help/tree.html',
            ),
        );
    }

    /**
     * Gets the full tree data in a jstree structure
     * @param ServiceBase $api
     * @param array $args
     * @return stdClass
     */
    public function getTemplateTree(ServiceBase $api, array $args)
    {
        $data = [];
        $tree = [];
        $records = [];
        $max_num = $this->getSugarConfig()->get('list_max_entries_per_page', 20);
        $offset = -1;
        $total = 0;
        $max_limit = $this->getSugarConfig()->get('max_list_limit');

        //set parameters
        if (array_key_exists('filter', $args)) {
            $data = $this->getFilteredTreeData($args['filter']);
        } elseif (array_key_exists('root', $args)) {
            $data = $this->getRootedTreeData($args['root']);
        } else {
            $data = $this->getRootedTreeData(null);
        }

        if (array_key_exists('offset', $args)) {
            $offset = $args['offset'];
        }

        //if the max_num is in-between 1 and $max_limit, set it, otherwise use max_limit
        if (array_key_exists('max_num', $args) && ($args['max_num'] < 1 || $args['max_num'] > $max_limit)) {
            $max_num = $max_limit;
        } elseif (array_key_exists('max_num', $args)) {
            $max_num = $args['max_num'];
        }

        // get total records in this set, calculate start position, slice data to current page
        $total = count($data);

        $offset = ($offset == -1) ? 0 : $offset;

        if ($offset < $total) {
            $data = array_slice($data, $offset, $max_num);
            
            //build the treedata
            foreach ($data as $node) {
                //create new leaf
                $records[] = $this->generateNewLeaf($node, $offset);
                $offset++;
            }
        }

        if ($total <= $offset) {
            $offset = -1;
        }

        $tree['records'] = $records;
        $tree['next_offset'] = $offset;

        return $tree;
    }

    /**
     * gets an instance of sugarconfig
     *
     * @return SugarConfig
     */
    protected function getSugarConfig()
    {
        return SugarConfig::getInstance();
    }

    /**
     * generates new leaf node
     * @param $node
     * @param $index
     * @return stdClass
     */
    protected function generateNewLeaf($node, $index)
    {
        $returnObj =  new \stdClass();
        $returnObj->id = $node['id'];
        $returnObj->type = $node['type'];
        $returnObj->data = $node['name'];
        $returnObj->state = ($node['type'] == 'product')? '' : 'closed';
        $returnObj->index = $index;

        return $returnObj;
    }

    protected function getFilteredTreeData($filter)
    {
        $filter = "%$filter%";
        $unionFilter = "and name like ? ";

        return $this->getTreeData($unionFilter, $unionFilter, $filter, $filter);
    }

    protected function getRootedTreeData($root)
    {
        $union1Root = '';
        $union2Root = '';

        if ($root == null) {
            $union1Root = "and parent_id is null ";
            $union2Root = "and category_id is null ";
        } else {
            $union1Root = "and parent_id = ? ";
            $union2Root = "and category_id = ? ";
        }

        return $this->getTreeData($union1Root, $union2Root, $root, $root);
    }

    /**
     * Gets the tree data
     * @param $filter filter for the list.
     * @return mixed
     */
    protected function getTreeData($union1Filter, $union2Filter, $filter1, $filter2)
    {
        $q = "select id, name, 'category' as type from product_categories " .
                "where deleted = 0 " .
                    $union1Filter .
            "union all " .
            "select id, name, 'product' as type from product_templates " .
                "where deleted = 0 " .
                    $union2Filter .
            "order by type, name";

        $conn = $this->getDBConnection();
        $stmt = $conn->prepare($q);

        $stmt->execute(array($filter1, $filter2));

        return $stmt->fetchAll();
    }

    /**
     * Gets the DB connection for the query
     * @return \Sugarcrm\Sugarcrm\Dbal\Connection
     */
    public function getDBConnection()
    {
        $db = DBManagerFactory::getInstance();
        return $db->getConnection();
    }
}
