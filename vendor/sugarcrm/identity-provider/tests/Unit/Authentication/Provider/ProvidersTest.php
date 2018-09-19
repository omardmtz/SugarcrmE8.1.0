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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication\Provider;

use Sugarcrm\IdentityProvider\Authentication\Provider\Providers;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\Authentication\Provider\Providers
 */
class ProvidersTest extends \PHPUnit_Framework_TestCase
{
    public function testProviderCodes()
    {
        $this->assertEquals('local', Providers::LOCAL);
        $this->assertEquals('ldap', Providers::LDAP);
        $this->assertEquals('saml', Providers::SAML);
    }
}