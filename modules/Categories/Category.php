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


class Category extends SugarBean implements NestedBeanInterface
{

    public $table_name = 'categories';
    public $object_name = 'Category';
    public $module_dir = 'Categories';
    public $new_schema = true;
    public $importable = false;
    public $root;
    public $lft;
    public $rgt;
    public $lvl;


    /**
     * Save current node as new root.
     * @return String Id of new created bean.
     * @throws CategoriesExceptionInterface
     */
    public function saveAsRoot()
    {
        if (!empty($this->id)) {
            throw new CategoriesRuntimeException('The node cannot be makes root because it is not new.');
        }
        $this->lft = 1;
        $this->rgt = 2;
        $this->lvl = 0;

        if (empty($this->id)) {
            $this->new_with_id = true;
            $this->id = create_guid();
        }

        $this->root = $this->id;
        return parent::save();
    }

    /**
     * {@inheritDoc}
     */
    public function isRoot()
    {
        return $this->lft == 1;
    }

    /**
     * {@inheritDoc}
     */
    public function getTree($depth = null)
    {
        $tree = array();
        $stackLength = 0;
        $stack = array();
        $subnodes = $this->getTreeData($this->root);

        foreach ($subnodes as $node) {
            if ($depth && $node['lvl'] > $depth) {
                continue;
            }

            $data = $node;
            $data['children'] = array();
            $stackLength = count($stack);

            while ($stackLength > 0 && $stack[$stackLength - 1]['lvl'] >= $data['lvl']) {
                array_pop($stack);
                $stackLength--;
            }

            if ($stackLength == 0) {
                $i = count($tree);
                $tree[$i] = $data;
                $stack[] = & $tree[$i];
            } else {
                $i = count($stack[$stackLength - 1]['children']);
                $stack[$stackLength - 1]['children'][$i] = $data;
                $stack[] = & $stack[$stackLength - 1]['children'][$i];
            }
        }

        return $tree;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren($depth = 1)
    {
        $db = DBManagerFactory::getInstance();
        $query = $this->getQuery();

        $condition = array(
            'node.lft > ' . intval($this->lft),
            'node.rgt < ' . intval($this->rgt),
            'node.root = ' . $db->quoted($this->root),
        );

        if ($depth) {
            $lvl = $this->lvl + $depth;
            $condition[] = 'node.lvl <= ' . $lvl;
        }

        $query->whereRaw(implode(' AND ', $condition));
        $query->orderByRaw('node.lft', 'ASC');
        return $query->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function getNextSibling()
    {
        $db = DBManagerFactory::getInstance();
        $query = $this->getQuery();

        $condition = array(
            'node.lft = ' . ($this->rgt + 1),
            'node.root = ' . $db->quoted($this->root),
        );

        $query->whereRaw(implode(' AND ', $condition));
        $query->limit = 1;
        $result = $query->execute();
        return !empty($result) ? array_shift($result) : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getPrevSibling()
    {
        $db = DBManagerFactory::getInstance();
        $query = $this->getQuery();

        $condition = array(
            'node.rgt = ' . ($this->lft - 1),
            'node.root = ' . $db->quoted($this->root),
        );

        $query->whereRaw(implode(' AND ', $condition));
        $query->limit = 1;
        $result = $query->execute();
        return !empty($result) ? array_shift($result) : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getParents($depth = null, $reverseOrder = false)
    {
        $db = DBManagerFactory::getInstance();
        $query = $this->getQuery();
        $query->joinTable($this->table_name, array(
            'alias' => 'root',
        ))->on()->equalsField('root.id', 'node.root');

        $condition = array(
            'node.lft < ' . $this->lft,
            'node.rgt > ' . $this->rgt,
            'root.id = ' . $db->quoted($this->root),
        );

        $query->whereRaw(implode(' AND ', $condition));
        if ($reverseOrder) {
            $query->orderByRaw('node.lvl', 'DESC');
        } else {
            $query->orderByRaw('node.lvl', 'ASC');
        }

        $query->limit = $depth;
        return $query->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        $parents = $this->getParents(1, true);
        return array_shift($parents);
    }

    /**
     * {@inheritDoc}
     */
    public function isDescendantOf(NestedBeanInterface $target)
    {
        return $this->lft > $target->lft && $this->rgt < $target->rgt && $this->root === $target->root;
    }

    /**
     * {@inheritDoc}
     */
    public function append(NestedBeanInterface $node)
    {
        return $this->addNode($node, $this->rgt, 1);
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(NestedBeanInterface $node)
    {
        return $this->addNode($node, $this->lft + 1, 1);
    }

    /**
     * {@inheritDoc}
     */
    public function insertBefore(NestedBeanInterface $target)
    {
        return $target->addNode($this, $target->lft, 0);
    }

    /**
     * {@inheritDoc}
     */
    public function insertAfter(NestedBeanInterface $target)
    {
        return $target->addNode($this, $target->rgt + 1, 0);
    }

    /**
     * {@inheritDoc}
     */
    public function moveBefore(NestedBeanInterface $target)
    {
        $this->moveNode($target, $target->lft, 0);
    }

    /**
     * {@inheritDoc}
     */
    public function moveAfter(NestedBeanInterface $target)
    {
        $this->moveNode($target, $target->rgt + 1, 0);
    }

    /**
     * {@inheritDoc}
     */
    public function moveAsFirst(NestedBeanInterface $target)
    {
        $this->moveNode($target, $target->lft + 1, 1);
    }

    /**
     * {@inheritDoc}
     */
    public function moveAsLast(NestedBeanInterface $target)
    {
        $this->moveNode($target, $target->rgt, 1);
    }

    /**
     * {@inheritDoc}
     */
    public function remove()
    {
        $this->mark_deleted($this->id);
    }

    /**
     * Creates basic SugarQuery object.
     * @return \SugarQuery
     */
    protected function getQuery()
    {
        $query = new SugarQuery();
        $query->from($this, array(
            'alias' => 'node',
        ));

        return $query;
    }

    /**
     * Loads and returns all subnodes related to specified $root node.
     * @param string $root Id of root node to load data from.
     * @return array
     */
    protected function getTreeData($root)
    {
        $db = DBManagerFactory::getInstance();
        $query = $this->getQuery();
        $query->joinTable($this->table_name, array(
            'alias' => 'root',
        ))->on()->equalsField('root.id', 'node.root');
        $query->whereRaw('root.id = ' . $db->quoted($root) . ' AND node.lft > 1');
        $query->orderByRaw('node.lft', 'ASC');
        return $query->execute();
    }

    /**
     * This method change position of node in a tree.
     * @param Category $target
     * @param int $key minimal bound of index
     * @param int $levelUp raise the level to which
     */
    protected function moveNode(Category $target, $key, $levelUp)
    {
        $db = DBManagerFactory::getInstance();
        $left = $this->lft;
        $right = $this->rgt;
        $levelDelta = $target->lvl - $this->lvl + $levelUp;
        $delta = $right - $left + 1;

        $this->shiftLeftRight($key, $delta);
        if ($left >= $key) {
            $left += $delta;
            $right += $delta;
        }

        $this->update(
            array('lvl = lvl + ?'),
            'lft >= ? AND rgt <= ? AND root = ?',
            array($levelDelta, $left, $right, $this->root)
        );

        foreach (array('lft', 'rgt') as $attribute) {
            $condition = $attribute . ' >= ?'
                . ' AND ' . $attribute . ' <= ?'
                . ' AND root = ?';

            $this->update(
                array($attribute . ' = ' . $attribute . ' + ?'),
                $condition,
                array($key - $left, $left, $right, $this->root)
            );
        }

        $this->shiftLeftRight($right + 1, -$delta);

        $this->retrieve($this->id);
        $target->retrieve($target->id);
    }

    /**
     * Add new node to tree.
     * @param Category $node new child node.
     * @param int $key.
     * @param int $levelUp.
     * @return string
     * @throws CategoriesExceptionInterface
     */
    protected function addNode(Category $node, $key, $levelUp)
    {
        if (!empty($node->id)) {
            throw new CategoriesRuntimeException('The node cannot be added because it is not new.');
        }

        if (empty($node->name)) {
            throw new CategoriesRuntimeException('The node cannot be added because name is required.');
        }

        if ($this->deleted == 1) {
            throw new CategoriesRuntimeException('The node cannot be added because category is deleted.');
        }

        if ($node->deleted == 1) {
            throw new CategoriesRuntimeException('The node cannot be added because it is deleted.');
        }

        if (!$levelUp && $this->isRoot()) {
            throw new CategoriesRuntimeException('The node should not be root.');
        }

        $node->root = $this->root;
        $node->lft = $key;
        $node->rgt = $key + 1;
        $node->lvl = $this->lvl + $levelUp;
        $node->shiftLeftRight($key, 2);

        $node->save();
        $this->retrieve($this->id);
        $node->retrieve($node->id);

        return $node->id;
    }

    /**
     * This method shifting left and right indexes
     * @param int $key minimal bound of index
     * @param int $delta value of shifting relative to the current position
     */
    protected function shiftLeftRight($key, $delta)
    {
        foreach (array('lft', 'rgt') AS $attribute) {
            $this->update(
                array($attribute . ' = ' . $attribute . ' + ?'),
                $attribute . ' >= ? AND (root = ?)',
                array($delta, $key, $this->root)
            );
        }
    }

    /**
     * Creates and executes an UPDATE SQL statement.
     * @param array $fields the fields' expression to be updated.
     * @param mixed $conditions the conditions that will be put in the WHERE part.
     * @param array $params the parameters to be bound to the query.
     */
    protected function update($fields, $condition, array $params)
    {
        $db = DBManagerFactory::getInstance();

        $query = 'UPDATE ' . $this->table_name . ''
            . ' SET ' . implode(', ', $fields)
            . ' WHERE ' . $condition;

        $conn = $db->getConnection();
        $conn->executeUpdate($query, $params);
    }

    /**
     * This method marking as deleted current record and all descendant records
     * @inheritDoc
     */
    public function mark_deleted($id)
    {
        $this->retrieve($id);
        $hasChild = ($this->rgt - $this->lft) !== 1;
        if ($hasChild) {
            $descendants = $this->getChildren();
            while ($record = array_shift($descendants)) {
                $this->mark_deleted($record['id']);
            }
        }

        parent::mark_deleted($id);
        $this->shiftLeftRight($this->rgt + 1, ($this->lft - $this->rgt) - 1);
    }
    
    /**
     *  override default behavior
     * {@inheritDoc}
     */
    public function isACLRoleEditable(){
        return false;
    }
}
