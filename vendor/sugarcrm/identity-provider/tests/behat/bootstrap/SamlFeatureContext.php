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

namespace Sugarcrm\IdentityProvider\IntegrationTests\Bootstrap;

/**
 * Defines application features from the specific context.
 */
class SamlFeatureContext extends FeatureContext
{
    /**
     * @var UserCleaner
     */
    protected $userCleaner;

    /**
     * @var string
     */
    protected $samlServer;

    /**
     * @var array
     */
    protected $mangoInstances;

    /**
     * @var string
     */
    protected $defaultBaseUrl;

    /**
     * SetUp necessary configs.
     *
     * @param string $samlServer
     * @param array $sugarAdmin
     * @param array $mangoInstances
     * @param string $defaultInstance
     */
    public function __construct($samlServer, array $sugarAdmin, array $mangoInstances, $defaultInstance)
    {
        parent::__construct($sugarAdmin);
        $this->samlServer = $samlServer;
        $this->mangoInstances = $mangoInstances;
        $this->defaultBaseUrl = $this->mangoInstances[$defaultInstance];
        $this->userCleaner = new UserCleaner($this, $sugarAdmin);
    }

    /**
     * @AfterScenario @saml
     */
    public function afterSAMLScenario()
    {
        $this->userCleaner->clean();
        $this->setMinkParameter('base_url', $this->defaultBaseUrl);
    }

    /**
     * Save mango user list instead of beforeScenario
     * @Then I save mango user list
     * @And I save mango user list
     */
    public function iSaveMangoUserList()
    {
        $this->userCleaner->before();
    }

    /**
     * Switch to another window
     * @When /^I switch to login popup$/
     * @And /^I switch to login popup$/
     * @throws \RuntimeException
     */
    public function switchToLoginPopup()
    {
        $current = $this->getSession()->getWindowName();
        $windows = $this->getSession()->getWindowNames();
        if (count($windows) != 2) {
            throw new \RuntimeException("Only two windows must be opened at one moment.");
        }
        $second = array_filter($windows, function ($window) use ($current) {
                return $window != $current;
        });
        $second = array_pop($second);
        $this->getSession()->switchToWindow($second);
    }

    /**
     * Close login popup window and switch to main window.
     *
     * @When /^I close login popup$/
     * @And /^I close login popup$/
     * @throws \RuntimeException
     */
    public function closeLoginPopupWindow()
    {
        $currentUrl = $this->getSession()->getCurrentUrl();
        if (strpos($currentUrl, 'simplesaml/module.php/core/loginuserpass.php') === false) {
            throw new \RuntimeException("Window you are trying to close is not a login popup one.");
        }
        $this->getSession()->evaluateScript('window.close()');
        $this->getSession()->switchToWindow(null);
    }

    /**
     * Wait for the page to be redirected to the specific url
     * @Then /^I wait to be redirected to the saml server$/
     * @And /^I wait to be redirected to the saml server$/
     * Example: I wait to be redirected to the saml server
     */
    public function IWaitRedirectToSamlServer()
    {
        $this->spin(function (FeatureContext $context){
            $currentUrl = $context->getSession()->getCurrentUrl();
            return $this->samlServer == substr($currentUrl, 0, strlen($this->samlServer));
        }, 10);
    }

    /**
     * Switch to main window
     * @When /^I switch to main window$/
     * @And /^I switch to main window$/
     */
    public function switchToMainWindow()
    {
        $this->getSession()->switchToWindow(null);
    }

    /**
     * wait until login window will be opened
     * @When /^I wait until login popup is opened$/
     * @And /^I wait until login popup is opened$/
     */
    public function waitForLoginPopup()
    {
        $this->spin(function (FeatureContext $context) {
            return count($context->getSession()->getWindowNames()) == 2;
        }, 10);
    }

    /**
     * open SAML logout window
     * @And /^I initiate SAML logout$/
     * @When /^I initiate SAML logout$/
     */
    public function openSamlLogoutWindow()
    {
        $currentUrl = $this->getSession()->getCurrentUrl();
        $redirectTo = sprintf(
            '%s/saml2/idp/SingleLogoutService.php?ReturnTo=%s',
            $this->samlServer,
            $currentUrl
        );
        $this->visit($redirectTo);
    }

    /**
     * @Given /^I initiate IDP login for SPEntityId ([^ ]+)$/
     */
    public function iInitiateIDPLogin($spEntityId)
    {
        $this->visit(sprintf('%s/saml2/idp/SSOService.php?spentityid=%s', $this->samlServer, $spEntityId));
    }

    /**
     * @Given /^I use ([^ ]+) Mango instance$/
     */
    public function iUseMangoInstance($instance)
    {
        if (isset($this->mangoInstances[$instance])) {
            $this->setMinkParameter('base_url', $this->mangoInstances[$instance]);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function getContextSpecificUrl($url)
    {
        if ($url == 'saml-server') {
            return substr($this->samlServer, 0, strrpos($this->samlServer, '/'));
        }
        return parent::getContextSpecificUrl($url);
    }
}
