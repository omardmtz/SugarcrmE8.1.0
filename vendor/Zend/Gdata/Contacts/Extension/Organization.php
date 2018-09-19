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


require_once 'vendor/Zend/Gdata/Extension.php';


class Zend_Gdata_Contacts_Extension_Organization extends Zend_Gdata_Extension
{

    protected $_rootNamespace = 'gd';
    protected $_rootElement = 'organization';
    protected $_orgName = null;
    protected $_orgTitle = null;

    /**
     * Constructs a new Zend_Gdata_Contacts_Extension_Name object.
     * @param string $value (optional) The text content of the element.
     */
    public function __construct($value = null)
    {
        $this->registerAllNamespaces(Zend_Gdata_Contacts::$namespaces);
        parent::__construct();
    }

    protected function takeChildFromDOM($child)
    {
        $absoluteNodeName = $child->namespaceURI . ':' . $child->localName;

        switch ($absoluteNodeName)
        {
            case $this->lookupNamespace('gd') . ':' . 'orgName';
                $entry = new Zend_Gdata_Entry();
                $entry->transferFromDOM($child);
                $this->_orgName = $entry;
                break;

            case $this->lookupNamespace('gd') . ':' . 'orgTitle';
                $entry = new Zend_Gdata_Entry();
                $entry->transferFromDOM($child);
                $this->_orgTitle = $entry;
                break;

            default:
                parent::takeChildFromDOM($child);
                break;
        }
    }


    public function getOrganizationName()
    {
        if($this->_orgName != null)
            return $this->_orgName->getText();
        else
            return '';
    }
    public function getOrganizationTitle()
    {
        if($this->_orgTitle != null)
            return $this->_orgTitle->getText();
        else
            return '';
    }



}


