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

$sugar_config_si = array (
    'setup_db_host_name' => '127.0.0.1', // we use local mysql installed by us into docker container
    'setup_db_database_name' => 'sugarcrm',
    'setup_db_drop_tables' => 1,
    'setup_db_create_database' => 1,
    'setup_db_pop_demo_data' => 1,
    'setup_site_admin_user_name' => 'admin',
    'setup_site_admin_password' => 'admin',
    'setup_db_create_sugarsales_user' => 1,
    'setup_db_admin_user_name' => 'root',
    'setup_db_admin_password' => 'Sugar123',
    'setup_db_sugarsales_user' => 'alex', // Can be same as DB_ROOT_USER
    'setup_db_sugarsales_password' => '', // Can be same as DB_ROOT_PASS
    'setup_db_type' => 'mysql',
    'setup_license_key_users' => 150,
    'setup_license_key_oc_licences' => 0,
    'setup_license_key' => getenv('SUGAR_LICENSE_KEY'),
    'setup_site_url' => 'http://localhost/',
    'setup_system_name' => 'SugarCRM Dev',
    'default_currency_iso4217' => 'USD',
    'default_currency_name' => 'US Dollars',
    'default_currency_significant_digits' => '2',
    'default_currency_symbol' => '$',
    'default_date_format' => 'Y-m-d',
    'default_time_format' => 'H:i',
    'default_decimal_seperator' => '.',
    'default_export_charset' => 'ISO-8859-1',
    'default_language' => 'en_us',
    'default_locale_name_format' => 's f l',
    'default_number_grouping_seperator' => ',',
    'export_delimiter' => ',',
    'demoData' => 'no', // Demo Data by default, 'yes' or 'no'
    'fts_type' => 'Elastic',
    'setup_fts_type' => 'Elastic',
    'setup_fts_host' => 'behat-tests-env-elastic', //Your Elastic server host
    'setup_fts_port' => '9200', //Your Elastic server port
);
