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

class SugarUpgradeDeleteDNBDashlets extends UpgradeScript
{
    public $order = 7600;
    public $type = self::UPGRADE_DB;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        // Only run for less than 7.9
        if (version_compare($this->from_version, '7.9.0.0', '>=')) {
            return;
        }

        // Get all live dashboards. For each dashboard, remove the D%B dashlets.
        $sql = "SELECT id, metadata
                FROM dashboards
                WHERE deleted=0";
        $result = $this->db->query($sql);
        $queryTemplate = "UPDATE dashboards SET metadata='%s' WHERE id='%s'";
        while ($dashboard = $this->db->fetchByAssoc($result, false)) {
            $metadata = json_decode($dashboard['metadata']);
            $changed = $this->deleteDNB($metadata);

            // If there is no change, skip.
            if (!$changed) {
                continue;
            }

            $metadata = json_encode($metadata);
            $this->db->query(
                sprintf(
                    $queryTemplate,
                    $metadata,
                    $this->db->quote($dashboard['id'])
                )
            );
        }
    }

    /**
     * Removes D&B Dashlets from passed in metadata.
     *
     * @param array metadata The dashboard metadata
     * @return boolean true if changed, otherwise false
     */
    public function deleteDNB($metadata)
    {
        if (!property_exists($metadata, 'components')) {
            return false;
        }

        $changed = false;
        $components = $metadata->components;

        // Parse through the dashboard metadata and remove the D&B dashlets

        // Note that we need to remove the D&B dashlets at the
        // `$column->rows` level, since each dashlet is an ordered array
        // containing an unordered array, which has the dashlet metadata.
        foreach ($components as $component) {
            if (!property_exists($component, 'rows')) {
                continue;
            }

            // Store the rows that become empty as a result of deleting the
            // D&B dashlet. We can't delete them yet because
            // we are looping over `$component->rows[$rowsKey]`, and if we
            // delete them in place, it will throw the $rowsKey off.
            $emptyRowsKeys = array();
            foreach ($component->rows as $rowsKey => $rows) {
                $dashletsToDelete = array();
                foreach ($rows as $dashletKey => $dashlet) {
                    if (!property_exists($dashlet, 'view')) {
                        continue;
                    }
                    $view = $dashlet->view;
                    $shouldRemove = property_exists($view, 'type') && strpos($view->type, 'dnb-') === 0;
                    if ($shouldRemove) {
                        // Mark this dashlet for deletion.
                        // Don't delete it now, because that will screw up the
                        // iteration.
                        $dashletsToDelete[] = $dashletKey;
                        $changed = true;
                    }
                }

                // Loop backward through the dashlets to delete and remove them.
                // This must be done backwards to avoid messing up the indexes
                // on $component->rows[$rowsKey].
                $lastDashletIndex = count($dashletsToDelete) - 1;
                for ($i = $lastDashletIndex; $i >= 0; $i--) {
                    array_splice($component->rows[$rowsKey], $dashletsToDelete[$i], 1);
                }

                // If the removal removed the last element of the row,
                // mark this row for deletion.
                // Don't delete it now, because that will screw up the
                // iteration.
                if (count($component->rows[$rowsKey]) === 0) {
                    $emptyRowsKeys[] = $rowsKey;
                }
            }

            // Delete the rows that have become empty as a result of deleting
            // the dashlet.
            // This must be done backwards to avoid messing up the indexes
            // on $emptyRowsKeys.
            for ($i = count($emptyRowsKeys) - 1; $i >=0; $i--) {
                array_splice($component->rows, $emptyRowsKeys[$i], 1);
            }
        }
        return $changed;
    }
}
