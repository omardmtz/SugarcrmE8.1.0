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
 * Class EmailParticipant
 *
 * EmailParticipants beans should not be saved directly. They are an entity that is only used in relationships with
 * Emails. Add or remove from those relationships to make changes to the participants of an email. This enables the
 * relationship strategies to enforce certain behaviors regarding the sender and recipients of an email.
 * EmailParticipants beans will be saved via {@link SugarRelationship::resaveRelatedBeans()}. The only exception is
 * {@link RelateRecordApi::createRelatedRecord}, whose flow results in the saving an EmailParticipants bean, but does so
 * such that the beans are linked during the save operation, allowing for the relationship strategies to enforce their
 * rules.
 */
class EmailParticipant extends SugarBean
{
    /**
     * Email Participants does not use team security.
     *
     * @inheritdoc
     */
    public $disable_team_security = true;

    /**
     * Email Participants data cannot be imported.
     *
     * @inheritdoc
     */
    public $importable = false;

    /**
     * @inheritdoc
     */
    public $module_dir = 'EmailParticipants';

    /**
     * @inheritdoc
     */
    public $new_schema = true;

    /**
     * @inheritdoc
     */
    public $object_name = 'EmailParticipant';

    /**
     * @inheritdoc
     */
    public $table_name = 'emails_email_addr_rel';

    /**
     * The foreign key to the Emails record.
     *
     * @var string
     */
    public $email_id;

    /**
     * The role the participant plays in an email: from, to, cc, or bcc.
     *
     * @var string
     */
    public $address_type;

    /**
     * The foreign key to the Email Addresses record. This field keeps track of the email address that was used for this
     * participant of the email.
     *
     * @var string
     */
    public $email_address_id;

    /**
     * The email address that `email_address_id` references.
     *
     * @var string
     */
    public $email_address;

    /**
     * The module name for the record that participated in the email. This can be any module with an email address --
     * Accounts, Contacts, Leads, Targets, and Users. This field may be empty if the participant was an email address
     * without a reference to a record.
     *
     * @var string
     */
    public $parent_type;

    /**
     * The ID of the record that participated in the email. This can be the ID for a record from any module with an
     * email address -- Accounts, Contacts, Leads, Targets, and Users. This field may be empty if the participant was an
     * email address without a reference to a record.
     *
     * @var string
     */
    public $parent_id;

    /**
     * The name of the record that participated in the email. This can be the name for a record from any module with an
     * email address -- Accounts, Contacts, Leads, Targets, and Users. This field may be empty if the participant was an
     * email address without a reference to a record.
     *
     * @var string
     */
    public $parent_name;

    /**
     * @inheritdoc
     */
    public $date_entered;

    /**
     * @inheritdoc
     */
    public $date_modified;

    /**
     * @inheritdoc
     */
    protected $module_key = 'EmailParticipants';

    /**
     * EmailParticipants beans are added to the resave queue when they are linked to an email because of the way
     * one-to-many bean relationships work. As these beans are saved, the email's sender/recipients are changing, which
     * requires that the email's emails_text data be updated to reflect the changes. We can't add the Emails bean to the
     * resave queue, as well, because that flow would allow for duplicate senders to be saved. In this case -- where
     * saving the Emails bean has the potential to create more relationships -- it's not a good idea to add both the LHS
     * and RHS beans to the resave queue. Instead, we save the emails_text data when the EmailParticipants bean is saved
     * so that we can defer the update until we are certain that the correct data will be saved.
     *
     * @inheritdoc
     */
    public function save($check_notify = false)
    {
        $result = parent::save($check_notify);
        $email = BeanFactory::retrieveBean('Emails', $this->email_id, ['disable_row_level_security' => true]);

        // Don't save emails_text data if the email is being created. The emails_text data will be saved by the Emails
        // bean itself.
        if ($email && $email->isUpdate()) {
            $email->saveEmailText();
        }

        return $result;
    }

    /**
     * When an EmailParticipants bean is deleted, the the email's sender/recipients are changed, which requires that the
     * email's emails_text data be updated to reflect the changes.
     *
     * @inheritdoc
     */
    public function mark_deleted($id)
    {
        $email = BeanFactory::retrieveBean('Emails', $this->email_id, ['disable_row_level_security' => true]);
        parent::mark_deleted($id);

        // Don't save emails_text data if the email is being created. The emails_text data will be saved by the Emails
        // bean itself.
        if ($email && $email->isUpdate()) {
            $email->saveEmailText();
        }
    }

    /**
     * Cannot undelete EmailParticipants beans.
     *
     * @inheritdoc
     */
    public function mark_undeleted($id)
    {
    }

    /**
     * @inheritdoc
     */
    public function getRecordName()
    {
        if (empty($this->parent_name) && empty($this->email_address)) {
            return '';
        } elseif (empty($this->parent_name)) {
            return $this->email_address;
        } elseif (empty($this->email_address)) {
            return $this->parent_name;
        }

        return "{$this->parent_name} <{$this->email_address}>";
    }

    /**
     * @inheritdoc
     */
    public function get_summary_text()
    {
        return $this->getRecordName();
    }
}
