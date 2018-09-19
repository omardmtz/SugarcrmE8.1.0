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

 // $Id: db_utils.php 56343 2010-05-10 21:18:10Z jenny $

/**
 * @deprecated use DBManager::convert() instead.
 */
function db_convert($string, $type, $additional_parameters=array(),$additional_parameters_oracle_only=array())
	{
    return $GLOBALS['db']->convert($string, $type, $additional_parameters, $additional_parameters_oracle_only);
            }

/**
 * @deprecated use DBManager::concat() instead.
 */
function db_concat($table, $fields)
	{
    return $GLOBALS['db']->concat($table, $fields);
}

/**
 * @deprecated use DBManager::fromConvert() instead.
 */
function from_db_convert($string, $type)
	{
    return $GLOBALS['db']->fromConvert($string, $type);
	}

/**
 * Replaces specific characters with their HTML entity values
 * @param string $string String to check/replace
 * @param bool $encode Default true
 * @return string
 *
 * @todo Make this utilize the external caching mechanism after re-testing (see
 *       log on r25320).
 *
 * Bug 49489 - removed caching of to_html strings as it was consuming memory and
 * never releasing it
 */
function to_html($string, $encode=true)
{
	if (empty($string) || !$encode) {
		return $string;
	}
    if(defined('ENTRY_POINT_TYPE') && constant('ENTRY_POINT_TYPE') == 'api') {
        return $string;
    }
    return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
}


/**
 * Replaces specific HTML entity values with the true characters
 * @param string $string String to check/replace
 * @param bool $decode Default true
 * @return string
 */
function from_html($string, $decode=true)
{
    if (!is_string($string) || !$decode) {
        return $string;
    }
    if(defined('ENTRY_POINT_TYPE') && constant('ENTRY_POINT_TYPE') == 'api') {
        return $string;
    }

    return htmlspecialchars_decode($string, ENT_QUOTES);
}

/*
 * Return a version of $proposed that can be used as a column name in any of our supported databases
 * Practically this means no longer than 25 characters as the smallest identifier length for our supported DBs is 30 chars for Oracle plus we add on at least four characters in some places (for indices for example)
 * @param string $name Proposed name for the column
 * @param string $ensureUnique
 * @param int $maxlen Deprecated and ignored
 * @return string Valid column name trimmed to right length and with invalid characters removed
 */
function getValidDBName ($name, $ensureUnique = false, $maxLen = 30)
{
    return DBManagerFactory::getInstance()->getValidDBName($name, $ensureUnique);
}


/**
 * isValidDBName
 *
 * Utility to perform the check during install to ensure a database name entered by the user
 * is valid based on the type of database server
 * @param string $name Proposed name for the DB
 * @param string $dbType Type of database server
 * @return bool true or false based on the validity of the DB name
 */
function isValidDBName($name, $dbType)
{
    $db = DBManagerFactory::getTypeInstance($dbType);
    return $db->isDatabaseNameValid($name);
}
