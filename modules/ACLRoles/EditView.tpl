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


<script>
{literal}
function set_focus(){
	document.getElementById('name').focus();
}
{/literal}
</script>

<form method='POST' name='EditView' action='index.php'>
{sugar_csrf_form_token}
<TABLE width='100%' border='0' cellpadding=0 cellspacing = 0 class="actionsContainer">
<tbody>
<tr>
<td>
<input type='hidden' name='record' value='{$ROLE.id}'>
<input type='hidden' name='module' value='ACLRoles'>
<input type='hidden' name='action' value='Save'>
<input type='hidden' name='isduplicate' value='{$ISDUPLICATE}'>
<input type='hidden' name='return_record' value='{$RETURN.record}'>
<input type='hidden' name='return_action' value='{$RETURN.action}'>
<input type='hidden' name='return_module' value='{$RETURN.module}'> &nbsp;
{sugar_action_menu id="roleEditActions" class="clickMenu fancymenu" buttons=$ACTION_MENU flat=true}
</td>
</tr>
</tbody>
</table>
<TABLE width='100%' class="edit view"  border='0' cellpadding=0 cellspacing = 0  >
<TR>
<td scope="row" align='right'>{$MOD.LBL_NAME}:<span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td><td >
<input id='name' name='name' type='text' value='{$ROLE.name}'>
</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td scope="row" align='right'>{$MOD.LBL_DESCRIPTION}:</td>
<td ><textarea name='description' cols="80" rows="8">{$ROLE.description}</textarea></td>
</tr>
</table>

</form>
<script type="text/javascript">
addToValidate('EditView', 'name', 'varchar', true, '{$MOD.LBL_NAME}');
</script>
