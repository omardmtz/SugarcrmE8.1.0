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
 * Update PMSE Actions Assigned To field data.
 */
class SugarUpgradeFixPMSEActionsAssignedTo extends UpgradeScript
{
    public $order = 9010;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // Only run this if the source is 7.6.0.0 and the target is greater than or equal to 7.7.0.0
        if (version_compare($this->from_version, '7.6.0.0', '==')
            && version_compare($this->to_version, '7.7.0.0', '>=')) {
            $query = "SELECT id, act_fields FROM pmse_bpm_activity_definition WHERE act_type = 'TASK' AND act_fields IS NOT NULL";
            $result = $this->db->query($query);
            while ($row = $this->db->fetchByAssoc($result, false)) {
                $fields = json_decode($row['act_fields']);
                if (is_array($fields)) {
                    $need_save = false;
                    foreach ($fields as &$field) {
                        if ($field->field == 'assigned_user_id' && $field->type == 'DropDown') {
                            $user = BeanFactory::getBean('Users', $field->value);
                            $field->type = 'user';
                            $field->label = $user->getFieldValue('name');
                            $need_save = true;
                        }
                    }
                    unset($field);
                    if ($need_save) {
                        $fields = $this->db->quoted(json_encode($fields));
                        $id = $this->db->quoted($row['id']);
                        $query = "UPDATE pmse_bpm_activity_definition SET act_fields = $fields WHERE id = $id";
                        $this->db->query($query);
                    }
                }
            }
        }
    }
}
