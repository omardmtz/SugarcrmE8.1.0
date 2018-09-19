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
 * Replaces parent constructor calls like parent::ClassName() with parent::__construct()
 */
class SugarUpgradeFixParentConstructorCalls extends UpgradeScript
{
    public $order = 600;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (version_compare($this->from_version, '7.9.0.0', '>=')) {
            return;
        }

        // these files should have been automatically removed by previous upgrades but still sometimes exist,
        // so we need to remove them manually
        $this->upgrader->fileToDelete(array(
            'include/SugarPDF.php',
            'modules/Opportunities/views/view.sidequickcreate.php',
            'include/database/MssqlManager2.php',
            'include/database/MssqlHelper2.php',
            'include/FCKeditor_Sugar/FCKeditor_Sugar.php',
            'include/database/OracleHelper.php',
            'modules/Documents/DocumentTreeView.php',
        ), $this);

        // remove old backups which potentially contain classes calling parent constructors the PHP4 way
        $this->upgrader->fileToDelete(glob('modules/*/.pre_500'), $this);

        if (!file_exists('stock-parent-calls.json')) {
            $this->log('File stock-parent-calls.json does not exist. Skipping.');
            return;
        }

        $calls = json_decode(file_get_contents('stock-parent-calls.json'), true);

        foreach ($calls as $file => $parentCalls) {
            $this->log('Processing ' . $file);
            $contents = file_get_contents($file);
            $replaced = false;
            foreach ($parentCalls as $parentCall) {
                list($class, $method) = $parentCall;
                $contents = preg_replace(
                    '/' . preg_quote($class) . '\s*::\s*' . preg_quote($method) . '\s*\(/im',
                    'parent::__construct(',
                    $contents,
                    -1,
                    $count
                );

                if ($count > 0) {
                    $this->log('Replaced ' . $class . '::' . $method . '() with parent::__construct() in ' . $file);
                    $replaced = true;
                } else {
                    $this->log('Could not find ' . $class . '::' . $method . '() in ' . $file);
                }
            }

            if ($replaced) {
                $this->log('Saving ' . $file);
                file_put_contents($file, $contents);
            }
        }

        unlink('stock-parent-calls.json');
    }
}
