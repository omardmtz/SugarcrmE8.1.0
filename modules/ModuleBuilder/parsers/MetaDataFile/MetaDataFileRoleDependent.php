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
class MetaDataFileRoleDependent implements MetaDataFileInterface
{
    /**
     * @var MetaDataFile
     */
    protected $file;

    /**
     * @var string
     */
    protected $role;

    /**
     * Constructor
     *
     * @param MetaDataFileInterface $file
     * @param string $role
     */
    public function __construct(MetaDataFileInterface $file, $role)
    {
        $this->file = $file;
        $this->role = $role;
    }

    /** {@inheritDoc} */
    public function getPath()
    {
        $path = $this->file->getPath();
        $view = array_pop($path);
        array_push($path, 'roles', $this->role, $view);

        return $path;
    }
}
