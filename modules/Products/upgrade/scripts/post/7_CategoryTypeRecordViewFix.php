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

class SugarUpgradeCategoryTypeRecordViewFix extends UpgradeScript
{
    public $order = 7500;
    public $type = self::UPGRADE_CUSTOM;
    public $version = '7.2.0';

    protected $file = 'custom/modules/Products/clients/base/views/record/record.php';
    protected $field_rename_map = array(
        'type_id' => 'type_name',
        'category_id' => 'category_name'
    );

    public function run()
    {
        // if we are coming from anything newer than 7, just bail
        if (version_compare($this->from_version, '7.0.0', '>')) {
            return;
        }

        if (!file_exists($this->file)) {
            # if we don't have a custom file, then bail
            return;
        }
        $viewdefs = null;

        include $this->file;

        if (!empty($viewdefs)) {
            $viewdefs = $this->fixFieldName($viewdefs);
            sugar_file_put_contents($this->file, "<?php\n\n \$viewdefs = " . var_export($viewdefs, true) . ";\n");

        }

        $viewdefs = null;
    }

    /**
     * loop over view, find labels that match pattern and remove them
     * @param array $arr
     * @return array return array with removed
     */
    public function fixFieldName($arr)
    {
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $arr[$key] = $this->fixFieldName($val);
            } elseif ($key === 'name' && isset($this->field_rename_map[$val])) {
                $arr[$key] = $this->field_rename_map[$val];
            }
        }
        return $arr;
    }
}
