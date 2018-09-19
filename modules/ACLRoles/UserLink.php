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
 * Class UserLink
 *
 * Class representing link to users for ACLRole bean.
 */
class UserLink extends Link2
{
    /**
     * Clear acl cache when adding acl_roles_users relationships
     * {@inheritDoc}
     * @see Link2::add()
     */
    public function add($rel_keys, $additional_values = array())
    {
        // clear acl cache
        if ($this->focus instanceof ACLRole) {
            if (!is_array($rel_keys)) {
                $rel_keys = array($rel_keys);
            }
            foreach ($rel_keys as $rel_key) {
                if ($rel_key instanceof User) {
                    AclCache::getInstance()->clear($rel_key->id);
                } else {
                    AclCache::getInstance()->clear($rel_key);
                }
            }
        } elseif ($this->focus instanceof User) {
            AclCache::getInstance()->clear($this->focus->id);
        }
        return parent::add($rel_keys, $additional_values);
    }

    /**
     * Clear acl cache when deleting a acl_roles_users relationship
     * {@inheritDoc}
     * @see Link2::delete()
     */
    public function delete($id, $related_id = '')
    {
        if (empty($related_id)) {
            // clear acl cache for all users in this role
            if (empty($this->focus->id)) {
                $this->focus = BeanFactory::getBean($this->focus->module_name, $id);
            }
            $query = $this->getQuery();
            if (!empty($query)) {
                $db = DBManagerFactory::getInstance();
                $result = $db->query($query);
                while ($row = $db->fetchByAssoc($result, false)) {
                    AclCache::getInstance()->clear($row['id']);
                }
            }
            return parent::delete($id, $related_id);
        }

        $userBean = $this->getUserBean($related_id);
        if ($userBean === null) {
            return parent::delete($id, $related_id);
        }
        // Just updating user date_modified property.
        // We need to do this to update user hash and as result invalidate HTTP ETag.
        $userBean->setModifiedDate(TimeDate::getInstance()->nowDb());
        $userBean->save();
        // clear acl acche
        AclCache::getInstance()->clear($related_id);
        return parent::delete($id, $related_id);
    }

    /**
     * Returns user bean instance.
     *
     * @param string $id
     * @return SugarBean|null
     */
    protected function getUserBean($id)
    {
        return BeanFactory::retrieveBean('Users', $id);
    }
}
