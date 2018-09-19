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

class SugarUpgradeForecastManagerSetManagerSaved extends UpgradeScript
{
    public $order = 2195;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (!version_compare($this->from_version, '7.1.5', '<')) {
            return;
        }

        // simple update
        $sql = "UPDATE forecast_manager_worksheets SET manager_saved = 1
                    WHERE id IN (
                      SELECT * FROM (
                        SELECT id FROM forecast_manager_worksheets
                        WHERE manager_saved = 0
                        AND draft = 1
                        AND deleted = 0
                        AND (((likely_case != likely_case_adjusted
                                AND likely_case != 0.000000 )
                              OR (best_case != best_case_adjusted
                                  AND best_case != 0.000000 )
                              OR (worst_case != worst_case_adjusted
                                  AND worst_case != 0.000000 )
                            )
                            OR assigned_user_id = modified_user_id
                        )
                      ) records)
                ";
        $this->db->query($sql);
    }
}
