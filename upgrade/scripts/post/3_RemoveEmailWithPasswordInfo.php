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
 * Remove email with password info.
 *
 */
class SugarUpgradeRemoveEmailWithPasswordInfo extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_DB;
    public $version = '7.7.2';

    public function run()
    {
        // only handles version before 7.7.2
        if (version_compare($this->from_version, '7.7.2.0', '>=')) {
            return;
        }

        $this->removeEmailWithPasswordInfo();
    }

    /**
     * remove email with password infos
     */
    protected function removeEmailWithPasswordInfo()
    {
        $pwdsettings = array('lostpasswordtmpl', 'generatepasswordtmpl');
        $subjects = array();

        foreach ($pwdsettings as $setting) {
            $subject = $this->getEmailTemplateSubject($setting);
            if (!empty($subject)) {
                $subjects[] = $subject;
            }
        }

        $whereClause = " WHERE team_id = '1' AND type = 'archived' " .
            " AND parent_type = 'User' AND status = 'sent' AND " .
            " name IN " . '(\'' . implode("', '", $subjects) . '\')';

        $tableName = BeanFactory::newBean('Emails')->table_name;

        // select all the email beans matching password email
        $selectQuery = "SELECT id FROM " . $tableName . $whereClause;
        $hasPasswordEmail = false;

        // to mark all password email beans as deleted, which will force to update elastic corresponding records
        $selectResult = $this->db->query($selectQuery);
        while ($row = $this->db->fetchByAssoc($selectResult)) {
            $hasPasswordEmail = true;
            $emailBean = BeanFactory::getBean('Emails', $row['id']);
            $emailBean->deleted = 1;
            $emailBean->save();
        }

        if ($hasPasswordEmail) {
            // delete those emails from DB
            $deleteQuery = "DELETE FROM " . $tableName . $whereClause;
            $this->db->query($deleteQuery);
        }
    }

    /**
     * to get Email subject from EmailTemplates
     * @param $pwdSetting
     * @return mixed|null
     */
    protected function getEmailTemplateSubject($pwdSetting)
    {
        $id = $this->config['passwordsetting'][$pwdSetting];
        if (!empty($id)) {
            $emailTemplate = BeanFactory::newBean('EmailTemplates');
            $emailTemplate->disable_row_level_security = true;

            if ($emailTemplate->retrieve($id) != '') {
                if (!empty($emailTemplate->subject)) {
                    return $emailTemplate->subject;
                }
            }
        }
        return null;
    }
}
