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
<!--end body panes-->
{if $use_table_container}
</td>
</tr>
</table>
{/if}
</div>
<div class="clear"></div>
</div>
<div id="bottomLinks">
{if $AUTHENTICATED}
{$BOTTOMLINKS}
{/if}
</div>

<div class="clear"></div>
<div id="arrow" title="Show" class="up"><i class="icon-chevron-down"></i></div>
<div id="footer">
{if $COMPANY_LOGO_URL}
    <img src="{$COMPANY_LOGO_URL}" class="logo" id="logo" title="{$STATISTICS}" border="0"/>
{/if}
    <div id="buffer"></div>
{if $HELP_LINK}
    <div id="help" class="help">{$HELP_LINK}</div>
{/if}
    <div id="partner">
        <div id="integrations">
        {foreach from=$DYNAMICDCACTIONS item=action}
                {$action.script} {$action.image}
            {/foreach}
        </div>
    </div>
{if $AUTHENTICATED}
    <div id="productTour">
        {$TOUR_LINK}
    </div>
{/if}
    <a href="http://www.sugarcrm.com" target="_blank" class="copyright">&#169; 2013 SugarCRM Inc.</a>
    <script>
        var logoStats = "&#169; 2004-2013 SugarCRM Inc. All Rights Reserved. {$STATISTICS|addslashes}";
    </script>

{literal}


    <div class="clear"></div>
</div>
{/literal}
</body>
</html>

