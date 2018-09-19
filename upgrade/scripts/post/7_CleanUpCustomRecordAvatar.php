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

class SugarUpgradeCleanUpCustomRecordAvatar extends UpgradeScript
{
    public $order = 7100;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        //run only when upgrading from 7.x to 7.2.1
        if (version_compare($this->from_version, '7.0', '<') || version_compare($this->from_version, '7.2.1', '>=')) {
            return;
        }

        foreach (glob('custom/modules/*/clients/{base,portal}/views/record/record.php', GLOB_BRACE) as $recordFile) {
            require $recordFile;

            if (!empty($viewdefs)) {
                $module = key($viewdefs);

                //make sure header panel exists and has fields
                if (!empty($viewdefs[$module]) && !empty($viewdefs[$module]['base']) &&
                    !empty($viewdefs[$module]['base']['view']['record']) &&
                    !empty($viewdefs[$module]['base']['view']['record']['panels']) &&
                    !empty($viewdefs[$module]['base']['view']['record']['panels'][0]['fields'])
                ) {
                    $newViewdefs = $this->cleanUpAvatarField($viewdefs, $module);
                    sugar_file_put_contents_atomic(
                        $recordFile,
                        "<?php\n\n"
                        . "/* This file was updated by 7_CleanUpCustomRecordAvatar */\n"
                        . "\$viewdefs['{$module}']['base']['view']['record'] = "
                        . var_export(
                            $newViewdefs[$module]['base']['view']['record'], true)
                        . ";\n"
                    );
                }
            }
            $viewdefs = null;
        }
    }

    /**
     * Removes the `width` and `height` properties from the avatar field as they are out of date.
     *
     * Assumes that `$vdefs` contains a header panel with a list of fields.
     *
     * @param array $vdefs Custom record view definitions.
     * @param string $module Module of `record.php`.
     */
    private function cleanUpAvatarField(array $vdefs, $module)
    {
        foreach($vdefs[$module]['base']['view']['record']['panels'][0]['fields'] as $key => $headerField) {
            if (isset($headerField['type']) && $headerField['type'] === 'avatar') {
                unset($vdefs[$module]['base']['view']['record']['panels'][0]['fields'][$key]['width']);
                unset($vdefs[$module]['base']['view']['record']['panels'][0]['fields'][$key]['height']);
            }
        }
        return $vdefs;
    }
}
