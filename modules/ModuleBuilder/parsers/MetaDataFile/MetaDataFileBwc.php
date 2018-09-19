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
 * BWC metadata file
 */
class MetaDataFileBwc implements MetaDataFileInterface
{
    /**
     * @var MetaDataFile
     */
    protected $file;

    /**
     * Constructor
     *
     * @param MetaDataFileInterface $file
     */
    public function __construct(MetaDataFileInterface $file)
    {
        $this->file = $file;
    }

    /** {@inheritDoc} */
    public function getPath()
    {
        $path = $this->file->getPath();
        array_splice($path, 2, 0, array('metadata'));

        return $path;
    }
}
