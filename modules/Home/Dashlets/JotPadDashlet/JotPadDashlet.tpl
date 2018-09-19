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


<div id='jotpad_{$id}' ondblclick='JotPad.edit(this, "{$id}")' style='overflow: auto; width: 100%; height: {$height}px; border: 1px #ddd solid'>{$savedText}</div>
<textarea id='jotpad_textarea_{$id}' rows="5" onblur='JotPad.blur(this, "{$id}")' style='display: none; width: 100%; height: {$height}px; overflow: auto'></textarea>