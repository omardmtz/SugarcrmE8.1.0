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

namespace Sugarcrm\IdentityProvider\App;

use Doctrine\DBAL\Connection;

use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\PsrLogMessageProcessor;

use Psr\Log\LoggerInterface;

use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\CsrfServiceProvider;

use Sugarcrm\IdentityProvider\App\Authentication\Adapter\ConfigAdapterFactory;
use Sugarcrm\IdentityProvider\App\Provider\ConfigAdapterFactoryServiceProvider;
use Sugarcrm\IdentityProvider\App\Provider\ConsentRepositoryProvider;
use Sugarcrm\IdentityProvider\App\Provider\JoseServiceProvider;
use Sugarcrm\IdentityProvider\App\Provider\OAuth2ServiceProvider;
use Sugarcrm\IdentityProvider\App\Provider\AuthProviderManagerProvider;
use Sugarcrm\IdentityProvider\App\Provider\ConfigServiceProvider;
use Sugarcrm\IdentityProvider\App\Provider\SrnManagerServiceProvider;
use Sugarcrm\IdentityProvider\App\Provider\UserMappingServiceProvider;
use Sugarcrm\IdentityProvider\Authentication\UserMapping\MappingInterface;
use Sugarcrm\IdentityProvider\App\Provider\TenantConfigurationServiceProvider;
use Sugarcrm\IdentityProvider\App\Provider\ConsentRequestProvider;
use Sugarcrm\IdentityProvider\App\Provider\UsernamePasswordTokenFactoryProvider;
use Sugarcrm\IdentityProvider\App\Provider\ErrorPageHandlerProvider;
use Sugarcrm\IdentityProvider\App\Repository\ConsentRepository;
use Sugarcrm\IdentityProvider\Authentication\Token\UsernamePasswordTokenFactory;
use Sugarcrm\IdentityProvider\Srn\Manager;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class Application extends SilexApplication
{
    const ENV_PROD = 'prod';
    const ENV_DEV = 'dev';
    const ENV_TESTS = 'tests';
    const ENV_DEFAULT = self::ENV_PROD;

    /**
     * @var string
     */
    protected $env;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * Allowed environments
     * @var array
     */
    protected $allowedEnv = [
        self::ENV_TESTS,
        self::ENV_DEV,
        self::ENV_PROD,
    ];

    /**
     * @inheritdoc
     */
    public function __construct(array $values = ['env' => self::ENV_DEFAULT])
    {
        $environment = (string) $values['env'];
        $this->env = in_array($environment, $this->allowedEnv) ? $environment : self::ENV_DEFAULT;

        $this->rootDir = realpath(__DIR__ . '/../../');

        parent::__construct();

        $this->register(new ConfigServiceProvider(isset($values['configOverride']) ? $values['configOverride'] : []));

        $this->register(new MonologServiceProvider(), $this['config']['monolog']);
        $this->extend('monolog', function (Logger $monolog, Application $app) {
            return $monolog->pushProcessor(new UidProcessor())
                ->pushProcessor(new WebProcessor())
                ->pushProcessor(new IntrospectionProcessor())
                ->pushProcessor(new PsrLogMessageProcessor())
                ->pushHandler(
                    new ErrorLogHandler(
                        ErrorLogHandler::OPERATING_SYSTEM,
                        $app['config']['monolog']['monolog.level']
                    )
                );
        });

        $this->register(new AssetServiceProvider(), [
            'assets.named_packages' => [
                'css' => ['base_path' => 'css'],
                'js' => ['base_path' => 'js'],
                'images' => ['base_path' => 'img'],
            ],
        ]);

        $this->register(new TwigServiceProvider(), [
            'twig.options' => [
                'cache' => $this->rootDir . '/var/cache/twig',
                'strict_variables' => true,
            ],
            'twig.path' => __DIR__ . '/Resources/views',
        ]);

        $this->register(new ValidatorServiceProvider());

        $this->register(new DoctrineServiceProvider(), $this['config']['db']);
        $this->register(new ConsentRepositoryProvider());

        // Should be before TenantConfigurationServiceProvider
        $this->register(new ConfigAdapterFactoryServiceProvider());

        // Should be before:
        //  AuthProviderManagerProvider, UserMappingServiceProvider, UsernamePasswordTokenFactoryProvider
        // Add after DoctrineServiceProvider
        $this->register(new TenantConfigurationServiceProvider());

        $this->register(new SessionServiceProvider(), [
            'session.test' => $environment === self::ENV_TESTS,
            'session.storage.options' => $this['config']['session.storage.options'],
        ]);

        $this['session.storage.handler'] = function () {
            return new PdoSessionHandler(
                $this['db']->getWrappedConnection(),
                [
                    'db_table' => 'sessions',
                    'db_id_col' => 'session_id',
                    'db_data_col' => 'session_value',
                    'db_lifetime_col' => 'session_lifetime',
                    'db_time_col' => 'session_time',
                    'lock_mode' => PdoSessionHandler::LOCK_ADVISORY,
                ]
            );
        };

        $this->register(new AuthProviderManagerProvider());
        $this->register(new UserMappingServiceProvider());
        $this->register(new JoseServiceProvider());
        $this->register(new OAuth2ServiceProvider());
        $this->register(new ConsentRequestProvider());
        $this->register(new UsernamePasswordTokenFactoryProvider());
        $this->register(new SrnManagerServiceProvider());
        $this->register(new CsrfServiceProvider());
        $this->register(new ErrorPageHandlerProvider());

        // bind routes
        $this->mount('', new ControllerProvider());
    }

    /**
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * SERVICE ACCESSORS
     */

    /**
     * @return \Twig_Environment
     */
    public function getTwigService()
    {
        return $this['twig'];
    }

    /**
     * @return RecursiveValidator
     */
    public function getValidatorService()
    {
        return $this['validator'];
    }

    /**
     * @return Connection
     */
    public function getDoctrineService()
    {
        return $this['db'];
    }

    /**
     * @return AuthenticationProviderManager
     */
    public function getAuthManagerService()
    {
        return $this['authManager'];
    }

    /**
     * @return UrlGenerator
     */
    public function getUrlGeneratorService()
    {
        return $this['url_generator'];
    }

    /**
     * @return LoggerInterface;
     */
    public function getLogger()
    {
        return $this['logger'];
    }

    /**
     * @return Session;
     */
    public function getSession()
    {
        return $this['session'];
    }

    /**
     * @param string $type Type of the mapping service (ldap, saml).
     *
     * @return MappingInterface
     */
    public function getUserMappingService($type)
    {
        $mappingServiceName = strtoupper($type) . 'UserMapping';
        if (empty($this[$mappingServiceName])) {
            throw new \InvalidArgumentException("Requested mapping service $mappingServiceName is missing");
        }
        return $this[$mappingServiceName]();
    }

    /**
     * @param string $username
     * @param string $password
     * @return UsernamePasswordTokenFactory
     */
    public function getUsernamePasswordTokenFactory($username, $password)
    {
        return $this['usernamePasswordTokenFactory']($username, $password);
    }

    /**
     * @return ConsentRepository
     */
    public function getConsentRepository()
    {
        return $this['consentRepository'];
    }

    /**
     * @return TenantConfiguration
     */
    public function getTenantConfiguration()
    {
        return $this['tenantConfiguration'];
    }

    /**
     * @return ConfigAdapterFactory
     */
    public function getConfigAdapterFactory()
    {
        return $this['configAdapterFactory'];
    }

    /**
     * @return Manager
     */
    public function getSrnManager()
    {
        return $this['SrnManager'];
    }

    /**
     * @return \Sugarcrm\IdentityProvider\App\Authentication\OAuth2Service
     */
    public function getOAuth2Service()
    {
        return $this['oAuth2Service'];
    }

    /**
     * @return \Sugarcrm\IdentityProvider\App\Authentication\JoseService
     */
    public function getJoseService()
    {
        return $this['JoseService'];
    }

    /**
     * @return \Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentRestService
     */
    public function getConsentRestService()
    {
        return $this['consentRestService'];
    }

    /**
     * @return \Symfony\Component\Security\Csrf\CsrfTokenManager
     */
    public function getCsrfTokenManager()
    {
        return $this['csrf.token_manager'];
    }
}
