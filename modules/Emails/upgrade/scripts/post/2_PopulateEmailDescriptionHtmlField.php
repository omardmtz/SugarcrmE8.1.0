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
 * Perform Updates to emails_text description and description_html Fields as needed
 * to ensure Email content is always present for display in Email Record View
 */
class SugarUpgradePopulateEmailDescriptionHtmlField extends UpgradeDBScript
{
    public $order = 2200;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        // Only run this if upgrading from versions less than 7.11
        if (version_compare($this->from_version, '7.11.0', '>=')) {
            return;
        }

        // These functions are Order-Dependent
        $this->setDescriptionHtmlFromDescriptionIfEmpty();
        $this->setDescriptionToNullForDraftEmails();
    }

    /**
     * For ALL Text-Only Emails (description_html is NULL or Blank):
     *    Set the description_html field to the description field value replacing line feed
     *    characters ('\r\n', '\n')  with html breaks <br />
     */
    protected function setDescriptionHtmlFromDescriptionIfEmpty()
    {
        $this->log('Set description_html field to description field value with <br /> replacing CRLF characters');

        $sql = "UPDATE emails_text SET description_html = REPLACE(REPLACE(description, '\r', ''), '\n', '<br />')" .
               ' WHERE ' . $this->db->getEmptyFieldSQL('description_html') .
               ' AND ' . $this->db->getNotEmptyFieldSQL('description');
        $this->executeUpdate($sql);
    }

    /**
     * For ALL Draft Emails where description_html is Not (NULL or Blank):
     *    Set the description to Null
     */
    protected function setDescriptionToNullForDraftEmails()
    {
        $this->log('Set description field to NULL for all Draft emails');

        $sql = 'UPDATE emails_text SET description=NULL' .
               ' WHERE ' . $this->db->getNotEmptyFieldSQL('description') .
               " AND email_id IN (SELECT id from emails e WHERE e.state='Draft')";
        $this->executeUpdate($sql);
    }
}
