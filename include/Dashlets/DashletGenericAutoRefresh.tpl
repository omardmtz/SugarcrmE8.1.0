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
<!--
var autoRefreshProcId{$strippedDashletId} = '';
if (document.getElementById("{$dashletId}_interval").value > 0) {ldelim}
    autoRefreshProcId{$strippedDashletId} = setInterval('refreshDashlet{$strippedDashletId}()', "{$dashletRefreshInterval}");
{rdelim}	
function refreshDashlet{$strippedDashletId}() 
{ldelim}
    //refresh only if offset is 0
    if ( SUGAR.mySugar && document.getElementById("{$dashletId}_offset").value == '0' ) {ldelim}
        SUGAR.mySugar.retrieveDashlet("{$dashletId}","{$url}");
    {rdelim}
{rdelim}
-->
</script>
