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

require_once 'modules/Users/authentication/SAMLAuthenticate/saml.php';

/**
 * This file is where the user authentication occurs.
 * No redirection should happen in this file.
 * @deprecated Will be removed in 7.11. IDM-46
 * @deprecated Please use new idM Mango library Glue \IdMSAMLAuthenticate
 */
class SAMLAuthenticateUser extends SugarAuthenticateUser
{

    /**
     * SAML response object
     * @var OneLogin_Saml_Response
     */
    public $samlresponse;

    /**
     * SAML settings
     * @var OneLogin_Saml_Settings
     */
    public $settings;

    /**
     * XPath for assertion XML
     * @var DOMXpath
     */
    protected $xpath = null;

    /**
     * @var SAMLRequestRegistry
     */
    protected $requestRegistry;

    /**
     * Does the actual authentication of the user and returns an id that will be
     * used
     * to load the current user (loadUserOnSession)
     *
     * @param string $name
     * @param string $password
	 * @param STRING $fallback - is this authentication a fallback from a failed authentication
     * @return string id - used for loading the user
     *
     *         Contributions by Erik Mitchell erikm@logicpd.com
     */
    public function authenticateUser($name, $password, $fallback = false)
    {
        $GLOBALS['log']->debug('authenticating user.');
        $GLOBALS['log']->deprecated(sprintf(
            'The %s in %s was called. Please see \IdMSugarAuthenticate',
            __METHOD__,
            __CLASS__
        ));

        if (empty($_POST['SAMLResponse'])) {
            return parent::authenticateUser($name, $password, $fallback);
        }

        $GLOBALS['log']->debug('have saml data.');
        $this->settings = SAMLAuthenticate::loadSettings();
        try {
            $this->samlresponse = new OneLogin_Saml_Response($this->settings, $_POST['SAMLResponse']);
        } catch (Exception $e) {
            $GLOBALS['log']->error("Unexpected exception: ".$e->getMessage());
            return '';
        }

        if (SugarConfig::getInstance()->get('saml.validate_request_id')) {
            $requestId = $this->getRequestId($this->samlresponse);
            if (!$requestId) {
                $GLOBALS['log']->warn('SAML response does not contain request ID');
                return '';
            }

            $registry = $this->getRequestRegistry();
            if (!$registry->isRequestRegistered($requestId)) {
                $GLOBALS['log']->warn('SAML request ' . $requestId . ' is not registered');
                return '';
            }

            $GLOBALS['log']->info('Unregistering SAML request ' . $requestId);
            $registry->unregisterRequest($requestId);
        }

        if ($this->samlresponse->isValid()) {
            $GLOBALS['log']->debug('response is valid');

            $this->samlresponse->attributes = $this->samlresponse->getAttributes();
            if(!empty($this->settings->useXML)) {
                $this->xpath = new DOMXpath($this->samlresponse->document);
            }

            $id = $this->get_user_id();
            if (!empty($this->settings->id)) {
                $user = $this->fetch_user($id, $this->settings->id);
            } else {
                $user = $this->fetch_user($id);
            }

            // user already exists use this one
            if ($user->id) {
                $GLOBALS['log']->debug('have db results');
                if ($user->status != 'Inactive') {
                    $GLOBALS['log']->debug('have current user');
                    $this->updateCustomFields($user);
                    return $user->id;
                } else {
                    $GLOBALS['log']->debug('have inactive user');
                    return '';
                }
            } else {
                $xpath = new DOMXpath($this->samlresponse->document);
                if (isset($this->settings->customCreateFunction)) {
                    return call_user_func($this->settings->customCreateFunction, $this,
                        $this->samlresponse->getNameId(), $xpath, $this->settings);
                } else {
                    return $this->createUser($this->samlresponse->getNameId());
                }
            }
        }
        return '';
    }

    /**
     * Updates the custom fields listed in settings->saml_settings['update'] in
     * our db records with the data from the xml in the saml assertion.
     * Every field listed in the ['update'] array is a key whose value is an attribute name.
     * If the value of the node does not equal the value in our
     * records, update our records to match the value from the assertion.
     *
     * @param User $user - user fetched from our db.
     * @return int - 0 = no action taken, 1 = user record saved, -1 = no update.
     */
    protected function updateCustomFields($user)
    {
        $customFields = $this->getCustomFields('update');

        if (empty($customFields)) {
            $GLOBALS['log']->debug("No custom fields! So returning 0.");
            return 0;
        }

        $GLOBALS['log']->debug("updateCF()... userid={$user->id}");

        $attrs = $this->samlresponse->getAttributes();
        $customFieldUpdated = false;

        foreach ($customFields as $field => $attrfield) {
            $GLOBALS['log']->debug("Top of fields loop with $field.");
            if (!property_exists($user, $field)) {
                $GLOBALS['log']->debug("$field is not a user field.");
                // custom field not listed in db query results!
                continue;
            }
            if (!$this->hasAttribute($attrfield)) {
                continue;
            }

            $customFieldValue = $user->$field;
            $xmlValue = $this->getAttribute($attrfield);
            $GLOBALS['log']->debug("$field SAML returned $xmlValue");

            if ($customFieldValue != $xmlValue) {
                // need to update our user record.
                $customFieldUpdated = true;
                $user->$field = $xmlValue;
                $GLOBALS['log']->debug("db is out of date. setting {$field} to {$xmlValue}");
            }
        }

        if ($customFieldUpdated) {
            $GLOBALS['log']->debug("updateCustomFields calling user->save() and returning 1");
            $user->save();
            return 1;
        }

        $GLOBALS['log']->debug("updateCustomFields found no fields to update. Returning -1");
        return -1;
    }

