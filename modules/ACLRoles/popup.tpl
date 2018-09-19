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

<script type="text/javascript" src="{sugar_getjspath file="include/javascript/popup_helper.js"}"></script>
<script type="text/javascript">
<!--
/* initialize the popup request from the parent */
{literal}

{/literal}
-->
</script>
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="list view">

	<tr height="20">
	<td scope="col" width="1%" >{$CHECKALL}&nbsp;</td>
		<td scope="col" width="20%"  nowrap><slot>{$MOD.LBL_NAME}</slot></td>
		<td scope="col" width="10%"  nowrap><slot>{$MOD.LBL_DESCRIPTION}</slot></td>
	  </tr>

{foreach from=$ROLES item="ROLE"}

<tr height="20" >
    			<td>{$PREROW}&nbsp;</td>
    			<td valign=TOP  ><slot><a href="#" onclick="send_back('Users','{$ROLE.id}');">{$ROLE.name}</a></slot></td>
    			<td valign=TOP  ><slot>{$ROLE.description}</slot></td>

</tr>
{foreachelse}
        <tr>
            <td colspan="2">{$MOD.LBL_NO_ROLES}</td>
        </tr>
{/foreach}



</table>
{$ASSOCIATED_JAVASCRIPT_DATA}
