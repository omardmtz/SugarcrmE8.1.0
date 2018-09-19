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

class KBContentsRelateRecordApi extends RelateRecordApi
{
    /**
     * Override createRelatedLinks().
     * @inheritdoc
     */
    public function registerApiRest()
    {
        return array(
            'createRelatedLinks' => array(
                'reqType' => 'POST',
                'path' => array('KBContents', '?', 'link'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'createRelatedLinks',
                'shortHelp' => 'Relates existing records to this module.',
                'longHelp' => 'include/api/help/module_record_link_post_help.html',
            ),
        );
    }

    /**
     * Disable linking for `localizations` and `revisions`.
     * @inheritdoc
     */
    public function createRelatedLinks(
        ServiceBase $api,
        array $args,
        $securityTypeLocal = 'view',
        $securityTypeRemote = 'view'
    ) {
        if (in_array($args['link_name'], array('localizations', 'revisions'))) {
            throw new SugarApiExceptionInvalidParameter('Unable to link existing record as localisation or revision.');
        }

        return parent::createRelatedLinks($api, $args, $securityTypeLocal, $securityTypeRemote);
    }
}
