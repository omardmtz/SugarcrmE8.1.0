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

class PdfManagerHooks
{
    /**
     * Fixes TinyMCE converting & to &amp;, protecting smarty templates.
     *
     * @param PdfManager $bean
     * @param [type] $event
     * @param array $params
     * @return void
     */
    public function fixAmp(PdfManager $bean, $event, $params = array())
    {
        $bean->body_html = str_replace('&amp;', '&', $bean->body_html);
    }
}
