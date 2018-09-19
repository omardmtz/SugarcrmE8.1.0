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
 * Repair vendor links
 */
class SugarUpgradeRepairVendors extends UpgradeScript
{
    public $order = 9000;
    public $type = self::UPGRADE_CUSTOM;

    protected $excludedScanDirectories = array(
        'backup',
        'disable',
        'disabled',
        'tmp',
        'temp',
    );

    public $directories = array(
        'pre7' => array(
            'include/HTMLPurifier' => 'vendor/HTMLPurifier',
            'include/HTTP_WebDAV_Server' => 'vendor/HTTP_WebDAV_Server',
            'include/Pear' => 'vendor/Pear',
            'include/Smarty' => 'vendor/Smarty',
            'XTemplate' => 'vendor/XTemplate',
            'Zend' => 'vendor/Zend',
            'include/lessphp' => 'vendor/lessphp',
            'log4php' => 'vendor/log4php',
            'include/nusoap' => 'vendor/nusoap',
            'include/oauth2-php' => 'vendor/oauth2-php',
            'include/pclzip' => 'vendor/pclzip',
            'include/reCaptcha' => 'vendor/reCaptcha',
            'include/tcpdf' => 'vendor/tcpdf',
            'include/ytree' => 'vendor/ytree',
            'include/SugarSearchEngine/Elastic/Elastica' => 'vendor/ruflin/elastica/lib/Elastica',
            'include/phpmailer' => 'vendor/phpmailer/phpmailer',
        ),
        'pre75' => array(
            'vendor/Elastica' => 'vendor/ruflin/elastica/lib/Elastica',
            'vendor/OneLogin' => 'vendor/onelogin/php-saml/lib',
            'vendor/PHPMailer' => 'vendor/phpmailer/phpmailer',
        ),
        'pre76' => array(
            'vendor/OneLogin' => 'vendor/onelogin/php-saml/lib',
            'vendor/PHPMailer' => 'vendor/phpmailer/phpmailer',
        ),
    );

    protected $sugarSpecificFiles = array(
        'include/Smarty/plugins/function.sugar_action_menu.php' =>
            'include/SugarSmarty/plugins/function.sugar_action_menu.php',
    );

    public function run()
    {
        // determine directory set
        if (version_compare($this->from_version, '7.0.0', "<")) {
            $directories = $this->directories['pre7'];
        } elseif (version_compare($this->from_version, '7.0.0', ">=") && version_compare($this->from_version, '7.5.0', "<")) {
            $directories = $this->directories['pre75'];
        } elseif (version_compare($this->from_version, '7.5.0', ">=") && version_compare($this->from_version, '7.6.0', "<")) {
            $directories = $this->directories['pre76'];
        } else {
            return;
        }

        // check custom Smarty plugins
        $this->replaceCustomSmartyPlugins();

        // check custom directory
        $this->scanDir('custom/', $directories);

        // check ModuleBuilder modules
        if (!empty($this->upgrader->state['MBModules'])) {
            foreach ($this->upgrader->state['MBModules'] as $mbModule) {
                $this->scanDir("modules/{$mbModule}/", $directories);
            }
        }
    }