    /**
     * Determines if there are custom fields to add to our select statement, and
     * returns a comma prepended, comma-delimited list of those custom fields.
     *
     * @param OneLogin_Saml_Response $this->samlresponse
     * @param OneLogin_Saml_Settings $this->settings
     * @return String $additionalFields = either empty, or ", field1[, field2,
     *         fieldn]"
     */
    protected function getAdditionalFieldsToSelect()
    {
        $fields = $this->getCustomFields('update');
        if (!empty($fields)) {
            return ',' . implode(',', array_keys($fields));
        }
        return '';
    }

    /**
     * Returns an array of custom field names.
     * These names are the keys in the
     * 'update' hash in $this->settings->saml2_settings hash.
     * See modules/Users/authentication/SAMLAuthenticate/settings.php for
     * details.
     *
     * @param string $which - which custom fields: 'check', 'create' or 'update'
     * @return array - list of custom field names.
     *
     */
    protected function getCustomFields($which)
    {
        if (isset($this->settings->saml2_settings[$which])) {
            return $this->settings->saml2_settings[$which];
        } else {
            return array();
        }
    }

    /**
     * Creates a user with the given User Name and returns the id of that new
     * user
     * populates the user with what was set in the SAML Response
     *
     * @param string $name
     * @return string $id
     */
    protected function createUser($name)
    {
        if (empty($this->settings->provisionUsers)) {
            return '';
        }
        $GLOBALS['log']->debug("Called createUser");
        $user = BeanFactory::newBean('Users');
        $user->user_name = $name;
        $user->email1 = $name;
        $user->last_name = $name;
        $user->employee_status = 'Active';
        $user->status = 'Active';
        $user->is_admin = 0;
        $user->external_auth_only = 1;
        $user->system_generated_password = 0;

        // Loop through the create custom fields and update their values in the
        // user object from the xml SAML response.
        $customFields = $this->getCustomFields('create');
        $GLOBALS['log']->debug("number of custom fields: " . count($customFields));
        foreach ($customFields as $field => $attrfield) {
            $GLOBALS['log']->debug("xpath for $field is $attrfield");
            if (!$this->hasAttribute($attrfield)) {
                continue;
            }

            if ($field == 'id') {
                $user->new_with_id = true;
            }

            $value = $this->getAttribute($attrfield);
            $GLOBALS['log']->debug("Setting $field to $value");
            $user->$field = $value;
        }

        $GLOBALS['log']->debug("finished loop - saving.");
        $user->save();
        $GLOBALS['log']->debug("New user id is " . $user->id);
        return $user->id;
    }

    /**
     * Retrieves user ID from SamlResponse according to SamlSettings
     *
     * @return string
     */
    protected function get_user_id()
    {
        $fields = $this->getCustomFields('check');
        if (isset($fields['user_name'])) {
            if ($this->hasAttribute($fields['user_name'])) {
                return $this->getAttribute($fields['user_name']);
            }
        }

        return $this->samlresponse->getNameId();
    }

    /**
     * Get value of the assertion attribute
     * @param string $name
     * @return null|string
     */
    protected function getAttribute($name)
    {
        if($this->xpath) {
            $xmlNodes = $this->xpath->query($name);
            if ($xmlNodes === false)
            {
                // malformed xpath!
                $GLOBALS['log']->debug("Bad xpath: $name");
                return null;
            }
            if ($xmlNodes->length == 0)
            {
                // no nodes match xpath!
                $GLOBALS['log']->debug("No nodes match this xpath: $name");
                return null;
            }
            return $xmlNodes->item(0)->nodeValue;
        }
        if(isset($this->samlresponse->attributes[$name])) {
            return $this->samlresponse->attributes[$name][0];
        }
        return null;
    }

    /**
     * Check if assertion attribute exists
     * @param string $name
     */
    protected function hasAttribute($name)
    {
        if($this->xpath) {
            $res = $this->getAttribute($name);
            return !is_null($res);
        }
        return isset($this->samlresponse->attributes[$name]);
    }

    /**
     * Fetches user by provided ID and field name
     *
     * @param mixed $id
     * @param string $field
     * @return User
     */
    protected function fetch_user($id, $field = null)
    {
        $user = BeanFactory::newBean('Users');

        if (null !== $field) {
            switch ($field) {
                case 'user_name':
                    // fetch user id by username
                    $id = $user->db->getOne(
                        'select id from users where user_name = ' . $user->db->quoted($id) .
                             ' and deleted = 0');
                    if (!empty($id)) {
                        $user->retrieve($id);
                    }
                    break;
                case 'id':
                    $user->retrieve($id);
                    break;
                default:
                    // nothing else is implemented
                    break;
            }
        } else {
            // use email as a default primary key (onelogin.com provides it)
            $user->retrieve_by_email_address($id);
        }

        return $user;
    }

    /**
     * This is called when a user logs in
     *
     * @param string $name
     * @param string $password
     * @param boolean $fallback - is this authentication a fallback from a failed authentication
     * @param array $params
     * @return boolean
     */
    public function loadUserOnLogin($name, $password, $fallback = false, array $params = array())
    {
        // provide dummy login and password to parent class so that
        // authentication
        // process could go on
        return parent::loadUserOnLogin('onelogin', 'onelogin', $fallback, $params);
    }

    /**
     * Returns ID of the request which the response belongs to
     *
     * @param OneLogin_Saml_Response $response SAML response
     * @return string
     */
    protected function getRequestId(OneLogin_Saml_Response $response)
    {
        return $response->document->documentElement->getAttribute('InResponseTo');
    }

    /**
     * Returns SAML request registry
     *
     * @return SAMLRequestRegistry
     */
    protected function getRequestRegistry()
    {
        if (!$this->requestRegistry) {
            $this->requestRegistry = new SAMLRequestRegistry();
        }

        return $this->requestRegistry;
    }
}
