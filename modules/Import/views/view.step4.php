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
/*********************************************************************************

 * Description: view handler for step 4 of the import process
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/


class ImportViewStep4 extends ImportView
{

    /**
     * @see SugarView::display()
     */
 	public function display()
    {
        global $mod_strings, $sugar_config;

        // Check to be sure we are getting an import file that is in the right place
        $uploadFile = "upload://".basename($_REQUEST['tmp_file']);
        if(!file_exists($uploadFile)) {
            trigger_error($mod_strings['LBL_CANNOT_OPEN'],E_USER_ERROR);
        }

        $uploadFiles = explode("-", $uploadFile);
        $currentPart = end($uploadFiles);

        $delimiter = $this->getDelimiterValue();
        // Open the import file
        $importSource = new ImportFile(
            $uploadFile,
            $delimiter,
            html_entity_decode($_REQUEST['custom_enclosure'], ENT_QUOTES),
            true,
            true,
            $sugar_config['import_max_records_per_file'] * $currentPart
        );

        //Ensure we have a valid file.
        if ( !$importSource->fileExists() )
            trigger_error($mod_strings['LBL_CANNOT_OPEN'],E_USER_ERROR);

        if (!ImportCacheFiles::ensureWritable())
        {
            trigger_error($mod_strings['LBL_ERROR_IMPORT_CACHE_NOT_WRITABLE'], E_USER_ERROR);
        }

        $importer = new Importer($importSource, $this->bean);
        $importer->import();
    }
}
