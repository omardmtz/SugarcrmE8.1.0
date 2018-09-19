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
 * Update configuration for Advanced Workflow's new format.
 */
class SugarUpgradeFixPARelatedModules extends UpgradeScript
{
    /**
     * {@inheritdoc }
     * @var int
     */
    public $order = 9000;

    /**
     * {@inheritdoc }
     * @var int
     */
    public $type = self::UPGRADE_DB;

    /**
     * Handle a list of modules
     * @var array
     */
    private $projectModules = array();

    /**
     * Handle a list of related modules
     * @var array
     */
    private $relatedModules = array();

    /**
     * Handle a list of process to be disabled
     * @var array
     */
    private $processesToDisable = array();

    /**
     * Disabled all processes with errors
     */
    protected function disableProcesses() {
        global $db;
        $projects = array();
        foreach($this->processesToDisable as $key => $value) {
            $projects[] = $key;
        }
        if (!empty($projects)) {
            $db->query("UPDATE pmse_project SET prj_status= 'INACTIVE' WHERE prj_id IN (".implode(',',$projects).")");
        }
    }

    /**
     * Fix terminated fields on Process Definitions
     */
    protected function fixProcessDefinitions() {
        global $db;
        $targetRowsSQL = "SELECT id, pro_terminate_variables, id as pro_id FROM pmse_bpm_process_definition WHERE pro_terminate_variables != ''";
        $targetRows = $db->query($targetRowsSQL);
        $this->updateTableWithNewJSON('pmse_bpm_process_definition', $targetRows, 'pro_terminate_variables');
    }

    /**
     * This method replaces the criteria element with the new format
     * @param $table
     * @param $rows
     * @param $target_column
     */
    protected function updateTableWithNewJSON($table, $rows, $target_column) {
        global $db;
        foreach ($rows as $row) {
            $id_row = $row['id'];
            $json = json_decode(html_entity_decode($row[$target_column]), true);

            $newJSON = array();
            foreach ($json as $item) {
                if ($item['expType'] != "MODULE") {
                    array_push($newJSON, $item);
                    continue;
                }
                $related = $this->getRelationshipByProjectID($row["prj_id"], $item['expModule']);

                // If the relation was not found log and mark PD to be disabled
                if ($related === null) {
                    $this->log("Relation ".$item['expModule']." doesn't exists anymore, it will be removed.");
                    $this->processesToDisable[$row["prj_id"]] = true;
                    continue;
                }

                if (!$related || $related === $item['expModule']) {
                    array_push($newJSON, $item);
                    continue;
                }

                $item['expModule'] = $related;
                array_push($newJSON, $item);
            }

            $json = json_encode($newJSON);
            $updateSQL = "UPDATE $table SET $target_column = '$json' WHERE id = '$id_row'";
            $db->query($updateSQL);
        }
    }

    /**
     * Fix criteria fields on Gateways
     */
    protected function fixGatewayFlowDefinitions() {
        global $db;
        $targetRowSQL = "SELECT id, prj_id, flo_condition FROM pmse_bpmn_flow WHERE "
            ."flo_element_origin_type = 'bpmnGateway' AND flo_condition != ''";
        $targetRows = $db->query($targetRowSQL);
        $this->updateTableWithNewJSON("pmse_bpmn_flow", $targetRows, 'flo_condition');
    }

    /**
     * Fix criteria fields on Events
     */
    protected function fixEventDefinitions() {
        global $db;
        $targetRowsSQL = "SELECT id, evn_criteria, prj_id FROM pmse_bpm_event_definition WHERE evn_criteria != ''";
        $targetRows = $db->query($targetRowsSQL);
        $this->updateTableWithNewJSON('pmse_bpm_event_definition', $targetRows, 'evn_criteria');
    }

    /**
     * @param $columns
     * @param $target_module
     * @return bool
     */
    protected function getFixedBRColumnDefinition ($columns, $target_module) {
        $modified = false;
        $conditions  = $columns['conditions'];
        $newConditions = array();
        foreach($conditions as $c) {
            $related_module = $this->getRelationshipData($target_module, $c['module'], 'one-to-one');
            if ($c['module'] !== $related_module) {
                $modified = true;
                $c['module'] = $related_module;
            }
            array_push($newConditions, $c);
        }
        $columns['conditions'] = $newConditions;
        $conclusions = $columns['conclusions'];
        $newConclusions = array();
        foreach ($conclusions as $c) {
            if (empty($c)) {
                array_push($newConclusions, $c);
                continue;
            }
            $related_module = $this->getRelationshipData($target_module, $c, 'one-to-one');
            if ($c !== $related_module) {
                $modified = true;
            }
            array_push($newConclusions, $related_module);
        }
        $columns['conclusions'] = $newConclusions;
        return $modified ? $columns : $modified;
    }

