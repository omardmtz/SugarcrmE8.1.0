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

/**
 * @return bool
 * @deprecated
 * @desc Creates the include language directory under the custom directory.
 */
function create_include_lang_dir()
{
    return SugarAutoLoader::ensureDir("custom/include/language");
}

/**
 * @return bool
 * @param module string
 * @deprecated
 * @desc Creates the module's language directory under the custom directory.
 */
function create_module_lang_dir($module)
{
    return SugarAutoLoader::ensureDir("custom/modules/$module/language");
}

/**
 * @return string
 * @param the_array array, language string, module string
 * @desc Returns the contents of the customized language pack.
 */
function create_field_lang_pak_contents($old_contents, $key, $value, $language, $module)
{
	if(!empty($old_contents))
	{
		$old_contents = preg_replace("'[^\[\n\r]+\[\'{$key}\'\][^\;]+;[\ \r\n]*'i", '', $old_contents);
		$contents = str_replace("\n?>","\n\$mod_strings['{$key}'] = '$value';\n?>", $old_contents);
	} else {
   	    $contents = "<?php\n"
			. '// Creation date: ' . date('Y-m-d H:i:s') . "\n"
			. "// Module: $module\n"
			. "// Language: $language\n\n"
			. "\$mod_strings['$key'] = '$value';"
			. "\n?>";
	}

   return $contents;
}

/**
 * @return string&
 * @param the_array array, language string
 * @desc Returns the contents of the customized language pack.
 */
function &create_dropdown_lang_pak_contents(&$the_array, $language)
{
   $contents = "<?php\n" .
               '// ' . date('Y-m-d H:i:s') . "\n" .
               "// Language: $language\n\n" .
               '$app_list_strings = ' .
               var_export($the_array, true) .
               ";\n?>";

   return $contents;
}

/**
 * @return bool
 * @param module string, key string, value string
 * @desc Wrapper function that will create a field label for every language.
 */
function create_field_label_all_lang($module, $key, $value, $overwrite = false)
{
   $languages = get_languages();
   $return_value = false;

   foreach($languages as $lang_key => $lang_value)
   {
      $return_value = create_field_label($module, $lang_key, $key, $value, $overwrite);
      if(!$return_value)
      {
         break;
      }
   }

   return $return_value;
}

/**
 * @return bool
 * @param module string, language string, key string, value string
 * @desc Returns true if new field label can be created, false otherwise.
 *       Probable reason for returning false: new_field_key already exists.
 */
function create_field_label($module, $language, $key, $value, $overwrite=false)
{
    $mod_strings = return_module_language($language, $module);

    if (isset($mod_strings[$key]) && !$overwrite) {
        $GLOBALS['log']->info("Tried to create a key that already exists: $key");
        return false;
    }

    $dirname = 'custom/modules/' . basename($module) . '/language';

    if (SugarAutoLoader::ensureDir($dirname)) {
        $filename = "$dirname/$language.lang.php";

        if (is_file($filename) && filesize($filename) > 0) {
            $old_contents = file_get_contents($filename);
        } else {
            $old_contents = '';
        }

        $contents = create_field_lang_pak_contents($old_contents, $key, $value, $language, $module);

        if (file_put_contents($filename, $contents) === false) {
            $GLOBALS['log']->fatal("Unable to write edited language pak to file: $filename");
            return false;
        }

        return true;
    } else {
        $GLOBALS['log']->info("Unable to create dir: $dirname");
    }

    return false;
}

/**
 * @return bool
 * @param dropdown_name string
 * @desc Wrapper function that creates a dropdown type for all languages.
 */
function create_dropdown_type_all_lang($dropdown_name)
{
   $languages = get_languages();
   $return_value = false;

   foreach($languages as $lang_key => $lang_value)
   {
      $return_value = create_dropdown_type($dropdown_name, $lang_key);
      if(!$return_value)
      {
         break;
      }
   }

   return $return_value;
}

/**
 * Utility function that allows saving of custom app strings. This is a wrapper
 * for save_custom_app_list_strings_contents() primarily because it will do the
 * exact same thing but with a different cache key for clearing.
 * 
 * @param string $contents The contents of the new file
 * @param string $language The language to save
 * @return boolean
 * @deprecated This method will be deprecated make use of save_custom_dropdown_strings instead
 */
function save_custom_app_strings_contents($contents, $language)
{
    return save_custom_app_list_strings_contents($contents, $language, 'app_strings');
}
/**
 * @return bool
 * @param array contents $app_list_strings
 * @param string $language
 * @param string $custom_dir_name
 * @desc Saves the app_list_strings to file in the 'custom' dir.
 * @deprecated This method will be deprecated make use of save_custom_dropdown_strings instead
 */
