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


    $extensions = array(
        "actionviewmap" =>   array("section" => "action_view_map","extdir" => "ActionViewMap",  "file" => 'action_view_map.ext.php'),
        "actionfilemap" =>   array("section" => "action_file_map","extdir" => "ActionFileMap",  "file" => 'action_file_map.ext.php'),
        "actionremap" =>     array("section" => "action_remap",   "extdir" => "ActionReMap",    "file" => 'action_remap.ext.php'),
    	"administration" =>  array("section" => "administration", "extdir" => "Administration", "file" => 'administration.ext.php', "module" => "Administration"),
    	"dependencies" =>    array("section" => "dependencies",   "extdir" => "Dependencies",   "file" => 'deps.ext.php'),
    	"entrypoints" =>     array("section" => "entrypoints",	  "extdir" => "EntryPointRegistry",	"file" => 'entry_point_registry.ext.php', "module" => "application"),
    	"exts"         =>    array("section" => "extensions",	  "extdir" => "Extensions",		"file" => 'extensions.ext.php', "module" => "application"),
    	"file_access" =>     array("section" => "file_access",    "extdir" => "FileAccessControlMap", "file" => 'file_access_control_map.ext.php'),
    	"languages" =>       array("section" => "language",	      "extdir" => "Language",    	"file" => '' /* custom rebuild */),
        'dropdown_filters' => array(
            'section' => 'dropdown_filters',
            'extdir' => 'DropdownFilters',
            'file'  => 'dropdownfilters.ext.php',
        ),
    	"layoutdefs" =>      array("section" => "layoutdefs", 	  "extdir" => "Layoutdefs",     "file" => 'layoutdefs.ext.php'),
        "links" =>           array("section" => "linkdefs",       "extdir" => "GlobalLinks",    "file" => 'links.ext.php', "module" => "application"),
    	"logichooks" =>      array("section" => "hookdefs", 	  "extdir" => "LogicHooks",     "file" => 'logichooks.ext.php'),
        "tinymce" =>         array("section" => "tinymce",        "extdir" => "TinyMCE",        "file" => 'tinymce.ext.php'),
        "menus" =>           array("section" => "menu",    	      "extdir" => "Menus",          "file" => "menu.ext.php"),
        "modules" =>         array("section" => "beans", 	      "extdir" => "Include", 	    "file" => 'modules.ext.php', "module" => "application"),
        "schedulers" =>      array("section" => "scheduledefs",	  "extdir" => "ScheduledTasks", "file" => 'scheduledtasks.ext.php', "module" => "Schedulers"),
        "app_schedulers" =>  array("section" => "appscheduledefs","extdir" => "ScheduledTasks", "file" => 'scheduledtasks.ext.php', "module" => "application"),
        "userpage" =>        array("section" => "user_page",      "extdir" => "UserPage",       "file" => 'userpage.ext.php', "module" => "Users"),
        "utils" =>           array("section" => "utils",          "extdir" => "Utils",          "file" => 'custom_utils.ext.php', "module" => "application"),
    	"vardefs" =>         array("section" => "vardefs",	      "extdir" => "Vardefs",    	"file" => 'vardefs.ext.php'),
        "jsgroupings" =>     array("section" => "jsgroups",	      "extdir" => "JSGroupings",    "file" => 'jsgroups.ext.php'),
        "wireless_modules" => array("section" => "wireless_modules","extdir" => "WirelessModuleRegistry", "file" => 'wireless_module_registry.ext.php'),
        "wireless_subpanels" => array("section" => "wireless_subpanels", "extdir" => "WirelessLayoutdefs",     "file" => 'wireless.subpaneldefs.ext.php'),
        'tabledictionary' => array("section" => '', "extdir" => "TableDictionary", "file" => "tabledictionary.ext.php", "module" => "application"),

        "sidecar" => array("section"=>'sidecar', 'extdir' => 'clients/__PH_PLATFORM__/__PH_TYPE__/__PH_SUBTYPE__', 'file' => '__PH_SUBTYPE__.ext.php'),

        // Extention framework support for console commands
        'console' => array(
            'section' => 'console',
            'extdir' => 'Console',
            'file' => 'console.ext.php',
            'module' => 'application'
        ),
        'platforms' => array(
            'section' => 'platforms',
            'extdir' => 'Platforms',
            'file' => 'platforms.ext.php',
            'module' => 'application',
        ),
);
if(SugarAutoLoader::existing("custom/application/Ext/Extensions/extensions.ext.php")) {
    include("custom/application/Ext/Extensions/extensions.ext.php");
}

