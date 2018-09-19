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
 *  Dashboards is used to store dashboard configuration data.
 */
class Dashboard extends Basic 
{    
    public $table_name = "dashboards";
    public $module_name = 'Dashboards';
    public $module_dir = 'Dashboards';
    public $object_name = "Dashboard";
    
    /**
     * This overrides the default retrieve function setting the default to encode to false
     */
    function retrieve($id='-1', $encode=false, $deleted=true)
    {
        $dashboard = parent::retrieve($id, $encode, $deleted);

        if ($dashboard === null) {
            return null;
        }
        // Expand the metadata for processing.
        $metadata = json_decode($dashboard->metadata);

        // If we don't have a components in metadata for whatever reason, we're out, send back unchanged.
        if(!isset($metadata->components)) {
            return $dashboard;
        }

        $dirty = false;

        // Loop through the dashboard, drilling down to the dashlet level.
        foreach($metadata->components as $component_key => $component) {
            foreach($component->rows as $row_key => $row) {
                foreach($row as $item_key => $item) {
                    // Check if this user has access to the module upon which this dashlet is based.
                    if(isset($item->context->module) && !SugarACL::checkAccess($item->context->module, 'access')) {
                        // The user does not have access, remove the dashlet.
                        unset($metadata->components[$component_key]->rows[$row_key][$item_key]);

                        // Check if this row is now empty.
                        if(count($metadata->components[$component_key]->rows[$row_key]) == 0) {
                            // This row is now empty, remove it and mark the metadata as dirty.
                            unset($metadata->components[$component_key]->rows[$row_key]);
                            $dirty = true;
                        }
                    }
                }
            }
        }

        // Check if we've modified the metadata.
        if($dirty) {
            // Loop through the rows re-assigning sequential array keys for dashboard display.
            foreach($metadata->components as $key => $value) {
                $metadata->components[$key]->rows = array_values($metadata->components[$key]->rows);
            }
        }

        // Re-encode and save the metadata back to the dashboard object before returning it.
        $dashboard->metadata = json_encode($metadata);

        return $dashboard;
    }

    /**
     * This function fetches an array of dashboards for the current user
     *
     * 'view' is deprecated because it's reserved db word.
     * Some old API (before 7.2.0) can use 'view'.
     * Because of that API will use 'view' as 'view_name' if 'view_name' isn't present.
     * Returns all the dashboards available for the User given.
     *
     * Optionally you can pass the view in the $options to filter the
     * dashboards of a certain view.
     * For homepage the view is assumed empty.
     *
     * @param User $user The user that we want to get the dashboards from.
     * @param array $options A list of options such as: limit, offset and view.
     *
     * @return array The list of the User's dashboard and next offset.
     */
    public function getDashboardsForUser(User $user, array $options = array())
    {
        $order = !empty($options['order_by']) ? $options['order_by'] : 'date_entered desc';
        $from = "{$this->table_name}.assigned_user_id = '".$this->db->quote($user->id)."'
                 AND {$this->table_name}.dashboard_module ='".$this->db->quote($options['dashboard_module'])."'";
        if (isset($options['view']) && !isset($options['view_name'])) {
            $options['view_name'] = $options['view'];
        }
        if (!empty($options['view_name'])) {
            $from .= " and view_name =" . $this->db->quoted($options['view_name']);
        }
        $offset = !empty($options['offset']) ? (int)$options['offset'] : 0;
        $limit = !empty($options['limit']) ? (int)$options['limit'] : -1;
        $result = $this->get_list($order,$from,$offset,$limit,-1,0);
        $nextOffset = (count($result['list']) > 0 && count($result['list']) ==  $limit) ? ($offset + $limit) : -1;
        return array('records'=>$result['list'], 'next_offset'=>$nextOffset);
    }

    /**
     * This overrides the default save function setting assigned_user_id
     * @see SugarBean::save()
     *
     * 'view' is deprecated because it's reserved db word.
     * Some old API (before 7.2.0) can use 'view'.
     * Because of that API will use 'view' as 'view_name' if 'view_name' isn't present.
     */
    function save($check_notify = FALSE)
    {
        if (empty($this->assigned_user_id)) {
            $this->assigned_user_id = $GLOBALS['current_user']->id;
        }

        if (empty($this->team_id)) {
            $this->team_id = $GLOBALS['current_user']->getPrivateTeamID();
        }

        if (empty($this->team_set_id)) {
            $this->load_relationship('teams');
            $this->teams->add(array($this->team_id));
        }

        if (empty($this->acl_team_set_id)) {
            $this->acl_team_set_id = '';
        }

        if (isset($this->view) && !isset($this->view_name)) {
            $this->view_name = $this->view;
        }

        return parent::save($check_notify);
    }

    /**
     * 'view' is deprecated because it's reserved db word.
     * Some old API (before 7.2.0) can use 'view'.
     * Because of that API will return 'view' with the same value as 'view_name'.
     *
     * @param string $order_by
     * @param string $where
     * @param int    $row_offset
     * @param int    $limit
     * @param int    $max
     * @param int    $show_deleted
     * @param bool   $singleSelect
     * @param array  $select_fields
     *
     * @return array
     */
    public function get_list($order_by = "", $where = "", $row_offset = 0, $limit = -1, $max = -1, $show_deleted = 0, $singleSelect = false, $select_fields = array())
    {
        $result = parent::get_list($order_by, $where, $row_offset, $limit, $max, $show_deleted, $singleSelect, $select_fields);
        if (!empty($result['list'])) {
            foreach ($result['list'] as $dashboard) {
                $dashboard->view = $dashboard->view_name;
            }
        }
        return $result;
    }
}
