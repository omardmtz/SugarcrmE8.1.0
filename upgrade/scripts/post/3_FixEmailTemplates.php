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
 * Upgrade email templates in database.
 *
 * CRYS-457
 *
 * @property array $config Loaded $sugar_config
 * @property string $from_version Version being upgraded
 */
class SugarUpgradeFixEmailTemplates extends UpgradeScript
{
    public $order = 5000;
    public $type = self::UPGRADE_DB;
    public $version = '7.5.0';

    public function run()
    {
        if (!version_compare($this->from_version, '7.0.0', '<')) {
            return;
        }

        $this->handleGeneratePasswordTemplate();
        $this->handleLostPasswordTemplate();
    }

    /**
     * Upgrade lost password template.
     */
    protected function handleLostPasswordTemplate()
    {
        $id = $this->config['passwordsetting']['lostpasswordtmpl'];
        if (empty($id)) {
            return;
        }
        $bodyHtml = $this->getEmailTemplateBodyHtml($id);
        $find = '<p>$config_site_url</p>';

        if (!empty($bodyHtml) && false !== strpos($bodyHtml, $find)) {
            $replace = '<p><a title="$config_site_url" href="$config_site_url">$config_site_url</a></p>';
            $bodyHtml = str_replace($find, $replace, $bodyHtml);

            $this->updateEmailTemplateBodyHtml($id, $bodyHtml);
        }
    }

    /**
     * Upgrade generate password template.
     */
    protected function handleGeneratePasswordTemplate()
    {
        $id = $this->config['passwordsetting']['generatepasswordtmpl'];
        if (empty($id)) {
            return;
        }
        $bodyHtml = $this->getEmailTemplateBodyHtml($id);
        $find = '<p> $contact_user_link_guid </p>';

        if (!empty($bodyHtml) && false !== strpos($bodyHtml, $find)) {
            $replace = '<p> <a title="$contact_user_link_guid" href="$contact_user_link_guid">$contact_user_link_guid</a> </p>';
            $bodyHtml = str_replace($find, $replace, $bodyHtml);

            $this->updateEmailTemplateBodyHtml($id, $bodyHtml);
        }
    }

    /**
     * Gets the html body from the email template.
     *
     * @param string $id
     * @return string|bool False on failure.
     */
    protected function getEmailTemplateBodyHtml($id)
    {
        $emailTemplate = BeanFactory::retrieveBean('EmailTemplates', $id);
        if ($emailTemplate) {
            return $emailTemplate->body_html;
        }
        return false;
    }

    /**
     * Update html body in the email template.
     *
     * @param string $id
     * @param string $bodyHtml
     * @return bool
     */
    protected function updateEmailTemplateBodyHtml($id, $bodyHtml)
    {
        $emailTemplate = BeanFactory::retrieveBean('EmailTemplates', $id);
        if ($emailTemplate) {
            $emailTemplate->body_html = $bodyHtml;
            return (bool) $emailTemplate->save();
        }
        return false;
    }
}
