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
<a href="index.php?entryPoint=download&id={$parentFieldArray.ID}&type={$displayParams.module}{$vardef.displayParams.module}" class="tabDetailViewDFLink" target='_blank'>{sugar_fetch object=$parentFieldArray key=$col}
{if isset($vardef.allowEapm) && $vardef.allowEapm && isset($parentFieldArray.DOC_TYPE) }
{capture name=imageNameCapture assign=imageName}
{sugar_fetch object=$parentFieldArray key=DOC_TYPE}_image_inline.png
{/capture}
{capture name=imageURLCapture assign=imageURL}
{sugar_getimagepath file=$imageName}
{/capture}
{if strlen($imageURL)>1}{sugar_getimage name=$imageName alt=$imageName other_attributes='border="0" '}{/if}
{/if}
</a>