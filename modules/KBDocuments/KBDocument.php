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

class KBDocument extends SugarBean
{
    public $new_schema = true;
    public $module_dir = 'KBDocuments';
    public $object_name = 'KBDocument';
    public $table_name = 'kbdocuments';

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return false;
        }
        return false;
    }
}
