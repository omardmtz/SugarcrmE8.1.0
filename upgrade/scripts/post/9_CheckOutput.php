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
  * Scan modules to find print_r, var_dump, exit, die using and replace it with custom sugar_upgrade_* functions.
  */
class SugarUpgradeCheckOutput extends UpgradeScript
{
    public $order = 9200;
    public $type = self::UPGRADE_CUSTOM;

    protected $excludedScanDirectories = array(
        'backup',
        'disabled',
        'tmp',
        'temp',
    );

    public $filesToFix = array();

    protected $sePattern = <<<ENDP
if\s*\(\s*!\s*defined\s*\(\s*['|"]sugarEntry['|"]\s*\)\s*(\|\|\s*!\s*sugarEntry\s*)?\)\s*{?\s*die\s*\(\s*['|"](.*?)['|"]\s*\)\s*;\s*}?
ENDP;

    public function checkFiles($files)
    {
        foreach ($files as $file => $functions) {
            if (empty($functions)) {
                continue;
            }
            // check for any occurrence of the directories and flag them
            $fileContents = file_get_contents($file);

            // skip entryPoint check die
            $checkEntryPoint = false;
            if (preg_match("#($this->sePattern)#i", $fileContents, $seMatch)) {
                $checkEntryPoint = true;
                $fileContents = preg_replace("#{$this->sePattern}#i", '', $fileContents);
            }

            $regExp = implode('|', $functions);

            if (preg_match_all('#([^_])\b('. $regExp . ')\b(.*?(,(.*?)\)?)?);#is', $fileContents, $matchAll, PREG_SET_ORDER)) {

                $changedContents = $fileContents;
                $changedContentsFlag = false;

                foreach($matchAll as $match) {
                    // skip print_r with second param
                    if ('print_r' == $match[2] && !empty($match[5])) {
                        continue;
                    }

                    $pattern = $match[0];
                    $replace = preg_replace('#([^_])\b(' . $regExp . ')\b([^_])#is', '\\1sugar_upgrade_\\2\\3', $pattern);

                    if (!empty($pattern) && !empty($replace)) {
                        $changedContents = preg_replace("#" . preg_quote($pattern, '#') . "#is", $replace, $changedContents);
                        $changedContentsFlag = true;
                    }

                    if (true == $checkEntryPoint && !empty($seMatch[0])) {
                        $changedContents = preg_replace('#^(<\?php\s*)#is', '\\1' . $seMatch[0], $changedContents);
                        $changedContentsFlag = true;
                        $checkEntryPoint = false;
                    }
                }

                if (true == $changedContentsFlag) {
                    $this->backupFile($file);
                    $this->putFile($file, $changedContents);
                }
            }
        }
    }

    public function run()
    {
        $healthCheck = array();
        if (!empty($this->state['healthcheck'])) {
            foreach ($this->state['healthcheck'] as $healthMeta) {
                switch ($healthMeta['report']) {
                    case 'foundDieExit' :
                        $healthCheck[$healthMeta['params'][0]][] = 'exit';
                        $healthCheck[$healthMeta['params'][0]][] = 'die';
                        break;
                    case 'foundPrintR' :
                        $healthCheck[$healthMeta['params'][0]][] = 'print_r';
                        break;
                    case 'foundVarDump' :
                        $healthCheck[$healthMeta['params'][0]][] = 'var_dump';
                        break;
                    // ignoring foundEcho foundPrint because we don't know how to fix them correctly
                }
            }
        }

        $this->checkFiles($healthCheck);
    }
}
