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


class CategoriesApiHelper extends SugarBeanApiHelper
{
    /**
     * {@inheritdoc}
     */
    public function formatForApi(SugarBean $bean, array $fieldList = array(), array $options = array())
    {
        $action = (!empty($options['action']) && $options['action'] == 'list') ? 'list' : 'view';
        $hasAccess = empty($bean->deleted) && $bean->ACLAccess($action);
        $data = parent::formatForApi($bean, $fieldList, $options);
        if (!$hasAccess) {
            if ($this->api->action == 'view') {
                $data['name'] = $bean->name;
                $data['_acl'] = $this->getBeanAcl($bean, $fieldList);
            }
        }
        return $data;
    }
}
