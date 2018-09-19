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

use Silex\Application as App;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Sugarcrm\IdentityProvider\App\Authentication\BearerAuthentication;
use Sugarcrm\IdentityProvider\App\Controller\AuthenticationController;
use Sugarcrm\IdentityProvider\App\Controller\ConsentController;
use Sugarcrm\IdentityProvider\App\Controller\MainController;
use Sugarcrm\IdentityProvider\App\Controller\SAMLController;
use Sugarcrm\IdentityProvider\App\Provider\TenantConfigInitializer;

class ControllerProvider implements ControllerProviderInterface
{
    /**
     * Returns routes to connect to the given application.
     *
     * @param App $app
     * @return ControllerCollection
     */
    public function connect(App $app)
    {
        /* @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];
        $tenantConfigInitializer = new TenantConfigInitializer($app);
        $mainController = new MainController();
        $samlController = new SAMLController();
        $consentController = new ConsentController($app);
        $authenticationController = new AuthenticationController();
        $restBearerAuthentication = new BearerAuthentication(
            $app['oAuth2Service'],
            'idp.auth.password',
            $app['logger']
        );
        $controllers->before(function ($request) use ($tenantConfigInitializer) {
            $exceptionRoutes = [
                'loginProcess',
                'loginRender',
                'consentInit',
                'consentFinish',
                'restAuthenticate'
            ];
            //TODO This constraint should be more complex solution Or need to be removed.
            if (!in_array($request->attributes->get('_route'), $exceptionRoutes)) {
                call_user_func($tenantConfigInitializer, $request);
            }
        });

        $controllers
            ->get('/login-end-point', [$mainController, 'loginEndPointAction'])
            ->bind('loginEndPoint');

        $controllers
            ->get('/', [$mainController, 'renderFormAction'])
            ->bind('loginRender');
        $controllers
            ->post('/', [$mainController, 'postFormAction'])
            ->bind('loginProcess');

        $controllers
            ->post('/authenticate', [$authenticationController, 'authenticate'])
            ->before([$restBearerAuthentication, 'authenticateClient'])
            ->bind('restAuthenticate');

        $controllers
            ->get('saml/logout-end-point', [$samlController, 'logoutEndPointAction'])
            ->bind('samlLogoutEndPoint');
        $controllers
            ->get('saml/login-end-point', [$samlController, 'loginEndPointAction'])
            ->bind('samlLoginEndPoint');
        $controllers
            ->get('/saml', [$samlController, 'renderFormAction'])
            ->bind('samlRender');
        $controllers
            ->get('/saml/init', [$samlController, 'initAction'])
            ->bind('samlInit');
        $controllers
            ->post('/saml/acs', [$samlController, 'acsAction'])
            ->bind('samlAcs');
        $controllers
            ->match('/saml/logout', [$samlController, 'logoutAction'])
            ->bind('samlLogout');
        $controllers
            ->get('/saml/logout/init', [$samlController, 'logoutInitAction'])
            ->bind('samlLogoutInit');
        $controllers
            ->get('/saml/metadata', [$samlController, 'metadataAction'])
            ->bind('samlMetaData');

        $controllers
            ->get('/consent', [$consentController, 'consentInitAction'])
            ->bind('consentInit');

        $controllers
            ->get('/consent/confirmation', [$consentController, 'consentConfirmationAction'])
            ->bind('consentConfirmation');

        $controllers
            ->get('/consent/finish', [$consentController, 'consentFinishAction'])
            ->bind('consentFinish');

        $controllers
            ->get('/consent/cancel', [$consentController, 'consentCancelAction'])
            ->bind('consentCancel');

        return $controllers;
    }
}
