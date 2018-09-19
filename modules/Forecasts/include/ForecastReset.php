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
 * Class ForecastReset
 *
 * This is used to reset the Forecast Module and all the related modules
 */
class ForecastReset
{
    /**
     * Where do we put the Extension File
     *
     * @var string
     */
    protected $module_ext_path = 'custom/Extension/modules/Forecasts/Ext';

    /**
     * Name of the Extension File
     *
     * @var string
     */
    protected $columns_ext_file = 'clients/base/views/config-worksheet-columns/rli_fields.ext.php';

    /**
     * Additional Columns that need to be added when Forecasting on RevenueLineItems
     *
     * @var array
     */
    protected $rli_worksheet_columns = array(
        array(
            'name' => 'opportunity_name',
            'label' => 'LBL_OPPORTUNITY_NAME',
            'label_module' => 'Opportunities',
            'order' => 3
        ),
        array(
            'name' => 'product_template_name',
            'label' => 'LBL_PRODUCT',
            'label_module' => 'RevenueLineItems',
            'order' => 7
        ),
        array(
            'name' => 'list_price',
            'label' => 'LBL_LIST_PRICE',
            'label_module' => 'RevenueLineItems',
            'order' => 15
        ),
        array(
            'name' => 'cost_price',
            'label' => 'LBL_COST_PRICE',
            'order' => 16
        ),
        array(
            'name' => 'discount_price',
            'label' => 'LBL_DISCOUNT_PRICE',
            'label_module' => 'RevenueLineItems',
            'order' => 17
        ),
        array(
            'name' => 'discount_amount',
            'label' => 'LBL_TOTAL_DISCOUNT_AMOUNT',
            'order' => 18
        ),
        array(
            'name' => 'quantity',
            'label' => 'LBL_LIST_QUANTITY',
            'label_module' => 'RevenueLineItems',
            'order' => 19
        ),
        array(
            'name' => 'category_name',
            'label' => 'LBL_CATEGORY',
            'order' => 20
        ),
        array(
            'name' => 'total_amount',
            'label' => 'LBL_CALCULATED_LINE_ITEM_AMOUNT',
            'label_module' => 'RevenueLineItems',
            'order' => 21
        ),
    );

    /**
     * What tables should be truncated
     *
     * @var array
     */
    protected $tables = array(
        'forecast_worksheets',
        'forecasts'
    );

    /**
     * Reset the Forecast Module to have no data
     *
     * This truncates all the tables related to forecasts
     */
    public function truncateForecastData()
    {
        /* @var $db DBManager */
        $db = DBManagerFactory::getInstance();
        foreach ($this->tables as $table) {
            $db->commit();
            $db->query($db->truncateTableSQL($table));
            $db->commit();
        }

        // truncate the forecast_manager_worksheets_audit table if it exists
        $fmw = BeanFactory::newBean('ForecastManagerWorksheets');
        $audit_table = $fmw->get_audit_table_name();
        if ($db->tableExists($audit_table)) {
            $db->commit();
            $db->query($db->truncateTableSQL($audit_table));
            $db->commit();
        }

        // we don't truncate the manager worksheets, we need to delete the committed records first
        $sql = 'DELETE FROM forecast_manager_worksheets WHERE draft = 0';
        $db->query($sql);
        $db->commit();

        // now update all the draft records to have best, likely and worst set to 0
        // and all the other additional behind the scenes fields set to 0.
        // this basically makes a draft row with just a quota in it
        $sql = 'UPDATE forecast_manager_worksheets SET
                  best_case = 0,
                  worst_case = 0,
                  likely_case = 0,
                  best_case_adjusted = 0,
                  worst_case_adjusted = 0,
                  likely_case_adjusted = 0,
                  opp_count = 0,
                  pipeline_opp_count = 0,
                  pipeline_amount = 0,
                  closed_amount = 0,
                  manager_saved = 0';
        $db->query($sql);
        $db->commit();
    }

    /**
     * Handle setting the default worksheet_columns back into the db.
     *
     * @param String $forecast_by The Module that we are currently forecasting by
     */
    public function setDefaultWorksheetColumns($forecast_by, $rebuild = true)
    {
        SugarAutoLoader::load('modules/Forecasts/ForecastsDefaults.php');
        $edition = ($forecast_by === 'RevenueLineItems') ? 'ent' : 'pro';
        $columns = ForecastsDefaults::getWorksheetColumns($edition);

        // we need to check the setting for best and worst
        $settings = Forecast::getSettings(true);
        if ($settings['show_worksheet_best'] != 1) {
            // remove best since it's include by default
            foreach ($columns as $i => $column) {
                if ($column === 'best_case') {
                    unset($columns[$i]);
                    break;
                }
            }
        }

        if ($settings['show_worksheet_worst'] == 1) {
            // add worse_Case since it's not include by default
            $columns[] = 'worst_case';
        }

        /* @var $admin Administration */
        $admin = BeanFactory::newBean('Administration');
        $admin->saveSetting('Forecasts', 'worksheet_columns', $columns, 'base');

        // update the metadata
        $this->updateConfigWorksheetColumnsMetadata($forecast_by, $rebuild);

        // now write out the correct list view
        $this->setWorksheetColumns('base', $columns, $forecast_by);

    }

