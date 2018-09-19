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

namespace Sugarcrm\Sugarcrm\Security\Csrf;

use Sugarcrm\Sugarcrm\Security\Crypto\CSPRNG;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 *
 * CSRF token generator using our build-in CSPRNG.
 *
 */
class CsrfTokenGenerator implements TokenGeneratorInterface
{
    /**
     * @var CSPRNG
     */
    protected $csprng;

    /**
     * Token size
     * @var integer
     */
    protected $size = 32;

    /**
     * Ctor
     * @param CSPRNG $csprng
     */
    public function __construct(CSPRNG $csprng = null)
    {
        $this->csprng = $csprng ?: CSPRNG::getInstance();
    }

    /**
     * {@inheritdoc}
     */
    public function generateToken()
    {
        // generate encoded token
        $token = $this->csprng->generate($this->size, true);

        // strip off url unfriendly chars
        return strtr($token, '+/', '-_');
    }

    /**
     * Set token size
     * @param integer $size
     */
    public function setSize($size)
    {
        $this->size = (int) $size;
    }
}
