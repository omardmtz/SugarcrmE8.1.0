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
 * QuickSearchDefaults class, outputs default values for setting up quicksearch
 *
 * @copyright  2004-2007 SugarCRM Inc.
 * @license    http://www.sugarcrm.com/crm/products/sugar-professional-eula.html  SugarCRM Professional End User License
 * @since      Class available since Release 4.0
 */

class QuickSearchDefaults
{

	var $form_name = 'EditView';

    /**
     * getQuickSearchDefaults
     *
     * This is a static function to get an instance of QuickSearchDefaults object
     *
     * @param array $lookup Array with custom files and class names for custom QuickSearchDefaults classes, optional
     * @return QuickSearchDefaults
     */
    static public function getQuickSearchDefaults(array $lookup = array())
    {
        $lookup['custom/include/QuickSearchDefaults.php'] = 'QuickSearchDefaultsCustom';

        foreach ($lookup as $file => $klass) {
            if (file_exists($file)) {
                require_once($file);
                return new $klass();
            }
        }

        return new QuickSearchDefaults();
    }

	function setFormName($name = 'EditView') {
		$this->form_name = $name;
	}

    function getQSParent($parent = 'Accounts') {
        global $app_strings;

        $qsParent = array(
                    'form' => $this->form_name,
                    'method' => 'query',
                    'modules' => array($parent),
                    'group' => 'or',
                    'field_list' => array('name', 'id'),
                    'populate_list' => array('parent_name', 'parent_id'),
                    'required_list' => array('parent_id'),
                    'conditions' => array(array('name'=>'name','op'=>'like_custom','end'=>'%','value'=>'')),
                    'order' => 'name',
                    'limit' => '30',
                    'no_match_text' => $app_strings['ERR_SQS_NO_MATCH']
                    );

        return $qsParent;
    }

    function getQSAccount($nameKey, $idKey, $billingKey = null, $shippingKey = null, $additionalFields = null) {

        global $app_strings;


        $field_list = array('name', 'id');
        $populate_list = array($nameKey, $idKey);
        if($billingKey != null) {
            $field_list = array_merge($field_list, array('billing_address_street', 'billing_address_city',
                                                           'billing_address_state', 'billing_address_postalcode', 'billing_address_country'));

            $populate_list = array_merge($populate_list, array($billingKey . "_address_street", $billingKey . "_address_city",
                                                                $billingKey . "_address_state", $billingKey . "_address_postalcode", $billingKey . "_address_country"));
        } //if

        if($shippingKey != null) {
            $field_list = array_merge($field_list, array('shipping_address_street', 'shipping_address_city',
                                                           'shipping_address_state', 'shipping_address_postalcode', 'shipping_address_country'));

            $populate_list = array_merge($populate_list, array($shippingKey . "_address_street", $shippingKey . "_address_city",
                                                                $shippingKey . "_address_state", $shippingKey . "_address_postalcode", $shippingKey . "_address_country"));
        }

        if(!empty($additionalFields) && is_array($additionalFields)) {
           $field_list = array_merge($field_list, array_keys($additionalFields));
           $populate_list = array_merge($populate_list, array_values($additionalFields));
        }

        $qsParent = array(
					'form' => $this->form_name,
                    'method' => 'query',
                    'modules' => array('Accounts'),
                    'group' => 'or',
                    'field_list' => $field_list,
                    'populate_list' => $populate_list,
                    'conditions' => array(array('name'=>'name','op'=>'like_custom','end'=>'%','value'=>'')),
                    'required_list' => array($idKey),
                    'order' => 'name',
                    'limit' => '30',
                    'no_match_text' => $app_strings['ERR_SQS_NO_MATCH']
                    );

        return $qsParent;
    }

    /**
     * getQSContact
     * This is a customized method to handle returning in JSON notation the QuickSearch formats
     * for searching the Contacts module for a contact name.  The method takes into account
     * the locale settings (s = salutation, f = first name, l = last name) that are permissible.
     * It should be noted though that any other characters present in the formatting will render
     * this widget non-functional.
     * @return The JSON format of a QuickSearch definition for the Contacts module
     */
    function getQSContact($name, $idName) {
        global $app_strings, $locale;

        $qsContact = array('form' => $this->form_name,
        				   'method'=>'get_contact_array',
                           'modules'=>array('Contacts'),
                           'field_list' => array('salutation', 'first_name', 'last_name', 'id'),
                           'populate_list' => array($name, $idName, $idName, $idName),
                           'required_list' => array($idName),
                           'group' => 'or',
                           'conditions' => array(
                                                 array('name'=>'first_name', 'op'=>'like_custom','end'=>'%','value'=>''),
                                                 array('name'=>'last_name', 'op'=>'like_custom','end'=>'%','value'=>'')
                                           ),
                           'order'=>'last_name',
                           'limit'=>'30',
                           'no_match_text'=> $app_strings['ERR_SQS_NO_MATCH']);
        return $qsContact;
    }

