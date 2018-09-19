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

$tbaConfig = new TeamBasedACLConfigurator();
$tbaModuleOptions = $tbaConfig->getModuleOptions();

if (!defined('ACL_ALLOW_NONE')) {
    define('ACL_ALLOW_ADMIN_DEV', 100);
    define('ACL_ALLOW_ADMIN', 99);
    define('ACL_ALLOW_DEV', 95);
    define('ACL_ALLOW_ALL', 90);
    define('ACL_ALLOW_ENABLED', 89);
    define('ACL_ALLOW_SELECTED_TEAMS', $tbaModuleOptions['ACL_ALLOW_SELECTED_TEAMS']);
    define('ACL_ALLOW_OWNER', 75);
    define('ACL_ALLOW_NORMAL', 1);
    define('ACL_ALLOW_DEFAULT', 0);
    define('ACL_ALLOW_DISABLED', -98);
    define('ACL_ALLOW_NONE', -99);
}

// These are rendering descriptions for Access Levels giving information such as
// the label, color, and text color to use when rendering the access level
$GLOBALS['ACLActionAccessLevels'] = array(
    ACL_ALLOW_ALL => array(
        'color' => '#008000',
        'label' => 'LBL_ACCESS_ALL',
        'text_color' => 'white',
    ),
    ACL_ALLOW_OWNER => array(
        'color' => '#6F6800',
        'label' => 'LBL_ACCESS_OWNER',
        'text_color' => 'white',
    ),
    ACL_ALLOW_SELECTED_TEAMS => array(
        'color' => '#FFAA00',
        'label' => 'LBL_ACCESS_SELECTED_TEAMS',
        'text_color' => 'white',
    ),
    ACL_ALLOW_NONE => array(
        'color' => '#FF0000',
        'label' => 'LBL_ACCESS_NONE',
        'text_color' => 'white',
    ),
    ACL_ALLOW_ENABLED => array(
        'color' => '#008000',
        'label' => 'LBL_ACCESS_ENABLED',
        'text_color' => 'white',
    ),
    ACL_ALLOW_DISABLED => array(
        'color' => '#FF0000',
        'label' => 'LBL_ACCESS_DISABLED',
        'text_color' => 'white',
    ),
    ACL_ALLOW_ADMIN => array(
        'color' => '#0000FF',
        'label' => 'LBL_ACCESS_ADMIN',
        'text_color' => 'white',
    ),
    ACL_ALLOW_NORMAL => array(
        'color' => '#008000',
        'label' => 'LBL_ACCESS_NORMAL',
        'text_color' => 'white',
    ),
    ACL_ALLOW_DEFAULT => array(
        'color' => '#008000',
        'label' => 'LBL_ACCESS_DEFAULT',
        'text_color' => 'white',
    ),
    ACL_ALLOW_DEV => array(
        'color' => '#0000FF',
        'label' => 'LBL_ACCESS_DEV',
        'text_color' => 'white',
    ),
    ACL_ALLOW_ADMIN_DEV => array(
        'color' => '#0000FF',
        'label' => 'LBL_ACCESS_ADMIN_DEV',
        'text_color' => 'white',
    ),
);

$actionsDropdown = array(
    ACL_ALLOW_ALL,
    ACL_ALLOW_OWNER,
);
if ($tbaConfig->isEnabledGlobally()) {
    $actionsDropdown[] = ACL_ALLOW_SELECTED_TEAMS;
}
$actionsDropdown[] = ACL_ALLOW_DEFAULT;
$actionsDropdown[] = ACL_ALLOW_NONE;

