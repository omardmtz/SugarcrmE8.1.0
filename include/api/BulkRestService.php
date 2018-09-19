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
 * Bulk API Rest service class
 * Shortcuts some functions that we don't need to do on bulk requests
 */
class BulkRestService extends RestService
{
    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
        parent::__construct();
    }

    /**
     * Shortcut authentication since we're already authenticated before
     * @see RestService::authenticateUser()
     */
    protected function authenticateUser()
    {
        $this->user = $this->parent->user;
        return array('isLoggedIn' => true, 'exception' => false);
    }

    /**
     * Don't check metadata - top request checks it
     * @see RestService::isMetadataCurrent()
     */
    protected function isMetadataCurrent()
    {
        return true;
    }

    /**
     * Don't load envt - top request loads it
     * @see ServiceBase::loadUserEnvironment()
     */
    protected function loadUserEnvironment()
    {
    }

    /**
     * Never release session
     * @see ServiceBase::releaseSession()
     */
    protected function releaseSession()
    {
    }
}

