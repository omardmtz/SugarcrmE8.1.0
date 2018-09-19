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

use Behat\Gherkin\Node\TableNode;

class LdapFeatureContext extends FeatureContext
{
    /**
     * @var UserCleaner
     */
    protected $userCleaner;

    /**
     * @var array
     */
    protected $ldapConfig;

    /**
     * SetUp necessary configs.
     *
     * @param array $sugarAdmin
     * @param array $ldapConfig
     */
    public function __construct(array $sugarAdmin, array $ldapConfig)
    {
        parent::__construct($sugarAdmin);
        $this->ldapConfig = $ldapConfig;
        $this->userCleaner = new UserCleaner($this, $sugarAdmin);
    }

    /**
     * @BeforeScenario @ldap
     */
    public function beforeLdapScenario()
    {
        $this->iAmOnHomepage();
        $this->userCleaner->before();
        $this->restartSessionAndGoToHomePage();
    }

    /**
     * @AfterScenario @ldap
     */
    public function afterLdapScenario()
    {
        $this->restartSessionAndGoToHomePage();
        $this->disableLdap();
        $this->userCleaner->clean();
        $this->restartSessionAndGoToHomePage();
    }

    /**
     * Disable Ldap
     */
    protected function disableLdap()
    {
        $this->iLogin($this->sugarAdmin['username'], $this->sugarAdmin['password']);
        $this->iGoToAdministration();
        $this->waitForThePageToBeLoaded();
        $this->clickLink('Password Management');
        $this->waitForThePageToBeLoaded();
        $this->waitForElement('#system_ldap_enabled');
        $this->uncheckOption('system_ldap_enabled');
        $this->pressButton('btn_save');
        $this->waitForThePageToBeLoaded();
    }

    /**
     * Filling LDAP settings.
     *
     * @param TableNode $table
     * @param string $adminKey
     *
     * @Given /^As "([^"]*)" filling in the following LDAP settings:$/
     */
    public function fillLdapSettings($adminKey, TableNode $table)
    {
        $credentials = $this->$adminKey;
        if (empty($credentials)) {
            throw new \RuntimeException('Configuration for '.$adminKey.' not found');
        }

        $this->iLogin($credentials['username'], $credentials['password']);
        $this->iSkipLoginWizard();
        $this->iGoToAdministration();
        $this->clickLink('Password Management');

        $page = $this->getSession()->getPage();
        $this->waitForElement('#system_ldap_enabled');

        $page->checkField('system_ldap_enabled');
        $clickToEditElement = $page->find(
            'xpath',
            '//span[contains(@class, "button") and text() = "Click to Edit"]'
        );
        $passwordEditElement = $page->findById('ldap_admin_password');
        $passwordEditHidden = false;
        if ($clickToEditElement && !$passwordEditElement->isVisible()) {
            $passwordEditHidden = true;
        }
        foreach ($table->getHash() as $row) {
            switch ($row['type']) {
                case 'checkbox':
                    if ($row['value'] == 'checked') {
                        $page->checkField($row['field']);
                    } else {
                        $page->uncheckField($row['field']);
                    }
                    break;
                default:
                    if ($row['field'] == 'ldap_admin_password' &&
                        $passwordEditHidden &&
                        $clickToEditElement->isVisible()
                    ) {
                        $clickToEditElement->click();
                    }
                    $page->fillField($row['field'], $this->getFieldValue($row['field'], $row['value']));
                    break;
            }
        }
        $this->pressButton('btn_save');
        $this->waitForThePageToBeLoaded();
        $this->iLogout();
    }

    /**
     * override ldap config value if exists
     * @param string $name
     * @param string $value
     * @return string
     */
    protected function getFieldValue($name, $value)
    {
        if ($name != 'ldap_hostname') {
            return $value;
        }
        return !empty($this->ldapConfig['servers'][$value]) ? $this->ldapConfig['servers'][$value] : $value;
    }

    /**
     * @inheritdoc
     */
    public function iAmOnHomepage()
    {
        $this->visitPath('/');
        $this->waitForThePageToBeLoaded();
        $this->iWaitUntilTheLoadingIsCompleted();
    }

    /**
     * Clear restart session and go to homepage
     */
    protected function restartSessionAndGoToHomePage()
    {
        $this->getSession()->reset();
        $this->getSession()->restart();
        $this->iAmOnHomepage();
    }
}
