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
<div class="dcmenuDivider" id="globalLinksDivider"></div>
<div id="globalLinksModule">
    <ul class="clickMenu" id="globalLinks">
        <li>
            <ul class="subnav iefixed">
                {foreach from=$GCLS item=GCL name=gcl key=gcl_key}
    			    <li><a id="{$gcl_key}_link" href="{$GCL.URL}" {if $smarty.foreach.gcl.last}class="last"{/if}{if !empty($GCL.ONCLICK)} onclick="{$GCL.ONCLICK}"{/if}>{$GCL.LABEL}</a></li>

	                {foreach from=$GCL.SUBMENU item=GCL_SUBMENU name=gcl_submenu key=gcl_submenu_key}
	                    <a id="{$gcl_submenu_key}_link" href="{$GCL_SUBMENU.URL}"{if !empty($GCL_SUBMENU.ONCLICK)} onclick="{$GCL_SUBMENU.ONCLICK}"{/if}>{$GCL_SUBMENU.LABEL}</a>
	                {/foreach}
                {/foreach}

                <li><a id="logout_link" href='{$LOGOUT_LINK}' class='utilsLink'>{$LOGOUT_LABEL}</a> </li>
            </ul>
            <span> 
        	    <div id="dcmenuUserIcon" {$NOTIFCLASS}>
				  {$NOTIFICON}
				</div>
            	<a id="welcome_link" href='javascript: void(0);'>{$CURRENT_USER}</a>
            	
            </span>
        </li>
    </ul>
</div>

{if $NOTIFCODE != ""}
	<div class="dcmenuDivider" id="notifDivider"></div>
	<div id="dcmenuSugarCube" {$NOTIFCLASS} {if $ISADMIN}onclick="DCMenu.notificationsList();" title="{$APP.LBL_PENDING_NOTIFICATIONS}"{/if}>
	  {$NOTIFCODE}
	</div>
{else}
<div id="dcmenuSugarCubeEmpty"></div>
{/if}