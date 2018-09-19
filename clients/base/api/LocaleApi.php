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

class LocaleApi extends SugarApi
{
    public function registerApiRest()
    {
        return array(
            'retrieve' => array(
                'reqType' => 'GET',
                'path' => array('locale'),
                'pathVars' => array(),
                'method' => 'localeOptions',
                'shortHelp' => 'Gets locale options so UI can populate the corresponding dropdowns',
                'longHelp' => 'include/api/help/locale_options_get_help.html',
                'ignoreMetaHash' => true,
                'keepSession' => true,
            ),
        );
    }

    public function localeOptions(ServiceBase $api, array $args)
    {
        global $locale, $sugar_config;
        return array(
            'timepref' => $sugar_config['time_formats'],
            'datepref' => $sugar_config['date_formats'],
            'default_locale_name_format' => $locale->getUsableLocaleNameOptions($sugar_config['name_formats']),
            'timezone' => TimeDate::getTimezoneList(),
        );
    }

}

