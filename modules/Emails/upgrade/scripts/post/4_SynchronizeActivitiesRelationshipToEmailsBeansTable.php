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

class SugarUpgradeSynchronizeActivitiesRelationshipToEmailsBeansTable extends UpgradeScript
{
    /**
     * This upgrade script updates data in the database by saving links between modules.
     *
     * {@inheritdoc}
     */
    public $type = self::UPGRADE_DB;

    /**
     * This upgrade script should run after all new modules are installed so that we are certain we have the most
     * up-to-date list of modules.
     *
     * {@inheritdoc}
     * @see SugarUpgradeNewModules::$order SugarUpgradeNewModules runs with 4100.
     */
    public $order = 4101;

    /**
     * {@inheritdoc}
     *
     * Any modules with the Activities relationship might have beans that are parents of emails. These parent
     * relationships must be synchronized to the emails_beans table. This upgrade script finds relationships that are
     * not synchronized to emails_beans and repairs it.
     *
     * This upgrade script only runs when upgrading from a version prior to 8.0.
     */
    public function run()
    {
        if (!version_compare($this->from_version, '8.0', '<')) {
            $this->log("from_version ({$this->from_version}) is not less than 8.0");
            return;
        }

        $modulesWithActivitiesRelationship = $this->getModulesWithActivitiesRelationship();
        $emails = $this->getEmails(array_keys($modulesWithActivitiesRelationship));

        foreach ($emails as $email) {
            $this->linkEmailToParent($email, $modulesWithActivitiesRelationship[$email->parent_type]);
        }
    }

    /**
     * Returns an array whose keys are the names of all modules with an Activities relationship. The values under each
     * key is the name of the link field used for the Activities relationship between the module and the Emails module.
     *
     * The link name <module>_activities_emails will be used when the Activities relationship is created using Module
     * Builder, before the module is deployed. The link name <module>_activities_1_emails will be used when the
     * Activities relationship is created using Studio.
     *
     * @return array
     */
    protected function getModulesWithActivitiesRelationship()
    {
        $modules = [];
        $email = BeanFactory::newBean('Emails');

        foreach (array_keys($GLOBALS['beanList']) as $module) {
            $moduleLowercase = strtolower($module);
            $activitiesLinkNames = [
                // Studio-generated link name.
                "{$moduleLowercase}_activities_1_emails",
                // Module Builder-generated link name.
                "{$moduleLowercase}_activities_emails",
            ];

            $bean = BeanFactory::newBean($module);
            $linkName = $bean instanceof SugarBean ? $email->findEmailsLink($bean) : false;

            // We only care about activities links. Presumably, all other links have been persisted as expected.
            if ($linkName && in_array($linkName, $activitiesLinkNames)) {
                $this->log("{$module} has an activities link named {$linkName}");
                $modules[$module] = $linkName;
            }
        }

        return $modules;
    }

    /**
     * Returns an array of all Emails beans that have a parent whose module is found in $parentModules.
     *
     * @param array $parentModules
     * @return array
     */
    protected function getEmails(array $parentModules)
    {
        $emails = [];
        $email = BeanFactory::newBean('Emails');

        foreach ($parentModules as $module) {
            $q = new SugarQuery();
            $q->from($email);
            $q->where()->equals('parent_type', $module);
            $beans = $email->fetchFromQuery($q);
            $emails = $emails + $beans;

            $ids = array_keys($beans);
            $this->log('Found ' . count($beans) . " email(s) with parent_type={$module}: " . implode(', ', $ids));
        }

        return $emails;
    }

    /**
     * Synchronize the Emails parent relationship to the emails_beans table for the Activities link.
     *
     * @param Email $email
     * @param string $linkName
     */
    protected function linkEmailToParent(Email $email, $linkName)
    {
        $this->log("Linking Emails/{$email->id} to {$linkName} on {$email->parent_type}/{$email->parent_id}");
        $bean = BeanFactory::retrieveBean($email->parent_type, $email->parent_id);

        if (!$bean) {
            $this->log("Could not find {$email->parent_type}/{$email->parent_id}");
            return;
        }

        if ($bean->load_relationship($linkName)) {
            if ($bean->$linkName->add($email)) {
                $this->log('Successfully linked the records');
            } else {
                $this->log('Failed to link the records');
            }
        } else {
            $this->log("Could not load the relationship named {$linkName} on {$email->parent_type}");
        }
    }
}
