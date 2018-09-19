<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

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

use Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Client;

function checkDBSettings($silent=false) {

    installLog("Begin DB Check Process *************");
    global $mod_strings;
    global $sugar_config;

    $errors = array();
    copyInputsIntoSession();

    $db = getInstallDbInstance();
    if (!empty($_SESSION['setup_db_options'])) {
        $db->setOptions($_SESSION['setup_db_options']);
    }

    installLog("testing with {$db->dbType}:{$db->variant}");


        if( trim($_SESSION['setup_db_database_name']) == '' ){
            $errors['ERR_DB_NAME'] = $mod_strings['ERR_DB_NAME'];
            installLog("ERROR::  {$errors['ERR_DB_NAME']}");
        }


        if (!$db->isDatabaseNameValid($_SESSION['setup_db_database_name'])) {
            $errIdx = 'ERR_DB_' . strtoupper($_SESSION['setup_db_type']) . '_DB_NAME_INVALID';
            $errors[$errIdx] = $mod_strings[$errIdx];
            installLog("ERROR::  {$errors[$errIdx]}");
        }

        if($_SESSION['setup_db_type'] != 'oci8') {
            // Oracle doesn't need host name, others do
            if( trim($_SESSION['setup_db_host_name']) == '' ){
                $errors['ERR_DB_HOSTNAME'] = $mod_strings['ERR_DB_HOSTNAME'];
                installLog("ERROR::  {$errors['ERR_DB_HOSTNAME']}");
            }
        }

        //check to see that password and retype are same, if needed
        if(!empty($_SESSION['dbUSRData']) && ($_SESSION['dbUSRData']=='create' || $_SESSION['dbUSRData']=='provide'))
        {
            if( $_SESSION['setup_db_sugarsales_password'] != $_SESSION['setup_db_sugarsales_password_retype'] ){
                $errors['ERR_DBCONF_PASSWORD_MISMATCH'] = $mod_strings['ERR_DBCONF_PASSWORD_MISMATCH'];
                installLog("ERROR::  {$errors['ERR_DBCONF_PASSWORD_MISMATCH']}");
            }
        }

        // bail if the basic info isn't valid
        if( count($errors) > 0 ){
                installLog("Basic form info is INVALID, exit Process.");
            if ($silent) {
                return $errors;
            } else {
                printErrors($errors);
            }
        } else {
            installLog("Basic form info is valid, continuing Process.");
        }

        $dbconfig = array(
                "db_host_name" => $_SESSION['setup_db_host_name'],
                "db_host_instance" => $_SESSION['setup_db_host_instance'],
        );

        if(!empty($_SESSION['setup_db_port_num'])) {
            $dbconfig["db_port"] = $_SESSION['setup_db_port_num'];
        } else {
            $_SESSION['setup_db_port_num'] = '';
        }

        // Needed for database implementation that do not allow connections to the server directly
        // and that typically require the manual setup of a database instances such as DB2
        if(empty($_SESSION['setup_db_create_database'])) {
            $dbconfig["db_name"] = $_SESSION['setup_db_database_name'];
        }

        // Bug 29855 - Check to see if given db name is valid
        if($_SESSION['setup_db_type'] == 'oci8') {
            if (preg_match("![\"'*\\?<>]+!i", $_SESSION['setup_db_database_name'])) {
                $errors['ERR_DB_OCI8_DB_NAME'] = $mod_strings['ERR_DB_OCI8_DB_NAME_INVALID'];
                installLog("ERROR::  {$errors['ERR_DB_OCI8_DB_NAME']}");
            }
        }
        else {
            if (preg_match("![\"'*/\\?:<>-]+!i", $_SESSION['setup_db_database_name']) ) {
                $errors['ERR_DB_MSSQL_DB_NAME'] = $mod_strings['ERR_DB_MSSQL_DB_NAME_INVALID'];
                installLog("ERROR::  {$errors['ERR_DB_MSSQL_DB_NAME']}");
            }
         }

        // test the account that will talk to the db if we're not creating it
        if( $_SESSION['setup_db_sugarsales_user'] != '' && !$_SESSION['setup_db_create_sugarsales_user'] ){
            $dbconfig["db_user_name"] = $_SESSION['setup_db_sugarsales_user'];
            $dbconfig["db_password"] = $_SESSION['setup_db_sugarsales_password'];
            installLog("Testing user account...");

            // try connecting to the DB
            if(!$db->connect($dbconfig, false)) {
                $error = $db->lastError();
                $errors['ERR_DB_LOGIN_FAILURE'] = $mod_strings['ERR_DB_LOGIN_FAILURE'];
                installLog("ERROR::  {$errors['ERR_DB_LOGIN_FAILURE']}");
            } else {
                installLog("Connection made using  host: {$_SESSION['setup_db_host_name']}, usr: {$_SESSION['setup_db_sugarsales_user']}");
                $db->disconnect();
            }
        }

        // privileged account tests
        if( empty($_SESSION['setup_db_admin_user_name']) ){
            $errors['ERR_DB_PRIV_USER'] = $mod_strings['ERR_DB_PRIV_USER'];
            installLog("ERROR:: {$errors['ERR_DB_PRIV_USER']}");
        } else {
            installLog("Testing priviliged account...");
            $dbconfig["db_user_name"] = $_SESSION['setup_db_admin_user_name'];
            $dbconfig["db_password"] = $_SESSION['setup_db_admin_password'];
            if(!$db->connect($dbconfig, false)) {
                $error = $db->lastError();
                $errors['ERR_DB_LOGIN_FAILURE'] = $mod_strings['ERR_DB_LOGIN_FAILURE'];
                installLog("ERROR::  {$errors['ERR_DB_LOGIN_FAILURE']}");
            } else {
                installLog("Connection made using  host: {$_SESSION['setup_db_host_name']}, usr: {$_SESSION['setup_db_sugarsales_user']}");
                $db_selected = $db->dbExists($_SESSION['setup_db_database_name']);
                if($silent==false && $db_selected && $_SESSION['setup_db_create_database'] && empty($_SESSION['setup_db_drop_tables'])) {
                    // DB exists but user didn't agree to overwrite it
                        $errStr = $mod_strings['ERR_DB_EXISTS_PROCEED'];
                        $errors['ERR_DB_EXISTS_PROCEED'] = $errStr;
                        installLog("ERROR:: {$errors['ERR_DB_EXISTS_PROCEED']}");
                } elseif($silent==false && !$db_selected && !$_SESSION['setup_db_create_database'] ) {
                    // DB does not exist but user did not allow to create it
                        $errors['ERR_DB_EXISTS_NOT'] = $mod_strings['ERR_DB_EXISTS_NOT'];
                        installLog("ERROR:: {$errors['ERR_DB_EXISTS_NOT']}");
                } else {
                    if($db_selected) {
                        installLog("DB Selected, will reuse {$_SESSION['setup_db_database_name']}");
                        if($silent == false && $db->tableExists('config')) {
                            $errors['ERR_DB_EXISTS_WITH_CONFIG'] = $mod_strings['ERR_DB_EXISTS_WITH_CONFIG'];
                            installLog("ERROR:: {$errors['ERR_DB_EXISTS_WITH_CONFIG']}");
                        }
                    } else {
                        installLog("DB not selected, will create {$_SESSION['setup_db_database_name']}");
                    }
                    if($_SESSION['setup_db_create_sugarsales_user'] && $_SESSION['setup_db_sugarsales_user'] != '' && $db_selected) {
                        if($db->userExists($_SESSION['setup_db_sugarsales_user'])) {
                            $errors['ERR_DB_USER_EXISTS'] = $mod_strings['ERR_DB_USER_EXISTS'];
                            installLog("ERROR:: {$errors['ERR_DB_USER_EXISTS']}");
                        }
                    }
                }

                // DB SPECIFIC
                $check = $db->canInstall();
                if($check !== true) {
                    $error = array_shift($check);
                    array_unshift($check, $mod_strings[$error]);
                    $errors[$error] = call_user_func_array('sprintf', $check);
                    installLog("ERROR:: {$errors[$error]}");
                } else {
                    installLog("Passed DB install check");
                }

                $db->disconnect();
            }
        }

        //Test FTS Settings
        if (empty($_SESSION['setup_fts_type'])) {
            installLog("ERROR:: Elastic Search is required.");
            $errors['ERR_FTS'] = $mod_strings['LBL_FTS_REQUIRED'];
        } else {
            installLog("Begining to check FTS Settings.");
            $engine = SearchEngine::newEngine($_SESSION['setup_fts_type'], getFtsSettings());
            $ftsStatus = $engine->verifyConnectivity(false);
            switch ($ftsStatus) {
                case Client::CONN_ERROR:
                case Client::CONN_FAILURE:
                    $errors['ERR_FTS'] = $mod_strings['LBL_FTS_CONN_ERROR'];
                    installLog("ERROR:: Unable to connect to FTS." . $_SESSION['setup_fts_type']);
                    break;
                case Client::CONN_NO_VERSION_AVAILABLE:
                    $errors['ERR_FTS'] = $mod_strings['LBL_FTS_NO_VERSION_AVAILABLE'];
                    installLog("ERROR:: No FTS version available." . $_SESSION['setup_fts_type']);
                    break;
                case Client::CONN_VERSION_NOT_SUPPORTED:
                    $errors['ERR_FTS'] = sprintf(
                        $mod_strings['LBL_FTS_UNSUPPORTED_VERSION'],
                        implode(', ', $engine->getContainer()->client->getAllowedVersions())
                    );
                    installLog("ERROR:: Unsupported version of Elastic search." . $_SESSION['setup_fts_type']);
                    break;
            }
            installLog("FTS connection results: $ftsStatus");
        }

    installLog("End DB Check Process *************");

    if ($silent) {
        return $errors;
    } else {
        printErrors($errors);
    }
}

