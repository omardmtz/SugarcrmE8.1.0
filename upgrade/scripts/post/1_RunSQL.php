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
 * Run SQL scripts from $temp_dir/scripts/ relevant to current conversion, e.g.
 * scripts/65x_to_67x_mysql.sql
 */
class SugarUpgradeRunSQL extends UpgradeScript
{
    public $order = 1000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        $vfrom = $this->implodeVersion($this->from_version, 2);
        $vto = $this->implodeVersion($this->to_version, 2);
        $this->log("Looking for SQL scripts from $vfrom/{$this->from_flavor} to $vto/{$this->to_flavor}");
        if ($vfrom == $vto) {
            if ($this->from_flavor == $this->to_flavor) {
                $vmfrom = $this->implodeVersion($this->from_version, 3);
                $vmto = $this->implodeVersion($this->to_version, 3);
                if ($vmfrom == $vmto) {
                    return;
                }
                $script = "{$vmfrom}_to_{$vmto}";
            } else {
                $script = "{$vfrom}_{$this->from_flavor}_to_{$this->to_flavor}";
            }
        } else {
            $script = "{$vfrom}_to_{$vto}";
        }
        $script .= "_" . $this->db->getScriptName() . ".sql";
        $filename = $this->context['new_source_dir'] . "/upgrade/scripts/sql/" . $script;
        $this->log("Checking script name: $script ($filename)");
        if (file_exists($filename)) {
            $this->log("Running script $filename");
            $this->parseAndExecuteSqlFile($filename);
        }
    }

    protected function parseAndExecuteSqlFile($sqlScript)
    {
        // TODO: resume support?
        $contents = file($sqlScript);
        $anyScriptChanges = $contents;
        $resumeAfterFound = false;
        $completeLine = '';
        foreach($contents as $line) {
            if (strpos($line, '--') === false) {
               $completeLine .= " " . trim($line);
               if (strpos($line, ';') !== false) {
                   $query = str_replace(';', '', $completeLine);
                   if ($query != null) {
                       $this->db->query($query);
                   }
                   $completeLine = '';
                }
            }
        } // foreach
    }
}
