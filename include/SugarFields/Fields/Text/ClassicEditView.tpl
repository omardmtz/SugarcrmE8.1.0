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

{if !empty($displayParams.maxlength)}
<textarea id="{$prefix}{$name}" name="{$prefix}{$name}" maxlength="{$displayParams.maxlength}" cols="{$displayParams.cols|default:60}" rows="{$displayParams.rows|default:4}" tabindex="{$tabindex}">{$value}</textarea>
{else}
<textarea id="{$prefix}{$name}" name="{$prefix}{$name}" cols="{$displayParams.cols|default:60}" rows="{$displayParams.rows|default:4}" tabindex="{$tabindex}">{$value}</textarea>
{/if}