function printErrors($errors ){

global $mod_strings;
    if(count($errors) == 0){
        echo 'dbCheckPassed';
        installLog("SUCCESS:: no errors detected!");
    }else if((count($errors) == 1 && (isset($errors["ERR_DB_EXISTS_PROCEED"])||isset($errors["ERR_DB_EXISTS_WITH_CONFIG"])))  ||
    (count($errors) == 2 && isset($errors["ERR_DB_EXISTS_PROCEED"]) && isset($errors["ERR_DB_EXISTS_WITH_CONFIG"])) ){
        ///throw alert asking to overwwrite db
        echo 'preexeest';
        installLog("WARNING:: no errors detected, but DB tables will be dropped!, issuing warning to user");
    }else{
        installLog("FATAL:: errors have been detected!  User will not be allowed to continue.  Errors are as follow:");
         //print out errors
        $validationErr  = "<p><b>{$mod_strings['ERR_DBCONF_VALIDATION']}</b></p>";
        $validationErr .= '<ul>';

        foreach($errors as $key =>$erMsg){
            if($key != "ERR_DB_EXISTS_PROCEED" && $key != "ERR_DB_EXISTS_WITH_CONFIG"){
                if($_SESSION['dbUSRData'] == 'same' && $key == 'ERR_DB_ADMIN'){
                    installLog(".. {$erMsg}");
                    break;
                }
                $validationErr .= '<li class="error">' . $erMsg . '</li>';
                installLog(".. {$erMsg}");
            }
        }
        $validationErr .= '</ul>';
        $validationErr .= '</div>';

         echo $validationErr;
    }

}