    /**
     * Scan Smarty plugins directories and relocate custom plugins to correct location
     */
    public function replaceCustomSmartyPlugins()
    {
        // Step 1: scan vendor/Smarty/plugin directory to get a list of system plugins
        $vendorSmartyPluginsList = array();
        $vendorSmartyPluginsPath = 'vendor/Smarty/plugins/';

        $failedToCopySmartyPluginsList = array();
        $customSmartyPluginsPaths = array(
            'include/Smarty/plugins/',
            'custom/include/Smarty/plugins/',
        );

        $includeSmartyPluginsPath = 'include/SugarSmarty/plugins/';
        $correctCustomSmartyPluginsPath = 'custom/include/SugarSmarty/plugins/';

        if (is_dir($vendorSmartyPluginsPath)) {
            $iter = new FilesystemIterator($vendorSmartyPluginsPath, FilesystemIterator::UNIX_PATHS);
            foreach ($iter as $item) {
                if ($item->getFileName() == '.' || $item->getFileName() == '..' || $item->isDir() || ($item->getExtension() != 'php')) {
                    continue;
                }
                $filename = $item->getFilename();
                $vendorSmartyPluginsList[] = $filename;
            }
        }

        // Step 2: scan custom plugin directories and relocate ONLY custom plugins to correct location
        foreach ($customSmartyPluginsPaths as $customSmartyPluginsPath) {
            if (is_dir($customSmartyPluginsPath)) {
                $iter = new FilesystemIterator($customSmartyPluginsPath, FilesystemIterator::UNIX_PATHS);
                foreach ($iter as $item) {
                    if ($item->getFilename() == '.' || $item->getFilename() == '..' || $item->isDir() || ($item->getExtension() != 'php')) {
                        continue;
                    }
                    $file = $item->getPathname();
                    if (!in_array($item->getFilename(), $vendorSmartyPluginsList) && !is_file($includeSmartyPluginsPath . $item->getFilename())) {
                        $this->log("Copy custom plugin {$file} to {$correctCustomSmartyPluginsPath}");

                        if (!sugar_is_dir($correctCustomSmartyPluginsPath)) {
                            mkdir_recursive($correctCustomSmartyPluginsPath);
                        }

                        if (copy_recursive($file, $correctCustomSmartyPluginsPath . $item->getFilename())) {
                            $this->upgrader->fileToDelete($file, $this);
                        } else {
                            $failedToCopySmartyPluginsList[] = $file;
                        }
                    }
                }
            }
        }

        //Step 3: remove all files from custom Smarty plugins destinations except the {$correctCustomSmartyPluginsPath}
        if (empty($failedToCopySmartyPluginsList)) {
            foreach ($customSmartyPluginsPaths as $customSmartyPluginsPath) {
                if (is_dir($customSmartyPluginsPath)) {
                    $this->log("Path {$customSmartyPluginsPath} is deleted from custom plugins directory due to a relocation of vendors");
                    rmdir_recursive($customSmartyPluginsPath);
                }
            }
        } else {
            foreach ($failedToCopySmartyPluginsList as $failedToCopySmartyPluginsItem) {
                $this->log("File {$failedToCopySmartyPluginsItem} cannot be copied to new location automatically");
            }
        }
    }

    /**
     * Repair include paths for Sugar Specific files that are not vendor files but do reside in another directory
     * @param string $file string name of the file to check and process
     */
    public function repairSugarSpecificFilesPath($file)
    {
        $replacedCount = 0;

        $contents = file_get_contents($file);

        $contents = str_replace(
            array_keys($this->sugarSpecificFiles),
            array_values($this->sugarSpecificFiles),
            $contents,
            $replacedCount
        );

        if ($replacedCount) {
            $this->log("Updating $file with replacing old path");
            $this->backupFile($file);
            sugar_file_put_contents($file, $contents);
        }
    }

    /**
     * Scan directory and replace vendors links
     * @param string $path
     * @param array $directories
     * @return array Files data
     */
    public function scanDir($path, array $directories)
    {
        if (file_exists($path)) {

            $iter = new FilesystemIterator($path, FilesystemIterator::UNIX_PATHS);

            foreach ($iter as $item) {
                $filename = $item->getFilename();
                if ($filename == '.' || $filename == '..') {
                    continue;
                }

                if(strpos($filename, ".suback.php") !== false) {
                    // we'll ignore .suback files, they are old upgrade backups
                    continue;
                }

                if ($item->isDir() && in_array($filename, $this->excludedScanDirectories)) {
                    continue;
                } elseif ($item->isDir()) {
                    if(strtolower($filename) == 'disable' || strtolower($filename) == 'disabled') {
                        // skip disable dirs
                        continue;
                    }
                    $this->scanDir($path . $filename . "/", $directories);
                } elseif ($item->getExtension() != 'php') {
                    continue;
                } else {
                    if ($item->isFile()) {
                        $file = $item->getPathname();

                        $this->repairSugarSpecificFilesPath($file);

                        // check for any occurrence of the directories and replace them
                        $fileContents = file_get_contents($file);
                        foreach ($directories as $pattern => $replace) {
                            if (preg_match("#(include|require|require_once|include_once)\s*[\(]?['\"]([^\._-]*?)(\b{$pattern}\b.*?)['\"][\)]?;#is", $fileContents, $match)) {

                                if ('vendor/' === $match[2]) {
                                    continue;
                                }

                                $replaceCallback = array(
                                    'pattern' => $pattern,
                                    'replace' => $replace
                                );

                                $fileContents = preg_replace_callback(
                                    "#(include|require|require_once|include_once)\s*[\(]?['\"]([^\._-]*?)(\b{$pattern}\b.*?)['\"][\)]?;#is",
                                    function ($matches) use ($replaceCallback) {
                                        if (!empty($matches[3])) {
                                            return preg_replace("#{$replaceCallback['pattern']}#is", "{$replaceCallback['replace']}", $matches[0]);
                                        } else {
                                            return $matches[0];
                                        }
                                    },
                                    $fileContents
                                );

                                $this->backupFile($file);
                                $this->log("Updating {$file} with replacing vendor path");
                                sugar_file_put_contents($file, $fileContents);
                            }
                        }
                    }
                }
            }
        }
    }
}
