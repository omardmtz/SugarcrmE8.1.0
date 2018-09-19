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

namespace Sugarcrm\IdentityProvider\App\Authentication\Adapter;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConfigAdapterFactory
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param $code
     * @return null|AbstractAdapter
     */
    public function getAdapter($code)
    {
        $method = sprintf('get%sAdapter', ucfirst(strtolower($code)));
        $adapter = null;
        if (method_exists($this, $method)) {
            $adapter = $this->$method();
        }
        return $adapter;
    }

    /**
     * @return null|SamlAdapter
     */
    protected function getSamlAdapter()
    {
        return new SamlAdapter($this->urlGenerator);
    }
}
