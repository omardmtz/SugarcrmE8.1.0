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

use Behat\Gherkin\Node\PyStringNode;
use Behat\SoapExtension\Context\SoapContext;
use Symfony\Component\Yaml\Yaml;

class SOAPFeatureContext extends SoapContext
{
    /**
     * @var array
     */
    protected $mangoInstances;

    /**
     * @var array
     */
    protected $sugarAdmin;

    /**
     * @var string
     */
    protected $soapSession;

    /**
     * SetUp necessary configs.
     *
     * @param array $mangoInstances
     */
    public function __construct(array $mangoInstances, array $sugarAdmin)
    {
        $this->mangoInstances = $mangoInstances;
        $this->sugarAdmin = $sugarAdmin;
    }

    /**
     * @param string $version
     * @param string $instance
     *
     * @Given /^I use SOAP service WSDL of ([^ ]+) version at ([^ ]+) Mango instance$/
     */
    public function iUseMangoSoapServiceWSDL($version, $instance)
    {
        if (isset($this->mangoInstances[$instance])) {
            $currentInstanceURL = $this->mangoInstances[$instance];
        } else {
            throw new \InvalidArgumentException('Unknown Mango instance');
        }
        $wsdl = sprintf('%s/service/v%s/soap.php?wsdl', $currentInstanceURL, $version);

        $this->iAmWorkingWithSoapServiceWSDL($wsdl);
    }

    /**
     * @param string $user Username
     * @param bool $isPlain True if password is not md5-encrypted
     * @param string $pass User's password
     *
     * @Given /^I call SOAP function "login" with user ([^ ]+) and (true|false) password ([^ ]+)$/
     */
    public function iLoginToMangoViaSOAP($user, $isPlain, $pass)
    {
        $isPlain = filter_var($isPlain, FILTER_VALIDATE_BOOLEAN);
        $params = [
            'user_auth' =>
                [
                    'user_name' => $user,
                    'password' => ($isPlain) ? $pass : md5($pass),
                    'version' => '.01'
                ],
            'application_name' => 'SoapTest',
            "name_value_list" => [],
        ];
        if ($isPlain) {
            $params['user_auth']['encryption'] = 'PLAIN';
        }

        $this->sendRequest('login', $params);
    }

    /**
     * @Given /^I login with (true|false) password$/
     */
    public function iLogin($isPlain)
    {
        $isPlain = filter_var($isPlain, FILTER_VALIDATE_BOOLEAN);
        $this->iLoginToMangoViaSOAP($this->sugarAdmin['username'], $isPlain, $this->sugarAdmin['password']);
        $this->soapSession = $this->extractResponseProperty('id');
    }

    /**
     * @When I call authenticated SOAP function :function with arguments:
     */
    public function iSendAuthenticatedRequestYAML($function, PyStringNode $arguments)
    {
        $args['session'] = $this->soapSession;
        $args = array_merge($args, Yaml::parse($arguments->getRaw()));
        $this->sendRequest($function, $args);
    }
}
