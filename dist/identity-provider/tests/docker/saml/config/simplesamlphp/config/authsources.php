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

$config = array(

    'admin' => array(
        'core:AdminPassword',
    ),

    'example-userpass' => array(
        'exampleauth:UserPass',
        'user1:user1pass' => array(
            'uid' => array('1'),
            'eduPersonAffiliation' => array('group1'),
            'email' => 'user1@example.com',
        ),
        'user2:user2pass' => array(
            'uid' => array('2'),
            'eduPersonAffiliation' => array('group2'),
            'email' => 'user2@example.com',
        ),
        'user3:user3pass' => array(
            'uid' => array('3'),
            'eduPersonAffiliation' => array('group1'),
            'email' => 'user3@example.com',
        ),
        'user4:user4pass' => array(
            'uid' => array('4'),
            'eduPersonAffiliation' => array('group2'),
            'email' => 'user4@example.com',
        ),
        'user5:user5pass' => array(
            'uid' => array('5'),
            'eduPersonAffiliation' => array('group1'),
            'email' => 'user5@example.com',
        ),
    ),

);