function save_custom_app_list_strings_contents($contents, $language, $cache_index = 'app_list_strings')
{
    $dirname = 'custom/include/language';
    if(SugarAutoLoader::ensureDir($dirname)) {
        $filename = "$dirname/$language.lang.php";
        if (file_put_contents($filename, $contents) === false) {
            $GLOBALS['log']->fatal("Unable to write edited language pak to file: $filename");
        } else {
            $cache_key = $cache_index . '.' . $language;
            sugar_cache_clear($cache_key);
            return true;
        }
    } else {
        $GLOBALS['log']->fatal("Unable to create dir: $dirname");
    }
    return false;
}

/**
 * @return bool
 * @param app_list_strings array
 * @desc Saves the app_list_strings to file in the 'custom' dir.
 */
function save_custom_app_list_strings(&$app_list_strings, $language)
{
    $dirname = 'custom/include/language';
    if(SugarAutoLoader::ensureDir($dirname)) {
        $filename = "$dirname/$language.lang.php";
        if(!write_array_to_file('app_list_strings', $app_list_strings, $filename)) {
            $GLOBALS['log']->fatal("Unable to write edited language pak to file: $filename");
        } else {
            $cache_key = 'app_list_strings.'.$language;
            sugar_cache_clear($cache_key);
            return true;
        }
    } else {
        $GLOBALS['log']->fatal("Unable to create dir: $dirname");
    }
    return false;
}

/**
 * @return bool
 * @param new_dd_strings array  array of language strings to change
 * @param all_languages bool whether this is for all languages or the current language
 * @param string language where labels will be replaced.  Use the current language by default
 * @desc Saves language changes to dropdown labels (in app_list_strings) in an extension file
 *
 */
function save_custom_dropdown_strings($new_dd_strings, $language = '', $all_languages = false)
{
    if (empty($new_dd_strings) || !is_array($new_dd_strings)) {
        return false;
    }
    $modules = array();
    //set the language(s) array
    if ($all_languages) {
        $languages = get_languages();
    } else {
        //use current language if no language was specified
        global $current_language;
        if (empty($language)) {
            $language =  $current_language;
        }
        $languages = array($language => $language); //need the array key defined
    }
    //iterate through each language
    foreach ($languages as $current_lang => $current_lang_name) {
        // get the default app_list_strings for the language
        $app_list_strings = return_app_list_strings_language($current_lang);
        $modules = array_keys($app_list_strings['moduleList']);
        $refresh = false;

        //iterate and overwrite/create the dropdown entries
        foreach ($new_dd_strings as $list_key => $list_val) {
            //process new string values if they exist in $app_list_strings and the new entry is different.. OR
            //if the key doesn't exist in $app_list_strings but there is a new value in the passed in strings
            //ignore if values are same or there is a new blank value
            if (isset($app_list_strings[$list_key]) && $app_list_strings[$list_key] != $new_dd_strings[$list_key]) {
                //rather than iterate and figure out which elements to add or remove, just overwrite with new values
                $app_list_strings[$list_key] = $new_dd_strings[$list_key];
                $extFilename = "custom/Extension/application/Ext/Language/$current_lang.sugar_" .
                    $list_key . '.php';
                $dir = dirname($extFilename);
                if (!is_dir($dir)) {
                    mkdir_recursive($dir);
                }
                //write out dropdown changes to app_list_strings in an extension file
                write_array_to_file_as_key_value_pair(
                    "app_list_strings['$list_key']",
                    $app_list_strings[$list_key],
                    $extFilename
                );
                $refresh = true;
            }
        }
    }
    //run quick repair if there has been a change
    if ($refresh) {
        $repairAndClear = new RepairAndClear();
        $actions = array('rebuildExtensions');
        $repairAndClear->repairAndClearAll($actions, $modules, false, false, '');
    }
}

function return_custom_app_list_strings_file_contents($language, $custom_filename = '')
{
	$contents = '';

	$filename = "custom/include/language/$language.lang.php";
	if(!empty($custom_filename))
		$filename = $custom_filename;

    if (file_exists($filename)) {
		$contents = file_get_contents($filename);
	}

	return $contents;
}

/**
 * @return bool
 * @param dropdown_name string, language string
 * @desc Creates a new dropdown type.
 */
function create_dropdown_type($dropdown_name, $language)
{
   $return_value = false;
   $app_list_strings = return_app_list_strings_language($language);

   if(isset($app_list_strings[$dropdown_name]))
   {
      $GLOBALS['log']->info("Tried to create a dropdown list key that already exists: $dropdown_name");
   }
   else
   {
		// get the contents of the custom app list strings file
		$contents = return_custom_app_list_strings_file_contents($language);

		// add the new dropdown_name to it
		if($contents == '')
		{
			$new_contents = "<?php\n\$app_list_strings['$dropdown_name'] = array(''=>'');\n?>";
		}
		else
		{
			$new_contents = str_replace('?>', "\$app_list_strings['$dropdown_name'] = array(''=>'');\n?>", $contents);
		}

		// save the new contents to file
		$return_value = save_custom_app_list_strings_contents($new_contents, $language);
   }

   return $return_value;
}