    /**
     * @param $ruleset
     * @param $target_module
     * @return array|bool
     */
    protected function getFixedBRRulesets($ruleset, $target_module) {
        $modified = false;
        $newRuleset = array();
        foreach ($ruleset as $rule) {
            $conditions = $rule['conditions'];
            $newConditions = array();
            foreach ($conditions as $c) {
                $related_module = $this->getRelationshipData($target_module, $c["variable_module"], 'one-to-one');
                if ($c['variable_module'] != $related_module) {
                    $modified = true;
                    $c['variable_module'] = $related_module;
                }
                $condition_value = $c["value"];
                $newValue = array();
                foreach ($condition_value as $v) {
                    if ($v['expType'] == 'VARIABLE') {
                        $related_module = $this->getRelationshipData($target_module, $v['expModule'], 'one-to-one');
                        if ($related_module != $v['expModule']) {
                            $modified = true;
                        }
                        $v['expModule'] = $related_module;
                    }
                    array_push($newValue, $v);
                }
                $c["value"] = $newValue;
                array_push($newConditions, $c);
            }
            $rule['conditions'] = $newConditions;

            $conclusions = $rule['conclusions'];
            $newConclusions = array();
            foreach ($conclusions as $c) {
                if ($c["conclusion_type"] == 'variable') {
                    $related_module = $this->getRelationshipData($target_module, $c["variable_module"], 'one-to-one');
                    if ($related_module != $c["variable_module"]) {
                        $modified = true;
                        $c["variable_module"] = $modified;
                    }
                }
                $conclusion_value = $c["value"];
                $newValue = array();
                foreach($conclusion_value as $v) {
                    if ($v["expType"] == "VARIABLE") {
                        $related_module = $this->getRelationshipData($target_module, $v['expModule'], 'one-to-one');
                        if ($related_module !== $v["expModule"]) {
                            $modified = true;
                        }
                        $v['expModule'] = $related_module;
                    }
                    array_push($newValue, $v);
                }
                $c["value"] = $newValue;

                array_push($newConclusions, $c);
            }
            $rule['conclusions'] = $newConclusions;
            array_push($newRuleset, $rule);
        }

        return $modified ? $newRuleset : false;
    }

    /**
     * Fix criteria elements on Business Rules
     */
    protected function fixBusinessRulesDefinition() {
        global $db;
        $targetRowSQL = "SELECT id, rst_source_definition FROM pmse_business_rules WHERE rst_source_definition != ''";
        $targetRows = $db->query($targetRowSQL);
        foreach($targetRows as $row) {
            $modified = false;
            $json = json_decode($row['rst_source_definition'], true);
            // target module
            $target_module = $json['base_module'];
            $columns = $this->getFixedBRColumnDefinition($json['columns'], $target_module);
            if ($columns) {
                $modified = true;
                $json['columns'] = $columns;
            }
            $ruleset = $this->getFixedBRRulesets($json['ruleset'], $target_module);
            if ($ruleset) {
                $modified = true;
                $json['ruleset'] = $ruleset;
            }
            if ($modified) {
                $json = json_encode($json);
                $row_id = $row["id"];
                $sql = "UPDATE pmse_business_rules SET rst_source_definition = '$json' WHERE id = '$row_id'";
                $db->query($sql);
            }
        }
    }

    /**
     * Fix criteria fields on Activities
     */
    protected function fixActivityDefinitions() {
        global $db;
        $moduleSql = "SELECT t1.id as id, t1.pro_id as pro_id, t1.act_field_module as act_field_module, t2.prj_id as prj_id "
            ."FROM pmse_bpm_activity_definition as t1 INNER JOIN pmse_bpm_process_definition as t2 "
            ." ON t1.pro_id = t2.id";
        $modules = $db->query($moduleSql);
        foreach ($modules as $row) {
            $newName = $this->getRelationshipByProjectID($row['prj_id'],$row['act_field_module']);
            if ($newName === null) {
                $this->processesToDisable[$row['prj_id']] = true;
            }
            $sql = "UPDATE pmse_bpm_activity_definition
                      SET act_field_module = '$newName'
                      WHERE id = '{$row['id']}'";
            $db->query($sql);
        }
    }

