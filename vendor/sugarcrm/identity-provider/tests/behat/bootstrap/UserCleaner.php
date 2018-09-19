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

use GuzzleHttp;
use Psr\Http\Message\ResponseInterface;

/**
 * Test Cleaner. Clean created in test users
 */
class UserCleaner
{
    /**
     * @var array
     */
    protected $adminCredentials = ['username' => '', 'password' => ''];

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var FeatureContext
     */
    protected $context;

    /**
     * List of Users that Was before scenario
     * @var array
     */
    protected $storedUserIds = [];

    /**
     * @param FeatureContext $context
     * @param array $adminCredentials list of admin credentials
     */
    public function __construct(FeatureContext $context, array $adminCredentials)
    {
        $this->context = $context;
        $this->adminCredentials = $adminCredentials;
    }

    /**
     * Store before scenario users
     * @throws \RuntimeException
     */
    public function before()
    {
        $this->storedUserIds = array_column($this->getUsers(), 'id');
        $this->accessToken = null;
    }

    /**
     * Delete added users
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function clean()
    {
        $userToDelete = array_diff(array_column($this->getUsers(), 'id'), $this->storedUserIds);
        $this->deleteUsers($userToDelete);
        $this->accessToken = null;
    }

    /**
     * List of users. Before call need be logged in as admin.
     * @return array
     * @throws \RuntimeException
     */
    protected function getUsers()
    {
        $accessToken = $this->getAccessToken();
        $usersUrl = rtrim($this->context->getMinkParameter('base_url'), '/') . '/rest/v10/Users';
        $client = new GuzzleHttp\Client();
        /** @var ResponseInterface $response */
        $response = $client->get($usersUrl, ['headers' => ['OAuth-Token' => $accessToken]]);
        $body = $response->getBody();
        $list = json_decode((string)$body, true);
        return $list['records'];
    }

    /**
     * Delete users that was added on scenario
     * @param array $userIds
     * @throws \LogicException
     * @throws \RuntimeException
     */
    protected function deleteUsers(array $userIds)
    {
        $accessToken = $this->getAccessToken();
        $client = new GuzzleHttp\Client();
        $deletePromises = [];
        foreach ($userIds as $index => $userId) {
            $userUrl =  rtrim($this->context->getMinkParameter('base_url'), '/'). '/rest/v10/Users/' . $userId;
            $deletePromises[] = $client->deleteAsync($userUrl, ['headers' => ['OAuth-Token' => $accessToken]]);
        }
        if ($deletePromises) {
            GuzzleHttp\Promise\settle($deletePromises)->wait();
        }
    }

    /**
     * Return access token
     * @return string
     * @throws \RuntimeException
     */
    protected function getAccessToken()
    {
        if (empty($this->accessToken)) {
            $client = new GuzzleHttp\Client();
            $url =  rtrim($this->context->getMinkParameter('base_url'), '/'). '/rest/v10/oauth2/token';
            $username = $this->adminCredentials['username'];
            $password = $this->adminCredentials['password'];
            try{
                $formParams = [
                    'client_id' => 'sugar',
                    'client_info' => ['current_language' => 'en_us'],
                    'client_secret' => '',
                    'current_language' => 'en_us',
                    'grant_type' => 'password',
                    'password' => $password,
                    'platform' => 'base',
                    'username' => $username
                ];
                $response = $client->post($url, ['form_params' => $formParams]);
                $body = $response->getBody();
                $result = json_decode((string)$body, true);
                $this->accessToken = $result['access_token'];
            }catch (\Exception $exception){
                throw new \RuntimeException("Can not login as admin usin credentials:{$username}|{$password}");
            }
        }
        return $this->accessToken;
    }
}