/**
 * @return string
 * @param identifier string, pairs array, first_entry string, selected_key string
 * @desc Generates the HTML for a dropdown list.
 */
function create_dropdown_html($identifier, $pairs, $first_entry='', $selected_key='')
{
   $html = "<select name=\"$identifier\">\n";

   if('' != $first_entry)
   {
      $html .= "<option name=\"\">$first_entry</option>\n";
   }

   foreach($pairs as $key => $value)
   {
      $html .= $selected_key == $key ?
               "<option name=\"$key\" selected=\"selected\">$value</option>\n" :
               "<option name=\"$key\">$value</option>\n";
   }

   $html .= "</select>\n";

   return $html;
}


function dropdown_item_delete($dropdown_type, $language, $index)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language);
   $dropdown_array =$app_list_strings_to_edit[$dropdown_type];
	helper_dropdown_item_delete($dropdown_array, $index);

	$contents = return_custom_app_list_strings_file_contents($language);
	$new_contents = replace_or_add_dropdown_type($dropdown_type, $dropdown_array,
		$contents);

   save_custom_app_list_strings_contents($new_contents, $language);
}

function helper_dropdown_item_delete(&$dropdown_array, $index)
{
   // perform the delete from the array
	$sliced_off_array = array_splice($dropdown_array, $index);
   array_shift($sliced_off_array);
	$dropdown_array = array_merge($dropdown_array, $sliced_off_array);
}

function dropdown_item_move_up($dropdown_type, $language, $index)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language);
	$dropdown_array =$app_list_strings_to_edit[$dropdown_type];

	if($index > 0 && $index < count($dropdown_array))
	{
		$key = '';
		$value = '';
		$i = 0;

		reset($dropdown_array);
		while(list($k, $v) = each($dropdown_array))
		{
			if($i == $index)
			{
				$key = $k;
				$value = $v;
				break;
			}

			$i++;
		}

		helper_dropdown_item_delete($dropdown_array, $index);
		helper_dropdown_item_insert($dropdown_array, $index - 1, $key, $value);

		// get the contents of the custom app list strings file
		$contents = return_custom_app_list_strings_file_contents($language);
		$new_contents = replace_or_add_dropdown_type($dropdown_type,
			$dropdown_array, $contents);

		save_custom_app_list_strings_contents($new_contents, $language);
	}
}

function dropdown_item_move_down($dropdown_type, $language, $index)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language);
	$dropdown_array =$app_list_strings_to_edit[$dropdown_type];

	if($index >= 0 && $index < count($dropdown_array) - 1)
	{
		$key = '';
		$value = '';
		$i = 0;

		reset($dropdown_array);
		while(list($k, $v) = each($dropdown_array))
		{
			if($i == $index)
			{
				$key = $k;
				$value = $v;
				break;
			}

			$i++;
		}

		helper_dropdown_item_delete($dropdown_array, $index);
		helper_dropdown_item_insert($dropdown_array, $index + 1, $key, $value);

		// get the contents of the custom app list strings file
		$contents = return_custom_app_list_strings_file_contents($language);
		$new_contents = replace_or_add_dropdown_type($dropdown_type,
			$dropdown_array, $contents);

		save_custom_app_list_strings_contents($new_contents, $language);
	}
}

function dropdown_item_insert($dropdown_type, $language, $index, $key, $value)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language);
	$dropdown_array =$app_list_strings_to_edit[$dropdown_type];
	helper_dropdown_item_insert($dropdown_array, $index, $key, $value);

	// get the contents of the custom app list strings file
	$contents = return_custom_app_list_strings_file_contents($language);
	$new_contents = replace_or_add_dropdown_type($dropdown_type,
		$dropdown_array, $contents);

   save_custom_app_list_strings_contents($new_contents, $language);
}

function helper_dropdown_item_insert(&$dropdown_array, $index, $key, $value)
{
	$pair = array($key => $value);
	if($index <= 0)
	{
		$dropdown_array = array_merge($pair, $dropdown_array);
	}
	if($index >= count($dropdown_array))
	{
		$dropdown_array = array_merge($dropdown_array, $pair);
	}
	else
	{
		$sliced_off_array = array_splice($dropdown_array, $index);
		$dropdown_array = array_merge($dropdown_array, $pair);
		$dropdown_array = array_merge($dropdown_array, $sliced_off_array);
	}
}

