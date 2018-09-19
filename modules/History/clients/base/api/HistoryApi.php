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

class HistoryApi extends RelateApi
{
    /**
     * This is the list of allowed History Modules
     * @var array
     */
    protected $moduleList = array(
        'meetings' => 'Meetings',
        'calls' => 'Calls',
        'notes' => 'Notes',
        'tasks' => 'Tasks',
        'emails' => 'Emails',
    );
    /**
     * filters per module for list requests
     * @var array
     */
    protected $moduleFilters = array(
        'Calls' => array(
            array(
                'status' => array(
                    '$in' => array(
                        'Not Held',
                        'Held'
                    ),
                ),
            ),
        ),
        'Meetings' =>array(
            array(
                'status' => array(
                    '$in' => array(
                        'Not Held',
                        'Held'
                    ),
                ),
            ),
        ),
        'Tasks' =>array(
            array(
                'status' => array(
                    '$in' => array(
                        'Deferred',
                        'Completed'
                    ),
                ),
            ),
        )
    );

    /**
     * This is the list of valid fields that should be on each select
     * @var array
     */
    protected $validFields = array(
        'name',
        'status',
        'description',
        'date_entered',
        'date_modified',
        'related_contact',
        'assigned_user_name',
        'assigned_user_id',
    );

    public function registerApiRest()
    {
        return array(
            'recordListView' => array(
                'reqType' => 'GET',
                'path' => array('<module>', '?', 'link', 'history'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'filterModuleList',
                'jsonParams' => array('filter'),
                'shortHelp' => 'Get the history records for a specific record',
                'longHelp' => 'include/api/help/history_filter.html',
                'exceptions' => array(
                    // Thrown in filterList
                    'SugarApiExceptionInvalidParameter',
                    // Thrown in filterListSetup and parseArguments
                    'SugarApiExceptionNotAuthorized',
                ),
            ),
        );
    }

    public function filterModuleList(ServiceBase $api, array $args, $acl = 'list')
    {
        if (!empty($args['module_list'])) {
            $module_list = explode(',', $args['module_list']);
            foreach ($this->moduleList as $link_name => $module) {
                $seed = BeanFactory::newBean($module);
                if (!in_array($module, $module_list) || !$seed->ACLAccess('list')) {
                    unset($this->moduleList[$link_name]);
                }
            }
        }

        // if the module list is empty then someone passed in bad modules for the history
        if (empty($this->moduleList)) {
            throw new SugarApiExceptionInvalidParameter("Module List is empty, must contain: Meetings, Calls, Notes, Tasks, or Emails");
        }

        $query = new SugarQuery();
        $api->action = 'list';
        $orderBy = array();

        // modules is a char field used for sorting on module name
        // it is added to the select below, it can be sorted on but needs to be removed from
        // the arguments to allow it to be maintained throughout the code
        $removedModuleDirection = false;
        if (!empty($args['order_by'])) {
            $orderBy = explode(',', $args['order_by']);
            foreach ($orderBy as $key => $list) {
                list($field, $direction) = explode(':', $list);
                // `picture` is considered the same field as `module` because it
                // corresponds to the module icon.
                if ($field === 'module' || $field === 'picture') {
                    unset($orderBy[$key]);
                    $removedModuleDirection = !empty($direction) ? $direction : 'DESC';
                }
            }
            $args['order_by'] = implode(',', $orderBy);
            $orderBy[] = "module:{$removedModuleDirection}";
        }

        if (!empty($args['fields'])) {
            $args['fields'] .= "," . implode(',', $this->validFields);
        } else {
            $args['fields'] = implode(',', $this->validFields);
        }

        if (!empty($args['order_by']) || !empty($args['fields'])) {
            $args = $this->scrubFields($args);
        }

        unset($args['order_by']);
        foreach ($this->moduleList as $link_name => $module) {
            $args['filter'] = array();
            $savedFields = $args['fields'];
            $args['link_name'] = $link_name;

            $fields = explode(',', $args['fields']);

            foreach ($fields as $k => $field) {
                if (isset($args['placeholder_fields'][$module][$field])) {
                    unset($fields[$k]);
                }
            }

            $args['fields'] = implode(',', $fields);
            if (!empty($this->moduleFilters[$module])) {
                $args['filter'] = $this->moduleFilters[$module];
            }

            /** @var SugarQuery $q */
            list($args, $q, $options) = $this->filterRelatedSetup($api, $args);
            $q->select()->selectReset();
            $q->orderByReset(); // ORACLE doesn't allow order by in UNION queries
            if (!empty($args['placeholder_fields'])) {
                $newFields = array_merge($args['placeholder_fields'][$module], $fields);
            } else {
                $newFields = $fields;
            }

            sort($newFields);
            foreach ($newFields as $field) {
                if ($field == 'module') {
                    continue;
                }
                // special case for description on emails
                if($module == 'Emails' && $field == 'description') {
                    // ORACLE requires EMPTY_CLOB() for union queries if CLOB fields were used before
                    $q->select()->fieldRaw(DBManagerFactory::getInstance()->emptyValue('text') . " email_description");
                } else {
                    if (isset($args['placeholder_fields'][$module][$field])) {
                        $q->select()->fieldRaw("'' {$args['placeholder_fields'][$module][$field]}");
                    } else {
                        $q->select()->field($field);
                    }
                }
            }

            $q->select()->field('id');
            $q->select()->field('assigned_user_id');
            $q->limit = $q->offset = null;
            $q->select()->fieldRaw("'{$module}'", 'module');
            $query->union($q);
            $query->limit($options['limit'] + 1);
            $query->offset($options['offset']);
            $args['fields'] = $savedFields;
        }

        if (!empty($orderBy)) {
            if ($removedModuleDirection !== false) {
                $orderBy[] = "module:{$removedModuleDirection}";
            }
            foreach ($orderBy as $order) {
                $ordering = explode(':', $order);
                if (count($ordering) > 1) {
                    $query->orderByRaw("{$ordering[0]}", "{$ordering[1]}");
                } else {
                    $query->orderByRaw("{$ordering[0]}");
                }
            }
        } else {
            $query->orderByRaw('date_modified');
        }

        return $this->runQuery($api, $args, $query, $options);
    }

    protected function scrubFields(array $args)
    {
        $filters = !empty($args['order_by']) ? explode(',', $args['order_by']) : array();
        foreach ($filters as $filter) {
            $order_by = explode(':', $filter);
            foreach ($this->moduleList as $module_name) {
                $seed = BeanFactory::newBean($module_name);
                if (!isset($seed->field_defs[$order_by[0]])) {
                    $args['placeholder_fields'][$module_name][$order_by[0]] = $order_by[0];
                } else {
                    if (empty($args['fields'])) {
                        $args['fields'] = "{$order_by[0]}";
                    } else {
                        $args['fields'] .= ",{$order_by[0]}";
                    }
                }
            }
        }

        $fields = !empty($args['fields']) ? explode(',', $args['fields']) : array();
        foreach ($fields as $key => $field) {
            foreach ($this->moduleList as $module_name) {
                $seed = BeanFactory::newBean($module_name);
                if (!isset($seed->field_defs[$field])) {
                    $args['placeholder_fields'][$module_name][$field] = $field;
                }
            }
        }
        return $args;
    }

    protected function runQuery(ServiceBase $api, array $args, SugarQuery $q, array $options, SugarBean $seed = null)
    {
        $beans = array(
            '_rows' => array()
        );

        foreach ($q->execute() as $row) {
            /** @var SugarBean $bean */
            $bean = BeanFactory::newBean($row['module']);
            $bean->populateFromRow($row);
            if ($bean->ACLAccess('list')) {
                $beans[$row['id']] = $bean;
                $beans['_rows'][$row['id']] = $row;
            }
        }

        $rows = $beans['_rows'];
        unset($beans['_rows']);

        $data = array();
        $data['next_offset'] = -1;

        $i = 0;
        foreach ($beans as $bean_id => $bean) {
            if ($i == $options['limit']) {
                unset($beans[$bean_id]);
                $data['next_offset'] = (int)($options['limit'] + $options['offset']);
                continue;
            }
            $i++;

            $this->populateRelatedFields($bean, $rows[$bean_id]);
        }

        // add on the contact_id and contact_name fields so we get those
        // returned in the response
        $args['fields'] .= ',contact_id,contact_name';
        $data['records'] = $this->formatBeans($api, $args, $beans);

        foreach ($data['records'] as $id => $record) {
            $data['records'][$id]['moduleNameSingular'] = $GLOBALS['app_list_strings']['moduleListSingular'][$record['_module']];
            $data['records'][$id]['moduleName'] = $GLOBALS['app_list_strings']['moduleList'][$record['_module']];

            // Have to tack on from/to/description here due to not all modules
            // having all these fields
            if($record['_module'] == 'Emails') {
                /* @var $q SugarQuery */
                $q = new SugarQuery();
                $q->select(array('description', 'from_addr', 'to_addrs'));
                $q->from(BeanFactory::newBean('EmailText'));
                $q->where()->equals('email_id', $data['records'][$id]['id']);
                foreach ($q->execute() as $row) {
                    $data['records'][$id]['description'] = $row['description'];
                    $data['records'][$id]['from_addr'] = $row['from_addr'];
                    $data['records'][$id]['to_addrs'] = $row['to_addrs'];
                }
            }
        }

        return $data;
    }
}
