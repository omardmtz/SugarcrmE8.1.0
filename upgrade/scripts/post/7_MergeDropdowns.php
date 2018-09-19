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
 * Merges all customized dropdown lists with their respective core dropdown lists. Uses data from the
 * {@link SugarUpgradeLoadDropdowns} pre-upgrade script.
 */
class SugarUpgradeMergeDropdowns extends UpgradeScript
{
    // BR-3995 fix: This script needs to run before 7_FixModuleNamesMismatch
    // to retain custom module names
    public $order = 7920;
    public $type = self::UPGRADE_CUSTOM;
    public $version = '7.6.0';

    protected $dropdownHelper = null;
    protected $dropdownParser = null;

    /**
     * Returns instance of ParserDropDown
     * @return ParserDropDown
     */
    public function getDropdownParser()
    {
        if (is_null($this->dropdownParser)) {
            if (!class_exists('ParserDropDown')) {
            }
            $this->dropdownParser = new ParserDropDown();
        }
        return $this->dropdownParser;
    }

    /**
     * Returns instance of UpgradeDropdownsHelper
     * @return UpgradeDropdownsHelper
     */
    public function getDropdownHelper()
    {
        if (is_null($this->dropdownHelper)) {
            if (!class_exists('UpgradeDropdownsHelper')) {
                // use the version in the new source directory
                $importFile = "{$this->context['new_source_dir']}/upgrade/UpgradeDropdownsHelper.php";
                require_once $importFile;
            }
            $this->dropdownHelper = new UpgradeDropdownsHelper();
        }
        return $this->dropdownHelper;
    }

    /**
     * {@inheritdoc}
     *
     * This upgrader should always be run since changes to the core dropdown lists can occur in any version.
     *
     * Merges all customized dropdown lists with their respective core dropdown lists and saves the result to the
     * appropriate i18n translation file in the custom directory.
     *
     * @see SugarUpgradeLoadDropdowns::run
     * @see ParserDropDown::saveDropDown
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.6.2.1', '>=')) {
            $this->log('**** Skipped Dropdown Lists Merge **** Sugar version is too new');
            return;
        }
        //In 7.6.0 through 7.6.2, the load order for custom language files dependend on mtime.
        //If custom/include was the last file touched before upgrade, we need to run this script.
        if (version_compare($this->from_version, '7.6.0', '>=')) {
            //Check for each language if the custom/include file was the last touched.
            foreach ($this->upgrader->state['dropdowns_to_merge'] as $language => $dropdowns) {

                $isMTimeExists = array_key_exists('mtime', $dropdowns) && is_array($dropdowns['mtime']);
                if ($isMTimeExists && $dropdowns['mtime']['include'] < $dropdowns['mtime']['ext']) {
                    unset($this->upgrader->state['dropdowns_to_merge'][$language]);
                }
            }
            //If there was nothing left to upgrade, return.
            if ((empty($this->upgrader->state['dropdowns_to_merge']))) {
                $this->log('**** Skipped Dropdown Lists Merge **** Nothing left to merge as mtime eliminated all options');
                return;
            }
        }

        if (empty($this->context['new_source_dir'])) {
            $this->log('**** Skipped Dropdown Lists Merge **** The new source directory was not found.');
            return;
        }

        if (empty($this->upgrader->state['dropdowns_to_merge'])) {
            $this->log('**** Skipped Dropdown Lists Merge **** There are no dropdown lists to merge.');
            return;
        }


        $merger = new DropdownMerger();
        $helper = $this->getDropdownHelper();
        $parser = $this->getDropdownParser();

        foreach ($this->upgrader->state['dropdowns_to_merge'] as $language => $dropdowns) {
            $new = $helper->getDropdowns("include/language/{$language}.lang.php");

            foreach ($dropdowns['custom'] as $name => $customOptions) {
                if (!isset($new[$name])) {
                    $listValue = $this->prepareForSave($customOptions);
                } else {
                    $oldOptions = array();
                    $newOptions = $new[$name];

                    if (isset($dropdowns['old']) && isset($dropdowns['old'][$name])) {
                        $oldOptions = $dropdowns['old'][$name];
                    }

                    $listValue = $this->prepareForSave($merger->merge($oldOptions, $newOptions, $customOptions));
                }


                $_REQUEST['dropdown_lang'] = $language;
                $_REQUEST['view_package'] = 'studio';
                $params = array(
                    'dropdown_lang' => $language,
                    'dropdown_name' => $name,
                    'list_value' => $listValue,
                    'skip_sync' => true,
                    'view_package' => 'studio',
                    'use_push' => in_array($name, $helper->getDropdownsToPush()),
                    'skipSaveExemptDropdowns' => true,
                );

                $parser->saveDropDown($params);
                $this->log("{$name} has been merged for {$language}");
            }
        }
    }

    /**
     * {@link ParserDropDown::saveDropDown} expects the dropdown options to be in JSON format, like the following:
     * <code>
     * <?php
     * $options = '[["foo","Foo"],["bar","Bar"]]';
     * </code>
     *
     * This method takes the dropdown options as a standard hash and converts it to the expected JSON.
     *
     * @param array $dropdown
     * @return string
     */
    protected function prepareForSave($dropdown = array())
    {
        $json = getJSONobj();
        $pairs = array();

        foreach ($dropdown as $key => $value) {
            $pairs[] = array($key, $value);
        }

        return $json->encode($pairs);
    }
}