    function getQSUser($p_name = 'assigned_user_name', $p_id ='assigned_user_id') {
        global $app_strings;

        $qsUser = array('form' => $this->form_name,
        				'method' => 'get_user_array', // special method
                        'field_list' => array('user_name', 'id'),
                        'populate_list' => array($p_name, $p_id),
                        'required_list' => array($p_id),
                        'conditions' => array(array('name'=>'user_name','op'=>'like_custom','end'=>'%','value'=>'')),
                        'limit' => '30','no_match_text' => $app_strings['ERR_SQS_NO_MATCH']);
        return $qsUser;
    }
    function getQSCampaigns($c_name = 'campaign_name', $c_id = 'campaign_id') {
        global $app_strings;

        $qsCampaign = array('form' => $this->form_name,
        					'method' => 'query',
                            'modules'=> array('Campaigns'),
                            'group' => 'or',
                            'field_list' => array('name', 'id'),
                            'populate_list' => array($c_name, $c_id),
                            'conditions' => array(array('name'=>'name','op'=>'like_custom','end'=>'%','value'=>'')),
                            'required_list' => array('campaign_id'),
                            'order' => 'name',
                            'limit' => '30',
                            'no_match_text' => $app_strings['ERR_SQS_NO_MATCH']);
        return $qsCampaign;
    }


    function getQSTeam($t_name = 'team_name', $t_id = 'team_id') {
        global $app_strings;

        $qsTeam = array(
        			'form' => $this->form_name,
                    'method' => 'query',
                    'modules'=> array('Teams'),
                    'group' => 'or',
                    'field_list' => array('name', 'id'),
                    'populate_list' => array($t_name, $t_id),
                    'required_list' => array('team_id'),
                    'conditions' => array(array('name'=>'name','op'=>'like_custom','end'=>'%','value'=>''),
                                                 array('name'=>'name','op'=>'like_custom','begin'=>'(','end'=>'%','value'=>'')),
                    'order' => 'name', 'limit' => '30', 'no_match_text' => $app_strings['ERR_SQS_NO_MATCH']);
        return $qsTeam;
    }

    /**
     * Loads Quick Search Object for any object (if suitable method is defined)
     *
     * @param string $module the given module we want to load the vardefs for
     * @param string $object the given object we wish to load the vardefs for
     * @param string $relationName the name of the relation between entities
     * @param type $nameField the name of the field to populate
     * @param type $idField the id of the field to populate
     */
    function loadQSObject($module, $object, $relationName, $nameField, $idField)
    {
        $result = array();
        VardefManager::loadVardef($module, $object);
        if (isset($GLOBALS['dictionary'][$object]['relationships']) && array_key_exists($relationName, $GLOBALS['dictionary'][$object]['relationships']))
        {
            if (method_exists($this, 'getQS' . $module))
            {
                $result = $this->{'getQS' . $module};
            } elseif (method_exists($this, 'getQS' . $object))
            {
                $result = $this->{'getQS' . $object};
            }
        } else
        {
            if (method_exists($this, 'getQS' . $module))
            {
                $result = $this->{'getQS' . $module}($nameField, $idField);
            } elseif (method_exists($this, 'getQS' . $object))
            {
                $result = $this->{'getQS' . $object}($nameField, $idField);
            }
        }
        return $result;
    }

    // BEGIN QuickSearch functions for 4.5.x backwards compatibility support
    function getQSScripts() {
		global $sugar_version, $sugar_config, $theme;
		$qsScripts = '<script type="text/javascript">sqsWaitGif = "' . SugarThemeRegistry::current()->getImageURL('sqsWait.gif') . '";</script>
		<script type="text/javascript" src="'. getJSPath('include/javascript/quicksearch.js') . '"></script>';
		return $qsScripts;
	}

	function getQSScriptsNoServer() {
		return $this->getQSScripts();
	}

	function getQSScriptsJSONAlreadyDefined() {
		global $sugar_version, $sugar_config, $theme;
		$qsScriptsJSONAlreadyDefined = '<script type="text/javascript">sqsWaitGif = "' . SugarThemeRegistry::current()->getImageURL('sqsWait.gif') . '";</script><script type="text/javascript" src="' . getJSPath('include/javascript/quicksearch.js') . '"></script>';
		return $qsScriptsJSONAlreadyDefined;
	}
    // END QuickSearch functions for 4.5.x backwards compatibility support
}

?>
