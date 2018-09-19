<?php
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
//implements Smarty 3 style {nocache}{/nocache} block to prevent caching of a section of a template.
//remove this upon upgrade to Smarty 3
function smarty_block_nocache($param, $content, &$smarty) {
   return $content;
} 
?>