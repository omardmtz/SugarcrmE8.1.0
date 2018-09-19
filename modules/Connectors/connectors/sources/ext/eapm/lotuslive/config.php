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

$config = array (
  'name' => 'LotusLive',
  'eapm' => array(
    'enabled' => true,
    'only' => true,
  ),
  'order' => 16,
  'properties' => array (
      'oauth_consumer_key' => '',
      'oauth_consumer_secret' => '',
  ),
  'encrypt_properties' => array (
      'oauth_consumer_secret',
  ),
);
