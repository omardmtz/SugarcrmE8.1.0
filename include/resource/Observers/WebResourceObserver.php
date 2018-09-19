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


/**
 * WebResourceObserver.php
 * This is a subclass of ResourceObserver to provide notification handling
 * for web clients.
 */
class WebResourceObserver extends ResourceObserver {
/**
 * notify
 * Web implementation to notify the browser
 * @param msg String message to possibly display
 * 
 */
public function notify($msg = '') {
   echo $msg;
   sugar_cleanup(true);
}	
	
}
