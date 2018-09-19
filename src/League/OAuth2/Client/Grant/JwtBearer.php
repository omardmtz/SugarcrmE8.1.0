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

namespace Sugarcrm\Sugarcrm\League\OAuth2\Client\Grant;

use League\OAuth2\Client\Grant\AbstractGrant;

/**
 * urn:ietf:params:oauth:grant-type:jwt-bearer grant type
 */
class JwtBearer extends AbstractGrant
{
    /**
     * @inheritdoc
     */
    protected function getName()
    {
        return 'urn:ietf:params:oauth:grant-type:jwt-bearer';
    }

    /**
     * @inheritdoc
     */
    protected function getRequiredRequestParameters()
    {
        return [
            'assertion',
        ];
    }
}