function dropdown_item_edit($dropdown_type, $language, $key, $value)
{
	$app_list_strings_to_edit = return_app_list_strings_language($language);
	$dropdown_array =$app_list_strings_to_edit[$dropdown_type];

	$dropdown_array[$key] = $value;

	$contents = return_custom_app_list_strings_file_contents($language);

	// get the contents of the custom app list strings file
	$new_contents = replace_or_add_dropdown_type($dropdown_type,
		$dropdown_array, $contents);

   save_custom_app_list_strings_contents($new_contents, $language);
}

function replace_or_add_dropdown_type($dropdown_type, &$dropdown_array,
   &$file_contents)
{
	$new_contents = "<?php\n?>";
	$new_entry = override_value_to_string('app_list_strings',
		$dropdown_type, $dropdown_array);

	if(empty($file_contents))
	{
		// empty file, must create the php tags
   	$new_contents = "<?php\n$new_entry\n?>";
	}
	else
	{
		// existing file, try to replace
		$new_contents = replace_dropdown_type($dropdown_type,
			$dropdown_array, $file_contents);

		$new_contents = dropdown_duplicate_check($dropdown_type, $new_contents);

		if($new_contents == $file_contents)
		{
			// replace failed, append to end of file
			$new_contents = str_replace("?>", '', $file_contents);
			$new_contents .= "\n$new_entry\n?>";
		}
	}

	return $new_contents;
}

function replace_or_add_app_string($name, $value,
   &$file_contents)
{
	$new_contents = "<?php\n?>";
	$new_entry = override_value_to_string('app_strings',
		$name, $value);

	if(empty($file_contents))
	{
		// empty file, must create the php tags
   	$new_contents = "<?php\n$new_entry\n?>";
	}
	else
	{
		// existing file, try to replace
		$new_contents = replace_app_string($name,
			$value, $file_contents);

		$new_contents = app_string_duplicate_check($name, $new_contents);

		if($new_contents == $file_contents)
		{
			// replace failed, append to end of file
			$new_contents = str_replace("?>", '', $file_contents);
			$new_contents .= "\n$new_entry\n?>";
		}
	}

	return $new_contents;
}


function dropdown_duplicate_check($dropdown_type, &$file_contents)
{

	if(!empty($dropdown_type) &&
		!empty($file_contents))
	{
		$pattern = '/\$app_list_strings\[\''. $dropdown_type .
			'\'\][\ ]*=[\ ]*array[\ ]*\([^\)]*\)[\ ]*;/';

		$result = array();
		preg_match_all($pattern, $file_contents, $result);

		if(count($result[0]) > 1)
		{
			$new_entry = $result[0][0];
			$new_contents = preg_replace($pattern, '', $file_contents);

			// Append the new entry.
			$new_contents = str_replace("?>", '', $new_contents);
			$new_contents .= "\n$new_entry\n?>";
			return $new_contents;
		}

		return $file_contents;
	}

	return $file_contents;

}

function replace_dropdown_type($dropdown_type, &$dropdown_array,
	&$file_contents)
{
	$new_contents = $file_contents;

	if(!empty($dropdown_type) &&
		is_array($dropdown_array) &&
		!empty($file_contents))
	{
		$pattern = '/\$app_list_strings\[\''. $dropdown_type .
			'\'\][\ ]*=[\ ]*array[\ ]*\([^\)]*\)[\ ]*;/';
		$replacement = override_value_to_string('app_list_strings',
			$dropdown_type, $dropdown_array);
		$new_contents = preg_replace($pattern, $replacement, $file_contents, 1);
	}

	return $new_contents;
}

function replace_app_string($name, $value,
	&$file_contents)
{
	$new_contents = $file_contents;

	if(!empty($name) &&
		is_string($value) &&
		!empty($file_contents))
	{
		$pattern = '/\$app_strings\[\''. $name .'\'\][\ ]*=[\ ]*\'[^\']*\'[\ ]*;/';
		$replacement = override_value_to_string('app_strings',
			$name, $value);
		$new_contents = preg_replace($pattern, $replacement, $file_contents, 1);
	}

	return $new_contents;
}

function app_string_duplicate_check($name, &$file_contents)
{

	if(!empty($name) &&
		!empty($file_contents))
	{
		$pattern = '/\$app_strings\[\''. $name .'\'\][\ ]*=[\ ]*\'[^\']*\'[\ ]*;/';

		$result = array();
		preg_match_all($pattern, $file_contents, $result);

		if(count($result[0]) > 1)
		{
			$new_entry = $result[0][0];
			$new_contents = preg_replace($pattern, '', $file_contents);

			// Append the new entry.
			$new_contents = str_replace("?>", '', $new_contents);
			$new_contents .= "\n$new_entry\n?>";
			return $new_contents;
		}
		return $file_contents;
	}

	return $file_contents;

}