    public function setWorksheetColumns($platform, $worksheetColumns, $forecastBy)
    {
        if (!is_array($worksheetColumns)) {
            return false;
        }

        SugarAutoLoader::load('modules/ModuleBuilder/parsers/ParserFactory.php');
        $listDefsParser = ParserFactory::getParser(MB_LISTVIEW, 'ForecastWorksheets', null, null, $platform);
        $listDefsParser->resetPanelFields();

        // get the proper order from the admin panel, where we defined what is displayed, in the order that we want it
        $mm = MetaDataManager::getManager();
        $views = $mm->getModuleViews('Forecasts');
        $fields = $views['config-worksheet-columns']['meta']['panels'][0]['fields'];

        /* @var $forecastWorksheet ForecastWorksheet */
        $forecastWorksheet = BeanFactory::newBean('ForecastWorksheets');

        // sort the fields correctly
        usort(
            $fields,
            function ($a, $b) {
                if ($a['order'] === $b['order']) {
                    return 0;
                }
                return ($a['order'] < $b['order']) ? -1 : 1;
            }
        );

        $cteable = array(
            'commit_stage',
            'worst_case',
            'likely_case',
            'best_case',
            'date_closed',
            'sales_stage'
        );

        $currency_fields = array(
            'worst_case',
            'likely_case',
            'best_case',
            'list_price',
            'cost_price',
            'discount_price',
            'discount_amount',
            'total_amount'
        );

        foreach ($fields as $field) {
            if (!in_array($field['name'], $worksheetColumns)) {
                continue;
            }

            $column = $field['name'];
            $additionalDefs = array();

            // set the label for the parent_name field, depending on what we are forecasting by
            if ($column == 'parent_name') {
                $label = $forecastBy == 'Opportunities' ? 'LBL_OPPORTUNITY_NAME' : 'LBL_REVENUELINEITEM_NAME';
                $additionalDefs = array_merge(
                    $additionalDefs,
                    array('label' => $label)
                );
            }

            if (in_array($column, $cteable)) {
                $additionalDefs = array_merge(
                    $additionalDefs,
                    array('click_to_edit' => true)
                );
            }
            if (in_array($column, $currency_fields)) {
                $additionalDefs = array_merge(
                    $additionalDefs,
                    array(
                        'convertToBase' => true,
                        'showTransactionalAmount' => true
                    )
                );
            }

            // make sure sortable matches what is in the vardefs
            $fieldVarDefs = $forecastWorksheet->getFieldDefinition($column);
            if (isset($fieldVarDefs['sortable'])) {
                $additionalDefs['sortable'] = $fieldVarDefs['sortable'];
            }

            $listDefsParser->addField($column, $additionalDefs);
        }

        // save the file, but we don't need to load the the $_REQUEST, so pass false
        $listDefsParser->handleSave(false);
    }


    /**
     * Update the metadata depending on what we are forecasting on
     *
     * @param String $forecast_by The Module that we are currently forecasting by
     */
    public function updateConfigWorksheetColumnsMetadata($forecast_by, $refresh = true)
    {
        if ($forecast_by === 'RevenueLineItems') {
            $contents = "<?php\n\n";
            $key = "viewdefs['Forecasts']['base']['view']['config-worksheet-columns']['panels'][0]['fields']";
            foreach ($this->rli_worksheet_columns as $val) {
                $contents .= override_value_to_string_recursive2($key, $val['name'], $val, false);
            }

            sugar_file_put_contents($this->module_ext_path . DIRECTORY_SEPARATOR . $this->columns_ext_file, $contents);
        } else {
            if (file_exists($this->module_ext_path . DIRECTORY_SEPARATOR . $this->columns_ext_file)) {
                unlink($this->module_ext_path . DIRECTORY_SEPARATOR . $this->columns_ext_file);
            }
        }

        if ($refresh) {
            $this->runRebuildExtensions(array('Forecasts'));

            MetaDataManager::refreshModulesCache('Forecasts');
        }
    }

    public function runRebuildExtensions(array $modules = array('Forecasts'))
    {
        SugarAutoLoader::load('modules/Administration/QuickRepairAndRebuild.php');
        $rac = new RepairAndClear();
        $rac->show_output = false;
        $rac->module_list = $modules;
        $rac->clearVardefs();
        $rac->rebuildExtensions($modules);
    }
}
