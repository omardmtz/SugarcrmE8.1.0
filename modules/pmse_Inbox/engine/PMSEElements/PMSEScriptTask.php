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


use Sugarcrm\Sugarcrm\ProcessManager;

class PMSEScriptTask extends PMSEActivity
{
    /**
     * @var $this |bool|null|SugarBean|User|void
     */
    protected $currentUser;

    /**
     * @var TimeDate
     */
    protected $timeDate;

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        global $current_user;
        $this->currentUser = $current_user;
        $this->timeDate = TimeDate::getInstance();
        parent::__construct();
    }

    /**
     * @param mixed $currentUser
     * @codeCoverageIgnore
     */
    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * @return mixed
     * @codeCoverageIgnore
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * @param $value
     * @param $bean
     * @return string|type
     */
    public function getCustomUser($value, $bean)
    {
        switch ($value) {
            case 'currentuser':
                $newValue = $this->userAssignmentHandler->getCurrentUserId();
                break;
            case 'supervisor':
                $newValue = $this->userAssignmentHandler->getSupervisorId($this->currentUser->id);
                break;
            case 'owner':
                $newValue = $this->userAssignmentHandler->getRecordOwnerId($bean->id, $bean->module_dir);
                break;
            case 'created_by':
                $newValue = $bean->$value;
                break;
            case 'modified_user_id':
                if (isset($bean->dataChanges[$value])) {
                    $newValue = $bean->dataChanges[$value]['before'];
                } else {
                    $newValue = !empty($bean->$value) ? $bean->$value : '';
                }
                break;
            default:
                $newValue = $value;
                break;
        }
        return $newValue;
    }

    /**
     * @param $field
     * @param $value
     * @return string
     * @throws Exception
     */
    public function getDBDate($field, $value)
    {
        $result = $value;
        switch ($field->type) {
            case 'Date':
                $date = $this->timeDate->fromIsoDate($value);
                if (empty($date)) {
                    $date = $this->timeDate->fromIso($value);
                }
                if (!($date instanceof SugarDateTime)) {
                    throw ProcessManager\Factory::getException(
                        'DateTime',
                        "Cannot convert '{$value}' to SugarDateTime.",
                        1
                    );
                }
                $result = $date->asDbDate();
                break;
            case 'Datetime':
                $date = $this->timeDate->fromIso($value);
                if (empty($date)) {
                    $date = $this->timeDate->fromString($value);
                }
                if (!($date instanceof SugarDateTime)) {
                    throw ProcessManager\Factory::getException(
                        'DateTime',
                        "Cannot convert '{$value}' to SugarDateTime.",
                        1
                    );
                }
                $result = $date->asDb();
                break;
        }
        return $result;
    }
}
