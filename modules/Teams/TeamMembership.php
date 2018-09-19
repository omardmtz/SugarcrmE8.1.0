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


class TeamMembership extends SugarBean {
    // Stored fields
    var $id;
    var $team_id;
    var $user_id;
    var $explicit_assign;
    var $implicit_assign;
    var $deleted;
    var $date_modified;

    var $table_name = "team_memberships";
    var $object_name = "TeamMembership";
    var $module_name = 'TeamMemberships';
    var $module_dir = 'Teams';
    var $disable_custom_fields = true;

    var $encodeFields = Array("name", "description");



    // todo sort by username.

    var $new_schema = true;

    public function __construct() {
        parent::__construct();
        $this->disable_row_level_security =true;
    }

    function get_list_view_data() {
        $team_fields = $this->get_list_view_array();
        return $team_fields;
    }

    public function list_view_parse_additional_sections(&$list_form)
    {
        return $list_form;
    }

    /**
     * Delete this team membership
     */
    public function delete()
    {
        $query = sprintf(
            'UPDATE %s set deleted = 1 where id = %s',
            $this->table_name,
            $this->db->quoted($this->id)
        );
        $result = $this->db->query($query, TRUE, "Error deleting team membership ($this->id): ");
    }

    /**
     * This method retrieves the membership for a given user_id and team_id.
     * The membership that this is called on is destroyed if a membership is
     * found matching user_id and team_id
     * @returns boolean True if found, false if not found.
     */
    public function retrieve_by_user_and_team($user_id, $team_id)
    {
        // determine whether the user is already on the team
        $query = 'SELECT id
            FROM team_memberships
            WHERE user_id = ?
                AND team_id = ?
                AND deleted = 0';
        $stmt = $this->db->getConnection()->executeQuery($query, [$user_id, $team_id]);
        $row = $stmt->fetch();

        if ($row!= null) {
            $this->retrieve($row['id']);
            return true;
        }

        return false;
    }
}

