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
 * Class ExtAPIGoToMeeting
 */
class ExtAPIGoToMeeting extends OAuthPluginBase implements WebMeeting
{
    protected $dateFormat = 'Y-m-d\TH:i:s';
    protected $url = 'https://api.getgo.com/';

    public $supportedModules = array('Meetings');
    public $supportMeetingPassword = false;
    public $authMethod = 'oauth';
    public $connector = "ext_eapm_gotomeeting";

    protected $oauthAuth = 'oauth/authorize';
    protected $oauthAccess = 'oauth/access_token';
    protected $meetingsAPI = '/G2M/rest/meetings';

    public $canInvite = false;
    public $sendsInvites = false;
    public $needsUrl = false;

    public function __construct()
    {
        $this->oauthAuth = $this->url . $this->oauthAuth;
        $this->oauthAccess = $this->url . $this->oauthAccess;
        $this->meetingsAPI = $this->url . $this->meetingsAPI;
    }

    /**
     * Makes a REST API request
     *
     * @param $url - URL
     * @param string $method - HTTP Method
     * @param array $data - raw POST data
     * @return Zend_Http_Response
     */
    public function makeRequest($url, $method = 'GET', $data = '')
    {
        try {
            $client = new Zend_Http_Client($url);

            $headers = array(
                    'Accept: application/json',
                    'Content-type: application/json'
            );
            if (!empty($this->eapmBean->oauth_token)) {
                $headers[] = 'Authorization: OAuth oauth_token=' . $this->eapmBean->oauth_token;
            }
            $client->setHeaders($headers);

            if (!empty($data)) {
                $client->setRawData($data);
            }

            return $client->request($method);
        } catch (Exception $e) {
            $GLOBALS['log']->error($e->getMessage());
            return null;
        }
    }

    /**
     * OAuth Login
     *
     * @return bool Success or failure
     */
    public function oauthLogin()
    {
        global $sugar_config;

        $apiKey = $this->getConnector()->getProperty('oauth_consumer_key');

        if (!isset($_REQUEST['code'])) {
            $callback = $sugar_config['site_url'] . '/index.php?module=EAPM&action=oauth&record=' . $this->eapmBean->id;
            $callback = $this->formatCallbackURL($callback);

            $queryData = array(
                'client_id' => $apiKey,
                'redirect_uri' => $callback
            );

            SugarApplication::redirect($this->getOauthAuthURL() . '?' . http_build_query($queryData));
        } else {
            $code = $_REQUEST['code'];

            $queryData = array(
                'grant_type' => 'authorization_code',
                'code' => $code,
                'client_id' => $apiKey
            );

            $accReq = $this->getOauthAccessURL() . '?' .  http_build_query($queryData);

            $rawResponse = $this->makeRequest($accReq);

            if ($rawResponse && $rawResponse->isSuccessful()) {
                $response = json_decode($rawResponse->getBody(), true);

                if (!empty($response['access_token'])) {
                    $this->eapmBean->oauth_token = $response['access_token'];
                    $this->eapmBean->validated = 1;
                    $this->eapmBean->save();

                    return true;
                }
            }

            return false;
        }
    }

    /**
     * Try to schedule a meeting using the API (both create/update)
     *
     * @param $bean - The bean to be saved on external account
     * @return array - returns 'success' and an error message if any
     */
    public function scheduleMeeting($bean)
    {
        if (!$this->eapmBean->validated || empty($this->eapmBean->oauth_token)) {
            return array(
                'success' => false,
                'errorMessage' => $GLOBALS['app_strings']['ERR_EXTERNAL_API_NO_OAUTH_TOKEN']
            );
        }

        if (empty($bean->external_id)) {
            $return = $this->createMeeting($bean);
        } else {
            $return = $this->updateMeeting($bean);
        }

        return $return;
    }

