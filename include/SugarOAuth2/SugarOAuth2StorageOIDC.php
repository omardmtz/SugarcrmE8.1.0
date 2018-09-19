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

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Provides legacy clients support which do not support oauth2/OIDC protocol
 * and uses username/password for authentication
 */
class SugarOAuth2StorageOIDC extends SugarOAuth2Storage
{
    /**
     * @inheritdoc
     */
    public function checkUserCredentials($client_id, $username, $password)
    {
        try {
            // noHooks since we'll take care of the hooks on API level, to make it more generalized
            $loginResult = $this->getAuthController()->login(
                $username,
                $password,
                ['passwordEncrypted' => false, 'noRedirect' => true, 'noHooks' => true]
            );
            if ($loginResult) {
                return $loginResult;
            }
        } catch (AuthenticationException $e) {
            throw new SugarApiExceptionNeedLogin($e->getMessage());
        }

        throw new SugarApiExceptionNeedLogin($this->getTranslatedMessage('ERR_INVALID_PASSWORD', 'Users'));
    }

    /**
     * @return AuthenticationController
     */
    protected function getAuthController()
    {
        return AuthenticationController::getInstance();
    }

    /**
     * Translate message by its label for a specified module.
     * Wrapper for Sugar's translate function.
     *
     * @param string $label
     * @param string $module
     * @return string
     */
    protected function getTranslatedMessage($label, $module)
    {
        return translate($label, $module);
    }
}
