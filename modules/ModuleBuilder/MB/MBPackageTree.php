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
require_once 'vendor/ytree/Tree.php';
require_once 'vendor/ytree/Node.php';
class MBPackageTree
{
    public function __construct()
    {
        $this->tree = new Tree('package_tree');
        $this->tree->id = 'package_tree';
        $this->mb = new ModuleBuilder();
        $this->populateTree($this->mb->getNodes(), $this->tree);
    }

    public function getName()
    {
        return 'Packages';
    }

    public function populateTree($nodes, &$parent)
    {
        foreach ($nodes as $node) {
            if(empty($node['label']))$node['label'] = $node['name'];
            $yn = new Node($parent->id . '/' . $node['name'],$node['label']);
            if(!empty($node['action']))
            $yn->set_property('action', $node['action']);
            $yn->set_property('href', 'javascript:void(0);');
            $yn->id = $parent->id . '/' . $node['name'];
            if(!empty($node['children']))$this->populateTree($node['children'], $yn);

            // Sets backward compatibility flag into the node defs for use on the
            // client if needed
            if (!empty($node['bwc'])) {
                $yn->set_property('bwc', $node['bwc']);
            }
            $parent->add_node($yn);
        }
    }

    public function fetch()
    {
        //return $this->tree->generate_header() . $this->tree->generate_nodes_array();
        return $this->tree->generate_nodes_array();
    }

    public function fetchNodes()
    {
        return $this->tree->generateNodesRaw();
    }

}

