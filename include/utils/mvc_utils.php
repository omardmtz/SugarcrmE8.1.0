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

function loadParentView($type)
{
    SugarAutoLoader::requireWithCustom('include/MVC/View/views/view.'.$type.'.php');
}


function getPrintLink()
{
    return "javascript:void window.open('index.php?',"
         . "'printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')";
}
