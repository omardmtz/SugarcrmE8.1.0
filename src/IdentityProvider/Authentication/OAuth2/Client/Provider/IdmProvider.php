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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\GenericProvider as BasicGenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use GuzzleHttp;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Sugarcrm\Sugarcrm\League\OAuth2\Client\Grant\JwtBearer;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class IdmProvider extends BasicGenericProvider
{
    /**
     * Identity provider endpoint
     * @var string
     */
    protected $idpUrl;

    /**
     * Public/private keys endpoint
     * @var string
     */
    protected $urlKeys;

    /**
     * ID of key set to retrieve
     * @var string
     */
    protected $keySetId;

    /**
     * @var string
     */
    protected $responseError = 'error';

    /**
     * @var string
     */
    protected $responseErrorCode = 'code';

    /**
     * @var string
     */
    protected $responseErrorMessage = 'message';

    /**
     * @var string
     */
    protected $urlUserInfo;

    /**
     * @var array
     */
    protected $caching = [];

    /**
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * @var array
     */
    protected $requestedOAuthScopes = [];

    /**
     * Adds HttpClient with retry policy.
     *
     * @inheritdoc
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        if (!array_key_exists('httpClient', $collaborators)) {
            $collaborators['httpClient'] = $this->createHttpClient($options);
        }
        parent::__construct($options, $collaborators);
    }

    /**
     * @inheritdoc
     */
    protected function getAccessTokenOptions(array $params)
    {
        unset($params['client_id'], $params['client_secret']);

        $options = parent::getAccessTokenOptions($params);
        $options['headers']['Authorization'] = $this->getHttpBasicAuthHeader();

        return $options;
    }

    /**
     * Create HTTP Basic auth string
     * @return string
     */
    protected function getHttpBasicAuthHeader()
    {
        return 'Basic ' . base64_encode(sprintf('%s:%s', urlencode($this->clientId), urlencode($this->clientSecret)));
    }

    /**
     * @inheritdoc
     */
    protected function getRequiredOptions()
    {
        return array_merge(parent::getRequiredOptions(), [
            'clientId',
            'clientSecret',
            'urlKeys',
            'keySetId',
            'idpUrl',
            'urlUserInfo',
        ]);
    }

    /**
     * Allow to use specific handler.
     *
     * @inheritdoc
     */
    protected function getAllowedClientOptions(array $options)
    {
        return array_merge(parent::getAllowedClientOptions($options), ['handler']);
    }

    /**
     * Introspect token and return resource owner details
     * @param AccessToken $token
     * @throws \RuntimeException
     * @return string
     */
    public function introspectToken(AccessToken $token)
    {
        $cacheKey = 'oidc_introspect_token_' . hash('sha256', $token->getToken());
        $result = $this->getCache($cacheKey);
        if (!is_null($result)) {
            return $result;
        }
        $url = $this->getResourceOwnerDetailsUrl($token);
        $options = [
            'headers' => [
                'content-type' => 'application/x-www-form-urlencoded',
                'Authorization' => $this->getHttpBasicAuthHeader(),
            ],
            'body' => $this->buildQueryString(['token' => $token->getToken()]),
        ];

        $request = $this->getRequestFactory()->getRequestWithOptions(self::METHOD_POST, $url, $options);
        $result = $this->getParsedResponse($request);
        $this->setCache($cacheKey, $result, 'introspectToken');
        return $result;
    }

    /**
     * return user info
     * @param AccessToken $token
     * @return mixed
     */
    public function getUserInfo(AccessToken $token)
    {
        $cacheKey = 'oidc_user_info_' . hash('sha256', $token->getToken());
        $result = $this->getCache($cacheKey);
        if (!is_null($result)) {
            return $result;
        }
        $authHeaders = $this->getAuthorizationHeaders($token->getToken());
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ] + $authHeaders,
        ];
        $request = $this->getRequestFactory()->getRequestWithOptions(
            self::METHOD_POST,
            $this->urlUserInfo,
            $options
        );
        $result = $this->getParsedResponse($request);
        $this->setCache($cacheKey, $result, 'userInfo');
        return $result;
    }

    /**
     * Remote user authentication on IdP
     *
     * @param string $username
     * @param string $password
     * @param string $tenant Tenant SRN
     * @return array
     */
    public function remoteIdpAuthenticate($username, $password, $tenant)
    {
        $accessToken = $this->getAccessToken('client_credentials', ['scope' => 'idp.auth.password']);
        $authHeaders = $this->getAuthorizationHeaders($accessToken->getToken());
        $options = [
            'headers' => [
                'content-type' => 'application/x-www-form-urlencoded',
            ] + $authHeaders,
            'body' => $this->buildQueryString(['user_name' => $username, 'password' => $password, 'tid' => $tenant]),
        ];

        $request = $this->getRequestFactory()->getRequestWithOptions(
            self::METHOD_POST,
            $this->idpUrl . '/authenticate',
            $options
        );
        return $this->getParsedResponse($request);
    }


    /**
     * Obtaining access token through JWT bearer flow.
     *
     * @param $assertion
     * @return AccessToken
     */
    public function getJwtBearerAccessToken($assertion)
    {
        return $this->getAccessToken(
            new JwtBearer(),
            ['scope' => implode($this->getScopeSeparator(), $this->requestedOAuthScopes), 'assertion' => $assertion]
        );
    }

    /**
     * Get oauth2 public or private key from specified endpoint.
     *
     * @return array
     * @throws RequestException
     * @throws \UnexpectedValueException
     */
    public function getKeySet()
    {
        $cacheKey = 'oidc_key_set';
        $keySet = $this->getCache($cacheKey);
        if (is_null($keySet)) {
            $accessToken = $this->getAccessToken('client_credentials', ['scope' => 'hydra.keys.get']);
            $keyRequest = $this->getAuthenticatedRequest(
                self::METHOD_GET,
                $this->urlKeys,
                $accessToken,
                ['scope' => 'hydra.keys.get']
            );
            $keyResponse = $this->getParsedResponse($keyRequest);

            if (!isset($keyResponse['keys'])) {
                throw new \UnexpectedValueException('Keys not found');
            }
            $keySet = $keyResponse['keys'];
            $this->setCache($cacheKey, $keySet, 'keySet');
        }

        return [
            'keys' => $keySet,
            'keySetId' => $this->keySetId,
            'clientId' => $this->clientId,
        ];
    }

    /**
     * Specifies conditions of how should retry of sending the request should be performed.
     *
     * @param int $maxRetries Maximum number of retries to get the response.
     * @return \Closure
     */
    public function retryDecider($maxRetries)
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) use ($maxRetries) {
            if ($retries >= $maxRetries) {
                return false;
            }

            if ($response && $response->getStatusCode() >= 500) {
                return true;
            }

            return false;
        };
    }

    /**
     * Get retry delay strategy based on config value.
     *
     * @param array $config IDM-mode http_client config.
     * @return \Closure
     */
    public function getDelayStrategy($config)
    {
        $value = (isset($config['http_client']['delay_strategy'])) ?
            $config['http_client']['delay_strategy'] : 'linear';

        switch ($value) {
            case 'exponential':
                return $this->retryDelayExponential();

            case 'linear':
            default:
                return $this->retryDelayLinear();
        }
    }

    /**
     * Increases delay time between http request retries by 1 second.
     *
     * @return \Closure that returns milliseconds of delay.
     */
    public function retryDelayLinear()
    {
        return function ($retries) {
            return 1000 * $retries;
        };
    }

    /**
     * Increases delay time between http request retries by 2^n-1 where n is the retry attempt counter.
     *
     * @return \Closure that returns milliseconds of delay.
     */
    public function retryDelayExponential()
    {
        return function ($retries) {
            return (int) pow(2, $retries - 1) * 1000;
        };
    }

    /**
     * Creates HttpClient with retry policy.
     *
     * @param array $config
     * @return HttpClient
     */
    protected function createHttpClient(array $config)
    {
        $retryCount = (isset($config['http_client']['retry_count'])) ? (int) $config['http_client']['retry_count'] : 0;

        $handlerStack = HandlerStack::create(GuzzleHttp\choose_handler());
        $handlerStack->push(
            Middleware::retry($this->retryDecider($retryCount), $this->getDelayStrategy($config)),
            'retryDecider'
        );

        $options['handler'] = $handlerStack;

        return new HttpClient(
            array_intersect_key($options, array_flip($this->getAllowedClientOptions($options)))
        );
    }

    /**
     * @inheritdoc
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data[$this->responseError]) && is_array($data[$this->responseError])) {
            $error = $data[$this->responseError];
            $message = !empty($error[$this->responseErrorMessage]) ? $error[$this->responseErrorMessage] : '';
            $code = !empty($error[$this->responseErrorCode]) ? $error[$this->responseErrorCode] : '';
            throw new IdentityProviderException($message, $code, $data);
        }
        return parent::checkResponse($response, $data);
    }

    /**
     * Get SugarCache instance.
     *
     * @return \SugarCacheAbstract
     */
    protected function getSugarCache()
    {
        return \SugarCache::instance();
    }

    /**
     * Set value to cache.
     * If ttlName is not found in config or is <= 0  value won't be put to cache.
     *
     * @param string $key
     * @param mixed $value
     * @param string $ttlName Value from idm_mode config that is responsible for specific request's cached value TTL
     */
    protected function setCache($key, $value, $ttlName)
    {
        $ttl = intval($this->caching['ttl'][$ttlName] ?? 0);
        if ($ttl > 0) {
            $this->getSugarCache()->set($key, $value, $ttl);
        }
    }

    /**
     * Get value from cache by key.
     *
     * @param string $key
     * @return mixed|null
     */
    protected function getCache($key)
    {
        return $this->getSugarCache()->get($key);
    }

    /**
     * @inheritdoc
     */
    public function getScopeSeparator()
    {
        return $this->scopeSeparator ?? parent::getScopeSeparator();
    }
}
