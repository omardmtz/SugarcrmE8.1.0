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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Listener\Success;

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Session\SessionStorage;

use Symfony\Component\Security\Core\Event\AuthenticationEvent;

class PostLoginAuthListener
{
    /**
     * set user in globals and session
     * @param AuthenticationEvent $event
     */
    public function execute(AuthenticationEvent $event)
    {
        global $log;
        /** @var \User $currentUser */
        $currentUser = $event->getAuthenticationToken()->getUser()->getSugarUser();
        $sugarConfig = \SugarConfig::getInstance();
        /** @var SessionStorage $sessionStorage */
        $sessionStorage = SessionStorage::getInstance();
        if (!$sessionStorage->sessionHasId()) {
            $sessionStorage->start();
        }

        //just do a little house cleaning here
        unset($sessionStorage['login_password']);
        unset($sessionStorage['login_error']);
        unset($sessionStorage['login_user_name']);
        unset($sessionStorage['ACL']);

        $uniqueKey = $sugarConfig->get('unique_key');

        //set the server unique key
        if (!empty($uniqueKey)) {
            $sessionStorage['unique_key'] = $uniqueKey;
        }

        //set user language
        $sessionStorage['authenticated_user_language'] = InputValidation::getService()->getValidInputRequest(
            'login_language',
            'Assert\Language',
            $sugarConfig->get('default_language')
        );

        $log->debug("authenticated_user_language is " . $sessionStorage['authenticated_user_language']);

        // Clear all uploaded import files for this user if it exists
        $tmp_file_name = \ImportCacheFiles::getImportDir() . "/IMPORT_" . $currentUser->id;

        if (file_exists($tmp_file_name)) {
            unlink($tmp_file_name);
        }
    }
}
