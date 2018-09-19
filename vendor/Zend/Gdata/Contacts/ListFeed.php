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
 * Implementation by SugarCRM, not shipped by ZF.
 *
 */

/**
 * @see Zend_Gdata_Feed
 */
require_once 'vendor/Zend/Gdata/Feed.php';


class Zend_Gdata_Contacts_ListFeed extends Zend_Gdata_Feed
{

    /**
     * The classname for individual feed elements.
     *
     * @var string
     */
    protected $_entryClassName = 'Zend_Gdata_Contacts_ListEntry';

    /**
     * The classname for the feed.
     *
     * @var string
     */
    protected $_feedClassName = 'Zend_Gdata_Contacts_ListFeed';

    public function __construct($element = null)
    {
        $this->registerAllNamespaces(Zend_Gdata_Contacts::$namespaces);
        parent::__construct($element);
    }
}
