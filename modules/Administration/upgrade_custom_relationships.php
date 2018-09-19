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
 * Searches through the installed relationships to find broken self referencing one-to-many relationships 
 * (wrong field used in the subpanel, and the left link not marked as left)
 */
function upgrade_custom_relationships($modules = array())
{
	global $current_user, $moduleList;
	if (!is_admin($current_user)) sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']); 
	
	
	if (empty($modules))
		$modules = $moduleList;
	
	foreach($modules as $module)
	{
		$depRels = new DeployedRelationships($module);
		$relList = $depRels->getRelationshipList();
		foreach($relList as $relName)
		{
			$relObject = $depRels->get($relName);
			$def = $relObject->getDefinition();
			//We only need to fix self referencing one to many relationships
			if ($def['lhs_module'] == $def['rhs_module'] && $def['is_custom'] && $def['relationship_type'] == "one-to-many")
			{
				$layout_defs = array();
				if (!is_dir("custom/Extension/modules/$module/Ext/Layoutdefs") || !is_dir("custom/Extension/modules/$module/Ext/Vardefs"))
					continue;
				//Find the extension file containing the vardefs for this relationship
				foreach(scandir("custom/Extension/modules/$module/Ext/Vardefs") as $file)
				{
					if (substr($file,0,1) != "." && strtolower(substr($file, -4)) == ".php")
					{
						$dictionary = array($module => array("fields" => array()));
						$filePath = "custom/Extension/modules/$module/Ext/Vardefs/$file";
						include($filePath);
						if(isset($dictionary[$module]["fields"][$relName]))
						{
							$rhsDef = $dictionary[$module]["fields"][$relName];
							//Update the vardef for the left side link field
							if (!isset($rhsDef['side']) || $rhsDef['side'] != 'left')
							{
								$rhsDef['side'] = 'left';
								$rhsDef['link-type'] = 'one';
								$fileContents = file_get_contents($filePath);
								$out = preg_replace(
									'/\$dictionary[\w"\'\[\]]*?' . $relName . '["\'\[\]]*?\s*?=\s*?array\s*?\(.*?\);/s',
									'$dictionary["' . $module . '"]["fields"]["' . $relName . '"]=' . var_export_helper($rhsDef) . ";",
									$fileContents
								);
								file_put_contents($filePath, $out);
							}
						}
					}
				}
				//Find the extension file containing the subpanel definition for this relationship
				foreach(scandir("custom/Extension/modules/$module/Ext/Layoutdefs") as $file)
				{
					if (substr($file,0,1) != "." && strtolower(substr($file, -4)) == ".php")
					{
						$layout_defs = array($module => array("subpanel_setup" => array()));
						$filePath = "custom/Extension/modules/$module/Ext/Layoutdefs/$file";
						include($filePath);

						$bean = BeanFactory::newBean($module);
						$fields = $bean->getFieldDefinitions();

						foreach ($layout_defs[$module]["subpanel_setup"] as $key => $subDef) {
							if (isset($layout_defs[$module]["subpanel_setup"][$key]['get_subpanel_data']) &&
								$layout_defs[$module]["subpanel_setup"][$key]['get_subpanel_data'] == $relName &&
								isset($fields[$relName]) &&
								$fields[$relName]['type'] != 'link'
							) {
								$fileContents = file_get_contents($filePath);
								$out = preg_replace(
									'/[\'"]get_subpanel_data[\'"]\s*=>\s*[\'"]' . $relName . '[\'"],/s',
									"'get_subpanel_data' => '{$def["join_key_lhs"]}',",
									$fileContents
								);
								file_put_contents($filePath, $out);
							}
						}
					}
				}
			}
		}
	}

    // Phase 2: Module builder has been incorrectly adding the id 
    // field attributes to created relationships
    foreach(glob('custom/Extension/modules/*/Ext/Vardefs/*.php') as $fileToFix) {
        // continue to the next if it's not an existing file or it's a directory
        if(!file_exists($fileToFix) || is_dir($fileToFix)) {
            continue;
        }

        $filename = basename($fileToFix);
        $dictionary = array();

        require($fileToFix);
        $tmp = array_keys($dictionary);
        if ( count($tmp) < 1 ) {
            // Empty dictionary
            continue;
        }
        $dictKey = $tmp[0];
        if ( !isset($dictionary[$dictKey]['fields']) ) {
            // Not modifying any fields, this isn't a relationship
            continue;
        }
        
        $isBadRelate = false;
        $idName = '';
        $linkField = null;
        $relateField = null;
        foreach ( $dictionary[$dictKey]['fields'] as $fieldName => $field ) {
            if ( isset($field['id_name']) && $fieldName != $field['id_name'] ) {
                if ( isset($field['type']) && $field['type'] == 'link' ) {
                    // This looks promising
                    if ( isset($dictionary[$dictKey]['fields'][$field['id_name']]) ) {
                        $idField = $dictionary[$dictKey]['fields'][$field['id_name']];
                        if ( isset($idField['type']) && $idField['type'] == 'link' ) {
                            // This looks like a winner
                            $idName = $field['id_name'];
                            $isBadRelate = true;
                            $linkField = $field;
                        }
                    }
                }
                if ( isset($field['type']) && $field['type'] == 'relate' ) {
                    $relateField = $field;
                }
            }
        }

        if ( !$isBadRelate ) {
            continue;
        }
                
		$depRels = new DeployedRelationships($dictKey);
        $relObj = $depRels->get($linkField['relationship']);
        if ( !$relObj ) {
            // The system doesn't know about the relationship object.
            $linkMetadataLocation = 'custom/metadata/'.$linkField['relationship'].'MetaData.php';
            if ( file_exists($linkMetadataLocation) ) {
                require $linkMetadataLocation;
                $linkDef = $dictionary[$linkField['relationship']];
                $relObj = RelationshipFactory::newRelationship($linkDef);
            }
        }

        $newIdField = array(
            'name' => $idName,
            'type' => 'id',
            'source' => 'non-db',
            'vname' => $idField['vname'],
            'id_name' => $idName,
            'link' => $relateField['link'],
            'table' => $relateField['table'],
            'module' => $relateField['module'],
            'rname' => 'id',
            'reportable' => false,
            'massupdate' => false,
            'duplicate_merge' => 'disabled',
            'hideacl' => true,
        );
        if ( $relObj && $relObj->getLhsModule() == $relObj->getRhsModule() ) {
            $selfReferencing = true;
        } else {
            $selfReferencing = false;
        }

        if ( $selfReferencing ) {
            $newLinkField = array(
                'name' => $relateField['link'] . '_right',
                'type' => 'link',
                'relationship' => $linkField['relationship'],
                'source' => 'non-db',
                'vname' => $idField['vname'],
                'id_name' => $relObj->getJoinKeyRHS(),
                'side' => 'right',
                'link-type' => 'many',
            );
        }
            
        $replaceString = '$dictionary["' . $dictKey . '"]["fields"]["' . $idName . '"]=' . var_export_helper($newIdField) . ";\n";
        if ( $selfReferencing ) {
            $replaceString .= '$dictionary["'. $dictKey .'"]["fields"]["'. $newLinkField['name'] .'"]=' . var_export_helper($newLinkField) .";\n";
        }

        $fileContents = file_get_contents($fileToFix);
        $out = preg_replace(
            '/\$dictionary[\w"\'\[\]]*?' . $idName . '["\'\[\]]*?\s*?=\s*?array\s*?\(.*?\);/s',
            $replaceString,
            $fileContents
        );
        if ( $selfReferencing ) {
            $out = preg_replace(
                '/\$dictionary[\w"\'\[\]]*?' . $relateField['name'] . '["\'\[\]]*?\s*?=\s*?array\s*?\(.*?\);/s',
                '$dictionary["' . $dictKey . '"]["fields"]["' . $relateField['name'] . '"]=' . var_export_helper($relateField) . ";\n",
                $out
            );
        }
        file_put_contents($fileToFix, $out);

        if ( $selfReferencing ) {
            // Now to fix bad layouts in self-linking relationships
            // Go to the Layoutdefs path
            $layoutPath = dirname(dirname($fileToFix)).'/Layoutdefs';
            foreach(glob($layoutPath.'/*.php') as $layoutToCheck) {
                // See if they match the id I just changed.
                $layout_defs = array();
                include $layoutToCheck;

                if (isset($layout_defs[$dictKey]['subpanel_setup'][$newIdField['name']])) {
                    $newLayout[$dictKey]['subpanel_setup'][$relateField['link']] = $layout_defs[$dictKey]['subpanel_setup'][$newIdField['name']];
                    $newLayout[$dictKey]['subpanel_setup'][$relateField['link']]['get_subpanel_data'] = $newLinkField['relationship'] . '_right';

                    write_array_to_file('layout_defs', $newLayout, $layoutToCheck);
                }
            }
        }
    }
    
}
if (isset($_REQUEST['execute']) && $_REQUEST['execute'])
	upgrade_custom_relationships();
