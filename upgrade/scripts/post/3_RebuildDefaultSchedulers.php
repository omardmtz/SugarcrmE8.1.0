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
 * Rebuild Default Schedulers
 */
class SugarUpgradeRebuildDefaultSchedulers extends UpgradeScript
{
    public $order = 3000;
    public $type = self::UPGRADE_DB;
    protected $stockSchedulers;
    protected $existingSchedulers;

    protected $deprecatedSchedulers = array(); // add deprecated jobs here

    public function run()
    {
        /** @var Scheduler $scheduler */
        $scheduler = BeanFactory::newBean('Schedulers');
        $this->stockSchedulers = $scheduler->getDefaultSystemSchedulers();

        $this->existingSchedulers = array();
        $query = new SugarQuery();
        $query->select(array('id', 'job', 'name'));
        // to get all records, including deleted
        foreach ($query->from(BeanFactory::newBean('Schedulers'), array('add_deleted' => false))->execute() as $data) {
            $this->existingSchedulers[$data['job']] = array(
                'id' => $data['id'],
                'name' => $data['name']
            );
        }

        $this->addMissingStockSchedulers();
        $this->deleteNonStockSchedulers();
    }

    /**
     * Add new OOTB Schedulers.
     */
    protected function addMissingStockSchedulers()
    {
        foreach ($this->stockSchedulers as $job => $scheduler) {
            if (!array_key_exists($scheduler->job, $this->existingSchedulers)) {
                $this->log("Add new OOTB scheduler job '{$job}'");
                $scheduler->save();
            }
        }
    }

    /**
     * Delete Schedulers, that are not in the OOTB list.
     */
    protected function deleteNonStockSchedulers()
    {
        foreach ($this->existingSchedulers as $job => $existing) {
            if (!array_key_exists($job, $this->stockSchedulers) && isset($this->deprecatedSchedulers[$job])) {
                $this->log("Deleting all deprecated-OOTB '{$job}' scheduler jobs");
                $this->db->query("DELETE FROM schedulers WHERE job = " . $this->db->quoted($job));
            }
        }
    }
}
