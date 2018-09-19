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
<input type="hidden" id="{$dashletId}_offset" name="{$dashletId}_offset" value="0">
<input type="hidden" id="{$dashletId}_interval" name="{$dashletId}_interval" value="{$dashletRefreshInterval}">
<script type='text/javascript'>
// <!--
document.getElementById("{$dashletId}_offset").value = "{$dashletOffset}";
document.getElementById("{$dashletId}_interval").value = "{$dashletRefreshInterval}";
if (typeof autoRefreshProcId{$strippedDashletId} != 'undefined') {ldelim}
    clearInterval(autoRefreshProcId{$strippedDashletId});
{rdelim}
if(document.getElementById("{$dashletId}_interval").value > 0) {ldelim}
    if (typeof refreshDashlet{$strippedDashletId} == 'undefined') {ldelim}
        function refreshDashlet{$strippedDashletId}() 
        {ldelim}
            //refresh only if offset is 0
            if (SUGAR.mySugar && document.getElementById("{$dashletId}_offset").value == '0' ) {ldelim}
                SUGAR.mySugar.retrieveDashlet("{$dashletId}","{$url}");
            {rdelim}
        {rdelim}
    {rdelim}
    autoRefreshProcId{$strippedDashletId} = setInterval('refreshDashlet{$strippedDashletId}()', document.getElementById("{$dashletId}_interval").value);
{rdelim}
// -->
</script>
