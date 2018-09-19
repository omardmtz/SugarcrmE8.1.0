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

namespace Sugarcrm\IdentityProvider\Tests\Functional\SAML;

use Sugarcrm\IdentityProvider\Tests\IDMFixturesHelper;

class MetadataFlowTest extends AppFlowTest
{
    public function testMetadataAction()
    {
        $crawler = $this->webClient->request('GET', '/saml/metadata');
        // crawler doesn't parse XML
        $html = $crawler->html();
        $this->assertContains('keydescriptor', $html);
        $this->assertContains('singlelogoutservice', $html);
        $this->assertContains('nameidformat', $html);
        $this->assertContains('assertionconsumerservice', $html);
    }

    public function getSamlParameters()
    {
        return IDMFixturesHelper::getOneLoginParameters();
    }
}
