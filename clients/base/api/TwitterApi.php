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
// A simple example class
class TwitterApi extends ConnectorApi
{
    public function registerApiRest()
    {
        return array(
            'getCurrentUser' => array(
                'reqType' => 'GET',
                'path' => array('connector','twitter', 'currentUser'),
                'pathVars' => array('connector','module', 'twitterId'),
                'method' => 'getCurrentUser',
                'shortHelp' => 'Gets current tweets for a user',
                'longHelp' => 'include/api/help/twitter_get_help.html',
            ),
            'getTweets' => array(
                'reqType' => 'GET',
                'path' => array('connector','twitter', '?'),
                'pathVars' => array('connector','module', 'twitterId'),
                'method' => 'getTweets',
                'shortHelp' => 'Gets current tweets for a user',
                'longHelp' => 'include/api/help/twitter_get_help.html',
            ),
        );
    }

    /**
     * gets twitter EAPM
     * @return array|bool|ExternalAPIBase
     */
    public function getEAPM()
    {
        // ignore auth and load to just check if connector configured
        $twitterEAPM = ExternalAPIFactory::loadAPI('Twitter', true);

        if (!$twitterEAPM) {
            $source = SourceFactory::getSource('ext_rest_twitter');
            if ($source && $source->hasTestingEnabled()) {
                try {
                    if (!$source->test()) {
                        return array('error' =>'ERROR_NEED_OAUTH');
                    }
                } catch (Exception $e) {
                    return array('error' =>'ERROR_NEED_OAUTH');
                }
            }
            return array('error' =>'ERROR_NEED_OAUTH');
        }

        $twitterEAPM->getConnector();

        $eapmBean = EAPM::getLoginInfo('Twitter');

        if (empty($eapmBean->id)) {
            return array('error' =>'ERROR_NEED_AUTHORIZE');
        }

        //return a fully authed EAPM
        $twitterEAPM = ExternalAPIFactory::loadAPI('Twitter');
        return $twitterEAPM;
    }

    /**
     * Gets Tweets for a user via proxy call to twitter
     * @param ServiceBase $api
     * @param array $args
     * @return mixed
     * @throws SugarApiExceptionRequestMethodFailure
     * @throws SugarApiExceptionMissingParameter
     */
    public function getTweets(ServiceBase $api, array $args)
    {
        $this->validateHash($args);
        $args2params = array(
            'twitterId' => 'screen_name',
            'count' => 'count'
        );
        $params = array();
        foreach ($args2params as $argKey => $paramKey) {
            if (isset($args[$argKey])) {
                $params[] = $args[$argKey];
            }
        }

        if (count($params) === 0) {
            throw new SugarApiExceptionMissingParameter('Error: Missing argument.', $args);
        }

        $extApi = $this->getEAPM();

        if (is_array($extApi) && isset($extApi['error'])) {
            throw new SugarApiExceptionRequestMethodFailure(null, $args, null, 424, $extApi['error']);
        }

        if ($extApi === false) {
           throw new SugarApiExceptionRequestMethodFailure($GLOBALS['app_strings']['ERROR_UNABLE_TO_RETRIEVE_DATA'], $args);
        }

        $result = $extApi->getUserTweets($args['twitterId'], 0, $args['count']);
        if (isset($result['errors'])) {
            $errorString = '';
            foreach($result['errors'] as $errorKey => $error) {
                if ($error['code'] === 34) {
                    throw new SugarApiExceptionNotFound('errors_from_twitter: '.$errorString, $args);
                }
                $errorString .= $error['code'].str_replace(' ', '_', $error['message']);
            }
            throw new SugarApiExceptionRequestMethodFailure('errors_from_twitter: '.$errorString, $args);
        }
        return $result;
    }

    /**
     * Gets Tweets for a user via proxy call to twitter
     * @param ServiceBase $api
     * @param array $args
     * @return mixed
     * @throws SugarApiExceptionRequestMethodFailure
     * @throws SugarApiExceptionMissingParameter
     */
    public function getCurrentUser(ServiceBase $api, array $args)
    {
        $this->validateHash($args);
        $extApi = $this->getEAPM();
        if (is_array($extApi) && isset($extApi['error'])) {
            throw new SugarApiExceptionRequestMethodFailure(null, $args, null, 0, $extApi['error']);
        }

        if ($extApi === false) {
            throw new SugarApiExceptionRequestMethodFailure(null, $args, null, 0, $GLOBALS['app_strings']['ERROR_UNABLE_TO_RETRIEVE_DATA']);
        }

        $result = $extApi->getCurrentUserInfo();
        if (isset($result['errors'])) {
            $errorString = '';
            foreach($result['errors'] as $errorKey => $error) {
                $errorString .= $error['code'].str_replace(' ', '_', $error['message']);
            }
            throw new SugarApiExceptionRequestMethodFailure(null, $args, null, 0, json_encode(array('status'=>$errorString)));
        }
        return $result;
    }
}
