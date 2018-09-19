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
 * Renames Process Author to Advanced Workflow in Scheduler Job
 */
class SugarUpgradeRenamePAToAWScheduler extends UpgradeScript
{
    public $order = 9789;

    public $type = self::UPGRADE_ALL;

    public function run()
    {
        $scheduler = BeanFactory::newBean('Schedulers');
        $table = $scheduler->getTableName();
        // update name in Schedulers table
        $this->updateAWFScheduledJobsName($table);
        // update name in job_queue table too for Job Log
        $this->updateAWFScheduledJobsName('job_queue');
    }

    /**
     * Updates the scheduler job name in db
     * @param string table name
     */
    protected function updateAWFScheduledJobsName($table)
    {
        if ($table) {
            $search = 'Process Author Scheduled Job';
            $name = $this->db->quoted($search);
            $ids = [];
            $sql = "SELECT id FROM $table WHERE name = $name";
            $result = $this->db->query($sql);
            if ($result) {
                while ($row = $this->db->fetchByAssoc($result)) {
                    $ids[] = $row['id'];
                }
            }

            if ($ids) {
                $count = count($ids);
                $modStrings = $this->getModuleLangArray();
                $replace = !empty($modStrings) && isset($modStrings['LBL_OOTB_PROCESS_AUTHOR_JOB']) ?
                    $modStrings['LBL_OOTB_PROCESS_AUTHOR_JOB'] :
                    'Advanced Workflow Scheduled Job';
                $name = $this->db->quoted($replace);
                array_walk($ids, function (&$val, $key, $db) {
                    $val = $db->quoted($val);
                }, $this->db);

                $ids = implode(",", $ids);

                $sql = "UPDATE $table SET name = $name WHERE id IN ($ids)";
                $this->db->query($sql);
                $this->log("'$search' has been updated to '$replace' for $count Scheduler Jobs in $table");
            }
        }
    }

    /**
     * Gets the language array for this. Had to do it this way
     * because the method to get this is protected in the driver.
     * @return array
     */
    protected function getModuleLangArray()
    {
        $mod_strings = array();
        $langfile = "modules/Scheduler/language/en_us.lang.php";
        if (!file_exists($langfile)) {
            $this->log("Failed to find the language file");
            // fail, can't find file
            return $mod_strings;
        }
        $this->log("Loading language file from $langfile");
        include $langfile;
        if (file_exists("custom/$langfile")) {
            $this->log("Loading custom language file from custom/$langfile");
            include "custom/$langfile";
        }
        return $mod_strings;
    }
}
