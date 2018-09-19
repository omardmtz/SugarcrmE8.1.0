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
 * Undeployed metadata file
 */
class MetaDataFileUndeployed implements MetaDataFileInterface
{
    /**
     * @var MetaDataFile
     */
    protected $file;

    /**
     * @var string
     */
    protected $package;

    /**
     * @var string
     */
    protected $location;

    /**
     * Constructor
     *
     * @param MetaDataFileInterface $file
     * @param string $package
     * @param string $location
     */
    public function __construct(MetaDataFileInterface $file, $package, $location)
    {
        $this->file = $file;
        $this->package = $package;
        $location = strtolower($location);

        switch ($location) {
            case MB_BASEMETADATALOCATION:
            case MB_HISTORYMETADATALOCATION:
            case MB_WORKINGMETADATALOCATION:
                break;
            default:
                // just warn rather than die
                $GLOBALS['log']->warn(
                    "Undeployed metadata location $location is not recognized"
                );
                break;
        }

        $this->location = $location;
    }

    /** {@inheritDoc} */
    public function getPath()
    {
        $path = $this->file->getPath();

        switch ($this->location) {
            case MB_HISTORYMETADATALOCATION:
                $path = array_merge(
                    explode('/', trim(MetaDataFiles::$paths[MB_WORKINGMETADATALOCATION], '/')),
                    array('modulebuilder','packages', $this->package),
                    $path
                );
                break;
            default:
                // get the module again, all so we can call this method statically without relying
                // on the module stored in the class variables
                require_once 'modules/ModuleBuilder/MB/ModuleBuilder.php';
                $mb = new ModuleBuilder();
                array_shift($path);
                $module = array_shift($path);
                $path = array_merge(
                    explode('/', trim($mb->getPackageModule($this->package, $module)->getModuleDir(), '/')),
                    $path
                );
        }

        return $path;
    }
}
