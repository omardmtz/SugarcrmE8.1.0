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

namespace Sugarcrm\Sugarcrm\Logger\Handler\Factory;

use Monolog\Handler\StreamHandler;
use Sugarcrm\Sugarcrm\Logger\Formatter\BackwardCompatibleFormatter;
use Sugarcrm\Sugarcrm\Logger\Handler\Factory;

/**
 * File handler factory
 */
class File implements Factory
{
    /**
     * Default configuration for file handler
     *
     * @var array
     */
    protected static $defaults = array(
        'dir' => '.',
        'name' => 'sugarcrm',
        'ext' => 'log',
        'suffix' => '',
        'dateFormat' => '%c',
    );

    /**
     * {@inheritDoc}
     *
     * Creates file handler
     */
    public function create($level, array $config)
    {
        $config = array_merge(self::$defaults, $config);

        $path = $this->getFilePath($config['dir'], $config['name'], $config['ext'], $config['suffix']);
        $handler = new StreamHandler($path, $level);

        $formatter = new BackwardCompatibleFormatter($config['dateFormat']);
        $handler->setFormatter($formatter);

        return $handler;
    }

    /**
     * Returns log file path
     *
     * @param string $dir Log directory
     * @param string $name File name
     * @param string $ext File extension
     * @param string $suffix File suffix
     * @return string
     */
    protected function getFilePath($dir, $name, $ext, $suffix)
    {
        $dir = rtrim($dir, '/');
        $ext = ltrim($ext, '.');
        $path = $dir . '/' . $name;

        if ($suffix && $this->isFileNameSuffixValid($suffix)) {
            // if the global config contains date-format suffix, it will create suffix by parsing datetime
            $path .= '_' . date(str_replace('%', '', $suffix));
        }

        if ($ext) {
            $path .= '.' . $ext;
        }

        return $path;
    }

    /**
     * Checks if the filename suffix is valid
     *
     * @param string $suffix Filename suffix
     * @return string
     */
    protected function isFileNameSuffixValid($suffix)
    {
        return isset(\SugarLogger::$filename_suffix[$suffix]);
    }
}
