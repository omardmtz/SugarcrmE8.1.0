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

class SugarUpgradeMigrateEmailAttachments extends UpgradeDBScript
{
    public $order = 2200;

    /**
     * {@inheritdoc}
     *
     * It is not always the case that Notes records with Emails records as parents are email attachments. Only Notes
     * records with Emails records as parents and a file are email attachments. The relationship between a Notes record
     * and an Emails record is a special one. To consummate this relationship -- such that it cannot be lost when the
     * Note's parent reference is changed -- this upgrade script migrates parent_type and parent_id to email_type and
     * email_id, respectively, for attachments. The parent_type and parent_id fields are nullified because an attachment
     * is related to an email through the email_type and email_id fields, as opposed to through the parent reference.
     *
     * This upgrade script only runs when upgrading from a version prior to 7.10.
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.10', '<')) {
            $now = TimeDate::getInstance()->nowDb();
            $sql = 'UPDATE notes SET ' .
                // Move the parent_type and parent_id values to email_type and email_id, respectively.
                'email_type=parent_type, email_id=parent_id, ' .
                // Clear the parent references.
                'parent_type=NULL, parent_id=NULL, ' .
                // Update the modified data.
                "date_modified='{$now}', modified_user_id=1 " .
                // It's only an attachment if the parent is an Emails or EmailTemplates record.
                "WHERE parent_type IN ('Emails', 'EmailTemplates') AND " .
                // parent_id is not empty.
                $this->db->getNotEmptyFieldSQL('parent_id') .
                "AND " .
                // filename is not empty.
                $this->db->getNotEmptyFieldSQL('filename') .
                "AND " .
                // file_mime_type is not empty.
                $this->db->getNotEmptyFieldSQL('file_mime_type');
            $this->executeUpdate($sql);
        }
    }
}
