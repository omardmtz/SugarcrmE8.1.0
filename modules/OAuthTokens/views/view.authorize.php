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



class OauthTokensViewAuthorize extends SugarView
{
	public function display()
    {
        if(!SugarOAuthServer::enabled()) {
            sugar_die($GLOBALS['mod_strings']['LBL_OAUTH_DISABLED']);
        }
        global $current_user;
        $tokenParam = (!isset($_REQUEST['token']) && isset($_REQUEST['oauth_token']))
            ? 'oauth_token'
            : 'token';
        $requestToken = $this->request->getValidInputRequest($tokenParam, 'Assert\Guid');
        $sugar_smarty = new Sugar_Smarty();
        $sugar_smarty->assign('APP', $GLOBALS['app_strings']);
        $sugar_smarty->assign('MOD', $GLOBALS['mod_strings']);
        $sugar_smarty->assign('token', $requestToken);
        $sugar_smarty->assign('sid', session_id());
        $token = OAuthToken::load($requestToken);
        if(empty($token) || empty($token->consumer) || $token->tstate != OAuthToken::REQUEST || empty($token->consumer_obj)) {
            sugar_die('Invalid token');
        }

        if(empty($_REQUEST['confirm'])) {
            $sugar_smarty->assign('consumer', sprintf($GLOBALS['mod_strings']['LBL_OAUTH_CONSUMERREQ'], $token->consumer_obj->name));
            $hash = md5(rand());
            $_SESSION['oauth_hash'] = $hash;
            $sugar_smarty->assign('hash', $hash);
            echo $sugar_smarty->fetch('modules/OAuthTokens/tpl/authorize.tpl');
        } else {
            if($_REQUEST['sid'] != session_id() || $_SESSION['oauth_hash'] != $_REQUEST['hash']) {
                sugar_die('Invalid request');
            }
            $verify = $token->authorize(array("user" => $current_user->id));
            if(!empty($token->callback_url)){
                $redirect_url=$token->callback_url;
                if(strchr($redirect_url, "?") !== false) {
                    $redirect_url .= '&';
                } else {
                    $redirect_url .= '?';
                }
                $redirect_url .= "oauth_verifier=".$verify.'&oauth_token=' . $requestToken;
                SugarApplication::redirect($redirect_url);
            }
            $sugar_smarty->assign('VERIFY', $verify);
            $sugar_smarty->assign('token', '');
            echo $sugar_smarty->fetch('modules/OAuthTokens/tpl/authorized.tpl');
        }
    }

}

