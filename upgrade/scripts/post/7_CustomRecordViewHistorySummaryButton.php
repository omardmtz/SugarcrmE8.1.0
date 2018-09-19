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

class SugarUpgradeCustomRecordViewHistorySummaryButton extends UpgradeScript
{
    public $order = 7110;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        //run only when upgrading from 7.x to 7.2.1
        if (version_compare($this->from_version, '7.2.2', '>=')) {
            return;
        }

        // this is the core modules that it's on out of the box
        $modules = array(
            'Accounts',
            'Bugs',
            'Cases',
            'Contacts',
            'Leads',
            'Opportunities',
            'Prospects',
            'RevenueLineItems'
        );

        $files = glob('custom/modules/{' . join(',', $modules) . '}/clients/base/views/record/record.php', GLOB_BRACE);
        foreach ($files as $recordFile) {
            require $recordFile;

            if (!empty($viewdefs)) {
                $module = key($viewdefs);

                //make sure header panel exists and has fields
                if (!empty($viewdefs[$module]) && !empty($viewdefs[$module]['base']) &&
                    !empty($viewdefs[$module]['base']['view']['record']) &&
                    !empty($viewdefs[$module]['base']['view']['record']['buttons'])
                ) {
                    $newViewdefs = $this->addHistorySummaryButton($viewdefs, $module);
                    sugar_file_put_contents_atomic(
                        $recordFile,
                        "<?php\n\n"
                        . "/* This file was updated by 7_CustomRecordViewHistorySummaryButton */\n"
                        . "\$viewdefs['{$module}']['base']['view']['record'] = "
                        . var_export(
                            $newViewdefs[$module]['base']['view']['record'],
                            true
                        )
                        . ";\n"
                    );
                }
            }
            $viewdefs = null;
        }
    }

    public function addHistorySummaryButton($viewdefs, $module)
    {
        // find the actiondropdown
        foreach ($viewdefs[$module]['base']['view']['record']['buttons'] as $button_key => $button) {
            if ($button['type'] == 'actiondropdown') {
                // loop though to find the button
                $place_button = true;
                foreach ($button['buttons'] as $sub_key => $sub_button) {
                    if (isset($sub_button['name']) && $sub_button['name'] == 'historical_summary_button') {
                        $place_button = false;
                        break;
                    }
                }

                if ($place_button) {
                    $place_after = count($button['buttons'])-2;
                    if ($place_after < 0) {
                        $place_after = 0;
                    }
                    $viewdefs[$module]['base']['view']['record']['buttons'][$button_key]['buttons'] =
                        array_merge(
                            array_slice($button['buttons'], 0, $place_after),
                            array(
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:historical_summary_button:click',
                                    'name' => 'historical_summary_button',
                                    'label' => 'LBL_HISTORICAL_SUMMARY',
                                    'acl_action' => 'view',
                                )
                            ),
                            array_slice($button['buttons'], $place_after)
                        );
                }
            }
        }

        return $viewdefs;
    }
}
