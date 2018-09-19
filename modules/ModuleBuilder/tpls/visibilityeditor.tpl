<!-- -->
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

{literal}
<style type="text/css">
	.x-grid3-dirty-cell {background: none;}
</style>
{/literal}
<form id='visibility_editor' name='visibility_editor'  onsubmit='return false;'>
{sugar_csrf_form_token}
</form>
<script type="text/javascript">
var visgrid =  {$visibility_grid};
var visibilityEditor = new ModuleBuilder.VisibilityEditor ( visgrid , 'visibility_editor' , {$onSave} , {$onClose} ) ;
visibilityEditor.myEditorPanel.show();
</script>

