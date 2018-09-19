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
 * SugarQuery_Builder_Field_Condition
 * @api
 */

class SugarQuery_Builder_Field_Condition extends SugarQuery_Builder_Field
{
    protected $rNameExists = false;

    public function expandField()
    {
        if(!isset($this->def['source']) || $this->def['source'] == 'db') {
            return;
        }
        // Exists only checks
        if (!empty($this->def['rname_exists'])) {
            $this->markNonDb();
            $this->rNameExists = true;
            return;
        }
        if(!empty($this->def['rname']) && !empty($this->def['link'])) {
            //if related module is not defined, lets get the related module through the link
            if (empty($this->def['module'])) {
                $linkName = $this->def['link'];
                $from = $this->query->from;
                if ($from->load_relationship($linkName)) {
                    $this->def['module'] =$from->$linkName->getRelatedModuleName();
                }
            }
            $this->table = $this->query->getJoinAlias($this->def['link']);
            $this->field = $this->def['rname'];
        } elseif (!empty($this->def['rname']) && !empty($this->def['table'])) {
            $this->table = $this->query->getJoinAlias($this->def['table'], false);
            $this->field = $this->def['rname'];
        }  elseif(!empty($this->def['rname_link']) && !empty($this->def['link'])) {
            $this->field = $this->def['rname_link'];
        }

        //if module is empty, and this is a relationship id field, then try to get module from the query
        if (empty($this->def['module']) && !empty($this->def['rname'])) {
            //check to the bean attached to the joined table for module name
            if (!empty($this->jta) && !empty($this->query->join[$this->jta]->bean->module_name)) {
                $this->def['module'] = $this->query->join[$this->jta]->bean->module_name;
            }
        }

        if (!empty($this->def['module'])) {
            $this->moduleName = $this->def['module'];
            $bean = BeanFactory::getDefinition($this->moduleName);
            if (isset($bean->field_defs[$this->field])) {
                $this->def = $bean->field_defs[$this->field];
            }
        }
        $this->checkCustomField();
    }

    public function verifyCondition($value, $query)
    {
        if ($this->rNameExists) {
            if (isTruthy($value)) {
                $query->whereRaw("{$this->jta}.{$this->def['rname']} IS NOT NULL");
            } else {
                $query->whereRaw("{$this->jta}.{$this->def['rname']} IS NULL");
            }
        }
    }


    public function shouldMarkNonDb()
    {
        // if its a linking table let it slide
        if(!empty($this->query->join[$this->table]->options['linkingTable'])) {
            $this->nonDb = 0;
            return;
        }
        if (empty($this->moduleName)) {
            $this->markNonDb();
            return;
        }
        if (isset($this->def['source']) && $this->def['source'] == 'non-db' && !isset($this->def['dbType'])) {
            $this->markNonDb();
            return;
        } elseif (empty($this->def)) {
            $this->markNonDb();
            return;
        }

        $this->nonDb = 0;
        return;
    }
}
