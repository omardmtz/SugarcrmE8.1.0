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

class EmailsApiHelper extends SugarBeanApiHelper
{
    /**
     * {@inheritdoc}
     *
     * `outbound_email_id` is not included in the response if the user does not have access to the outbound email
     * account.
     */
    public function formatForApi(\SugarBean $bean, array $fieldList = array(), array $options = array())
    {
        $data = parent::formatForApi($bean, $fieldList, $options);
        $oe = null;

        if (!empty($data['outbound_email_id'])) {
            $oe = BeanFactory::retrieveBean('OutboundEmail', $data['outbound_email_id']);
        }

        if (empty($oe)) {
             unset($data['outbound_email_id']);
        }

        return $data;
    }

    /**
     * {@link Email::state} is set before anything else because field-level ACL checks are dependent on an email's state
     * and the ACL checks are performed before the fields are populated. The state is converted to "Draft" if it is
     * submitted as "Ready". "Ready" is just a signal to the API that the email should be sent after it is saved. The
     * email should be saved as a draft first, in case sending fails. This allows the user to try again without losing
     * any data.
     *
     * {@link Email::outbound_email_id} is only populated if the email is a draft. An email is a draft at this stage if
     * it is either being saved as a draft or will be sent as a part of the current request. This is done to avoid
     * persisting data on {@link Email::outbound_email_id} when SugarCRM was not used to send the email or mutating the
     * sender-of-record of an archived email.
     *
     * {@inheritdoc}
     *
     * @throws SugarApiExceptionInvalidParameter Thrown if `state` is submitted and the state transition is not
     * supported.
     * @throws SugarApiExceptionNotAuthorized Thrown if `from` is submitted and the email is a draft.
     * @throws SugarApiExceptionNotFound Thrown if `outbound_email_id` is submitted and the record cannot be found or is
     * inaccessible to the user.
     */
    public function populateFromApi(SugarBean $bean, array $submittedData, array $options = array())
    {
        // Set the state before anything else because field-level ACL checks are dependent on an email's state and the
        // ACL checks are performed before the fields are populated.
        if (isset($submittedData['state'])) {
            if (!$bean->isStateTransitionAllowed($submittedData['state'])) {
                $message = sprintf('State transition to %s is invalid for creating an email', $submittedData['state']);

                if ($bean->isUpdate()) {
                    $message = sprintf(
                        'State transition from %s to %s is invalid for an email',
                        $bean->state,
                        $submittedData['state']
                    );
                }

                throw new SugarApiExceptionInvalidParameter($message);
            }

            // "Ready" is just a signal to the API that the email should be sent after it is saved. We want to save the
            // email as a draft first, in case sending fails. This allows the user to try again without losing any data.
            if ($submittedData['state'] === Email::STATE_READY) {
                $submittedData['state'] = Email::STATE_DRAFT;
            }

            $bean->state = $submittedData['state'];
        }

        // Maintain backward compatibility by making the email a draft if it is being created with type=draft and the
        // state argument was not provided.
        if (!$bean->isUpdate() &&
            !isset($submittedData['state']) &&
            isset($submittedData['type']) &&
            $submittedData['type'] === 'draft'
        ) {
            $submittedData['state'] = Email::STATE_DRAFT;
            $bean->state = Email::STATE_DRAFT;
        }

        // Maintain backward compatibility by mapping the sender's name and address string to the property that is used
        // for linking the sender to the email, via Email::saveEmailAddresses(), when saving.
        if (isset($submittedData['from_addr_name'])) {
            $bean->from_addr = $submittedData['from_addr_name'];
        }

        // Maintain backward compatibility by mapping the recipients' name and address string to the property that is
        // used for linking the recipients to the email, via Email::saveEmailAddresses(), when saving.
        if (isset($submittedData['to_addrs_names'])) {
            $bean->to_addrs = $submittedData['to_addrs_names'];
        }

        // Maintain backward compatibility by mapping the recipients' name and address string to the property that is
        // used for linking the recipients to the email, via Email::saveEmailAddresses(), when saving.
        if (isset($submittedData['cc_addrs_names'])) {
            $bean->cc_addrs = $submittedData['cc_addrs_names'];
        }

        // Maintain backward compatibility by mapping the recipients' name and address string to the property that is
        // used for linking the recipients to the email, via Email::saveEmailAddresses(), when saving.
        if (isset($submittedData['bcc_addrs_names'])) {
            $bean->bcc_addrs = $submittedData['bcc_addrs_names'];
        }

        if ($bean->state === Email::STATE_DRAFT) {
            if (isset($submittedData['from'])) {
                throw new SugarApiExceptionNotAuthorized('Not allowed to edit field from when saving a draft');
            }

            if (!empty($submittedData['assigned_user_id']) &&
                $submittedData['assigned_user_id'] !== $GLOBALS['current_user']->id
            ) {
                throw new SugarApiExceptionInvalidParameter(
                    'assigned_user_id must be empty or specify the ID of the current user'
                );
            }

            unset($submittedData['assigned_user_id']);
        }

        $hasOutboundEmailId = isset($submittedData['outbound_email_id']) && !empty($submittedData['outbound_email_id']);

        if ($hasOutboundEmailId && $bean->state === Email::STATE_DRAFT) {
            $oe = BeanFactory::retrieveBean('OutboundEmail', $submittedData['outbound_email_id']);

            if (!$oe) {
                throw new SugarApiExceptionNotFound(
                    sprintf(
                        'Could not find record: %s in module: OutboundEmail for the submitted outbound_email_id',
                        $submittedData['outbound_email_id']
                    )
                );
            }
        }

        return parent::populateFromApi($bean, $submittedData, $options);
    }
}
