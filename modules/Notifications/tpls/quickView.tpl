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

<div id="SpotResults">
    <div>{$APP.LBL_NOTIFICATIONS}</div>
    <ul>
        {foreach from=$data item=n}
            <li><a href='javascript:void(0)' onclick="DCMenu.viewMiniNotification('{$n->id}');">{$n->name}</li>
        {foreachelse}
            <li>-None-</li>
        {/foreach}
    </ul>
</div>