function copyInputsIntoSession(){
            if(isset($_REQUEST['setup_db_type'])){$_SESSION['setup_db_type']                        = $_REQUEST['setup_db_type'];}
            if(isset($_REQUEST['setup_db_admin_user_name'])){$_SESSION['setup_db_admin_user_name']  = $_REQUEST['setup_db_admin_user_name'];}
            if(isset($_REQUEST['setup_db_admin_password'])){$_SESSION['setup_db_admin_password']    = $_REQUEST['setup_db_admin_password'];}
            if(isset($_REQUEST['setup_db_database_name'])){$_SESSION['setup_db_database_name']      = $_REQUEST['setup_db_database_name'];}
            if(isset($_REQUEST['setup_db_host_name'])){$_SESSION['setup_db_host_name']              = $_REQUEST['setup_db_host_name'];}

            //FTS Support
            if (isset($_REQUEST['setup_fts_type'])) {
                $_SESSION['setup_fts_type'] = $_REQUEST['setup_fts_type'];
            }
            if (isset($_REQUEST['setup_fts_host'])) {
                $_SESSION['setup_fts_host'] = $_REQUEST['setup_fts_host'];
            }
            if (isset($_REQUEST['setup_fts_port'])) {
                $_SESSION['setup_fts_port'] = $_REQUEST['setup_fts_port'];
            }

            if(isset($_SESSION['setup_db_type']) && (!isset($_SESSION['setup_db_manager']) || isset($_REQUEST['setup_db_type']))) {
                $_SESSION['setup_db_manager'] = DBManagerFactory::getManagerByType($_SESSION['setup_db_type']);
            }

            if(isset($_REQUEST['setup_db_host_instance'])){
                $_SESSION['setup_db_host_instance'] = $_REQUEST['setup_db_host_instance'];
            }

            if(isset($_REQUEST['setup_db_port_num'])){
                $_SESSION['setup_db_port_num'] = $_REQUEST['setup_db_port_num'];
            }

            // on a silent install, copy values from $_SESSION into $_REQUEST
            if (isset($_REQUEST['goto']) && $_REQUEST['goto'] == 'SilentInstall') {
                if (isset($_SESSION['dbUSRData']) && !empty($_SESSION['dbUSRData']))
                    $_REQUEST['dbUSRData'] = $_SESSION['dbUSRData'];
                else $_REQUEST['dbUSRData'] = 'same';

                if (isset($_SESSION['setup_db_sugarsales_user']) && !empty($_SESSION['setup_db_sugarsales_user']))
                    $_REQUEST['setup_db_sugarsales_user'] = $_SESSION['setup_db_sugarsales_user'];
                else $_REQUEST['dbUSRData'] = 'same';

                $_REQUEST['setup_db_sugarsales_password'] = $_SESSION['setup_db_sugarsales_password'];
                $_REQUEST['setup_db_sugarsales_password_retype'] = $_SESSION['setup_db_sugarsales_password'];
            }

            //make sure we are creating or using provided user for app db connections
            $_SESSION['setup_db_create_sugarsales_user']  = true;//get_boolean_from_request('setup_db_create_sugarsales_user');
            $db = getInstallDbInstance();
            if( !$db->supports("create_user") ){
             //if the DB doesn't support creating users, make the admin user/password same as connecting user/password
              $_SESSION['setup_db_sugarsales_user']             = $_SESSION['setup_db_admin_user_name'];
              $_SESSION['setup_db_sugarsales_password']         = $_SESSION['setup_db_admin_password'];
              $_SESSION['setup_db_sugarsales_password_retype']  = $_SESSION['setup_db_sugarsales_password'];
              $_SESSION['setup_db_create_sugarsales_user']      = false;
              $_SESSION['setup_db_create_database']             = false;

            } else {
            	$_SESSION['setup_db_create_database']             = true;
                //retrieve the value from dropdown in order to know what settings the user
                //wants to use for the sugar db user.

                //use provided db admin by default
                $_SESSION['dbUSRData'] = 'same';

                if(isset($_REQUEST['dbUSRData'])  && !empty($_REQUEST['dbUSRData'])){
                    $_SESSION['dbUSRData'] = $_REQUEST['dbUSRData'];
                }


                  if($_SESSION['dbUSRData'] == 'auto'){
                    //create user automatically
                      $_SESSION['setup_db_create_sugarsales_user']          = true;
                      $_SESSION['setup_db_sugarsales_user']                 = "sugar".create_db_user_creds(5);
                      $_SESSION['setup_db_sugarsales_password']             = create_db_user_creds(10);
                      $_SESSION['setup_db_sugarsales_password_retype']      = $_SESSION['setup_db_sugarsales_password'];
                  }elseif($_SESSION['dbUSRData'] == 'provide'){
                    //use provided user info
                      $_SESSION['setup_db_create_sugarsales_user']          = false;
                      $_SESSION['setup_db_sugarsales_user']                 = $_REQUEST['setup_db_sugarsales_user'];
                      $_SESSION['setup_db_sugarsales_password']             = $_REQUEST['setup_db_sugarsales_password'];
                      $_SESSION['setup_db_sugarsales_password_retype']      = $_REQUEST['setup_db_sugarsales_password_retype'];
                  }elseif($_SESSION['dbUSRData'] == 'create'){
                    // create user with provided info
                      $_SESSION['setup_db_create_sugarsales_user']        = true;
                      $_SESSION['setup_db_sugarsales_user']               = $_REQUEST['setup_db_sugarsales_user'];
                      $_SESSION['setup_db_sugarsales_password']           = $_REQUEST['setup_db_sugarsales_password'];
                      $_SESSION['setup_db_sugarsales_password_retype']    = $_REQUEST['setup_db_sugarsales_password_retype'];
                  }else{
                   //Use the same login as provided admin user
                      $_SESSION['setup_db_create_sugarsales_user']      = false;
                      $_SESSION['setup_db_sugarsales_user']             = $_SESSION['setup_db_admin_user_name'];
                      $_SESSION['setup_db_sugarsales_password']         = $_SESSION['setup_db_admin_password'];
                      $_SESSION['setup_db_sugarsales_retype']           = $_SESSION['setup_db_admin_password'];
                  }
            }

            if(!isset($_SESSION['demoData']) || empty($_SESSION['demoData'])){
                $_SESSION['demoData'] = 'no';
            }
            if(isset($_REQUEST['demoData'])){$_SESSION['demoData'] = $_REQUEST['demoData'] ;}

            if($db->supports('create_db')) {
                if(!empty($_SESSION['setup_db_create_database'])) {
            	// if we're dropping DB, no need to drop tables
                	$_SESSION['setup_db_drop_tables']  = false;
                }
            } else {
                // we can't create DB, so can't drop it
                $_SESSION['setup_db_create_database'] = false;
            }

            if (isset($_REQUEST['goto']) && $_REQUEST['goto'] == 'SilentInstall' && isset($_SESSION['setup_db_drop_tables'])) {
                //set up for Oracle Silent Installer
                $_REQUEST['setup_db_drop_tables'] = $_SESSION['setup_db_drop_tables'] ;
            }

            if (!isset($_SESSION['setup_db_options'])) {
                $_SESSION['setup_db_options'] = array();
            }

            if (isset($_REQUEST['setup_db_ssl_is_enabled'])) {
                $_SESSION['setup_db_options']['ssl'] = isTruthy($_REQUEST['setup_db_ssl_is_enabled']);
            }
}

////    END PAGEOUTPUT
///////////////////////////////////////////////////////////////////////////////
?>
