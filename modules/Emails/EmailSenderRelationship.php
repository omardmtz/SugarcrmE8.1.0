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

require_once 'modules/Emails/EmailRecipientRelationship.php';

/**
 * Class EmailSenderRelationship
 *
 * Represents the relationship between Emails and modules that can be senders of an email. In particular, each email can
 * have one sender. The EmailParticipants module is used to enable a three-way relationship, with EmailAddresses being
 * the third prong. Emails should be on the left side of the relationship, while EmailParticipants should be on the
 * right side.
 */
class EmailSenderRelationship extends EmailRecipientRelationship
{
    /**
     * EmailSenderRelationship extends from a one-to-many relationship implementation for the purpose of code reuse.
     * However, each email can only have one sender, so the relationship is truly a one-to-one relationship in concept
     * and implementation.
     *
     * {@inheritdoc}
     */
    public $type = 'one-to-one';

    /**
     * Even when an email is a draft, the sender can be replaced by another sender also representing the current user,
     * but with additional data, like a new or different email address. Replacing the sender requires removing the
     * existing EmailParticipants bean. Removing the sender is not allowed for drafts. So, this property is used to
     * indicate that it is safe to remove the sender, even for drafts. It must always be reset to `false` after the
     * remove action has occurred, in success or failure, so that external callers of
     * {@link EmailSenderRelationship::remove()} cannot remove a draft's sender, intentionally or unintentionally.
     *
     * @var bool
     */
    private $allowRemove = false;

    /**
     * If an email already has a sender, then the sender is removed before the new one is added.
     *
     * When an email address is being linked and the existing row has the same email address:
     *
     * - The existing row is replaced by the new row if the new row represents a record.
     * - The new row is not added if it represents an email address and the existing row represents a record.
     *
     * {@inheritdoc}
     */
    public function add($lhs, $rhs, $additionalFields = array())
    {
        if ($lhs->isArchived()) {
            throw new SugarApiExceptionNotAuthorized("Cannot add to {$this->name} when the email is archived");
        }

        if ($lhs->state === Email::STATE_DRAFT) {
            if (empty($rhs->parent_type) && empty($rhs->parent_id)) {
                // Default the parent to the current user.
                $rhs->parent_type = $GLOBALS['current_user']->getModuleName();
                $rhs->parent_id = $GLOBALS['current_user']->id;
            }

            $isRhsParentCurrentUser = $rhs->parent_type === $GLOBALS['current_user']->getModuleName() &&
                $rhs->parent_id === $GLOBALS['current_user']->id;

            if (!$isRhsParentCurrentUser) {
                throw new SugarApiExceptionNotAuthorized('Only the current user can be added as the sender of a draft');
            }
        }

        $this->fixParentModule($rhs);
        $this->assertParentModule($rhs);
        $this->setEmailAddress($lhs, $rhs);

        if (empty($lhs->{$this->lhsLink}) && !$lhs->load_relationship($this->lhsLink)) {
            $lhsClass = get_class($lhs);
            $GLOBALS['log']->fatal("could not load LHS {$this->lhsLink} in {$lhsClass}");
            return false;
        }

        $currentRows = $lhs->{$this->lhsLink}->getBeans();

        // There can only be one. But just in case something weird happens, we'll iterate through the rows.
        foreach ($currentRows as $currentRow) {
            if ($currentRow->id === $rhs->id) {
                // They are the same. Let it be added again.
                continue;
            }

            // Equality is checked loosely because null and empty strings need to be considered the same.
            $doParentsMatch = $currentRow->parent_type == $rhs->parent_type &&
                $currentRow->parent_id == $rhs->parent_id;
            $doEmailAddressesMatch = $currentRow->email_address_id == $rhs->email_address_id;

            if (!$doEmailAddressesMatch) {
                // The email_address_id's do not collide. Consider it a new sender.
                $this->allowRemove = true;

                if (!$this->remove($lhs, $currentRow)) {
                    $this->allowRemove = false;
                    return false;
                }

                $this->allowRemove = false;
                continue;
            }

            if (empty($rhs->parent_type) || empty($rhs->parent_id)) {
                // We already have this email address stored, so keep the current row in case it includes a parent bean.
                return false;
            }

            if ($doParentsMatch) {
                // There is no reason to change the sender since the parents and email addresses are the same.
                return false;
            }

            // The sender has a different parent. Replace the sender.
            $this->allowRemove = true;

            if (!$this->remove($lhs, $currentRow)) {
                $this->allowRemove = false;
                return false;
            }

            $this->allowRemove = false;
        }

        return parent::add($lhs, $rhs, $additionalFields);
    }

    /**
     * $rhs is deleted after unlinking because it is no longer needed for anything. Resaves the emails_text data for
     * $lhs after changing its participants.
     *
     * {@inheritdoc}
     */
    public function remove($lhs, $rhs)
    {
        if (!$this->allowRemove &&
            $lhs->isUpdate() &&
            $lhs->state === Email::STATE_DRAFT &&
            !$rhs->deleted &&
            !$lhs->deleted
        ) {
            throw new SugarApiExceptionNotAuthorized("Cannot remove from {$this->name} when the email is a draft");
        }

        return parent::remove($lhs, $rhs);
    }

    /**
     * {@inheritdoc}
     */
    public function getType($side)
    {
        return REL_TYPE_ONE;
    }

    /**
     * For drafts, `email_address_id` comes from the chosen outbound email configuration. Set the email's
     * `outbound_email_id` field to the ID of a valid {@link OutboundEmail} configuration.
     *
     * {@inheritdoc}
     * @throws SugarApiExceptionInvalidParameter
     */
    protected function setEmailAddress(SugarBean $lhs, SugarBean $rhs)
    {
        $error = "The sender's email address must come from the draft's outbound email configuration.";
        $noConfig = "{$error} Set the email's outbound_email_id field to choose a configuration for sending the email.";
        $noMatch = "{$error} It cannot be overridden.";
        $failure = "{$error}. Failed to load the draft's outbound email configuration.";

        if ($lhs->state === Email::STATE_DRAFT) {
            if (empty($lhs->outbound_email_id)) {
                if (!empty($rhs->email_address_id)) {
                    throw new SugarApiExceptionInvalidParameter($noConfig);
                }
            } else {
                $oe = BeanFactory::retrieveBean('OutboundEmail', $lhs->outbound_email_id);

                if ($oe) {
                    if (empty($rhs->email_address_id)) {
                        $rhs->email_address_id = $oe->email_address_id;
                    } elseif ($rhs->email_address_id !== $oe->email_address_id) {
                        throw new SugarApiExceptionInvalidParameter($noMatch);
                    }
                } elseif (!empty($rhs->email_address_id)) {
                    throw new SugarApiExceptionInvalidParameter($failure);
                }
            }
        }

        parent::setEmailAddress($lhs, $rhs);
    }
}
