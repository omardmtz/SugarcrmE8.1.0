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

namespace Sugarcrm\IdentityProvider\App\Provider;

use Sugarcrm\IdentityProvider\App\Application;
use Sugarcrm\IdentityProvider\Srn;
use Symfony\Component\HttpFoundation\Request;

/**
 * Init config for tenant.
 */
class TenantConfigInitializer
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Config initializer.
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        $this->initConfig($request);
    }

    /**
     * Initializes config
     *
     * @param Request $request
     * @throws \RuntimeException
     */
    public function initConfig(Request $request)
    {
        $tenant = $this->getTenant($request);
        if (empty($tenant)) {
            $this->app->getLogger()->critical('Cant build configs without tenant id', [
                'request' => [
                    'request' => sprintf('%s %s', $request->getMethod(), $request->getRequestUri()),
                    'headers' => $request->headers->all(),
                    'queryString' => $request->getQueryString(),
                    'post' => $request->request->all(),
                ],
                'tags' => ['IdM.config'],
            ]);
            throw new \RuntimeException('Cant build configs without tenant id');
        }
        $request->getSession()->set('tenant', Srn\Converter::toString($tenant));
        $this->app['config'] = $this->app->getTenantConfiguration()->merge($tenant, $this->app['config']);
    }

    /**
     * Do we have tenant set
     *
     * @param Request $request
     * @return boolean
     */
    public function hasTenant(Request $request)
    {
        return $request->get('login_hint') || $request->get('tid') || $request->getSession()->has('tenant');
    }

    /**
     * Looks in all the various nooks and crannies and attempts to find an tenant srn
     *
     * @param Request $request
     * @return Srn
     */
    protected function getTenant(Request $request)
    {
        if (!empty($request->get('tid'))) {
            $tenantString = $request->get('tid');
        } elseif (!empty($request->get('login_hint'))) {
            $tenantString = $request->get('login_hint');
        } elseif ($request->getSession()->has('tenant')) {
            $tenantString = $request->getSession()->get('tenant');
        } else {
            return null;
        }
        try {
            return Srn\Converter::fromString($tenantString);
        } catch (\InvalidArgumentException $e) {
            //make double convertion to validate generated SRN
            return Srn\Converter::fromString(
                Srn\Converter::toString($this->app->getSrnManager()->createTenantSrn($tenantString))
            );
        }
    }
}
