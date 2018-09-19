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
 * repair the workflow sessions
 */
class SugarUpgradeRepairWorkflow extends UpgradeScript
{
    public $order = 3000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (!$this->toFlavor('pro')) return;


        // Disable time-elapsed workflows that don't have a proper Primary trigger and change their description
        $query = "SELECT DISTINCT w.id as workflow_id
                    FROM workflow w, workflow_triggershells wt
                    WHERE w.id = wt.parent_id
                    AND w.deleted = 0
                    AND wt.deleted = 0
                    AND w.type = 'Time'
                    AND wt.frame_type = 'Primary'
                    AND wt.type NOT IN ('compare_any_time', 'compare_specific')";
        $brokenWorkflows = $this->db->query($query);
        $descriptionFix = "THIS WORKFLOW WAS DEACTIVATED AUTOMATICALLY BY THE UPGRADE TO SUGAR 7 DUE TO INCOMPATIBILITY. PLEASE DELETE ALL CONDITIONS ON THE WORKFLOW AND RECREATE THEM.";
        while ($row = $this->db->fetchByAssoc($brokenWorkflows)) {
            $workflow = BeanFactory::getBean('WorkFlow', $row['workflow_id']);
            $workflow->status = 0;
            if (strpos($workflow->description, $descriptionFix) === false) {
                $workflow->description = "$descriptionFix\n"
                    . $workflow->description;
            }
            $workflow->save();
        }

    	// Call repair workflow
    	$workflow_object = new WorkFlow();
    	$workflow_object->repair_workflow(true);
    }
}
