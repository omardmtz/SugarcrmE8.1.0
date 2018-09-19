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
 * Update data for PMSE module.
 */
class SugarUpgradeRenamePMSE extends UpgradeScript
{
    public $order = 2200;
    public $type = self::UPGRADE_DB;
    public $version = '7.5';

    /**
     * Array with fields we need to update,
     * @var array
     */
    protected $fields = array(
        'pmse_project' => array(
            'prj_module',
        ),
        'pmse_bpm_dynamic_forms' => array(
            'dyn_module',
        ),
        'pmse_bpm_event_definition' => array(
            'evn_module',
        ),
        'pmse_bpm_flow' => array(
            'cas_sugar_module',
        ),
        'pmse_bpm_process_definition' => array(
            'pro_module',
        ),
        'pmse_bpm_related_dependency' => array(
            'evn_module',
            'pro_module',
            'rel_process_module',
            'rel_element_module',
        ),
        'pmse_business_rules' => array(
            'rst_module',
        ),
        'pmse_emails_templates' => array(
            'base_module',
        ),
        'pmse_inbox' => array(
            'cas_module',
        ),
    );

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ((version_compare($this->from_version, '7.6.0', '>=') && version_compare($this->from_version, '7.7.0', '<')))  {
            foreach ($this->fields as $table => $fields) {
                foreach ($fields as $field) {
                    $query = "UPDATE {$table} set {$field} = 'KBContents' where {$field} = 'KBDocuments'";
                    $this->db->query($query);
                }
            }
        }
    }
}
