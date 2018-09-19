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

/*********************************************************************************
* Description:
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
* Reserved. Contributor(s): contact@synolia.com - www.synolia.com
* *******************************************************************************/

require_once 'vendor/Zend/Oauth/Consumer.php';

class ext_rest_twitter extends ext_rest {

    protected $_has_testing_enabled = true;

    public function __construct(){
        parent::__construct();
        $this->_enable_in_wizard = false;
        $this->_enable_in_hover = true;
    }

    /**
     * This method is called from the administration interface to run a test of the service
     * It is up to subclasses to implement a test and set _has_testing_enabled to true so that
     * a test button is rendered in the administration interface
     *
     * @param $propParam optional param that'll override internal properties if set
     * @return result boolean result of the test function
     */
    public function test() {
        $properties = $this->getProperties();

        $api = ExternalAPIFactory::loadAPI('Twitter', true);

        // Start with a reasonable default
        $config = array(
            'callbackUrl' => 'http://www.sugarcrm.com',
            'requestTokenUrl' => 'https://api.twitter.com/oauth/request_token',
            'consumerKey' => $properties['oauth_consumer_key'],
            'consumerSecret' => $properties['oauth_consumer_secret']
        );

        if ($api) {
            $config['requestTokenUrl'] = $api->getOauthRequestURL();
        }

        try {
            $consumer = new Zend_Oauth_Consumer($config);
            $consumer->getRequestToken();
            return true;
        } catch (Exception $e) {
            $GLOBALS['log']->error("Error getting request token for twitter:".$e->getMessage());
            return false;
        }
    }

    /*
     * getItem
     *
     * As the twitter connector does not have a true API call, we simply
     * override this abstract
     */
    public function getItem($args=array(), $module=null){}


    /*
     * getList
     *
     * As the twitter connector does not have a true API call, we simply
     * override this abstract method
     */
    public function getList($args=array(), $module=null){}
}

?>
