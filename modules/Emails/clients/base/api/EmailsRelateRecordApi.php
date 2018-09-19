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

class EmailsRelateRecordApi extends RelateRecordApi
{
    /**
     * {@inheritdoc}
     */
    public function registerApiRest()
    {
        return [
            'createRelatedLink' => [
                'reqType' => 'POST',
                'path' => ['Emails', '?', 'link', '?', '?'],
                'pathVars' => ['module', 'record', '', 'link_name', 'remote_id'],
                'method' => 'createRelatedLink',
                'shortHelp' => 'Relates an existing record to an email',
                'longHelp' => 'modules/Emails/clients/base/api/help/emails_record_link_link_name_remote_id_post_help.html',
                'exceptions' => [
                    'SugarApiExceptionMissingParameter',
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionNotFound',
                ],
            ],
            'createRelatedLinks' => [
                'reqType' => 'POST',
                'path' => ['Emails', '?', 'link'],
                'pathVars' => ['module', 'record', ''],
                'method' => 'createRelatedLinks',
                'shortHelp' => 'Relates existing records to an email',
                'longHelp' => 'modules/Emails/clients/base/api/help/emails_record_link_post_help.html',
                'exceptions' => [
                    'SugarApiExceptionMissingParameter',
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionNotFound',
                ],
            ],
            'deleteRelatedLink' => [
                'reqType' => 'DELETE',
                'path' => ['Emails', '?', 'link', '?', '?'],
                'pathVars' => ['module', 'record', '', 'link_name', 'remote_id'],
                'method' => 'deleteRelatedLink',
                'shortHelp' => 'Deletes a relationship between an email and another record',
                'longHelp' => 'modules/Emails/clients/base/api/help/emails_record_link_link_name_remote_id_delete_help.html',
                'exceptions' => [
                    'SugarApiExceptionMissingParameter',
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionNotFound',
                ],
            ],
            'createRelatedLinksFromRecordList' => [
                'reqType' => 'POST',
                'path' => ['Emails', '?', 'link', '?', 'add_record_list', '?'],
                'pathVars' => ['module', 'record', '', 'link_name', '', 'remote_id'],
                'method' => 'createRelatedLinksFromRecordList',
                'shortHelp' => 'Relates existing records from a record list to an email',
                'longHelp' => 'modules/Emails/clients/base/api/help/emails_record_links_from_recordlist_post_help.html',
                'exceptions' => [
                    'SugarApiExceptionMissingParameter',
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionNotFound',
                ],
            ],
        ];
    }

    /**
     * Prevents existing Notes records from being linked as attachments and existing EmailParticipants records from
     * being linked as a sender or recipients.
     *
     * {@inheritdoc}
     * @throws SugarApiExceptionNotAuthorized
     */
    public function createRelatedLinks(
        ServiceBase $api,
        array $args,
        $securityTypeLocal = 'view',
        $securityTypeRemote = 'view'
    ) {
        if ($args['link_name'] === 'from') {
            throw new SugarApiExceptionNotAuthorized('Cannot link an existing sender');
        } elseif (in_array($args['link_name'], ['to', 'cc', 'bcc'])) {
            throw new SugarApiExceptionNotAuthorized('Cannot link existing recipients');
        } elseif ($args['link_name'] === 'attachments') {
            throw new SugarApiExceptionNotAuthorized('Cannot link existing attachments');
        }

        return parent::createRelatedLinks($api, $args, $securityTypeLocal, $securityTypeRemote);
    }

    /**
     * Prevents existing Notes records from being linked as attachments and existing EmailParticipants records from
     * being linked as a sender or recipients.
     *
     * {@inheritdoc}
     * @throws SugarApiExceptionNotAuthorized
     */
    public function createRelatedLinksFromRecordList(ServiceBase $api, array $args)
    {
        if ($args['link_name'] === 'from') {
            throw new SugarApiExceptionNotAuthorized('Cannot link an existing sender');
        } elseif (in_array($args['link_name'], ['to', 'cc', 'bcc'])) {
            throw new SugarApiExceptionNotAuthorized('Cannot link existing recipients');
        } elseif ($args['link_name'] === 'attachments') {
            throw new SugarApiExceptionNotAuthorized('Cannot link existing attachments');
        }

        return parent::createRelatedLinksFromRecordList($api, $args);
    }

    /**
     * The sender cannot be removed. Replace the sender with a different sender instead.
     *
     * {@inheritdoc}
     * @throws SugarApiExceptionNotAuthorized
     */
    public function deleteRelatedLink(ServiceBase $api, array $args)
    {
        if ($args['link_name'] === 'from') {
            throw new SugarApiExceptionNotAuthorized('The sender cannot be removed');
        }

        return parent::deleteRelatedLink($api, $args);
    }
}
