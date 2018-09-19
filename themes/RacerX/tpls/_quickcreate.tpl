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

<div id="quickCreate">
<ul class="clickMenu" id="quickCreateUL">
    <li>
        <ul class="subnav iefixed showLess" id="quickCreateULSubnav">
            {foreach from=$DCACTIONS item=action name=quickCreate}
                <li {if $smarty.foreach.quickCreate.index > 4}class="moreOverflow"{/if}><a href="javascript: if ( DCMenu.menu ) DCMenu.menu('{$action.module}','{$action.createRecordTitle}', true);" id="{$action.module}_quickcreate">{$action.createRecordTitle}</a></li>

            {/foreach}

            {if count($DCACTIONS) > 4}
                <li class="moduleMenuOverFlowMore"><a href="javascript: SUGAR.themes.toggleQuickCreateOverFlow('quickCreateULSubnav','more');">{$APP.LBL_SHOW_MORE} <div class="showMoreArrow"></div></a></li>
                <li class="moduleMenuOverFlowLess"><a href="javascript: SUGAR.themes.toggleQuickCreateOverFlow('quickCreateULSubnav','less');">{$APP.LBL_SHOW_LESS} <div class="showLessArrow"></div></a></li>
            {/if}
        </ul>
    </li>
</ul>
</div>
