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


/**
 * Exports a record of table EmailTemplate
 *
 * This class extends the class ADAMExporter to export a record
 * from the table BPMEmailTemplate to transport it from one instance to another.
 * @package PMSE
 * @codeCoverageIgnore
 */
class PMSEEmailTemplateExporter extends PMSEExporter
{

    public function __construct()
    {
        $this->bean = BeanFactory::newBean('pmse_Emails_Templates'); //new BpmEmailTemplate();
        $this->uid = 'id';
        $this->name = 'name';
        $this->extension = 'pet';
    }
}
