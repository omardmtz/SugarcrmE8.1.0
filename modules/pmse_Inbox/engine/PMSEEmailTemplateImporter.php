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
 * Imports a record of encrypted file.
 *
 * This class extends the class ADAMImporter to import
 * records to an encrypted file BPMEmailTemplate table.
 * @package PMSE
 * @codeCoverageIgnore
 */
class PMSEEmailTemplateImporter extends PMSEImporter
{

    public function __construct()
    {
        $this->bean = BeanFactory::newBean('pmse_Emails_Templates'); //new BpmEmailTemplate();
        $this->name = 'name';
        $this->id = 'id';
        $this->extension = 'pet';
        $this->module = 'base_module';
    }
}
