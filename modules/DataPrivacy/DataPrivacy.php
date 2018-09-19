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

use Doctrine\DBAL\DBALException;
use Sugarcrm\Sugarcrm\DataPrivacy\Erasure\FieldList;
use Sugarcrm\Sugarcrm\DependencyInjection\Container;
use Sugarcrm\Sugarcrm\Security\Context;

/**
 *  Class for data privacy.
 */
class DataPrivacy extends Issue
{
    public $table_name = 'data_privacy';
    public $module_name = 'DataPrivacy';
    public $module_dir = 'DataPrivacy';
    public $object_name = 'DataPrivacy';

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $source;

    /**
     * @var string
     */
    public $fields_to_erase;

    /**
     * @var string
     */
    public $status;

    /**
     * {@inheritDoc}
     * @see SugarBean::bean_implements()
     */
    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     * @see SugarBean::save()
     */
    public function save($check_notify = false)
    {
        $isAdmin = $GLOBALS['current_user']->isAdminForModule($this->module_name);
        $oldStatus = $this->fetched_row['status'] ?? null;

        if (!$this->isStatusChangeAllowed($isAdmin, $oldStatus, $this->status)) {
            throw new SugarApiExceptionNotAuthorized("changing 'status' is not allowed in " . $this->module_name);
        }

        //check the value defined in dataprivacy_status_dom
        if ($this->type === 'Request to Erase Information'
            && isset($this->fetched_row['status'])
            && $this->fetched_row['status'] !== 'Closed'
            && $this->status === 'Closed'
        ) {
            $context = Container::getInstance()->get(Context::class);
            $context->setAttribute('dp_request_id', $this->id);

            try {
                $this->completeErasure();
            } finally {
                $context->unsetAttribute('dp_request_id');
            }
        }

        return parent::save($check_notify);
    }

    /**
     * Should be called only by the after_relationship_delete logic hook when a related record is removed
     * @param string $link
     * @param string $id
     */
    public function relatedRecordRemoved(string $link, string $id)
    {
        if (!empty($this->fields_to_erase)) {
            $data = json_decode($this->fields_to_erase, true);
            if (isset($data[$link][$id])) {
                unset($data[$link][$id]);
                $this->fields_to_erase = json_encode($data);
                $this->save();
            }
        }
    }

    /**
     * Erase the fields for the current DPR record
     * @throws DBALException
     */
    private function completeErasure()
    {
        $data = empty($this->fields_to_erase) ? [] : json_decode($this->fields_to_erase, true);

        foreach ($data as $link => $moduleData) {
            $moduleName = null;
            if ($this->load_relationship($link) && $this->$link) {
                $moduleName = $this->$link->getRelatedModuleName();
            }
            if (empty($moduleName)) {
                continue;
            }
            foreach ($moduleData as $id => $fields) {
                if (empty($fields)) {
                    continue;
                }

                $bean = BeanFactory::retrieveBean($moduleName, $id);
                if (!$bean) {
                    continue;
                }

                $list = FieldList::fromArray($fields);
                $bean->erase($list, false);
            }
        }
    }

    /**
     * check if status change is allowed
     * @param bool $isAdmin
     * @param null|string $oldStatus
     * @param string $newStatus
     * @return bool
     */
    protected function isStatusChangeAllowed(bool $isAdmin, ?string $oldStatus, string $newStatus) : bool
    {
        if ($isAdmin) {
            if ((!empty($oldStatus) && $oldStatus !== 'Open' && $oldStatus != $newStatus)
                || (empty($oldStatus) && $newStatus != 'Open')) {
                return false;
            }
            return true;
        }

        if ((!empty($oldStatus) && $oldStatus != $newStatus)
            || (empty($oldStatus) && $newStatus !== 'Open')) {
            return false;
        }

        return true;
    }
}
