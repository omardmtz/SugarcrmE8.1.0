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
 * Identifies any customized dropdown lists and puts them in memory to be merged with any changes to the core dropdown
 * lists.
 *
 * Customized dropdown lists exist in the custom directory and only those that have been customized need to be merged.
 * Both the core and customized dropdown lists are placed into the upgrader's state when a customized dropdown list is
 * encountered.
 */
class SugarUpgradeLoadDropdowns extends UpgradeScript
{
    public $order = 402;
    public $type = self::UPGRADE_CUSTOM;
    public $version = '7.6.0';

    protected $dropdownHelper = null;

    /**
     * {@inheritdoc}
     *
     * This upgrader should always be run since changes to the core dropdown lists can occur in any version.
     *
     * Dropdown lists exist in the i18n translation files matching `include/language/*.lang.php` and
     * `custom/include/language/*.lang.php`. Only dropdown lists that are found in the custom files need to be merged
     * because dropdown lists that have not been customized are upgraded without conflict.
     *
     * When a customized dropdown list is encountered, both of the corresponding core and customized dropdown lists are
     * placed in the upgrader's state.
     * <code>
     * <?php
     * $this->upgrader->state['dropdowns_to_merge'] = array(
     *     '<language>' => array(
     *         'old' => array(
     *             '<dropdown_list_name>' => array(
     *                 // core options
     *             ),
     *         ),
     *         'custom' => array(
     *             '<dropdown_list_name>' => array(
     *                 // customized options
     *             ),
     *         ),
     *     ),
     * );
     * </code>
     *
     * Dropdown lists that were created by the customer are also merged in case a dropdown list with the same name is
     * added to core. In this event, the `old` array will not contain an entry for the dropdown list, but the `custom`
     * array will.
     * <code>
     * <?php
     * $this->upgrader->state['dropdowns_to_merge'] = array(
     *     '<language>' => array(
     *         'custom' => array(
     *             '<dropdown_list_name>' => array(
     *                 // customized options
     *             ),
     *         ),
     *     ),
     * );
     * </code>
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.6.2.1', '>=')) {
            $this->log('**** Skipped Dropdown Lists Merge **** Sugar version is too new');
            return;
        }

        if (empty($this->context['new_source_dir'])) {
            $this->log('**** Skipped Dropdown Lists Merge **** The new source directory was not found.');
            return;
        }

        if (!is_array($this->upgrader->state)) {
            $this->upgrader->state = array();
        }

        $this->upgrader->state['dropdowns_to_merge'] = array();

        $helper = $this->getDropdownHelper();

        // search each i18n translation file in the application
        foreach ($this->geti18nFiles() as $coreFile) {
            $customFile = $this->getCustomFile($coreFile);

            if (!$customFile) {
                // no custom file means there are no customized dropdown lists
                continue;
            }


            $language = $this->getLanguage($coreFile);

            $extfile = $this->getCustomFile("application/Ext/Language/{$language}.lang.ext.php");

            // load all of the core dropdown lists
            $core = $helper->getDropdowns($coreFile);

            // load all of the customized dropdown lists
            $custom = $helper->getDropdowns($customFile);

            if (empty($core) && empty($custom)) {
                // only restricted dropdown lists were encountered; restricted dropdown lists are ignored
                continue;
            }

            // we only care about the dropdown lists in core that have been customized
            $old = array_intersect_key($core, $custom);

            $this->upgrader->state['dropdowns_to_merge'][$language] = array(
                'old' => $old,
                'custom' => $custom,
                //track the mtime on the custom files to determine if we need to upgrade custom/include again...
                'mtime' => array(
                    'include' => $this->getFileMTime($customFile),
                    'ext' => $this->getFileMTime($extfile)
                )
            );

            $this->log("customized for {$language}: " . implode(', ', array_keys($old)));
        }
    }

    /**
     * Returns the path to the custom i18n translation file counterpart to the specified core i18n translation file.
     *
     * @param $coreFile
     * @return null|string Null is returned if the custom i18n translation file does not exist.
     */
    protected function getCustomFile($coreFile)
    {
        $customFile = "custom/{$coreFile}";

        if (file_exists($customFile)) {
            return $customFile;
        }

        return null;
    }

    /**
     * Wrapper for returning the modification time of a given file.
     * @param $file
     *
     * @return int
     */
    protected function getFileMTime($file)
    {
        if ($file && file_exists($file)) {
            return filemtime($file);
        }

        return 0;
    }

    /**
     * Extracts the i18n language key from the filename. For example, `en_us` for English-US.
     *
     * @param string $file The path to the i18n translation file.
     * @return string
     */
    protected function getLanguage($file)
    {
        $filename = pathinfo($file, PATHINFO_FILENAME);
        $suffixPos = stripos($filename, '.lang');

        if ($suffixPos === false) {
            return $filename;
        }

        return substr($filename, 0, strlen($filename) - $suffixPos);
    }

    /**
     * Returns an instance of {@link UpgradeDropdownsHelper}.
     *
     * @return UpgradeDropdownsHelper
     */
    protected function getDropdownHelper()
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
     * Globs the core language directory and returns an array of paths to any i18n translation files.
     *
     * @return array
     */
    protected function geti18nFiles()
    {
        return glob('include/language/*.lang.php');
    }
}
