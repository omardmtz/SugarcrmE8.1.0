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
<script type="text/javascript" src="{sugar_getjspath file='modules/ModuleBuilder/javascript/JSTransaction.js'}" ></script>
<script>
	var jstransaction = new JSTransaction();
	{literal}
	if (SUGAR.themes.tempHideLeftCol){
    	SUGAR.themes.tempHideLeftCol();
    }
    {/literal}
</script>

<link rel="stylesheet" type="text/css" href="{sugar_getjspath file="modules/ModuleBuilder/tpls/LayoutEditor.css"}" />
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file="vendor/ytree/TreeView/css/folders/tree.css"}" />

<script type="text/javascript" src='{sugar_getjspath file ='modules/ModuleBuilder/javascript/studio2.js'}' ></script>
<script type="text/javascript" src='{sugar_getjspath file ='modules/ModuleBuilder/javascript/studio2PanelDD.js'}' ></script>
<script type="text/javascript" src='{sugar_getjspath file ='modules/ModuleBuilder/javascript/studio2RowDD.js'}' ></script>
<script type="text/javascript" src='{sugar_getjspath file ='modules/ModuleBuilder/javascript/studio2FieldDD.js'}' ></script>
<script type="text/javascript" src='{sugar_getjspath file ='modules/ModuleBuilder/javascript/studiotabgroups.js'}'></script>
<script type="text/javascript" src='{sugar_getjspath file ='modules/ModuleBuilder/javascript/studio2ListDD.js'}' ></script>

<!--end ModuleBuilder Assistant!-->
<script type="text/javascript" language="Javascript" src='{sugar_getjspath file ='modules/ModuleBuilder/javascript/ModuleBuilder.js'}'></script>
<script type="text/javascript" language="Javascript" src='{sugar_getjspath file ='modules/ModuleBuilder/javascript/SimpleList.js'}'></script>
<script type="text/javascript" src='{sugar_getjspath file ='modules/ModuleBuilder/javascript/JSTransaction.js'}' ></script>
<script type="text/javascript" src='{sugar_getjspath file ='include/javascript/tiny_mce/tiny_mce.js'}' ></script>

<!-- Formula builder and dependency manager -->
<script src="{sugar_getjspath file='include/javascript/jquery/markitup/jquery.markitup.js'}"></script>
<script src="{sugar_getjspath file='include/javascript/jquery/markitup/sets/default/set.js'}"></script>

<script src="{sugar_getjspath file='sidecar/minified/sidecar.min.js'}"></script>
<script language="javascript">
    jQuery.noConflict();
</script>
<script src="{sugar_getjspath file='include/javascript/sugarAuthStore.js'}"></script>
<script src="{sugar_getjspath file='include/javascript/twitterbootstrap/bootstrap-colorpicker.js'}"></script>
<script src="{sugar_getjspath file='include/javascript/select2/select2.js'}"></script>

<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/ModuleBuilder/tpls/MB.css'}" />
