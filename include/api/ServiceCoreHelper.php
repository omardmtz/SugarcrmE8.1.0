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

// This is a set of classes that are here temporarialy until we get rid of any dependencies on the files in service/core

class SCErrorObject {
    var $errorMessage;
    function set_error($errorMessage) {
        $this->errorMessage = $errorMessage;
    }
    function error($errorObject) {
        throw new SugarApiExceptionError($errorObject->errorMessage);
    }
}