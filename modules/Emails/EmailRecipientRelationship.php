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
 * Class EmailRecipientRelationship
 *
 * Represents the relationship between Emails and modules that can be recipients of an email. In particular, each email
 * can have many recipients, all coming from different modules. The EmailParticipants module is used to enable a
 * three-way relationship, with EmailAddresses being the third prong. Emails should be on the left side of the
 * relationship, while EmailParticipants should be on the right side.
 */
class EmailRecipientRelationship extends One2MBeanRelationship
{
    /**
     * When an email address is being linked and it collides with a row with the same email address:
     *
     * - The existing row is replaced by the new row if the existing row represents an email address.
     * - The existing row is not removed if it represents a record. The new row is added.
     * - The new row is not added if it represents an email address and the existing row represents a record.
     *
     * {@inheritdoc}
     * @throws SugarApiExceptionNotAuthorized
     */
    public function add($lhs, $rhs, $additionalFields = array())
    {
        if ($lhs->isArchived()) {
            throw new SugarApiExceptionNotAuthorized("Cannot add to {$this->name} when the email is archived");
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

        foreach ($currentRows as $currentRow) {
            if ($currentRow->id === $rhs->id) {
                // They are the same. Let it be added again.
                continue;
            }

            // Equality is checked loosely because null and empty strings need to be considered the same.
            $doParentsMatch = $currentRow->parent_type == $rhs->parent_type &&
                $currentRow->parent_id == $rhs->parent_id;
            $doEmailAddressesMatch = $currentRow->email_address_id == $rhs->email_address_id;

            $currentRowHasParent = !empty($currentRow->parent_type) && !empty($currentRow->parent_id);
            $rhsHasParent = !empty($rhs->parent_type) && !empty($rhs->parent_id);

            if ($doParentsMatch) {
                if ($doEmailAddressesMatch) {
                    // The parents and email addresses match, so there's nothing to do.
                    return false;
                }

                if (empty($rhs->email_address_id) && !empty($currentRow->email_address_id)) {
                    // The parents match, so keep the email address that the current row has.
                    return false;
                }

                if (!$rhsHasParent) {
                    // Empty parents, we want both email addresses.
                    continue;
                }

                if (empty($currentRow->email_address_id)) {
                    // We want the email address.
                    if (!$this->remove($lhs, $currentRow)) {
                        return false;
                    }

                    continue;
                }

                // The parents match, but the email addresses do not. We want the new email address.
                if (!$this->remove($lhs, $currentRow)) {
                    return false;
                }
            } else {
                if ($doEmailAddressesMatch) {
                    if (empty($rhs->email_address_id)) {
                        // The email addresses are empty for both, so it's not a collision. Just add the new parent.
                        continue;
                    }

                    if ($currentRowHasParent && $rhsHasParent) {
                        // Keep both rows when the same email address is used for different parents.
                        continue;
                    }

                    if ($rhsHasParent) {
                        // The email addresses match, so take on the new parent.
                        if (!$this->remove($lhs, $currentRow)) {
                            return false;
                        }

                        continue;
                    }

                    if ($currentRowHasParent) {
                        // The new row doesn't have a parent, so preserve the current row.
                        return false;
                    }
                }
            }
        }

        return parent::add($lhs, $rhs, $additionalFields);
    }

    /**
     * $rhs is deleted after unlinking because it is no longer needed for anything. Resaves the emails_text data for
     * $lhs after changing its participants.
     *
     * {@inheritdoc}
     * @throws SugarApiExceptionNotAuthorized
     */
    public function remove($lhs, $rhs)
    {
        if ($lhs->isArchived() && !$rhs->deleted && !$lhs->deleted) {
            throw new SugarApiExceptionNotAuthorized("Cannot remove from {$this->name} when the email is archived");
        }

        $result = parent::remove($lhs, $rhs);

        // Don't just orphan the row; delete it.
        if ($result && !$rhs->deleted) {
            // We're either unlinking or deleting the email, so it is safe to delete the EmailParticipants bean. We
            // don't want to delete it if it is already being deleted because we would end up in an infinite loop.
            if ($lhs->isUpdate()) {
                // We can't rely on EmailParticipant::mark_deleted to save the emails_text data because removing causes
                // the email_id field to lose its value.
                $lhs->saveEmailText();
            }

            $rhs->mark_deleted($rhs->id);
        }

        return $result;
    }

    /**
     * Adds the EmailParticipants bean to the resave queue because not even disabling workflows should prevent an
     * email's sender and recipients from being saved.
     *
     * {@inheritdoc}
     */
    protected function updateFields($lhs, $rhs, $additionalFields)
    {
        parent::updateFields($lhs, $rhs, $additionalFields);

        // Register the EmailParticipants bean in case it has not yet been registered. We need to be absolutely certain
        // the bean being resaved is the one whose fields we just updated. This guarantees that the correct instance of
        // the bean is saved if `SugarRelationship::resaveRelatedBeans` reloads the bean and we happened to have an
        // older instance already in memory.
        BeanFactory::registerBean($rhs);
        SugarRelationship::addToResaveList($rhs);
    }

    /**
     * Changes the bean's `parent_type` to Users if it is Employees and the employee is actually a user.
     *
     * @param SugarBean $bean The EmailParticipants bean.
     */
    protected function fixParentModule(SugarBean $bean)
    {
        if ($bean->parent_type === 'Employees') {
            $parent = $this->getParent($bean);

            if ($parent && $parent->id && !empty($parent->user_name)) {
                $bean->parent_type = 'Users';
            }
        }
    }

    /**
     * Only modules that use the email_address template can be used as parents of an EmailParticipants bean. Users and
     * Employees are the only exceptions to this rule.
     *
     * @param SugarBean $bean The EmailParticipants bean.
     * @throws SugarApiExceptionNotAuthorized
     */
    protected function assertParentModule(SugarBean $bean)
    {
        if (!empty($bean->parent_type)) {
            $objectName = BeanFactory::getObjectName($bean->parent_type);
            $moduleName = BeanFactory::getModuleName($objectName);
            $usesTemplate = in_array($moduleName, ['Users', 'Employees']) ||
                VardefManager::usesTemplate($moduleName, 'email_address');

            if (!$usesTemplate) {
                throw new SugarApiExceptionNotAuthorized(
                    sprintf(
                        'Cannot add %s records to %s: %s must use the email_address template',
                        $moduleName,
                        $this->name,
                        $moduleName
                    )
                );
            }
        }
    }

    /**
     * Sets the `email_address_id` field on the EmailParticipants bean to the ID of the parent's primary email address
     * if the email is archived and the `email_address_id` field is empty. Links the email address to the parent if
     * `email_address_id` is not empty and the email address is not already linked to the parent.
     *
     * @param SugarBean $lhs The Emails bean.
     * @param SugarBean $rhs The EmailParticipants bean.
     */
    protected function setEmailAddress(SugarBean $lhs, SugarBean $rhs)
    {
        $rhsParent = $this->getParent($rhs);

        if ($rhsParent && $rhsParent->id) {
            if (empty($rhs->email_address_id)) {
                if ($lhs->state === Email::STATE_ARCHIVED) {
                    // This email is final, so choose the first valid email address.
                    $primary = $rhsParent->emailAddress->getPrimaryAddress($rhsParent);
                    $rhs->email_address_id = $rhsParent->emailAddress->getEmailGUID($primary);
                }
            } else {
                $emailAddress = BeanFactory::retrieveBean('EmailAddresses', $rhs->email_address_id);
                $this->addEmailAddressToRecord($rhsParent, $emailAddress);
            }
        }
    }

    /**
     * Returns the parent record of the bean.
     *
     * @param SugarBean $bean
     * @return null|SugarBean
     */
    private function getParent(SugarBean $bean)
    {
        if (empty($bean->parent_type) || empty($bean->parent_id)) {
            return null;
        }

        return BeanFactory::retrieveBean($bean->parent_type, $bean->parent_id, ['disable_row_level_security' => true]);
    }

    /**
     * Adds an email address to the bean so that they are linked.
     *
     * @param SugarBean $bean
     * @param SugarBean $emailAddress
     * @return bool
     */
    private function addEmailAddressToRecord(SugarBean $bean, SugarBean $emailAddress)
    {
        $emailAddresses = $bean->emailAddress->getAddressesForBean($bean, true);
        $matches = array_filter($emailAddresses, function ($address) use ($emailAddress) {
            return $address['email_address_id'] === $emailAddress->id;
        });

        if (count($matches) === 0) {
            if ($bean->emailAddress->addAddress($emailAddress->email_address) === false) {
                LoggerManager::getLogger()->error(
                    "Failed to add {$emailAddress->email_address} (EmailAddresses/{$bean->email_address_id}) to " .
                    "{$bean->module_dir}/{$bean->id} for {$this->name}"
                );
                return false;
            } else {
                $bean->emailAddress->save($bean->id, $bean->module_dir);
                $bean->emailAddress->dontLegacySave = true;
                $bean->emailAddress->populateLegacyFields($bean);
                LoggerManager::getLogger()->info(
                    "Added {$emailAddress->email_address} (EmailAddresses/{$bean->email_address_id}) to " .
                    "{$bean->module_dir}/{$bean->id} for {$this->name}"
                );
            }
        }

        return true;
    }
}