    /**
     * Create the meeting
     *
     * @param $bean - The bean to be saved
     * @return array - returns 'success' and an error message if any
     */
    private function createMeeting($bean)
    {
        $method = 'POST';

        $data = $this->buildData($bean);

        $rawResponse = $this->makeRequest($this->meetingsAPI, $method, json_encode($data));

        if ($rawResponse->isSuccessful()) {
            $response = json_decode($rawResponse->getBody(), true);

            if (!empty($response[0])) {
                $response = $response[0];

                if (!empty($response['meetingid']) &&
                    !empty($response['joinURL']) &&
                    !empty($response['uniqueMeetingId'])) {
                    $bean->join_url = $response['joinURL'];
                    $bean->external_id = $response['meetingid'] . '-' . $response['uniqueMeetingId'];
                    $bean->host_url = $this->getHostMeetingLink($response['meetingid']);
                    //Allow host URL to use parent frame's protocol so that we aren't trying to embed an HTTP iframe in an HTTPS page
                    $bean->host_url = preg_replace('/http[s]?:/', '', $bean->host_url);
                    $bean->creator = $this->account_name;

                    return array('success' => true);
                }
            }
        }

        $return = array('success' => false, 'errorMessage' => $rawResponse->getBody());

        return $return;
    }

    /**
     * Update the meeting
     *
     * @param $bean - The bean to be updated
     * @return array - returns 'success' and an error message if any
     */
    private function updateMeeting($bean)
    {
        $method = 'PUT';

        // External ID = meetingId - uniqueMeetingId
        $ids = explode('-', $bean->external_id);

        $url = $this->meetingsAPI . '/' . $ids[0];

        $data = $this->buildData($bean);

        $rawResponse = $this->makeRequest($url, $method, json_encode($data));

        if ($rawResponse->isSuccessful()) {
            $return = array('success' => true);
        } else {
            $bean->join_url = '';
            $bean->host_url = '';
            $bean->external_id = '';
            $bean->creator = '';
            
            $return = array('success' => false, 'errorMessage' => $rawResponse->getBody());
        }

        return $return;
    }

    /**
     * Return an array of all the data needed for the external api
     *
     * @param $bean - The bean to be saved
     * @return array - of data needed for external api create/update
     */
    private function buildData($bean)
    {
        global $timedate;

        $data = array();
        $data['subject'] = $bean->name;
        $data['conferencecallinfo'] = 'Hybrid';
        $data['timezonekey'] = '';
        $data['meetingtype'] = 'Scheduled';

        $dateStart = $timedate->fromDb($bean->date_start);
        $dateEnd = $timedate->fromDb($bean->date_end);
        $data['starttime'] = $dateStart->format($this->dateFormat);
        $data['endtime'] = $dateEnd->format($this->dateFormat);

        if (!empty($bean->password)) {
            $data['passwordrequired'] = 'true';
        } else {
            $data['passwordrequired'] = 'false';
        }

        return $data;
    }

    /**
     * Get the link for hosting the meeting
     *
     * @param $meetingId - The meeting id
     * @return string - Link
     */
    private function getHostMeetingLink($meetingId)
    {
        $url = $this->meetingsAPI . '/' . $meetingId . '/start';
        $rawResponse = $this->makeRequest($url);

        if ($rawResponse && $rawResponse->isSuccessful()) {
            $response = json_decode($rawResponse->getBody(), true);
            if (!empty($response['hostURL'])) {
                return $response['hostURL'];
            }
        }

        return '';
    }

    /**
     * Removes the meeting from external account
     *
     * @param $bean - The bean to be unscheduled
     * @return bool - Success of failure
     */
    public function unscheduleMeeting($bean)
    {
        // External ID = meetingId - uniqueMeetingId
        $ids = explode($bean->external_id, '-');

        $url = $this->meetingsAPI . '/' . $ids[0];
        $method = 'DELETE';

        $rawResponse = $this->makeRequest($url, $method);

        if ($rawResponse && $rawResponse->isSuccessful()) {
            $return = array('success' => true);
        } else {
            $return = array('success' => false);
        }

        return $return;
    }

    public function joinMeeting($bean, $attendeeName)
    {
    }

    public function inviteAttendee($bean, $attendee, $sendInvites = false)
    {
    }

    public function uninviteAttendee($bean, $attendee)
    {
    }

    public function listMyMeetings()
    {
    }

    public function getMeetingDetails($bean)
    {
    }
}
