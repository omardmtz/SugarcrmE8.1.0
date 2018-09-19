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

require_once 'ModuleInstall/ModuleInstaller.php';

/**
 * Upgrade script to clear vardefs with wrong links/relationships.
 */
class SugarUpgradeClearVarDefs extends UpgradeScript
{
    public $order = 1100;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * @var array Hash of modules.
     */
    protected $modules;

    /**
     * In case off Employees->Users hierarhy we need a way
     * to ignore fields which are related to Users module when we scan Employees module  
     */
    protected $combinedModules = array(
        'Employees' => array(
            'Users',
        ),
    );

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        global $beanList;

        foreach ($beanList as $bean => $class) {
            $this->modules[strtolower($bean)] = $bean;
        }

        $needClearCache = false;
        foreach ($beanList as $bean => $class) {
            VardefManager::refreshVardefs($bean, $class);
            $seed = BeanFactory::newBean($bean);
            if ($seed instanceof SugarBean) {
                if (!$this->checkBean($seed)) {
                    SugarRelationshipFactory::rebuildCache();
                    $needClearCache = true;
                }
            }
        }

        if ($needClearCache) {
            $this->cleanCache();
        }
    }

    /**
     * Get fields definition object for a $seed.
     * @param SugarBean $seed
     * @return DefinitionObject
     */
    protected function getDefinition($seed)
    {
        $defs = $seed->getFieldDefinitions();
        if (empty($defs)) {
            return null;
        }

        return new DefinitionObject($defs);
    }

    /**
     * Check $seed for errors in fields' definitions.
     * @param SugarBean $seed
     * @return bool If bean is ok - return true. Return false otherwise.
     */
    protected function checkBean($seed)
    {
        $result = true;
        $fieldDefs = $this->getDefinition($seed);
        if (empty($fieldDefs)) {
            return $result;
        }
        $linkToUpdate = $this->checkFieldsType($fieldDefs);
        $wrongRelations = $this->checkFieldsRelationships($fieldDefs, $seed);
        $this->checkFieldsLinks($fieldDefs, $seed, $wrongRelations);

        // Find wrong entrances of fields in def
        foreach ($fieldDefs as $fieldnm => $field) {
            if ($this->checkFields($fieldnm, $fieldDefs, $seed->module_dir)) {
                $result = false;
            }
        }
        if ($this->deleteRelationships($wrongRelations, $seed)) {
            $result = false;
        }
        $wrongFields = $fieldDefs->getWrongDefs();
        // Delete bad files like MB deletes.
        if (!empty($wrongFields)) {
            $result = false;
            foreach ($wrongFields as $field) {
                $this->removeField($seed, $field['name']);
            }
        }
        if ($this->updateLinks($linkToUpdate, $seed, $fieldDefs)) {
            $result = false;
        }
        return $result;
    }

    /**
     * Delete wrong relations.
     * @param array $relationships
     * @param SugarBean $seed
     * @return bool
     */
    protected function deleteRelationships($relationships, $seed)
    {
        $needClearCache = false;
        if (!empty($relationships)) {
            $needClearCache = true;
            foreach ($relationships as $rel) {
                if ($this->deleteRelationshipFiles($seed, $rel)) {
                    $this->log("Relationsip {$rel} for object {$seed->object_name} removed");
                }
            }
        }
        return $needClearCache;
    }

    /**
     * Find fields with wrong link.
     * @param DefinitionObject $fieldDefs
     * @param SugarBean $seed
     * @param array $wrongRelations
     */
    protected function checkFieldsLinks($fieldDefs, $seed, $wrongRelations)
    {
        $wrongFields = $fieldDefs->getWrongDefs();
        foreach ($fieldDefs as $fieldnm => $field) {
            if (!empty($field['link'])) {
                $lname = $field['link'];
                if (is_string($lname) && (array_key_exists($lname, $wrongFields) || empty($seed->$lname))) {
                    $fieldDefs->setWrongDef($fieldnm);
                }
            } elseif (!empty($field['relationship'])) {
                // Find fields with wrong relationship
                if (in_array($field['relationship'], $wrongRelations)) {
                    $fieldDefs->setWrongDef($fieldnm);
                }
            }
        }
    }

    /**
     * Find links with wrong relationship.
     * @param DefinitionObject $fieldDefs
     * @param SugarBean $seed
     * @return array Wrong relationships.
     */
    protected function checkFieldsRelationships($fieldDefs, $seed)
    {
        $wrongRelations = array();
        foreach ($fieldDefs as $fieldnm => $field) {
            // Check for bad links
            if ($field['type'] == 'link') {
                $seed->load_relationship($field['name']);
                $wRel = false;
                if (empty($seed->{$field['name']})) {
                    $wRel = true;
                } else {
                    if ($this->checkRelationshipDef($field['name'], $seed)) {
                        // Need to delete cache of TableDictionary to avoid inclusion of deleted files.
                        if (file_exists('custom/application/Ext/TableDictionary/tabledictionary.ext.php')) {
                            unlink('custom/application/Ext/TableDictionary/tabledictionary.ext.php');
                        }
                        SugarRelationshipFactory::deleteCache();
                        SugarRelationshipFactory::rebuildCache();
                        unset($seed->{$field['name']});
                        $seed->load_relationship($field['name']);
                    }
                    $relModule = $seed->{$field['name']}->getRelatedModuleName();
                    $relBean = $this->getBean($relModule);
                    if (empty($relBean)) {
                        $wRel = true;
                    }
                }
                if ($wRel) {
                    if (!empty($field['relationship'])) {
                        $wrongRelations[] = $field['relationship'];
                    }
                    $fieldDefs->setWrongDef($fieldnm);
                }
            }
        }
        return $wrongRelations;
    }

    /**
     * Try to get bean for wrong module name.
     * @param string $module
     * @return null|SugarBean
     */
    protected function getBean($module)
    {
        $bean = BeanFactory::newBean($module);
        if (!$bean) {
            $module = strtolower($module);
            if (!empty($this->modules[$module])) {
                $bean = BeanFactory::newBean($this->modules[$module]);
            }
        }
        return $bean;
    }

    /**
     * Check definition of link's relationship for correct module names.
     * @param string $link
     * @param SugarBean $seed
     * @return bool True if definition was modified, false otherwise.
     */
    protected function checkRelationshipDef($link, $seed)
    {
        if (!$seed->$link instanceof Link2) {
            return false;
        }
        $rel = $seed->$link->getRelationshipObject();
        if (empty($rel)) {
            return false;
        }
        $modified = false;
        $def = $rel->def;
        $mainKey = $relateKey = '';
        if ($seed->$link->getSide() == REL_LHS) {
            $mainKey = 'lhs_module';
            $relateKey = 'rhs_module';
        } elseif ($seed->$link->getSide() == REL_RHS) {
            $mainKey = 'rhs_module';
            $relateKey = 'lhs_module';
        }
        if (!empty($mainKey)) {
            if (!empty($def[$relateKey])) {
                $relatedBean = $this->getBean($def[$relateKey]);
                if (!empty($relatedBean->module_name)) {
                    $beanNames = array($relatedBean->module_name);
                    if (array_key_exists($relatedBean->module_name, $this->combinedModules)) {
                        $beanNames = array_merge($beanNames, $this->combinedModules[$relatedBean->module_name]);
                    }
                    if (!in_array($def[$relateKey], $beanNames)) {
                        $def[$relateKey] = $relatedBean->module_name;
                        $modified = true;
                    }
                }
            }
            if (!empty($seed->module_name)) {
                $beanNames = array($seed->module_name);
                if (array_key_exists($seed->module_name, $this->combinedModules)) {
                    $beanNames = array_merge($beanNames, $this->combinedModules[$seed->module_name]);
                }
                if (!empty($def[$mainKey]) && !in_array($def[$mainKey], $beanNames)) {
                    $def[$mainKey] = $seed->module_name;
                    $modified = true;
                }
            }
        }
        if ($modified) {
            global $dictionary;
            $dictionary[$seed->object_name]['relationships'][$def['name']] = $def;
            $this->updateRelationshipDefinition($seed, $def);
        }
        return $modified;
    }

    /**
     * Update definition of relationship.
     * @param SugarBean $seed
     * @param array $def
     */
    protected function updateRelationshipDefinition($seed, $def)
    {

        $files = $this->getFiles($seed, $def['name']);
        foreach ($files as $file) {
            $dictionary = array();
            include $file;
            $this->upgrader->backupFile($file);
            if (!empty($dictionary[$seed->object_name]['relationships'][$def['name']])) {
                $dictionary[$seed->object_name]['relationships'][$def['name']] = $def;
            }
            if (!empty($dictionary[$def['name']])) {
                $dictionary[$def['name']] = $def;
            }
            $this->log("Updating definition of {$def['name']} for module {$seed->module_dir} in {$file}");
            $out = "<?php\n // created: " . date('Y-m-d H:i:s') . "\n";
            foreach (array_keys($dictionary) as $key) {
                $out .= override_value_to_string_recursive2('dictionary', $key, $dictionary[$key]);
            }
            file_put_contents($file, $out);
        }
    }


    /**
     * Find fields with wrong type.
     * @param DefinitionObject $fieldDefs
     * @return array
     */
    protected function checkFieldsType($fieldDefs)
    {
        $linkToUpdate = array();
        foreach ($fieldDefs as $fieldnm => $field) {
            // Skip 'team_name' field
            if ($fieldnm == 'team_name') {
                unset($fieldDefs[$fieldnm]);
                continue;
            }
            // Check for empty names
            if (empty($field['name'])) {
                $field['name'] = $fieldnm;
                $fieldDefs[$fieldnm] = $field;
            }
            if (empty($field['type'])) {
                $fieldDefs->setWrongDef($fieldnm);
                continue;
            }
            // Check correct type for `id_name` of related field.
            if ($field['type'] == 'relate') {
                if (!empty($field['id_name']) &&
                    !empty($fieldDefs[$field['id_name']]) &&
                    ((!empty($field['link']) &&
                        $field['link'] != $field['id_name'] &&
                        !$this->isFieldLink($fieldDefs[$field['id_name']], $fieldDefs) &&
                        $fieldDefs[$field['id_name']]['type'] == 'link') ||
                        (empty($fieldDefs[$field['id_name']]['rname']) ||
                            empty($fieldDefs[$field['id_name']]['link'])
                        )
                    )
                ) {
                    $def = $fieldDefs[$field['id_name']];
                    $def['type'] = 'id';
                    if (!empty($field['link'])) {
                        $def['link'] = $field['link'];
                    }
                    if (empty($def['rname'])) {
                        $def['rname'] = 'id';
                    }
                    $fieldDefs[$field['id_name']] = $def;
                    $linkToUpdate[$field['id_name']] = 1;
                }
            }
        }
        return $linkToUpdate;
    }

    /**
     * Check whether field is real link.
     * @param array $def
     * @param DefinitionObject $defs
     * @return bool true if field is link, false otherwise.
     */
    protected function isFieldLink($def, $defs)
    {
        if (empty($def['relationship'])) {
            return false;
        }
        foreach (clone($defs) as $field) {
            if (!empty($field['link']) && is_string($field['link']) && $field['link'] == $def['name']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Update definition of related field.
     * @param array $links
     * @param SugarBean $seed
     * @param DefinitionObject $defs
     * @return bool Is link updated or not
     */
    protected function updateLinks($links, $seed, $defs)
    {
        $updated = false;
        foreach (array_keys($links) as $link) {
            $def = $defs[$link];
            if (empty($def) || empty($def['relationship'])) {
                continue;
            }
            $files = $this->getFiles($seed, $def['relationship'], $def['name']);
            foreach ($files as $file) {
                $dictionary = array();
                include $file;
                if (!empty($dictionary[$seed->object_name]['fields'][$link])) {
                    $this->upgrader->backupFile($file);
                    $this->log("Updating definition of {$def['name']} for module {$seed->module_dir} in {$file}");
                    $out = "<?php\n // created: " . date('Y-m-d H:i:s') . "\n";
                    $dictionary[$seed->object_name]['fields'][$link]['type'] = 'id';
                    $dictionary[$seed->object_name]['fields'][$link]['link'] = $def['link'];
                    $dictionary[$seed->object_name]['fields'][$link]['rname'] = $def['rname'];
                    foreach (array_keys($dictionary) as $key) {
                        $out .= override_value_to_string_recursive2('dictionary', $key, $dictionary[$key]);
                    }
                    file_put_contents($file, $out);
                    $updated = true;
                }
            }
        }
        return $updated;
    }

    /**
     * Delete files for wrong relationship like MI.
     * @param SugarBean $seed
     * @param String $rel_name
     * @return bool Flag indicates if any file was deleted.
     */
    protected function deleteRelationshipFiles($seed, $rel_name)
    {
        $result = false;
        $files = $this->getFiles($seed, $rel_name);
        foreach ($files as $file) {
            $result = $result || $this->deleteFile($file);
        }
        return $result;
    }

    /**
     * Return files where relationship can be defined.
     * @param SugarBean $seed
     * @param String $rel_name
     * @return array
     */
    protected function getAvailableFiles($seed, $rel_name)
    {
        $result = array(
            'main' => array(),
            'addon' => array(),
        );
        $mod = $seed->module_dir;
        if ($mod == 'Employees') {
            $mod = 'Users';
        }
        $basepath = "custom/Extension/modules/{$mod}/Ext/";
        $relationshipsPath = "custom/Extension/modules/relationships/";
        $relationshipsDirs = array(
            'layoutdefs',
            'vardefs',
            'wirelesslayoutdefs'
        );
        $paths = array(
            "{$basepath}Vardefs/",
            "{$basepath}Layoutdefs/",
            "{$basepath}WirelessLayoutdefs/",
            "custom/Extension/application/Ext/TableDictionary/",
        );
        if (!empty($rel_name)) {
            $fn = $rel_name ."_". $mod. ".php";

            foreach ($relationshipsDirs as $relationshipDir) {
                array_push($paths, "{$relationshipsPath}{$relationshipDir}/");
            }

            $files = array(
                "custom/metadata/{$rel_name}MetaData.php",
                "{$relationshipsPath}relationships/{$rel_name}MetaData.php"
            );
            foreach ($paths as $path) {
                array_push($files, $path . $fn);
            }
            //check for standard paths like `Repair and Rebuild` does.
            foreach ($files as $file) {
                if (file_exists($file)) {
                    array_push($result['main'], $file);
                }
            }
        }
        //check for non-standard paths.
        foreach ($paths as $path) {
            foreach (glob($path . "*.php") as $file) {
                if (!in_array($file, $result['main']) && !in_array($file, $result['addon'])) {
                    array_push($result['addon'], $file);
                }
            }
        }
        return $result;
    }

    /**
     * Return file list with definition of relationship and/or field.
     * @param SugarBean $seed
     * @param string $rel_name
     * @param string $field_name
     * @return array
     */
    protected function getFiles($seed, $rel_name = '', $field_name = '')
    {
        if (empty($rel_name) && !empty($field_name)) {
            $tmpRelName = $seed->getFieldDefinition($field_name);
            $tmpRelName = isset($tmpRelName['relationship']) ? $tmpRelName['relationship'] : '';
        } else {
            $tmpRelName = $rel_name;
        }
        $availableFiles = $this->getAvailableFiles($seed, $tmpRelName);
        $result = $availableFiles['main'];
        $files = $availableFiles['addon'];
        if (!empty($rel_name)) {
            $definition = "\$dictionary['{$seed->object_name}']['relationships']['{$rel_name}']";
            foreach ($files as $file) {
                if ($this->fileHasDefinition($file, $definition)) {
                    array_push($result, $file);
                }
            }
            $definition = "\$dictionary['{$rel_name}']";
            foreach ($files as $file) {
                if ($this->fileHasDefinition($file, $definition)) {
                    array_push($result, $file);
                }
            }
        }

        if (!empty($field_name)) {
            $definition = "\$dictionary['{$seed->object_name}']['fields']['{$field_name}']";
            foreach ($files as $file) {
                if (!in_array($file, $result) && $this->fileHasDefinition($file, $definition)) {
                    array_push($result, $file);
                }
            }
        }
        return $result;
    }

    /**
     * Checks if file has provided definition.
     * @param String $file
     * @param String $definition
     * @return bool
     */
    protected function fileHasDefinition($file, $definition)
    {
        $source = file_get_contents($file);
        $tokens = token_get_all($source);
        foreach ($tokens as $ind => $token) {
            if (is_array($token) && $token[0] == T_VARIABLE) {
                $res = $token[1];
                //Added -1 to $ind's upper bound since $ind++ is used in the loop
                while ($tokens[$ind] != '=' && $ind < count($tokens)-1) {
                    $ind++;
                    if (!is_array($tokens[$ind])) {
                        if ($tokens[$ind] == '=') {
                            break;
                        }
                        $res .= $tokens[$ind];
                    } elseif ($tokens[$ind][0] == T_CONSTANT_ENCAPSED_STRING) {
                        $res .= str_replace('"', "'", $tokens[$ind][1]);
                    }
                }
                if (strpos($res, $definition) === 0 && !is_array($tokens[$ind]) && $tokens[$ind] == '=') {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Delete files for wrong relationship like DynamicField.
     * @param SugarBean $seed
     * @param Array $field
     */
    protected function deleteFieldFile($seed, $field)
    {
        $mod = $seed->module_dir;
        $base_path = "custom/Extension/modules/{$mod}/Ext/Vardefs";
        $fn = "{$base_path}/sugarfield_{$field}.php";
        if (file_exists($fn)) {
            $this->deleteFile($fn);
        } else {
	        return false;
        }

        return true;
    }

    /**
     * Backup and delete file.
     * @param String $file
     * @return bool
     */
    protected function deleteFile($file)
    {
        $this->upgrader->backupFile($file);
        return unlink($file);
    }

    /**
     * Clears cache.
     */
    protected function cleanCache()
    {
        $mi = new ModuleInstaller();
        $mi->silent = true;
        Relationship::delete_cache();
        $mi->rebuild_vardefs();
        $mi->rebuild_tabledictionary();
    }

    /**
     * Checks internal subfield definition of a field.
     * @param String $field
     * @param array $defs
     * @param String $mod
     * @return bool
     */
    protected function checkFields($field, $defs, $mod)
    {
        $def = $defs[$field];
        $changed = false;
        if (!empty($def['source']) && $def['source'] != 'db' && $def['source'] != 'custom_fields') {
            return;
        }
        foreach (array('fields', 'db_concat_fields', 'sort_on') as $subField) {
            if (empty($def[$subField])) {
                continue;
            }
            if (!is_array($def[$subField])) {
                $def[$subField] = array($def[$subField]);
            }
            foreach ($def[$subField] as $k => $value) {
                $bad = false;
                if (empty($defs[$value])) {
                    if (!empty($def['join_name']) && strpos($value, $def['join_name'] . '.') === 0) {
                        $value = str_replace($def['join_name'] . '.', '', $value);
                        if (empty($defs[$value])) {
                            $bad = true;
                        }
                    } else {
                        $bad = true;
                    }
                }
                if ($bad) {
                    unset($def[$subField][$k]);
                    $changed = true;
                }
            }
            if (empty($def[$subField])) {
                unset($def[$subField]);
            }
        }
        if ($changed) {
            $this->writeDef($def, $mod);
        }
        return $changed;
    }

    /**
     * Write def to file, like DynamicField.
     * @param array $def
     * @param string $mod
     */
    public function writeDef($def, $mod)
    {
        $base_path = "custom/Extension/modules/{$mod}/Ext/Vardefs";
        $fn = "{$base_path}/sugarfield_{$def['name']}.php";
        $seed = BeanFactory::newBean($mod);
        $this->removeField($seed, $def['name']);

        $vBean = ($mod == "aCase") ? "Case" : $mod;
        $out =  "<?php\n // created: " . date('Y-m-d H:i:s') . "\n";
        foreach ($def as $property => $val) {
            $out .= override_value_to_string_recursive(
                array(
                    $vBean,
                    "fields",
                    $def['name'],
                    $property
                ),
                "dictionary",
                $val
            );
            $out .= "\n";
        }
        file_put_contents($fn, $out);
        $this->log("New file {$fn} for field {$def['name']} of object {$seed->object_name} created");
    }

    /**
     * Scan extension directories for file with bad field definition.
     * @param SugarBean $seed
     * @param String $field
     */
    protected function removeFieldFromExt($seed, $field)
    {
        $mod = $seed->module_dir;
        $files = $this->getFiles($seed, '', $field);

        if (count($files) == 0) {
            return false;
        }

        foreach ($files as $file) {
            $dictionary = array();
            include $file;
            if (!empty($dictionary[$seed->object_name]['fields'][$field])) {
                $this->upgrader->backupFile($file);
                $this->log("Remove definition of {$field} for module {$mod}");
                $out = "<?php\n // created: " . date('Y-m-d H:i:s') . "\n";
                unset ($dictionary[$seed->object_name]['fields'][$field]);
                if (empty($dictionary)) {
                    $this->deleteFile($file);
                } else {
                    foreach (array_keys($dictionary) as $key) {
                        $out .= override_value_to_string_recursive2('dictionary', $key, $dictionary[$key]);
                    }
                    file_put_contents($file, $out);
                }
            }
        }

        return true;
    }

    /**
     * Remove definition of a field.
     * @param SugarBean $seed
     * @param String $field
     */
    protected function removeField($seed, $field)
    {
        $fieldres = $this->deleteFieldFile($seed, $field);
        $extres = $this->removeFieldFromExt($seed, $field);

        if ($fieldres || $extres) {
            $this->log("Field {$field} for object {$seed->object_name} removed");
        }
    }
}

/**
 * Helper class for fields definitions.
 * Uses for handling fields definititions, iterating through them, handling wrong fields definitions.
 */
class DefinitionObject implements Iterator, ArrayAccess, Countable
{
    /**
     * @var array
     */
    protected $defs;

    /**
     * @var array
     */
    protected $offsets;

    /**
     * @var int
     */
    protected $current_offset;

    /**
     * @var array
     */
    protected $wrongDefs;

    /**
     * @param array $definitions
     */
    public function __construct($definitions)
    {
        if (!is_array($definitions)) {
            $definitions = array($definitions);
        }
        $this->defs = $definitions;
        $this->offsets = array_keys($definitions);
        $this->current_offset = 0;
        $this->wrongDefs = array();
    }

    public function setWrongDef($key)
    {
        $this->wrongDefs[$key] = $this->offsetGet($key);
        $this->offsetUnset($key);
    }

    public function getWrongDefs()
    {
        return $this->wrongDefs;
    }

    public function current()
    {
        return $this->defs[$this->key()];
    }

    public function next()
    {
        ++$this->current_offset;
    }

    public function key()
    {
        return ($this->current_offset < $this->count()) ? $this->offsets[$this->current_offset]: null;
    }

    public function valid()
    {
        return isset($this->defs[$this->key()]);
    }

    public function rewind()
    {
        $this->current_offset = 0;
    }

    public function offsetExists($offset)
    {
        return isset($this->defs[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->defs[$offset]) ? $this->defs[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->defs[$offset] = $value;
        return $this->offsetGet($offset);
    }

    public function offsetUnset($offset)
    {
        $index = array_search($offset, $this->offsets);
        if ($index === $this->current_offset) {
            --$this->current_offset;
        }
        unset($this->defs[$offset], $this->offsets[$index]);
        $ind = 0;
        $offsets = array();
        foreach ($this->offsets as $off) {
            $offsets[$ind++] = $off;
        }
        $this->offsets = $offsets;
    }

    public function count()
    {
        return count($this->defs);
    }
}
