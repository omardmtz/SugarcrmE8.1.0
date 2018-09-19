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

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

function bpminbox_get_username_by_id($userid)
{
    if (empty($userid)) {
        return false;
    }
    $user = SugarModule::get('Users')->loadBean();
    $user->retrieve($userid);
    if ($userid != $user->id) {
        return false;
    }
    if (showFullName()) {
        return $user->full_name;
    } else {
        return $user->user_name;
    }
}

function bpminbox_get_display_text($temp_module, $field, $field_value, $adv_type = null, $ext1 = null, $context = null)
{
    global $app_list_strings, $current_user;

    if ($temp_module->field_defs[$field]['type'] == "relate") {
        //echo $field;
        //bug 23502, assigned user should be displayed as username here. But I don't know if created user, modified user or even other module should display names instead of ids.
        if ($temp_module->field_defs[$field]['name'] == 'assigned_user_id' && !empty($field_value) && !empty($context['for_action_display'])) {
            if ($adv_type != 'exist_user') {
                return bpminbox_get_username_by_id($field_value);
            } else {
                $target_type = "assigned_user_name";
            }
        } else {
            if (!empty($temp_module->field_defs[$field]['dbType'])) {
                $target_type = $temp_module->field_defs[$field]['dbType'];
            } else {
                return $field_value;
            }
        }
    } elseif (!empty($temp_module->field_defs[$field]['calculated']) && !empty($context['for_action_display'])) {
        //Cannot set the value of calculated fields.
        return false;
    } else {
        $target_type = $temp_module->field_defs[$field]['type'];
    }


    //Land of the "one offs"
    //This is for meetings and calls, the reminder time
    if ($temp_module->field_defs[$field]['name'] == "reminder_time") {
        $target_type = "enum";
        $temp_module->field_defs[$field]['options'] = "reminder_time_options";
    }

    if ($target_type == "assigned_user_name") {

        if ($adv_type == null) {
            $user_array = get_user_array(true, "Active", $field_value, true);
            if (!isset($user_array[$field_value])) {
                return false;
            }


            return $user_array[$field_value];
        }
        if ($adv_type == "exist_user") {

            if ($ext1 == "Manager") {
                return "Manager of the " . $app_list_strings['wflow_adv_user_type_dom'][$field_value];
            } else {
                return $app_list_strings['wflow_adv_user_type_dom'][$field_value];
            }
        }
    }


    if ($adv_type == "datetime") {
        if (empty($field_value)) {
            $field_value = 0;
        }
        return $app_list_strings['tselect_type_dom'][$field_value] . " from " . $app_list_strings['wflow_action_datetime_type_dom'][$ext1];
    }

    if ($adv_type == "exist_team") {

        return $app_list_strings['wflow_adv_team_type_dom'][$field_value];
    }

    if ($adv_type == "value_calc") {

        return "existing value" . $app_list_strings['query_calc_oper_dom'][$ext1] . " by " . $field_value;
    }

    if ($adv_type == "enum_step") {

        return $app_list_strings['wflow_adv_enum_type_dom'][$ext1] . " " . $field_value . " step(s)";
    }

    if ($target_type === 'bool') {
        $field_value = (bool)$field_value;
    }

    if ($target_type === 'text' || $target_type === 'longtext') {
        $field_value = nl2html($field_value);
    }

    $sugarField = SugarFieldHandler::getSugarField($target_type);
    //$GLOBALS['log']->debug("Field: $field is of type $target_type, before: $field_value");
    $field_value = $sugarField->getEmailTemplateValue($field_value, $temp_module->field_defs[$field], $context);
    //$GLOBALS['log']->debug("after: $field_value");

    return $field_value;
}

function bpminbox_get_href($temp_module, $field, $field_value, $adv_type = null, $ext1 = null, $context = null)
{
    global $sugar_config;
    $link = $sugar_config['site_url'];
    if (isModuleBWC($temp_module->module_dir)) {
        $params = array(
            'module' => $temp_module->module_dir,
            'action' => 'DetailView',
            'record' => $temp_module->id,
        );
        $link .= '#bwc/index.php?' . http_build_query($params);
    } else {
        $link .= '/index.php#' . rawurlencode($temp_module->module_name);
        if ($temp_module->id) {
            $link .= '/' . rawurlencode($temp_module->id);
        }
    }

    return "<a href=\"$link\">{$temp_module->name}</a>";
}

//////////////////////Processing actions

