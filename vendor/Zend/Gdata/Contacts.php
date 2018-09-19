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
 * @see Zend_Gdata
 */
require_once 'vendor/Zend/Gdata.php';

/**
 * @see Zend_Gdata_Books_VolumeFeed
 */
require_once 'vendor/Zend/Gdata/Contacts/ListFeed.php';

/**
 * @see Zend_Gdata_Docs_DocumentListEntry
 */
require_once 'vendor/Zend/Gdata/Contacts/ListEntry.php';


/**
 * Service class for interacting with the Google Contacts data API
 */
class Zend_Gdata_Contacts extends Zend_Gdata
{

    const CONTACT_FEED_URI = 'https://www.google.com/m8/feeds/contacts/default/full';
    const AUTH_SERVICE_NAME = 'cp';
    const DEFAULT_MAJOR_PROTOCOL_VERSION = 3;

    protected $maxResults = 10;
    protected $startIndex = 1;
    /**
     * Namespaces used for Zend_Gdata_Calendar
     *
     * @var array
     */
    public static $namespaces = array(
        array('gContact', 'http://schemas.google.com/contact/2008', 1, 0),
    );

    /**
     * Create Gdata_Calendar object
     *
     * @param Zend_Http_Client $client (optional) The HTTP client to use when
     *          when communicating with the Google servers.
     * @param string $applicationId The identity of the app in the form of Company-AppName-Version
     */
    public function __construct($client = null, $applicationId = 'MyCompany-MyApp-1.0')
    {
        $this->registerPackage('Zend_Gdata_Contacts');
        $this->registerPackage('Zend_Gdata_Contacts_Extension');
        parent::__construct($client, $applicationId);
        $this->_httpClient->setParameterPost('service', self::AUTH_SERVICE_NAME);
        $this->setMajorProtocolVersion(self::DEFAULT_MAJOR_PROTOCOL_VERSION);
    }

    /**
     * Retrieve feed object
     *
     * @return Zend_Gdata_Calendar_ListFeed
     */
    public function getContactListFeed()
    {
        $query = new Zend_Gdata_Query(self::CONTACT_FEED_URI);
        $query->maxResults = $this->maxResults;
        $query->startIndex = $this->startIndex;
        return parent::getFeed($query,'Zend_Gdata_Contacts_ListFeed');
    }

    /**
     * Retrieve a single feed object by id
     *
     * @param string $entryID
     * @return string|Zend_Gdata_App_Feed
     */
    public function getContactEntry($entryID)
    {
        return parent::getEntry($entryID,'Zend_Gdata_Contacts_ListEntry');
    }

    /**
     * Set the max results that the feed should return.
     * 
     * @param  $maxResults
     * @return void
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
    }

    /**
     * Set the start index.
     *
     * @param  $value
     * @return void
     */
    public function setStartIndex($value)
    {
        $this->startIndex = $value;
    }

}
 
