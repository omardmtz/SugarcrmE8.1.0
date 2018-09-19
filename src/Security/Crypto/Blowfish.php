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

namespace Sugarcrm\Sugarcrm\Security\Crypto;

/**
 * Blowfish encryption
 *
 * @internal
 */
class Blowfish
{
    /**
     * retrieves the system's private key; will build one if not found, but anything encrypted before is gone...
     *
     * @param string $type
     *
     * @return string key
     */
    public static function getKey($type)
    {
        $key = array();

        $type = str_rot13($type);

        $keyCache = "custom/blowfish/{$type}.php";

        // build cache dir if needed
        if (!file_exists('custom/blowfish')) {
            mkdir_recursive('custom/blowfish');
        }

        // get key from cache, or build if not exists
        if (file_exists($keyCache)) {
            include $keyCache;
        } else {
            // create a key
            $key[0] = create_guid();
            write_array_to_file('key', $key, $keyCache);
        }
        return $key[0];
    }

    /**
     * Uses blowfish to encrypt data and base 64 encodes it. It stores the iv as part of the data
     *
     * @param string $key  key to base encoding off of
     * @param string $data string to be encrypted and encoded
     *
     * @return string
     */
    public static function encode($key, $data)
    {
        // To be backwards compatible with how mcrypt/Pear_BlowFish works, use zeropadding
        $data = $data . str_repeat(chr(0), 8 - ((strlen($data) % 8) ?: 8));
        return openssl_encrypt($data, 'bf-ecb', self::padKey($key), OPENSSL_ZERO_PADDING);
    }

    /**
     * Uses blowfish to decode data assumes data has been base64 encoded with the iv stored as part of the data
     *
     * @param string $key     key to base decoding off of
     * @param string $encoded base64 encoded blowfish encrypted data
     *
     * @return string
     */
    public static function decode($key, $encoded)
    {
        // To be backwards compatible with how mcrypt/Pear_BlowFish works, remove zeropadding
        return rtrim(openssl_decrypt($encoded, 'bf-ecb', self::padKey($key), OPENSSL_ZERO_PADDING), chr(0));
    }

    /**
     * Padds key to the proper length so it's cross-compatible between mcrypt and openssl.
     *
     * According to https://www.schneier.com/academic/archives/1994/09/description_of_a_new.html short keys should be
     * cycled over so keys A-AA-AAA are equivalent.
     *
     * @param string $key
     *
     * @return string
     */
    private static function padKey($key)
    {
        return strlen($key) < 16 ? str_repeat($key, ceil(16 / strlen($key))) : $key;
    }
}
