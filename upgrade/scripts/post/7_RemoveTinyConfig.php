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

class SugarUpgradeRemoveTinyConfig extends UpgradeScript
{
    public $order = 7900;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (version_compare($this->from_version, '7.8.0.0', '<')) {
            $configRemoved = false;
            $fileName = 'custom/modules/KBContents/clients/base/views/record/record.php';

            if (file_exists($fileName)) {
                include $fileName;

                if (!empty($viewdefs['KBContents']['base']['view']['record']['panels'])) {
                    $panels = $viewdefs['KBContents']['base']['view']['record']['panels'];
                    foreach ($panels as $i => $panel) {
                        if (!empty($panel['fields'])) {
                            foreach ($panel['fields'] as $j => $field) {
                                if ($field['name'] == 'kbdocument_body_set' && !empty($field['fields'])) {
                                    foreach ($field['fields'] as $k => $fd) {
                                        if ($fd['name'] == 'kbdocument_body' && !empty($fd['tinyConfig'])) {
                                            unset($viewdefs['KBContents']['base']['view']['record']['panels'][$i]['fields'][$j]['fields'][$k]['tinyConfig']);
                                            $configRemoved = true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($configRemoved) {
                        write_array_to_file('viewdefs', $viewdefs, $fileName);
                    }
                }
            }
        }
    }
}
