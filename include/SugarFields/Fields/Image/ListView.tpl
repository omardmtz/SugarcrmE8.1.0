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
{if !empty($parentFieldArray.$col)}
{if !empty($vardef.calculated)}
<a href="javascript:SUGAR.image.lightbox('{$parentFieldArray.$col}')">
<img src='{$parentFieldArray.$col}' style='height: 64px;'>
{else}
<a href="javascript:SUGAR.image.lightbox('index.php?entryPoint=download&id={$parentFieldArray.$col}&type=SugarFieldImage&isTempFile=1')">
<img src='index.php?entryPoint=download&id={$parentFieldArray.$col}&type=SugarFieldImage&isTempFile=1'
    style='height: 64px;'>
{/if}

{/if}
