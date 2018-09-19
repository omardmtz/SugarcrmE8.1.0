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


class Zend_Gdata_Contacts_Extension_PhoneNumber extends Zend_Gdata_Extension
{

    protected $_rootNamespace = 'gd';
    protected $_rootElement = 'phoneNumber';
    protected $_isPrimaryNumber = FALSE;
    protected $_phoneType = 'main';

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
            default:
                parent::takeChildFromDOM($child);
                break;
        }
    }

    /**
     * Extracts XML attributes from the DOM and converts them to the
     * appropriate object members.
     *
     * @param DOMNode $attribute The DOMNode attribute to be handled.
     */
    protected function takeAttributeFromDOM($attribute)
    {
        switch ($attribute->localName)
        {
            case 'primary':
                if(strtolower($attribute->nodeValue) == 'true')
                    $this->_isPrimaryNumber = true;
                else
                    $this->_isPrimaryNumber = false;
            break;
            
            case 'rel':
                $this->_phoneType = $attribute->nodeValue;
            break;

            default:
                parent::takeAttributeFromDOM($attribute);
            break;
        }
    }

    public function getPhoneType()
    {
        return str_replace($this->lookupNamespace('gd') . '#', '', $this->_phoneType);
    }
    
    public function getNumber()
    {
        return $this->getText();
    }

    public function isPrimary()
    {
        return $this->_isPrimaryNumber;
    }
}
 
