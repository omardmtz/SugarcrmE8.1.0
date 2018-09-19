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

class EmbeddedFile extends SugarBean
{

    public $table_name = 'embedded_files';
    public $object_name = 'EmbeddedFile';
    public $new_schema = true;
    public $module_dir = 'EmbeddedFiles';

    /**
     * {@inheritDoc}
     *
     * Remove the linked file.
     * @param string $id Record ID.
     */
    public function mark_deleted($id)
    {
        $file = "upload://$id";
        if (file_exists($file) && !unlink($file)) {
            $GLOBALS['log']->error("Could not unlink() the file: $file.");
        }
        parent::mark_deleted($id);
    }
}
