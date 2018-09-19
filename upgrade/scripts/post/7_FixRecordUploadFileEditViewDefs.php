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
 * Add populate list option to record edit view defs
 */
class SugarUpgradeFixRecordUploadFileEditViewDefs extends UpgradeScript
{
    public $order = 7900;
    public $type = self::UPGRADE_ALL;
    public $version = '7.7.2.0';
    
    /**
     * @var ModuleBuilder
     */
    protected $builder;
    
    /**
     * @var MetaDataManager
     */
    protected $metaData;
    
    /**
     * @var array
     */
    protected $template = array();
    
    /**
     * {@inheritdoc}
     */
    public function __construct($upgrader)
    {
        $this->builder = new ModuleBuilder();
        $this->metaData= new MetaDataManager();
        parent::__construct($upgrader);
        
        $viewdefs = array();
        require 'include/SugarObjects/templates/file/clients/base/views/record/record.php';
        $panels = $viewdefs['<module_name>']['base']['view']['record']['panels'];
        
        foreach ($panels as $panel) {
            if ($panel['name'] == 'panel_body') {
                foreach ($panel['fields'] as $field) {
                    if (is_string($field)) {
                        $field = array('name' => $field);
                    }
                    if ($field['name'] == 'uploadfile') {
                        $this->template = $field;
                    }
                }
            }
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.5', '<')
            || version_compare($this->from_version, '7.7.2', '>=')
            || empty($this->template)
        ) {
            return;
        }
        
        $packages = $this->builder->getPackageList();

        foreach ($packages as $packageName) {
            $package = $this->builder->getPackage($packageName);
            foreach ($package->modules as $module) {
                if (array_key_exists('file', $module->config['templates'])) {
                    $this->processModule($module);
                }
            }
        }
    }
    
    /**
     * @param MBModule $module
     */
    protected function processModule(MBModule $module)
    {
        
        $files = array(
            sprintf('modules/%s/clients/base/views/record/record.php', $module->key_name),
            $module->getModuleDir() . '/clients/base/views/record/record.php',
        );
        array_walk($files, array($this, 'processFile'), $module);
    }
    
    /**
     * @param $path
     * @param $key
     * @param $module
     */
    protected function processFile($path, $key, MBmodule $module)
    {
        if (!file_exists($path)) {
            return;
        }

        $changed = false;
        $viewdefs = array();
        require $path;
        $panels = $viewdefs[$module->key_name]['base']['view']['record']['panels'];
        foreach ($panels as $panelKey => $panel) {
            if ($panel['name'] == 'panel_body') {
                foreach ($panel['fields'] as $fieldKey => $field) {
                    if (is_string($field)) {
                        $field = array('name' => $field);
                    }
                    if ($field['name'] == 'uploadfile') {
                        $changed = true;
                        $panels[$panelKey]['fields'][$fieldKey] = array_merge($this->template, $field);
                    }
                }
            }
        }
        
        if ($changed) {
            $viewdefs[$module->key_name]['base']['view']['record']['panels'] = $panels;
            write_array_to_file(
                "viewdefs[\$module_name]['base']['view']['record']",
                $viewdefs[$module->key_name]['base']['view']['record'],
                $path,
                'w',
                "<?php\n\$module_name = '{$module->key_name}';\n"
            );
        }
    }
}
