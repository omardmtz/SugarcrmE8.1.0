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

class KBContentTemplate extends SugarBean
{
    public $table_name = 'kbcontent_templates';
    public $object_name = 'KBContentTemplate';
    public $new_schema = true;
    public $module_dir = 'KBContentTemplates';

    /**
     * {@inheritdoc}
     * Check KBContents create.
     **/
    public function save($check_notify = false)
    {
        if (!SugarACL::checkAccess('KBContents', 'create')) {
            return;
        }
        return parent::save($check_notify);
    }

    /**
     * {@inheritdoc}
     **/
    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return false;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function get_summary_text()
    {
        return $this->name;
    }
}
