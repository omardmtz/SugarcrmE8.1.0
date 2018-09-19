{*
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
*}
{{include file='include/Popups/tpls/header.tpl'}}
{{sugar_getscript file='include/javascript/popup_helper.js'}}
{{sugar_getscript file='include/javascript/yui/build/connection/connection.js'}}
{{sugar_getscript file='modules/ProductTemplates/Popup_picker.js'}}
{{$treeheader}}
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
    <tr><td>
        <table width="95%" border="0" cellspacing="0" cellpadding="0">
            <tr><td>
                <div id="productcatalog">{{$treeinstance}}</div>
            </td></tr>
        </table>
    </td></tr>
</table>