    /**
     * Initialize all process definitions
     */
    private function initProjects () {
        global $db;
        $sql = "SELECT prj_id, pro_module FROM pmse_bpm_process_definition";
        $result = $db->query($sql);

        foreach ($result as $row) {
            $this->projectModules[$row["prj_id"]] = $row["pro_module"];
        }
    }

    /**
     * Fix criteria elements inside subject and body fields on Email Templates
     */
    protected function fixEmailTemplates()
    {
        global $db;
        $moduleSql = "SELECT id, subject, body_html, base_module FROM pmse_emails_templates";
        $modules = $db->query($moduleSql);
        foreach ($modules as $row) {
            $updatedSubject = $this->parseString($row['subject'], $row['base_module']);
            $updatedHtml_body = $this->parseString($row['body_html'], $row['base_module']);
            if ($updatedSubject != $row['subject'] || $updatedHtml_body != $row['body_html']) {
                $sql = "UPDATE pmse_emails_templates
                            SET subject = '$updatedSubject', body_html = '$updatedHtml_body'
                            WHERE id='" . $row['id'] . "'";
                $db->query($sql);
            }

        }
    }

    /**
     * This method parse all string of the type '{::Module::Field::}' and
     * replaces it with the new format of criteria element
     * @param $template
     * @param $base_module
     * @return mixed
     */
    public function parseString($template, $base_module)
    {
        $newTemplate = $template;
        $component_array = Array();
        $component_array[$base_module] = Array();

        preg_match_all("/(({::)[^>]*?)(.*?)((::})[^>]*?)/", $template, $matches, PREG_SET_ORDER);

        foreach ($matches as $val) {
            $matched_component = $val[0];
            $matched_component_core = $val[3];
            $split_array = preg_split('{::}', $matched_component_core);
            $newRelatedField = $this->getRelationshipData($base_module, $split_array[0], 'one-to-one');
            if (!empty($newRelatedField) && $newRelatedField != $base_module) {
                $newValue = str_replace($split_array[0], $newRelatedField, $matched_component);
                $newTemplate = str_replace($matched_component, $newValue, $newTemplate);
            }
            if (empty($newRelatedField)) {
                $newTemplate = str_replace($matched_component, '', $newTemplate);
            }
        }

        return $newTemplate;
    }

    /**
     * Returns relationships by process definition
     * @param $prj_id
     * @param $relationName
     * @return null
     */
    protected function getRelationshipByProjectID ($prj_id, $relationName) {
        if (empty($this->projectModules[$prj_id])) {
            return;
        }
        $baseModule = $this->projectModules[$prj_id];
        return $this->getRelationshipData($baseModule, $relationName);
    }

    /**
     * Returns relationships base on a module
     * @param $baseModule
     * @param $relationName
     * @param string $rel_type
     * @return null
     */
    protected function getRelationshipData($baseModule, $relationName, $rel_type= 'all')
    {
        if (empty($this->relatedModules[$baseModule])) {
            $this->relatedModules[$baseModule] = array(
                "all" => array(),
                "one-to-one" => array(),
                "one-to-many" => array()
            );
        }
        if (empty($this->relatedModules[$baseModule][$rel_type])) {
            $relatedModule = new PMSERelatedModule();
            $relatedModules = $relatedModule->getRelatedBeans($baseModule, $rel_type);
            $this->relatedModules[$baseModule][$rel_type] = array();
            foreach ($relatedModules['result'] as $module) {
                $this->relatedModules[$baseModule][$rel_type][$module['relationship']] = $module['value'];
            }
        }

        return empty($this->relatedModules[$baseModule][$rel_type][$relationName]) ? null
            : $this->relatedModules[$baseModule][$rel_type][$relationName];
    }

    /**
     * {@inheritdoc }
     */
    public function run()
    {
        // The only supported upgrade for this is 7.6.0.0RC4 to 7.6.0.0
        if (version_compare($this->from_version, '7.6.0.0RC4', '==') && version_compare($this->to_version, '7.6.0.0', '==')) {
            $this->initProjects();
            $this->fixActivityDefinitions();
            $this->fixEventDefinitions();
            $this->fixGatewayFlowDefinitions();
            $this->fixEmailTemplates();
            $this->fixBusinessRulesDefinition();
            $this->fixProcessDefinitions();
            $this->disableProcesses();
        }
    }
}
