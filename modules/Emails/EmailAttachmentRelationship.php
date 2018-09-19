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
 * Class EmailAttachmentRelationship
 *
 * Represents a bean-based one-to-many relationship between Emails and Notes for attachments. Emails should be on the
 * left side of the relationship.
 */
class EmailAttachmentRelationship extends One2MBeanRelationship
{
    /**
     * Attachments cannot be linked to an existing archived email.
     *
     * {@inheritdoc}
     * @throws SugarApiExceptionNotAuthorized
     */
    public function add($lhs, $rhs, $additionalFields = array())
    {
        if ($lhs->isArchived()) {
            throw new SugarApiExceptionNotAuthorized("Cannot add to {$this->name} when the email is archived");
        }

        return parent::add($lhs, $rhs, $additionalFields);
    }

    /**
     * Attachments cannot be unlinked from an existing archived email. Unlinked attachments are deleted, as they cannot
     * exist without an email.
     *
     * {@inheritdoc}
     * @throws SugarApiExceptionNotAuthorized
     */
    public function remove($lhs, $rhs, $save = true)
    {
        if ($lhs->isArchived() && !$rhs->deleted && !$lhs->deleted) {
            throw new SugarApiExceptionNotAuthorized("Cannot remove from {$this->name} when the email is archived");
        }

        $result = parent::remove($lhs, $rhs, $save);

        if ($result && !$rhs->deleted) {
            $rhs->mark_deleted($rhs->id);
        }

        return $result;
    }
}
