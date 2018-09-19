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
use Sugarcrm\Sugarcrm\Security\Crypto\Blowfish;

function sugarEncode($key, $data){
	return base64_encode($data);
}


function sugarDecode($key, $encoded){
	$data = base64_decode($encoded);
	return $data;
}

///////////////////////////////////////////////////////////////////////////////
////	BLOWFISH
/**
 * retrives the system's private key; will build one if not found, but anything encrypted before is gone...
 * @param string type
 * @return string key
 * @deprecated Use Blowfish::getKey()
 */
function blowfishGetKey($type)
{
    return Blowfish::getKey($type);
}

/**
 * Uses blowfish to encrypt data and base 64 encodes it. It stores the iv as part of the data
 * @param STRING key - key to base encoding off of
 * @param STRING data - string to be encrypted and encoded
 * @return string
 * @deprecated Use Blowfish::encode()
 */
function blowfishEncode($key, $data){
    return Blowfish::encode($key, $data);
}

/**
 * Uses blowfish to decode data assumes data has been base64 encoded with the iv stored as part of the data
 * @param STRING key - key to base decoding off of
 * @param STRING encoded base64 encoded blowfish encrypted data
 * @return string
 * @deprecated Use Blowfish::decode()
 */
function blowfishDecode($key, $encoded){
    return Blowfish::decode($key, $encoded);
}
