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

class SugarUpgradeSynchronizeTasksRelationshipToEmailsBeansTable extends UpgradeScript
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
     * Prior to 7.10, the relationship between Emails and Tasks did not include a requisite link on both sides of the
     * relationship because the Tasks module did not have a link back to Emails using the `emails_tasks_rel`
     * relationship. The `emails` link was added to Tasks in 7.10 to resolve this issue.
     *
     * Some emails' parent records might be tasks. Prior to 7.10, these parent relationships were failing to be
     * synchronized to the emails_beans table. This upgrade script finds relationships that are not synchronized to
     * emails_beans and repairs it.
     *
     * This upgrade script only runs when upgrading from a version prior to 8.0.
     */
    public function run()
    {
        if (!version_compare($this->from_version, '8.0', '<')) {
            $this->log("from_version ({$this->from_version}) is not less than 8.0");
            return;
        }

        $emails = $this->getEmails();

        foreach ($emails as $email) {
            $this->linkEmailToLink($email);
        }
    }

    /**
     * Returns an array of all Emails beans that have a parent whose module is Tasks.
     *
     * @return array
     */
    protected function getEmails()
    {
        $email = BeanFactory::newBean('Emails');

        $q = new SugarQuery();
        $q->from($email);
        $q->where()->equals('parent_type', 'Tasks');
        $emails = $email->fetchFromQuery($q);

        $ids = array_keys($emails);
        $this->log('Found ' . count($emails) . ' email(s) with parent_type=Tasks: ' . implode(', ', $ids));

        return $emails;
    }

    /**
     * Synchronize the Emails parent relationship to the emails_beans table for the tasks link.
     *
     * @param Email $email
     */
    protected function linkEmailToLink(Email $email)
    {
        $this->log("Linking Emails/{$email->id} to emails on Tasks/{$email->parent_id}");
        $task = BeanFactory::retrieveBean('Tasks', $email->parent_id);

        if (!$task) {
            $this->log("Could not find Tasks/{$email->parent_id}");
            return;
        }

        if ($email->load_relationship('tasks')) {
            if ($email->tasks->add($task)) {
                $this->log('Successfully linked the records');
            } else {
                $this->log('Failed to link the records');
            }
        } else {
            $this->log('Could not load the relationship named tasks on Emails');
        }
    }
}
