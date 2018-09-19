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
class KBArticle extends SugarBean
{
    public $new_schema = true;
    public $module_dir = 'KBArticles';
    public $object_name = 'KBArticle';
    public $table_name = 'kbarticles';

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return false;
        }
        return false;
    }
    public function get_summary_text()
    {
        return $this->name;
    }
}
