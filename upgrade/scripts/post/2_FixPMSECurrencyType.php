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
 * Script to fix currency fields while upgrading to 7.7 and above. Previously currency took into account 
 * only integer fields. From 7.7 we switched to comparing using currency values. 
 */
class SugarUpgradeFixPMSECurrencyType extends UpgradeScript
{
    public $order = 2010;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * Determines whether this upgrader should run
     * @return boolean
     */
    protected function shouldRun()
    {
        // The supported upgrade for this is to 7.7, provided the from version is
        // less than 7.7 but at least 7.6.0
        $from = version_compare($this->from_version, '7.7', '<') && version_compare($this->from_version, '7.6', '>=');
        $to = version_compare($this->to_version, '7.7', '>=');
        return $from && $to;
    }

    public function run()
    {
        if ($this->shouldRun()) {
            $result = $GLOBALS['db']->query("select id, flo_condition from pmse_bpmn_flow where flo_condition is NOT NULL and deleted=0");
            while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
                $floCondition = $row['flo_condition'];
                $id = $row['id'];
                $conditionArray = json_decode(html_entity_decode($floCondition));

                if (!empty($conditionArray)) {
                    $updateDBFlag = false;
                    foreach ($conditionArray as $index => $condition) {
                        // we just want to fix currency related records and nothing else
                        if (($condition->expType == 'MODULE') &&
                            (!empty($condition->expSubtype)) && (strtolower($condition->expSubtype) == 'currency') &&
                            (empty($condition->expCurrency))) {
                            PMSEEngineUtils::fixCurrencyType($condition);
                            // an update to DB will be required
                            $updateDBFlag = true;
                        }
                    }
                    if ($updateDBFlag === true) {
                        $newCondition = json_encode($conditionArray);
                        // update the database
                        $GLOBALS['db']->query("UPDATE pmse_bpmn_flow SET flo_condition = '$newCondition' WHERE id = '$id'");
                    }
                }
            }
        } else {
            $this->log("FixPMSECurrencyType Upgrade Failed Version Test. From Version=" . $this->from_version . " To Version=" . $this->to_version);
        }
    }
}
