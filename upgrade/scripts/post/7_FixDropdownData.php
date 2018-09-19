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
 * Move custom dropdown data from custom/include/language to custom/Extension/application/Ext/Language
 */
class SugarUpgradeFixDropdownData extends UpgradeScript
{
    public $order = 7100;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        $originalPath = 'custom/include/language';
        $newPath = 'custom/Extension/application/Ext/Language';
        if (!version_compare($this->from_version, '7.6.1', "<")) {
            return;
        }

        if (!is_dir($originalPath)) {
            return;
        }

        if (!is_dir($newPath)) {
            sugar_mkdir($newPath, null, true);
        }

        foreach (glob($originalPath . '/*.lang.php') as $file) {
            require $file;
            $fileParts = explode('/', $file);
            $fileName = $fileParts[3];
            $nameParts = explode('.', $fileName);
            $lang = $nameParts[0];
            $strings = array();
            // if $app_list_strings exists and it's a custom DD, we want to move it to the new directory
            if (!empty($app_list_strings)) {
                foreach($app_list_strings as $name => $fields) {
                    // $app_list_strings DD strings
                    if (is_array($fields) && $this->isCustomField($name, $fields, $file)) {
                        $newFileName = $newPath . '/' . $lang . '.sugar_' . $name . '.php';
                        $this->write_to_file($newFileName, 'app_list_strings', $name, $fields, true);
                    }
                    // $app_list_strings non-DD strings
                    else {
                        $strings[$name] = $fields;
                    }
                }
                // if it is $app_list_strings but not a DD, we want to persist that back where it was
                if (!empty($strings)) {
                    unlink($file);
                    foreach($strings as $name => $fields) {
                        if (!file_exists($file)) {
                            $this->write_to_file($file, 'app_list_strings', $name, $fields, true);
                        }
                        else {
                            $this->write_to_file($file, 'app_list_strings', $name, $fields);
                        }
                    }
                }
            }
            // if $app_strings exists, we want to persist that back where it was
            if (!empty($app_strings)) {
                if (!empty($strings)) {
                    foreach($app_strings as $name => $field) {
                        $this->write_to_file($file, 'app_strings', $name, $field);
                    }
                }
                else {
                    unlink($file);
                    foreach($app_strings as $name => $field) {
                        if (!file_exists($file)) {
                            $this->write_to_file($file, 'app_strings', $name, $field, true);
                        }
                        else {
                            $this->write_to_file($file, 'app_strings', $name, $field);
                        }
                    }
                }
            }
        }
    }

    public function isCustomField($name, $fields, $file) {

        $OOBLangFile = substr($file, 7); // remove 'custom/' from the file path

        // OOB equivalent doesn't exist, it is a custom field
        if (!file_exists($OOBLangFile)) {
            return true;
        }

        include ($OOBLangFile);

        if (isset($app_list_strings)) {
            if (array_key_exists($name, $app_list_strings)) {
                if (is_array($app_list_strings[$name])) {
                    // Check if top level key exists in $app_list_strings[$name]
                    // it is a custom field if the key doesn't exist
                    foreach ($fields as $key => $value) {
                        if (!array_key_exists($key, $app_list_strings[$name])) {
                            return true;
                        }
                    }
                }
            }
            else {
                return true;
            }
        }
        return false;
    }

    public function write_to_file($file, $string_name, $name, $field, $timeHeader = false) {
        $output = "";
        if ($timeHeader) {
            $output =   "<?php\n" .
                "// created: " . date('Y-m-d H:i:s') . "\n";
        }
        $output .= "\$" . $string_name . "['" . $name . "'] = " . (is_array($field) ? var_export_helper($field) : "'" . str_replace("'", "\'", $field) . "'") . ";\n";
        $fp = fopen($file, 'a');
        fputs($fp, $output);
        fclose($fp);
    }
}
