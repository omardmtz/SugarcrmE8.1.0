<?php
 if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 7/21/11
 * Time: 11:58 AM
 * To change this template use File | Settings | File Templates.
 */
require_once('service/core/REST/SugarRest.php');

class SugarRestDecorator extends SugarRest{
    protected $decoratedClass;

    public function __construct($decoratedClass){
        $this->decoratedClass = $decoratedClass;
	}

    public function serve(){
        return $this->decoratedClass->serve();
    }
}
 
