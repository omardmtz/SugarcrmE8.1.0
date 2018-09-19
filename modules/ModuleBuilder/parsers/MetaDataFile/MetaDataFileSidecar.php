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
 * Sidecar metadata file
 */
class MetaDataFileSidecar implements MetaDataFileInterface
{
    /**
     * The path for almost all views
     *
     * @var string
     */
    protected $viewDir = 'views';

    /**
     * Mapping of special views to their path
     *
     * @var array
     */
    protected $viewDirs = array(
        MB_FILTERVIEW => 'filters',
    );

    /**
     * @var MetaDataFile
     */
    protected $file;

    /**
     * @var string
     */
    protected $client;

    /**
     * Constructor
     *
     * @param MetaDataFileInterface $file
     * @param string $client
     */
    public function __construct(MetaDataFileInterface $file, $client)
    {
        $this->file = $file;
        $this->client = $client;
    }

    /** {@inheritDoc} */
    public function getPath()
    {
        $path = $this->file->getPath();
        $viewDir = $this->getViewPath();
        array_splice($path, 2, 0, array('clients', $this->client, $viewDir));
        $path[] = end($path);

        return $path;
    }

    /**
     * Gets the view directory for the path from the view
     *
     * @return string
     */
    protected function getViewPath()
    {
        $view = $this->file->getView();
        if (isset($this->viewDirs[$view])) {
            return $this->viewDirs[$view];
        }

        return $this->viewDir;
    }
}