// These are the actions for a given type. It includes the ACCESS Levels for 
// that action and the label for that action.
$GLOBALS['ACLActions'] = array(
    'module' => array(
        'actions' => array(
            'admin' => array(
                'aclaccess' => array(
                    ACL_ALLOW_NORMAL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_ADMIN,
                    ACL_ALLOW_DEV,
                    ACL_ALLOW_ADMIN_DEV,
                ),
                'label' => 'LBL_ACTION_ADMIN',
                'default' => ACL_ALLOW_NORMAL,
            ),
            'access' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ENABLED,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_DISABLED,
                ),
                'label' => 'LBL_ACTION_ACCESS',
                'default' => ACL_ALLOW_ENABLED,
            ),
            'view' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_VIEW',
                'default' => ACL_ALLOW_ALL,
            ),
            'list' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_LIST',
                'default' => ACL_ALLOW_ALL,
            ),
            'edit' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_EDIT',
                'default' => ACL_ALLOW_ALL,
            ),
            'delete' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_DELETE',
                'default' => ACL_ALLOW_ALL,
            ),
            'import' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ALL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_NONE,
                ),
                'label' => 'LBL_ACTION_IMPORT',
                'default' => ACL_ALLOW_ALL,
            ),
            'export' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_EXPORT',
                'default' => ACL_ALLOW_ALL,
            ),
            'massupdate' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ALL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_NONE,
                ),
                'label' => 'LBL_ACTION_MASSUPDATE',
                'default' => ACL_ALLOW_ALL,
            ),
        ),
    ),
    'Tracker' => array(
        'actions' => array(
            'admin' => array(
                'aclaccess' => array(
                    ACL_ALLOW_NORMAL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_ADMIN,
                ),
                'label' => 'LBL_ACTION_ADMIN',
                'default' => ACL_ALLOW_NONE,
            ),
            'access' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ENABLED,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_DISABLED,
                ),
                'label' => 'LBL_ACTION_ACCESS',
                'default' => ACL_ALLOW_NONE,
            ),
            'view' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_VIEW',
                'default' => ACL_ALLOW_NONE,
            ),
            'list' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_LIST',
                'default' => ACL_ALLOW_NONE,
            ),
            'edit' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_EDIT',
                'default' => ACL_ALLOW_NONE,
            ),
            'delete' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_DELETE',
                'default' => ACL_ALLOW_NONE,
            ),
            'import' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ALL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_NONE,
                ),
                'label' => 'LBL_ACTION_IMPORT',
                'default' => ACL_ALLOW_NONE,
            ),
            'export' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_EXPORT',
                'default' => ACL_ALLOW_NONE,
            ),
            'massupdate' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ALL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_NONE,
                ),
                'label' => 'LBL_ACTION_MASSUPDATE',
                'default' => ACL_ALLOW_ALL,
            ),
        ),
    ),
    'TrackerQuery' => array(
        'actions' => array(
            'admin' => array(
                'aclaccess' => array(
                    ACL_ALLOW_NORMAL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_ADMIN,
                ),
                'label' => 'LBL_ACTION_ADMIN',
                'default' => ACL_ALLOW_NONE,
            ),
            'access' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ENABLED,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_DISABLED,
                ),
                'label' => 'LBL_ACTION_ACCESS',
                'default' => ACL_ALLOW_NONE,
            ),
            'view' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_VIEW',
                'default' => ACL_ALLOW_NONE,
            ),
            'list' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_LIST',
                'default' => ACL_ALLOW_NONE,
            ),
            'edit' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_EDIT',
                'default' => ACL_ALLOW_NONE,
            ),
            'delete' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_DELETE',
                'default' => ACL_ALLOW_NONE,
            ),
            'import' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ALL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_NONE,
                ),
                'label' => 'LBL_ACTION_IMPORT',
                'default' => ACL_ALLOW_NONE,
            ),
            'export' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_EXPORT',
                'default' => ACL_ALLOW_NONE,
            ),
            'massupdate' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ALL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_NONE,
                ),
                'label' => 'LBL_ACTION_MASSUPDATE',
                'default' => ACL_ALLOW_ALL,
            ),
        ),
    ),
    'TrackerPerf' => array(
        'actions' => array(
            'admin' => array(
                'aclaccess' => array(
                    ACL_ALLOW_NORMAL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_ADMIN,
                ),
                'label' => 'LBL_ACTION_ADMIN',
                'default' => ACL_ALLOW_NONE,
            ),
            'access' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ENABLED,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_DISABLED,
                ),
                'label' => 'LBL_ACTION_ACCESS',
                'default' => ACL_ALLOW_NONE,
            ),
            'view' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_VIEW',
                'default' => ACL_ALLOW_NONE,
            ),
            'list' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_LIST',
                'default' => ACL_ALLOW_NONE,
            ),
            'edit' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_EDIT',
                'default' => ACL_ALLOW_NONE,
            ),
            'delete' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_DELETE',
                'default' => ACL_ALLOW_NONE,
            ),
            'import' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ALL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_NONE,
                ),
                'label' => 'LBL_ACTION_IMPORT',
                'default' => ACL_ALLOW_NONE,
            ),
            'export' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_EXPORT',
                'default' => ACL_ALLOW_NONE,
            ),
            'massupdate' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ALL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_NONE,
                ),
                'label' => 'LBL_ACTION_MASSUPDATE',
                'default' => ACL_ALLOW_ALL,
            ),
        ),
    ),
    'TrackerSession' => array(
        'actions' => array(
            'admin' => array(
                'aclaccess' => array(
                    ACL_ALLOW_NORMAL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_ADMIN,
                ),
                'label' => 'LBL_ACTION_ADMIN',
                'default' => ACL_ALLOW_NONE,
            ),
            'access' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ENABLED,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_DISABLED,
                ),
                'label' => 'LBL_ACTION_ACCESS',
                'default' => ACL_ALLOW_NONE,
            ),
            'view' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_VIEW',
                'default' => ACL_ALLOW_NONE,
            ),
            'list' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_LIST',
                'default' => ACL_ALLOW_NONE,
            ),
            'edit' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_EDIT',
                'default' => ACL_ALLOW_NONE,
            ),
            'delete' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_DELETE',
                'default' => ACL_ALLOW_NONE,
            ),
            'import' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ALL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_NONE,
                ),
                'label' => 'LBL_ACTION_IMPORT',
                'default' => ACL_ALLOW_NONE,
            ),
            'export' => array(
                'aclaccess' => $actionsDropdown,
                'label' => 'LBL_ACTION_EXPORT',
                'default' => ACL_ALLOW_NONE,
            ),
            'massupdate' => array(
                'aclaccess' => array(
                    ACL_ALLOW_ALL,
                    ACL_ALLOW_DEFAULT,
                    ACL_ALLOW_NONE,
                ),
                'label' => 'LBL_ACTION_MASSUPDATE',
                'default' => ACL_ALLOW_ALL,
            ),
        ),
    ),
);

