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

namespace Sugarcrm\IdentityProvider\League\OAuth2\Client\Provider\HttpBasicAuth;

use League\OAuth2\Client\Provider\GenericProvider as BasicGenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class GenericProvider extends BasicGenericProvider
{
    /**
     * @var string
     */
    protected $urlIntrospectToken;

    /**
     * @var \Monolog\Logger
     */
    protected $logger;

    /**
     * Reads access token from the local file and returns it if found.
     *
     * @inheritdoc
     */
    public function getAccessToken($grant, array $options = [])
    {
        if (!empty($this->accessTokenFile) && is_readable($this->accessTokenFile)) {
            $tokenData = include $this->accessTokenFile;
            if (is_array($tokenData) && array_key_exists('access_token', $tokenData)) {
                return new AccessToken($tokenData);
            }
        }
        $this->logger->warning("Failed to read file '{file_name}' with access_token. Using direct request for it.", [
            'file_name' => $this->accessTokenFile,
            'tags' => ['IdM.oauth.authentication'],
        ]);
        return parent::getAccessToken($grant, $options);
    }

    /**
     * Checks the response. Triggers token refresh if token is expired.
     *
     * @inheritdoc
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        // ToDo: STS sends 500 if token is expired. We should wait/fix for proper response code.
        if (isset($data['error']['code']) && isset($data['error']['message']) &&
            $data['error']['code'] == 500 &&
            strpos($data['error']['message'], 'request is not allowed') !== false) {
            if (!empty($this->accessTokenRefreshUrl)) {
                // We do a fire-and-forget call to a refresh-token endpoint.
                $request = $this->getRequestFactory()->getRequestWithOptions(
                    self::METHOD_GET,
                    $this->accessTokenRefreshUrl,
                    ['timeout' => 0.00001]
                );
                try {
                    $this->getHttpClient()->send($request);
                } catch (\Exception $e) {
                }
            } else {
                $this->logger->warning("Failed to trigger access_token refresh. Please set up Refresh URL in ENV", [
                    'tags' => ['IdM.oauth.authentication'],
                ]);
            }
        }
        return parent::checkResponse($response, $data);
    }

    /**
     * @inheritdoc
     */
    protected function getAccessTokenOptions(array $params)
    {
        $encodedCredentials = base64_encode(
            sprintf('%s:%s', urlencode($params['client_id']), urlencode($params['client_secret']))
        );
        unset($params['client_id'], $params['client_secret']);

        $options = parent::getAccessTokenOptions($params);
        $options['headers']['Authorization'] = 'Basic ' . $encodedCredentials;

        return $options;
    }

    /**
     * @inheritdoc
     */
    protected function getRequiredOptions()
    {
        return array_merge(
            parent::getRequiredOptions(),
            ['clientId', 'clientSecret', 'accessTokenFile', 'accessTokenRefreshUrl']
        );
    }

    /**
     * Introspect token and return resource owner details
     * @param AccessToken $token
     * @throws \RuntimeException
     * @return array
     */
    public function introspectToken(AccessToken $token)
    {
        $options = $this->getAccessTokenOptions([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);
        $options['headers']['Accept'] = 'application/json';
        $options['body'] = $this->buildQueryString(['token' => $token->getToken()]);

        $request = $this->getRequestFactory()->getRequestWithOptions(
            self::METHOD_POST,
            $this->urlIntrospectToken,
            $options
        );
        return $this->getParsedResponse($request);
    }
}