function bpminbox_process_advanced_actions(& $focus, $field, $meta_array, & $rel_this)
{
    ////////////Later expand to be able to also extract from the rel_this as the choice of returning dynamic values

    global $current_user;
    if ($meta_array['adv_type'] == 'exist_user') {

        if ($meta_array['value'] == 'current_user') {

            if (!empty($current_user)) {
                if ($meta_array['ext1'] == "Self") {
                    //kbrill bug #14923
                    return $current_user->id;
                }
                if ($meta_array['ext1'] == "Manager") {
                    //kbrill bug #14923
                    return get_manager_info($current_user->id);
                }
            } else {
                return 1;
            }
            //if value is current_user
        }

        if ($meta_array['ext1'] == "Self") {
            return $focus->{$meta_array['value']};
        }
        if ($meta_array['ext1'] == "Manager") {
            return get_manager_info($focus->{$meta_array['value']});
        }
    }

    if ($meta_array['adv_type'] == 'exist_team') {
        if ($meta_array['value'] == 'current_team') {

            if (!empty($current_user)) {
                return $current_user->default_team;
            } else {
                return 1;
            }
            //if value is current_team
        }
        return $focus->{$meta_array['value']};
    }

    if ($meta_array['adv_type'] == 'value_calc') {

        $jang = get_expression($meta_array['ext1'], $rel_this->$field, $meta_array['value']);
        //echo $jang;
        return $jang;
    }

    if ($meta_array['adv_type'] == 'enum_step') {
        global $app_list_strings;
        $options_name = $rel_this->field_defs[$field]['options'];

        $target_array = $app_list_strings[$options_name];

        bpminbox_find_start_position($target_array, $rel_this->$field);
        if ($meta_array['ext1'] == 'retreat') {
            for ($i = 0; $i < $meta_array['value']; $i++) {
                prev($target_array);
            }
        }
        if ($meta_array['ext1'] == 'advance') {
            for ($i = 0; $i < $meta_array['value']; $i++) {
                next($target_array);
            }
        }
        $new_option = key($target_array);

        if (!empty($new_option) && $new_option != "") {
            return $new_option;
        } else {
            return $rel_this->$field;
        }
    }
}

function bpminbox_find_start_position(& $target_array, $target_key)
{
    $cycle_array = $target_array;
    foreach ($cycle_array as $key => $value) {
        if (key($target_array) == $target_key) {
            return;
        } else {
            next($target_array);
        }
    }
}

function bpminbox_check_special_fields($field_name, $source_object, $use_past_array = false, $context = null)
{
    global $locale;

    // FIXME: Special cases for known non-db but allowed fields
    if ($field_name == 'full_name') {
        if ($use_past_array == false) {
            //use the future value
            return $locale->getLocaleFormattedName($source_object->first_name, $source_object->last_name);
        } else {
            //use the past value
            return $locale->getLocaleFormattedName($source_object->fetched_row['first_name'],
                $source_object->fetched_row['last_name']);
        }
    } elseif ($field_name == 'modified_by_name' && $use_past_array) {
        return $source_object->old_modified_by_name;
    } elseif ($field_name == 'assigned_user_name' && $use_past_array) {
        // We have to load the user here since fetched_row only has the ID, not the name
        return bpminbox_get_username_by_id($source_object->fetched_row['assigned_user_id']);
    } elseif ($field_name == 'team_name') {
        if ($use_past_array == false) {
            if (empty($source_object->team_set_id)) {
                if (!empty($source_object->teams)) {
                    $source_object->teams->save();
                }
            }
            $team_set_id = $source_object->team_set_id;
            $team_id = $source_object->team_id;
        } else {
            $team_set_id = $source_object->fetched_row['team_set_id'];
            $team_id = $source_object->fetched_row['team_id'];
        }
        return TeamSetManager::getCommaDelimitedTeams($team_set_id, $team_id, true);
    } else {
        /* One off exception for if we are getting future date_created value.
          Use the fetched row for it. - jgreen
         */
        if ($use_past_array == false && $field_name != "date_entered") {
            //use the future value

            return bpminbox_get_display_text($source_object, $field_name, $source_object->$field_name, null, null,
                $context);
        } else {
            //use the past value
            return bpminbox_get_display_text($source_object, $field_name, $source_object->fetched_row[$field_name],
                null, null, $context);
        }
    }
    //In future, check for maybe currency type
    //end function check_special_fields
}

/**
 *
 * @param string $field_name_mapped e.g. 'amount_usdollar'
 * @param object $source_object e.g. Opportunities
 */
function get_bean_field_type($field_name_mapped, $bean_source_object)
{
    $unknown = 'unknown';
    $field = $bean_source_object->field_defs[$field_name_mapped];
    $type = (isset($field['type']) ? $field['type'] : $unknown);
    $db_type = (isset($field['dbType']) ? $field['dbType'] : $unknown);
    return array('type' => $type, 'db_type' => $db_type);
}

function bpminbox_execute_special_logic($field_name, &$source_object)
{
    $pmse = PMSE::getInstance();
    if ($pmse->fileExists('modules/' . $source_object->module_dir . '/SaveOverload.php')) {
        require_once FileLoader::validateFilePath('modules/' . $source_object->module_dir . '/SaveOverload.php');
        perform_save($source_object);
    }
}
