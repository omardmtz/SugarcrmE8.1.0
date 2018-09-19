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

namespace Sugarcrm\IdentityProvider\Encoder;

use Silex\Application;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Sugarcrm\IdentityProvider\Encoder\CryptPasswordEncoder;

/**
 * Builds encoder based on application settings
 * @see Mango/sugarcrm/src/Security/Password/Hash.php
 */
class EncoderBuilder
{
    const DEFAULT_BACKEND = 'native';
    const BACKEND_SHA2 = 'sha2';

    const DEFAULT_BCRYPT_COST = 10;

    /**
     * Get proper encoder according to the config. Config parameters names are identical to Mango's.
     *
     * @param array $config Configuration
     *
     * @return PasswordEncoderInterfaceInterface
     */
    public function buildEncoder(array $config)
    {
        $backend = isset($config['passwordHash']['backend'])
            ? $config['passwordHash']['backend']
            : self::DEFAULT_BACKEND;
        $algo = isset($config['passwordHash']['algo'])
            ? $config['passwordHash']['algo']
            : null;
        $options = isset($config['passwordHash']['options'])
            ? $config['passwordHash']['options']
            : [];

        switch ($backend) {
            case self::BACKEND_SHA2:
                $encoder = new CryptPasswordEncoder(
                    $algo,
                    isset($options['rounds']) ? $options['rounds'] : CryptPasswordEncoder::DEFAULT_ITERATIONS
                );
                break;
            case self::DEFAULT_BACKEND:
            default:
                switch ($algo) {
                    case PASSWORD_DEFAULT:
                    case null:
                        // PASSWORD_DEFAULT and PASSWORD_BCRYPT matches at the moment
                        // but it may be changed over time in future PHP versions
                        if (PASSWORD_DEFAULT != PASSWORD_BCRYPT) {
                            throw new \RuntimeException('Default encryption is different from Blowfish');
                        }
                    case PASSWORD_BCRYPT:
                        $encoder = new BCryptPasswordEncoder(
                            isset($options['cost']) ? $options['cost'] : self::DEFAULT_BCRYPT_COST
                        );
                        break;
                }
                break;
        }
        return $encoder;
    }
}
