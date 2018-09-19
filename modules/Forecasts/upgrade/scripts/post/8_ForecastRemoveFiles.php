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
 * Removes files that are no longer valid in 7.0 fore the forecast module.
 */
class SugarUpgradeForecastRemoveFiles extends UpgradeScript
{
    public $order = 8501;
    public $type = self::UPGRADE_CORE;

    public function run()
    {
        $files = array();

        // we only need to remove these files if the from_version is less than 7.0 but greater or equal than 6.7.0
        if (version_compare($this->from_version, '7.0', '<')
            && version_compare($this->from_version, '6.7.0', '>=')
        ) {
            // files to delete
            $files = array(
                'modules/Forecasts/clients/base/fields/bool/forecastSchedule.hbt',
                'modules/Forecasts/clients/base/fields/bool/forecastsWorksheet.hbt',
                'modules/Forecasts/clients/base/fields/commitStage/bool.hbt',
                'modules/Forecasts/clients/base/fields/commitStage/commitStage.js',
                'modules/Forecasts/clients/base/fields/commitStage/default.hbt',
                'modules/Forecasts/clients/base/fields/commitStage/enum.hbt',
                'modules/Forecasts/clients/base/fields/editableCurrency/detail.hbt',
                'modules/Forecasts/clients/base/fields/editableCurrency/edit.hbt',
                'modules/Forecasts/clients/base/fields/editableCurrency/editableCurrency.js',
                'modules/Forecasts/clients/base/fields/editableDate/default.hbt',
                'modules/Forecasts/clients/base/fields/editableDate/detail.hbt',
                'modules/Forecasts/clients/base/fields/editableDate/edit.hbt',
                'modules/Forecasts/clients/base/fields/editableDate/editableDate.js',
                'modules/Forecasts/clients/base/fields/editableEnum/detail.hbt',
                'modules/Forecasts/clients/base/fields/editableEnum/edit.hbt',
                'modules/Forecasts/clients/base/fields/editableEnum/editableEnum.js',
                'modules/Forecasts/clients/base/fields/editableInt/detail.hbt',
                'modules/Forecasts/clients/base/fields/editableInt/edit.hbt',
                'modules/Forecasts/clients/base/fields/editableInt/editableInt.js',
                'modules/Forecasts/clients/base/fields/enum/forecastSchedule.hbt',
                'modules/Forecasts/clients/base/fields/enum/forecastsChartOptions.hbt',
                'modules/Forecasts/clients/base/fields/enum/forecastsWorksheet.hbt',
                'modules/Forecasts/clients/base/fields/historyLog/detail.hbt',
                'modules/Forecasts/clients/base/fields/historyLog/historyLog.js',
                'modules/Forecasts/clients/base/fields/inspector/detail.hbt',
                'modules/Forecasts/clients/base/fields/inspector/inspector.js',
                'modules/Forecasts/clients/base/fields/recordLink/recordLink.hbt',
                'modules/Forecasts/clients/base/fields/recordLink/recordLink.js',
                'modules/Forecasts/clients/base/fields/userLink/detail.hbt',
                'modules/Forecasts/clients/base/fields/userLink/userLink.js',
                'modules/Forecasts/clients/base/layouts/info/info.hbt',
                'modules/Forecasts/clients/base/layouts/info/info.js',
                'modules/Forecasts/clients/base/layouts/info/info.php',
                'modules/Forecasts/clients/base/layouts/inspector/inspector.js',
                'modules/Forecasts/clients/base/layouts/records/records_old.hbt',
                'modules/Forecasts/clients/base/layouts/records/records_old.js',
                'modules/Forecasts/clients/base/views/forecastsCommitButtons/forecastsCommitButtons.hbt',
                'modules/Forecasts/clients/base/views/forecastsCommitButtons/forecastsCommitButtons.js',
                'modules/Forecasts/clients/base/views/forecastsCommitButtons/forecastsCommitButtons.php',
                'modules/Forecasts/clients/base/views/forecastsCommitLog/forecastsCommitLog.hbt',
                'modules/Forecasts/clients/base/views/forecastsCommitLog/forecastsCommitLog.js',
                'modules/Forecasts/clients/base/views/forecastsCommitLog/forecastsCommitLog.php',
                'modules/Forecasts/clients/base/views/forecastsCommitted/forecastsCommitted.hbt',
                'modules/Forecasts/clients/base/views/forecastsCommitted/forecastsCommitted.js',
                'modules/Forecasts/clients/base/views/forecastsCommitted/forecastsCommitted.php',
                'modules/Forecasts/clients/base/views/forecastsProgress/forecastsProgress.hbt',
                'modules/Forecasts/clients/base/views/forecastsProgress/forecastsProgress.js',
                'modules/Forecasts/clients/base/views/forecastsProgress/forecastsProgress.php',
                'modules/Forecasts/clients/base/views/forecastsTimeperiod/forecastsTimeperiod.hbt',
                'modules/Forecasts/clients/base/views/forecastsTimeperiod/forecastsTimeperiod.js',
                'modules/Forecasts/clients/base/views/forecastsTimeperiod/forecastsTimeperiod.php',
                'modules/Forecasts/clients/base/views/forecastsFilter/forecastsFilter.hbt',
                'modules/Forecasts/clients/base/views/forecastsFilter/forecastsFilter.js',
                'modules/Forecasts/clients/base/views/forecastsFilter/forecastsFilter.php',
                'modules/Forecasts/clients/base/views/forecastsTitle/forecastsTitle.hbt',
                'modules/Forecasts/clients/base/views/forecastsTitle/forecastsTitle.js',
                'modules/Forecasts/clients/base/views/forecastsTree/forecastsTree.hbt',
                'modules/Forecasts/clients/base/views/forecastsTree/forecastsTree.js',
                'modules/Forecasts/clients/base/views/forecastsTree/forecastsTree.php',
                'modules/Forecasts/clients/base/views/forecastsWorksheet/forecastsWorksheet.hbt',
                'modules/Forecasts/clients/base/views/forecastsWorksheet/forecastsWorksheet.js',
                'modules/Forecasts/clients/base/views/forecastsWorksheet/forecastsWorksheet.php',
                'modules/Forecasts/clients/base/views/forecastsWorksheetManager/forecastsWorksheetManager.hbt',
                'modules/Forecasts/clients/base/views/forecastsWorksheetManager/forecastsWorksheetManager.js',
                'modules/Forecasts/clients/base/views/forecastsWorksheetManager/forecastsWorksheetManager.php',
                'modules/Forecasts/clients/base/views/forecastsWorksheetManagerTotals/forecastsWorksheetManagerTotals.hbt',
                'modules/Forecasts/clients/base/views/forecastsWorksheetManagerTotals/forecastsWorksheetManagerTotals.js',
                'modules/Forecasts/clients/base/views/forecastsWorksheetManagerTotals/forecastsWorksheetManagerTotals.php',
                'modules/Forecasts/clients/base/views/forecastsWorksheetTotals/forecastsWorksheetTotals.hbt',
                'modules/Forecasts/clients/base/views/forecastsWorksheetTotals/forecastsWorksheetTotals.js',
                'modules/Forecasts/clients/base/views/forecastsWorksheetTotals/forecastsWorksheetTotals.php',
                'modules/Forecasts/ForecastManagerWorksheet.php',
                'modules/Forecasts/ForecastWorksheet.php',
                'modules/Forecasts/WorksheetSeedData.php',
                'modules/Forecasts/clients/base/api/ForecastsCommittedApi.php',
                'modules/Forecasts/clients/base/api/ForecastsCurrentUserApi.php',
                'modules/Forecasts/clients/base/api/ForecastsFilters.php',
                'modules/Forecasts/clients/base/api/ForecastsWorksheetApi.php',
                'modules/Forecasts/clients/base/api/ForecastsWorksheetManagerApi.php',
                'modules/Forecasts/action_view_map.php',
                'modules/Forecasts/Chart.tpl',
                'modules/Forecasts/Charts.php',
                'modules/Forecasts/DetailView.php',
                'modules/Forecasts/DetailView.tpl',
                'modules/Forecasts/Error.php',
                'modules/Forecasts/forecasts.js',
                'modules/Forecasts/field_arrays.php',
                'modules/Forecasts/ListView.html',
                'modules/Forecasts/ListViewForecast.tpl',
                'modules/Forecasts/Menu.php',
                'modules/Forecasts/SearchForm.html',
                'modules/Forecasts/TreeData.php',
                'modules/Forecasts/Worksheet.php',
                'modules/Forecasts/index.php',
                'modules/Forecasts/views/view.noaccess.php',
                'clients/base/layouts/inspector',
                'clients/base/views/inspector-header',
                'modules/Forecasts/clients/base/views/forecastsChart',
                'modules/Forecasts/views/view.sidecar.php',
                'modules/Forecasts/clients/base/api/ForecastsCurrentUserApi.php',
                'modules/Forecasts/clients/base/api/ForecastsCommittedApi.php',
                'modules/Forecasts/clients/base/api/FiltersApi.php',
                'modules/Forecasts/clients/base/api/ForecastsWorksheetApi.php',
                'modules/Forecasts/clients/base/api/ForecastsWorksheetManager.php',
                'modules/Forecasts/tpls'                
            );
        }

        // Delete files renamed or no longer necessary from 7.0 forward.
        if (version_compare($this->from_version, '7.0', '>')) {
            $files = array(
                'modules/Forecasts/clients/base/plugins/DisableMassdelete.js'
            );
        }

        if (version_compare($this->from_version, '7.6', '<')
            && version_compare($this->from_version, '6.7.0', '>='))
        {
            $newFiles = array(
                'modules/Forecasts/clients/base/layouts/config',
                'modules/Forecasts/clients/base/layouts/config-main',
                'modules/Forecasts/clients/base/views/forecastsConfigForecastBy',
                'modules/Forecasts/clients/base/views/forecastsConfigHeaderButtoms',
                'modules/Forecasts/clients/base/views/forecastsConfigHelpers',
                'modules/Forecasts/clients/base/views/forecastsConfigRanges',
                'modules/Forecasts/clients/base/views/forecastsConfigScenarios',
                'modules/Forecasts/clients/base/views/forecastsConfigTimeperiods',
                'modules/Forecasts/clients/base/views/forecastsConfigVariables',
                'modules/Forecasts/clients/base/views/forecastsConfigWorksheetColumns',
                'tests/modules/Forecasts/clients/base/layouts/config',
                'tests/modules/Forecasts/clients/base/views/forecastsConfigForecastBy',
                'tests/modules/Forecasts/clients/base/views/forecastsConfigHeaderButtoms',
                'tests/modules/Forecasts/clients/base/views/forecastsConfigRanges',
                'tests/modules/Forecasts/clients/base/views/forecastsConfigTimeperiods',
                'tests/modules/Forecasts/clients/base/views/forecastsConfigVariables',
                'tests/modules/Forecasts/clients/base/views/forecastsConfigWorksheetColumns',
            );
            $files = array_merge($files, $newFiles);
        }

        if (version_compare($this->to_version, '7.6', '<=')) {
            $files[] = 'modules/Forecasts/upgrade/scripts/post/2_ForecastsChangeForecastBy.php';
            $files[] = 'modules/Forecasts/clients/base/fields/reportingUsers/reportingUsers.php';
        }

        if (!empty($files)) {
            $this->upgrader->fileToDelete($files, $this);
        }
    }
}

