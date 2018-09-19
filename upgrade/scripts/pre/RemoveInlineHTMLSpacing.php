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
 * Walking through custom directory and trim spaces in php files
 */
class SugarUpgradeRemoveInlineHTMLSpacing extends UpgradeScript
{
    public $order = 500;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        /** @var $node SplFileInfo */
        $this->log("**** Removal of inline HTML started");
        $directory = new RecursiveDirectoryIterator($this->context['source_dir'] . '/custom', FilesystemIterator::UNIX_PATHS);
        $offset = strlen($this->context['source_dir']) + 1;
        $iterator = new RecursiveIteratorIterator($directory);
        foreach ($iterator as $node) {
            if (!$node->isFile()) {
                continue;
            }

            if (pathinfo($node->getPathname(), PATHINFO_EXTENSION) != 'php') {
                continue;
            }

            // initializing variables
            $content = file_get_contents($node->getPathname());
            $length = strlen($content);
            $start = 0;
            $end = $length;

            // detecting leading spaces
            for ($i = 0; $i < $length; $i ++) {
                $char = $content[$i];
                switch ($char) {
                    case " " :
                    case "\t" :
                    case "\n" :
                    case "\r" :
                        break;
                    default :
                        $start = $i;
                        break 2;
                }
            }
            // detecting ending spaces
            for ($i = ($length - 1); $i > 0; $i --) {
                $char = $content[$i];
                switch ($char) {
                    case " " :
                    case "\t" :
                    case "\n" :
                    case "\r" :
                        break;
                    default :
                        $end = $i + 1;
                        break 2;
                }
            }

            // removing spaces only if file has closing php tag
            if ($end >= 2 && substr($content, $end - 2, 2) == '?>') {
                // \n after closing tag is okay for us
                if ($end + 1 == $length && $content[$end] == "\n") {
                    $end = $length;
                }
            } else {
                $end = $length;
            }

            // if something was detected then trimming file
            if ($start != 0 || $end != $length) {
                $this->backupFile(substr($node->getPathname(), $offset));
                $content = substr($content, $start, $end - $start);
                file_put_contents($node->getPathname(), $content);
                $this->log($node->getPathname() . " has been trimmed");
            }
        }
        $this->log("**** Removal of inline HTML ended");
    }
}
