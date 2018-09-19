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
require_once('modules/Reports/templates/templates_group_chooser.php');
require_once('modules/Reports/templates/templates_reports_functions_js.php');
require_once('modules/Reports/templates/templates_list_view.php');
require_once('modules/Reports/templates/templates_reports_request_js.php');

require_once('modules/Reports/config.php');

global $global_json;
$global_json = getJSONobj();

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

//////////////////////////////////////////////
// TEMPLATE:
//////////////////////////////////////////////
function reportCriteriaWithResult(&$reporter,&$args) {
	global $current_user,$theme;
	global $current_language;
	global $mod_strings, $app_strings, $timedate;
	global $sugar_config, $sugar_version;
    global $app_list_strings;

	$sort_by = '';
	$sort_dir = '';
	$summary_sort_by = '';
	$summary_sort_dir = '';
	$report_type = '';


	$smarty = new Sugar_Smarty();

	if (isset($reporter->report_def['order_by'][0]['name']) && isset($reporter->report_def['order_by'][0]['table_key'])) {
		$sort_by = $reporter->report_def['order_by'][0]['table_key'].":".$reporter->report_def['order_by'][0]['name'];
	} // if
	if (isset($reporter->report_def['order_by'][0]['sort_dir'])) {
		$sort_dir = $reporter->report_def['order_by'][0]['sort_dir'];
	} // if

	if ( ! empty($reporter->report_def['summary_order_by'][0]['group_function']) && $reporter->report_def['summary_order_by'][0]['group_function'] == 'count') {

            $summary_sort_by = $reporter->report_def['summary_order_by'][0]['table_key'].":".'count';
	} else if ( isset($reporter->report_def['summary_order_by'][0]['name'])) {
		$summary_sort_by = $reporter->report_def['summary_order_by'][0]['table_key'].":".$reporter->report_def['summary_order_by'][0]['name'];

		if ( ! empty($reporter->report_def['summary_order_by'][0]['group_function'])) {
			$summary_sort_by .=":". $reporter->report_def['summary_order_by'][0]['group_function'];
		} else if ( ! empty($reporter->report_def['summary_order_by'][0]['column__function'])) {
	    	$summary_sort_by .=":". $reporter->report_def['summary_order_by'][0]['column_function'];
	    } // else if
	} // else if

	if ( isset($reporter->report_def['summary_order_by'][0]['sort_dir'])) {
		$summary_sort_dir = $reporter->report_def['summary_order_by'][0]['sort_dir'];
	} // if
	if ( isset($reporter->report_def['report_type'])) {
		$report_type = $reporter->report_def['report_type'];
	} // if

	$issetSaveResults = false;
	$isSaveResults = false;

	$request = InputValidation::getService();
	$saveReportAs = $request->getValidInputRequest('save_report_as');

	if (isset($args['save_result'])) {
		$issetSaveResults = true;
		$smarty->assign('save_report_as_str', $saveReportAs);
		if($args['save_result']) {
			$isSaveResults = true;
		} // if
	} // if
    $buttonDuplicateAsOrigin = '<a onclick=\'document.EditView.to_pdf.value="";document.EditView.to_csv.value="";document.EditView.action.value="ReportsWizard";document.EditView.save_as.value="true";' .
        'document.EditView.submit();\' href=\'#\'>' . $mod_strings['LBL_DUPLICATE_AS_ORIGINAL'] . '</a>';
    $buttonDuplicateAsSummation = '<a onclick=\'document.EditView.to_pdf.value="";document.EditView.to_csv.value="";document.EditView.action.value="ReportsWizard";document.EditView.save_as.value="true";' .
        'document.EditView.save_as_report_type.value="summation";document.EditView.submit();\' href=\'#\'>' . $mod_strings['LBL_DUPLICATE_AS_SUMMATON'] . '</a>';
    $buttonDuplicateAsDetail = '<a onclick=\'document.EditView.to_pdf.value="";document.EditView.to_csv.value="";document.EditView.action.value="ReportsWizard";document.EditView.save_as.value="true";' .
        'document.EditView.save_as_report_type.value="summation_with_details";document.EditView.submit();\' href=\'#\'>' . $mod_strings['LBL_DUPLICATE_AS_SUMMATION_DETAILS'] . '</a>';
    $buttonDuplicateAsMatrix = '<a onclick=\'document.EditView.to_pdf.value="";document.EditView.to_csv.value="";document.EditView.action.value="ReportsWizard";document.EditView.save_as.value="true";' .
        'document.EditView.save_as_report_type.value="matrix";document.EditView.submit();\' href=\'#\'>' . $mod_strings['LBL_DUPLICATE_AS_MATRIX'] . '</a>';
    $buttonDuplicateAsTabular = '<a onclick=\'document.EditView.to_pdf.value="";document.EditView.to_csv.value="";document.EditView.action.value="ReportsWizard";document.EditView.save_as.value="true";' .
        'document.EditView.save_as_report_type.value="tabular";document.EditView.submit();\' href=\'#\'>' . $mod_strings['LBL_DUPLICATE_AS_ROWS_AND_COLS'] . '</a>';

	if ($report_type == 'tabular') {
		$duplicateButtons = array(
            '<input class="button" onclick="showDuplicateOverlib(this,\'tabular\');" type="button" ' .
				' value="'.$app_strings['LBL_DUPLICATE_BUTTON_LABEL'].'">',
            $buttonDuplicateAsOrigin ,
            $buttonDuplicateAsSummation ,
            $buttonDuplicateAsDetail ,
            $buttonDuplicateAsMatrix
        );
	}
	// Summation with Details
	else if ($report_type == 'summary' && (!empty($reporter->report_def['display_columns']) && count($reporter->report_def['display_columns']) > 0 )) {
		$canCovertToMatrix = 0;
		if ((!empty($reporter->report_def['group_defs']) && count($reporter->report_def['group_defs']) <= 3  ))
			$canCovertToMatrix = 1;
		$duplicateButtons = array(
            '<input type=button class="button" onclick="showDuplicateOverlib(this,\'summation_with_details\','.$canCovertToMatrix.');" type="button" ' .
                'value="'.$app_strings['LBL_DUPLICATE_BUTTON_LABEL'].'"/>',
            $buttonDuplicateAsOrigin ,
            $buttonDuplicateAsSummation ,
            $buttonDuplicateAsTabular
        );
        if ($canCovertToMatrix) {
            $duplicateButtons[] = $buttonDuplicateAsMatrix;
        }
    }
	// Matrix
	else if ($report_type == 'summary' && (!empty($reporter->report_def['layout_options']))) {
        $duplicateButtons = array(
            '<input class="button" onclick="showDuplicateOverlib(this,\'matrix\');" type="button" ' .
				' value="'.$app_strings['LBL_DUPLICATE_BUTTON_LABEL'].'">',
            $buttonDuplicateAsOrigin,
            $buttonDuplicateAsSummation,
            $buttonDuplicateAsDetail,
            $buttonDuplicateAsTabular,
        );
    }

	// Summation
	else if ($report_type == 'summary') {
		$canCovertToMatrix = 0;
		if ((!empty($reporter->report_def['group_defs']) && count($reporter->report_def['group_defs']) <= 3  ))
        {
			$canCovertToMatrix = 1;
        }

		$duplicateButtons = array(
            '<input class="button" onclick="showDuplicateOverlib(this,\'summation\','.$canCovertToMatrix.');" type="button" ' .
				'value="'.$app_strings['LBL_DUPLICATE_BUTTON_LABEL'].'" >',
            $buttonDuplicateAsOrigin ,
            $buttonDuplicateAsDetail ,
            $buttonDuplicateAsTabular,
        );


        if ($canCovertToMatrix) {
            $duplicateButtons[] = $buttonDuplicateAsMatrix;
        }

    }

    $smarty->assign('duplicateButtons', $duplicateButtons);
	$smarty->assign('mod_strings', $mod_strings);
	$smarty->assign('app_strings', $app_strings);
	$smarty->assign('current_language', $current_language);
	$smarty->assign('sugar_config', $sugar_config);
	$smarty->assign('sugar_version', $sugar_version);
	$smarty->assign('issetSaveResults', $issetSaveResults);
	$smarty->assign('isSaveResults', $isSaveResults);
	$smarty->assign('report_type', $report_type);
	$smarty->assign('reportDetailView', getReportDetailViewString($reporter,$args));
	$form_header = get_form_header( $mod_strings['LBL_TITLE'].": ".$args['reporter']->saved_report->name, "", false);
	$smarty->assign('form_header', $form_header);
	$smarty->assign('report_offset', $reporter->report_offset);
	$smarty->assign('sort_by', $sort_by);
	$smarty->assign('sort_dir', $sort_dir);
	$smarty->assign('summary_sort_by', $summary_sort_by);
	$smarty->assign('summary_sort_dir', $summary_sort_dir);

	$saveAs = $request->getValidInputRequest('save_as');
	$record = $request->getValidInputRequest('record', 'Assert\Guid');
	if ($saveAs !== null &&  $saveAs == 'true') {
	    $report_id = '';
	} else if (isset($reporter->saved_report->id) ) {
	    $report_id = $reporter->saved_report->id;
	} elseif (!empty($record)) {
	    $report_id = $record;
	} else {
	    $report_id = '';
	} // else

	$smarty->assign('report_id', $report_id);
	$smarty->assign('to_pdf', isset($_REQUEST['to_pdf']) ? $_REQUEST['to_pdf'] : "");
	$smarty->assign('to_csv', isset($_REQUEST['to_csv']) ? $_REQUEST['to_csv'] : "");

	$isAdmin = false;
	if ($current_user->is_admin) {
		$isAdmin = true;
	} // if
	$smarty->assign('isAdmin', $isAdmin);
	if ($isAdmin) {
		$smarty->assign('show_query', true);
		if (!empty($_REQUEST['show_query'])) {
			$smarty->assign('show_query_checked', true);
		} // if
	} // if

	$schedule_value = $app_strings['LBL_LINK_NONE'];
	if(isset($args['reporter']->saved_report->schedule_id) && $args['reporter']->saved_report->active == 1) {
		$schedule_value = $timedate->to_display_date_time($args['reporter']->saved_report->next_run);
	} // if
	$smarty->assign('schedule_value', $schedule_value);

	$current_favorites = $current_user->getPreference('favorites', 'Reports');
	if (!is_array($current_favorites)){
		$current_favorites = array();
	}
	$report_ids_array = array_keys($current_favorites, $current_user->id);
	if (!is_array($report_ids_array)) {
		$report_ids_array = array();
	} // if

	if (isset($args['warnningMessage'])) {
		$smarty->assign('warnningMessage', $args['warnningMessage']);
	} // if
	if(!empty($args['reporter']->saved_report)) {
	    $context = array("bean" => $args['reporter']->saved_report);
	} else {
	    $context = array();
	}
	$report_edit_access = SugarACL::checkAccess('Reports', 'edit', $context);
	$smarty->assign('report_edit_access', $report_edit_access);
	$report_delete_access = SugarACL::checkAccess('Reports', 'delete', $context);
	$smarty->assign('report_delete_access', $report_delete_access);
	$report_export_access = SugarACL::checkAccess('Reports', 'export', $context);
	$smarty->assign('report_export_access', $report_export_access);

    //check to see if exporting is allowed
    $isExportAccess = hasExportAccess($args) && $report_export_access;

	$smarty->assign('report_export_as_csv_access', $isExportAccess);
	$formSubmit = $request->getValidInputRequest('form_submit', null, false);
	$smarty->assign('form_submit', $formSubmit);

	$global_json = getJSONobj();
	global $ACLAllowedModules;
	$ACLAllowedModules = getACLAllowedModules();
	$smarty->assign('ACLAllowedModules', $global_json->encode(array_keys($ACLAllowedModules)));

	template_reports_filters($smarty, $args);
	$smarty->assign('reporter_report_type', $args['reporter']->report_type);
	$smarty->assign('current_user_id', $current_user->id);
	$smarty->assign('md5_current_user_id', md5($current_user->id));
	if (!hasRuntimeFilter($reporter)) {
		$smarty->assign('filterTabStyle', "display:none");
	} else {
		$smarty->assign('filterTabStyle', "display:''");
	}
	$smarty->assign('reportResultHeader', $mod_strings['LBL_REPORT_RESULTS']);
	$reportDetailsButtonTitle = $mod_strings['LBL_REPORT_HIDE_DETAILS'];
	$reportDetailsTableStyle = '';
	if (isset($args['reportCache'])) {
		$reportCache = $args['reportCache'];
		if (!empty($reportCache->report_options_array)) {
			if (array_key_exists("showDetails", $reportCache->report_options_array) && !$reportCache->report_options_array['showDetails']) {
				$reportDetailsButtonTitle = $mod_strings['LBL_REPORT_SHOW_DETAILS'];
				$reportDetailsTableStyle = 'display:none';
			}
		} // if
	} // if



	$smarty->assign('reportDetailsButtonTitle', $reportDetailsButtonTitle);
	$smarty->assign('reportDetailsTableStyle', $reportDetailsTableStyle);
    $smarty->assign('cache_path', sugar_cached(''));
	template_reports_request_vars_js($smarty, $reporter,$args);
	//custom chart code
    $sugarChart = SugarChartFactory::getInstance();
	$resources = $sugarChart->getChartResources();
	$smarty->assign('chartResources', $resources);
	$smarty->assign('id', empty($_REQUEST['id']) ? false : $_REQUEST['id']);

    //Bug#51609: Create action buttons for report view. Previously existed in _reportCriteriaWithResult.tpl
    $buttons = array();
    $buttons[] = <<<EOD
        <input name="runReportButton" id="runReportButton" type="submit" class="button" accessKey="{$mod_strings['LBL_RUN_REPORT_BUTTON_KEY']}" title="{$mod_strings['LBL_RUN_BUTTON_TITLE']}"
               onclick="this.form.to_pdf.value='';this.form.to_csv.value='';this.form.save_report.value='';" value="{$mod_strings['LBL_RUN_REPORT_BUTTON_LABEL']}">
EOD
    ;

    $reportName =  $args['reporter']->saved_report->name;
    $reportNameEncoded = json_encode($reportName, JSON_HEX_APOS | JSON_HEX_QUOT);

    $shareButtonCode = 'parent.SUGAR.App.bwc.shareRecord("Reports", "$report_id", '.$reportNameEncoded.");";
    $buttons[] = <<<EOD
        <input type="button" class="button" name="shareReportButton" id="shareReportButton" accessKey="{$app_strings['LBL_SHARE_BUTTON_KEY']}" value="{$app_strings['LBL_SHARE_BUTTON_LABEL']}" title="{$app_strings['LBL_SHARE_BUTTON_TITLE']}"
               onclick='$shareButtonCode'>
EOD;

    if ($report_edit_access) {
        $buttons[] = <<<EOD
            <input type="submit" class="button" name="editReportButton" id="editReportButton" accessKey="{$app_strings['LBL_EDIT_BUTTON_KEY']}" value="{$app_strings['LBL_EDIT_BUTTON_LABEL']}" title="{$app_strings['LBL_EDIT_BUTTON_TITLE']}"
               onclick="this.form.to_pdf.value='';this.form.to_csv.value='';this.form.action.value='ReportsWizard';">
EOD
        ;
    }
    array_push($buttons, $duplicateButtons);

    if ($report_edit_access) {
        $buttons[] = <<<EOD
        <input type="button" class="button"  name="scheduleReportButton" id="scheduleReportButton" value="{$mod_strings['LBL_REPORT_SCHEDULE_TITLE']}"
               onclick="schedulePOPUP()">
EOD
        ;
    }
    if ($report_export_access) {
        //workaround for SP-1685, Need to clear bwcModel so change confirmation doesn't fire after making a PDF.
        $buttons[] = <<<EOD
        <input type="submit" class="button" name="printPDFButton" id="printPDFButton" accessKey="{$app_strings['LBL_VIEW_PDF_BUTTON_KEY']}" value="{$app_strings['LBL_VIEW_PDF_BUTTON_LABEL']}" title="{$app_strings['LBL_VIEW_PDF_BUTTON_TITLE']}"
               onclick="if (window&&window.parent&&window.parent.App&&window.parent.App.controller
               &&window.parent.App.controller.layout
               &&window.parent.App.controller.layout._components[0]
               &&window.parent.App.controller.layout._components[0].bwcModel
               &&window.parent.App.controller.layout._components[0].bwcModel.clear)
               {window.parent.App.controller.layout._components[0].bwcModel.clear({silent:true});this.form.save_report.value='';this.form.to_csv.value='';this.form.to_pdf.value='on'}">
EOD
        ;
    }
    if ($isExportAccess) {
        $buttons[] = <<<EOD
        <input type="button" class="button"  name="exportReportButton" id="exportReportButton" value="{$mod_strings['LBL_EXPORT']}" onclick="do_export();">
EOD
        ;
    }

    if ($report_delete_access) {
        $buttons[] = <<<EOD
        <input type="button" class="button"  name="deleteReportButton" id="deleteReportButton" accessKey="{$app_strings['LBL_DELETE_BUTTON_KEY']}" value="{$app_strings['LBL_DELETE_BUTTON_LABEL']}" title="{$app_strings['LBL_DELETE_BUTTON_TITLE']}"
               onclick="if (confirm(SUGAR.language.get('app_strings','NTC_DELETE_CONFIRMATION'))){this.form.to_pdf.value='';this.form.to_csv.value='';this.form.is_delete.value='1';this.form.action.value='ReportsWizard';this.form.submit();}">
EOD
        ;
    }
    $smarty->assign('action_button', $buttons);

    $reportType = ($reporter->report_def['report_type'] == 'tabular' ? $mod_strings['LBL_ROWS_AND_COLUMNS_REPORT'] : $mod_strings['LBL_SUMMATION_REPORT']);
    if (!empty($reporter->report_def['display_columns']) &&
        !empty($reporter->report_def['group_defs'])) {

        $reportType = $mod_strings['LBL_SUMMATION_WITH_DETAILS'];
    } // if
    if (isset($reporter->report_def['layout_options'])) {
        $reportType = $mod_strings['LBL_MATRIX_REPORT'];
    } // if
    $fullTableList = $reporter->report_def['full_table_list'];
    $fullTableListArray = array();
    foreach($fullTableList as $key => $value) {
        if (!isset($value['name'])) {
            if (!isset($fullTableListArray[$value['module']])) {
                $module_str = $value['module'];
                if(isset($app_list_strings['moduleList'][$module_str]))
                {
                    $module_str = $app_list_strings['moduleList'][$module_str];
                }
                $fullTableListArray[$value['module']] = $module_str;
            } // if
        } else {
            if (!isset($fullTableListArray[$value['name']])) {
                $fullTableListArray[$value['name']] = $value['name'];
            } // if
        } // else
    } // foreach
    $displayColumnsList = $reporter->report_def['display_columns'];
    $displayColumnsArray = array();
    foreach($displayColumnsList as $key => $value) {
        $displayColumnsArray[] = $value['label'];
    } // foreach
    $group_defs = $reporter->report_def['group_defs'];
    $group_defsArray = array();
    if (!empty($group_defs)) {
        foreach($group_defs as $key => $value) {
            $group_defsArray[] = $value['label'];
        } // foreach
    } // if
    $summary_columnsList = $reporter->report_def['summary_columns'];
    $summaryColumnsArray = array();
    if (!empty($summary_columnsList)) {
        foreach($summary_columnsList as $key => $value) {
            $summaryColumnsArray[] = $value['label'];
        } // foreach
    } // if
    $summaryAndGroupDefData="";
    if (!empty($group_defs) && !empty($summary_columnsList)) {
        $summaryAndGroupDefData = '<tr><td wrap="true">';
        $summaryAndGroupDefData = $summaryAndGroupDefData . "<b>" . $mod_strings['LBL_GROUP_BY'] . ": </b>" . implode(", ", $group_defsArray) . "</td><td wrap=\"true\">";
        $summaryAndGroupDefData = $summaryAndGroupDefData . "<b>" . $mod_strings['LBL_SUMMARY_COLUMNS'] . ": </b>" . implode(", ", $summaryColumnsArray) . "</td></tr>";

    } else if (!empty($group_defs) || !empty($summary_columnsList)) {
        $summaryAndGroupDefData = '<tr><td wrap="true">';
        if (!empty($group_defs)) {
            $summaryAndGroupDefData = $summaryAndGroupDefData . "<b>" . $mod_strings['LBL_GROUP_BY'] . ": </b>" . implode(", ", $group_defsArray) . "</td><td wrap=\"true\">&nbsp;</td>";
        } // if
        if (!empty($summary_columnsList)) {
            $summaryAndGroupDefData = $summaryAndGroupDefData . "<b>" . $mod_strings['LBL_SUMMARY_COLUMNS'] . ": </b>" . implode(", ", $summaryColumnsArray) . "</td><td wrap=\"true\">&nbsp;</td>";
        } // if
    } // else

    $reportFilters = "";
    if (isset($reporter->report_def['filters_def']) && !isset($reporter->report_def['filters_def']['Filter_1'][0])){
        $reportFilters = " " . $mod_strings['LBL_NONE_STRING'];
    } else {
        $reportFilters = "<span id=\"filter_results\" valign=\"bottom\">&nbsp;<img id=\"filter_results_image\" src=\"".SugarThemeRegistry::current()->getImageURL('basic_search.gif')."\" width=\"8px\" height=\"10px\" onclick=\"showFilterString();\"></span><span id=\"filter_results_text\" style=\"visibility:hidden;\"></span>";
    } // else

    $smarty->assign('reportFilters', $reportFilters);
    $smarty->assign('reportName', $reportName);
    $smarty->assign('reportType', $reportType);
    $smarty->assign('reportModuleList', implode(", ", $fullTableListArray));
    $smarty->assign('reportDisplayColumnsList', implode(", ", $displayColumnsArray));
    require_once('modules/Teams/TeamSetManager.php');
    $smarty->assign('reportTeam', TeamSetManager::getFormattedTeamsFromSet($args['reporter']->saved_report, true));
    $smarty->assign('reportAssignedToName', $args['reporter']->saved_report->assigned_user_name);
    $smarty->assign('summaryAndGroupDefData', $summaryAndGroupDefData);

    // Set fiscal start date
    $admin = BeanFactory::newBean('Administration');
    $config = $admin->getConfigForModule('Forecasts', 'base');
    if (!empty($config['is_setup']) && !empty($config['timeperiod_start_date'])) {
        $smarty->assign("fiscalStartDate", $config['timeperiod_start_date']);
    }

    $smarty->assign('ENTROPY', mt_rand());
    echo $smarty->fetch("modules/Reports/templates/_reportCriteriaWithResult.tpl");

	reportResults($reporter, $args);
} // fn

function hasRuntimeFilter(&$reporter) {
	$hasRuntimeFilter = false;
	if (count($reporter->report_def['filters_def']) <= 0) {
		return false;
	}
	$filterDefs = $reporter->report_def['filters_def']['Filter_1'];
	$hasRuntimeFilter = checkRunTimeFilter($filterDefs, $hasRuntimeFilter);
	return $hasRuntimeFilter;
} // fn

function checkRunTimeFilter($filters, $isRunTimeFilter) {
	if ($isRunTimeFilter) {
		return $isRunTimeFilter;
	} // if
	$i = 0;
	while (isset($filters[$i])) {
		$current_filter = $filters[$i];
		if (isset($current_filter['operator'])) {
			$isRunTimeFilter = checkRunTimeFilter($current_filter, $isRunTimeFilter);
			if ($isRunTimeFilter) {
				return $isRunTimeFilter;
			} // if
		}
		else {
			if(isset($current_filter['runtime']) && $current_filter['runtime'] == 1) {
				$isRunTimeFilter = true;
				return $isRunTimeFilter;
			} // if
		}
		$i++;
	} // while
} // fn

function checkFilterModified($filters, $filterModified, &$newFilters) {
	if ($filterModified) {
		return $filterModified;
	} // if
	if (isset($filters['operator']) || isset($newFilters['operator'])) {
		if ((isset($filters['operator']) && !isset($newFilters['operator'])) ||
			(!isset($filters['operator']) && isset($newFilters['operator'])) ||
			($filters['operator'] != $newFilters['operator'])) {

			$filterModified = true;
			return $filterModified;
		} // if
	}
	$i = 0;
	while (isset($filters[$i])) {
		$current_filter = $filters[$i];
		if (!isset($newFilters[$i])) {
			$filterModified = true;
			return true;
		} // if
		$new_filter = $newFilters[$i];
		if (isset($current_filter['operator'])) {
			if (!isset($new_filter['operator'])) {
				$filterModified = true;
				return true;
			}
			$filterModified = checkFilterModified($current_filter, $filterModified, $new_filter);
			if ($filterModified) {
				return true;
			} // if
		} else {
			if(($current_filter['name'] != $new_filter['name'])
				|| ($current_filter['table_key'] != $new_filter['table_key'])) {
				$filterModified = true;
				return true;
			} // if
			if((isset($current_filter['runtime']) && !isset($new_filter['runtime']))
				|| (!isset($current_filter['runtime']) && isset($new_filter['runtime']))) {
				$filterModified = true;
				return true;
			} // if

			//do not perform this check if runtime filter
			if(!isset($current_filter['runtime']) && !isset($new_filter['runtime'])) {
				$item = 0;
				$stop = false;
				while(!$stop){
					if ( !isset($current_filter['input_name'.$item]) && !isset($new_filter['input_name'.$item])){
						$stop = true;
					}else if( (isset($current_filter['input_name'.$item]) && !isset($new_filter['input_name'.$item])) || ( !isset($current_filter['input_name'.$item]) && isset($new_filter['input_name'.$item]))){
						$stop = true;
						$filterModified = true;
						return $filterModified;
					}else if($current_filter['input_name'.$item] != $new_filter['input_name'.$item]){
						$stop = true;
						$filterModified = true;
						return $filterModified;
					}else{
						$item++;
					}
				}
			}//fi
		} // else
		if(!isset($current_filter['runtime']) && !isset($new_filter['runtime'])) {
			$newFilters[$i] = $filters[$i];
		} // if
		$i++;
	} // while
	return $filterModified;
} // fn

function getFlatListFilterContents($filters, &$filterContentsArray) {
	$i = 0;
	if (isset($filters['operator'])) {
		$filterContentsArray[] = $filters['operator'];
	}
	while (isset($filters[$i])) {
		$current_filter = $filters[$i];
		if (isset($current_filter['operator'])) {
			$filterContentsArray[] = $current_filter['operator'];
			getFlatListFilterContents($current_filter, $filterContentsArray);
		}
		else {
			$filterContentsArray[] = $current_filter;
		} // else
		$i++;
	} // while
} // fn

function hasReportFilterModified($reportId, $filtersContent) {
	$returnArray = array();
	$reportCache = new ReportCache();
	$isModified = false;
	if ($reportCache->retrieve($reportId)) {
		if ((is_array($filtersContent) && !is_array($reportCache->contents_array)) ||
			(!is_array($filtersContent) && is_array($reportCache->contents_array)) ||
			(!empty($filtersContent) && empty($reportCache->contents_array)) ||
			(empty($filtersContent) && !empty($reportCache->contents_array))) {

				$isModified = true;
		} else {
				$filterContentsArray = array();
				getFlatListFilterContents($filtersContent['Filter_1'], $filterContentsArray);
				$reportCacheFilterContentsArray = array();
				getFlatListFilterContents($reportCache->contents_array['filters_def']['Filter_1'], $reportCacheFilterContentsArray);
				if (count($filterContentsArray) != count($reportCacheFilterContentsArray)) {
					$isModified = true;
				} else {
					$isModified = checkFilterModified($filtersContent['Filter_1'], $isModified, $reportCache->contents_array['filters_def']['Filter_1']);
				}
		} // else
	} // if
	$returnArray['reportCache'] = $reportCache;
	$returnArray['isModified'] = $isModified;
	return $returnArray;

} // fn

function saveReportFilters($reportId, $filtersContent) {
	$reportCache = new ReportCache();
	$reportCache->retrieve($reportId);
	if (empty($reportCache->id)) {
		$reportCache->id = $reportId;
		$reportCache->new_with_id = true;
	}
	$reportCache->contents = $filtersContent;
	$reportCache->save();
	return $reportCache;
} // fn

function updateReportAccessDate($reportId, $filtersContent) {
	$reportCache = new ReportCache();
	$reportCache->retrieve($reportId);
	if (empty($reportCache->id)) {
		$reportCache->id = $reportId;
		$reportCache->new_with_id = true;
		$reportCache->contents = $filtersContent;
		$reportCache->save();
	} else {
		$reportCache->update();
	}
} // fn

function getReportCacheObject($reportId) {
	$reportCache = new ReportCache();
	$reportCache->retrieve($reportId);
	return $reportCache;
} // fn

function updateReportOptions($reportId, $reportOptionsArray) {
	$reportCache = new ReportCache();
	$reportCache->retrieve($reportId);
	if (empty($reportCache->id)) {
		$reportCache->new_with_id = true;
	} // if
	$reportCache->updateReportOptions($reportOptionsArray);
} // fn

function getReportDetailViewString(&$reporter,&$args) {
	global $mod_strings, $app_strings;
	$order   = array("\r\n", "\n", "\r");
	$classname = "dataLabel";
	$reportName = $reporter->name;
	$focus = $reporter->saved_report;
	$assignedUserName = '';
	$assignedTeamName = '';
	$reportType = ($reporter->report_def['report_type'] == 'tabular' ? $mod_strings['LBL_ROWS_AND_COLUMNS_REPORT'] : $mod_strings['LBL_SUMMATION_REPORT']);

	$detailViewString = "<table border=0 width=\'50%\' cellspacing=\'0\' cellpadding=\'0\'><tr class={$classname}><td class={$classname}>{$mod_strings['LBL_REPORT_NAME']}:";
	$detailViewString = $detailViewString . str_replace($order, "", $reportName);
	$detailViewString = $detailViewString . "</td>";
	$detailViewString = $detailViewString . "<td class={$classname}>{$mod_strings['LBL_REPORT_TYPE']}:";
	$detailViewString = $detailViewString . $reportType . "</td>";
	$detailViewString = $detailViewString . "</tr></table>";
	return $detailViewString;
}

function template_reports_report(&$reporter,&$args) {

	global $current_user;
	global $current_language;
	global $mod_strings, $app_strings;
	global $sugar_config, $sugar_version;

	$sort_by = '';
	$sort_dir = '';
	$summary_sort_by = '';
	$summary_sort_dir = '';
	$report_type = '';


	$smarty = new Sugar_Smarty();

	if (isset($reporter->report_def['order_by'][0]['name']) && isset($reporter->report_def['order_by'][0]['table_key'])) {
		$sort_by = $reporter->report_def['order_by'][0]['table_key'].":".$reporter->report_def['order_by'][0]['name'];
	} // if
	if (isset($reporter->report_def['order_by'][0]['sort_dir'])) {
		$sort_dir = $reporter->report_def['order_by'][0]['sort_dir'];
	} // if

	if ( ! empty($reporter->report_def['summary_order_by'][0]['group_function']) && $reporter->report_def['summary_order_by'][0]['group_function'] == 'count') {

            $summary_sort_by = $reporter->report_def['summary_order_by'][0]['table_key'].":".'count';
	} else if ( isset($reporter->report_def['summary_order_by'][0]['name'])) {
		$summary_sort_by = $reporter->report_def['summary_order_by'][0]['table_key'].":".$reporter->report_def['summary_order_by'][0]['name'];

		if ( ! empty($reporter->report_def['summary_order_by'][0]['group_function'])) {
			$summary_sort_by .=":". $reporter->report_def['summary_order_by'][0]['group_function'];
		} else if ( ! empty($reporter->report_def['summary_order_by'][0]['column__function'])) {
	    	$summary_sort_by .=":". $reporter->report_def['summary_order_by'][0]['column_function'];
	    } // else if
	} // else if

	if ( isset($reporter->report_def['summary_order_by'][0]['sort_dir'])) {
		$summary_sort_dir = $reporter->report_def['summary_order_by'][0]['sort_dir'];
	} // if
	if ( isset($reporter->report_def['report_type'])) {
		$report_type = $reporter->report_def['report_type'];
	} // if

	$issetSaveResults = false;
	$isSaveResults = false;
	$request = InputValidation::getService();
	$saveReportAs = $request->getValidInputRequest('save_report_as');
	if (isset($args['save_result'])) {
		$issetSaveResults = true;
		$smarty->assign('save_report_as_str', $saveReportAs);
		if($args['save_result']) {
			$isSaveResults = true;
		} // if
	} // if

	$smarty->assign('mod_strings', $mod_strings);
	$smarty->assign('app_strings', $app_strings);
	$smarty->assign('current_language', $current_language);
	$smarty->assign('sugar_config', $sugar_config);
	$smarty->assign('sugar_version', $sugar_version);
	$smarty->assign('issetSaveResults', $issetSaveResults);
	$smarty->assign('isSaveResults', $isSaveResults);
	$smarty->assign('report_type', $report_type);

	$form_header = get_form_header($mod_strings['LBL_TITLE'].": ".$args['reporter']->saved_report->name, "", false);
	$smarty->assign('form_header', $form_header);
	$smarty->assign('report_offset', $reporter->report_offset);
	$smarty->assign('sort_by', $sort_by);
	$smarty->assign('sort_dir', $sort_dir);
	$smarty->assign('summary_sort_by', $summary_sort_by);
	$smarty->assign('summary_sort_dir', $summary_sort_dir);

	$saveAs = $request->getValidInputRequest('save_as');
	$record = $request->getValidInputRequest('record','Assert\Guid');

	if ($saveAs !== null &&  $saveAs == 'true') {
	    $report_id = '';
	} else if (isset($reporter->saved_report->id) ) {
	    $report_id = $reporter->saved_report->id;
	} elseif (!empty($record)) {
	    $report_id = $record;
	} else {
	    $report_id = '';
	} // else

	$smarty->assign('report_id', $report_id);
	$smarty->assign('to_pdf', isset($_REQUEST['to_pdf']) ? $_REQUEST['to_pdf'] : "");
	$smarty->assign('to_csv', isset($_REQUEST['to_csv']) ? $_REQUEST['to_csv'] : "");

	if(!empty($args['reporter']->saved_report)) {
	    $context = array("bean" => $args['reporter']->saved_report);
	} else {
	    $context = array();
	}
	$report_edit_access = SugarACL::checkAccess('Reports', 'edit', $context);
	$smarty->assign('report_edit_access', $report_edit_access);
	$report_delete_access = SugarACL::checkAccess('Reports', 'delete', $context);
	$smarty->assign('report_delete_access', $report_delete_access);
	$report_export_access = SugarACL::checkAccess('Reports', 'export', $context);
	$smarty->assign('report_export_access', $report_export_access);
	$smarty->assign('form_submit', empty($_REQUEST['form_submit']) ? false : $_REQUEST['form_submit']);

	$global_json = getJSONobj();
	global $ACLAllowedModules;
	$ACLAllowedModules = getACLAllowedModules();
	$smarty->assign('ACLAllowedModules', $global_json->encode(array_keys($ACLAllowedModules)));


	$tabs = array();

	array_push($tabs,array('title'=>$mod_strings['LBL_1_REPORT_ON'],'link'=>'module_join_tab','key'=>'module_join_tab'));
	array_push($tabs,array('title'=>$mod_strings['LBL_2_FILTER'],'link'=>'filters_tab','key'=>'filters_tab'));
	if ( $args['reporter']->report_type == 'tabular') {
	  array_push($tabs,array('title'=>$mod_strings['LBL_3_GROUP'],'hidden'=>true,'link'=>'group_by_tab','key'=>'group_by_tab'));
	  array_push($tabs,array('title'=>$mod_strings['LBL_3_CHOOSE'],'link'=>'columns_tab','key'=>'columns_tab'));
	  array_push($tabs,array('title'=>$mod_strings['LBL_5_CHART_OPTIONS'],'hidden'=>true,'link'=>'chart_options_tab','key'=>'chart_options_tab'));
	} else {
	  array_push($tabs,array('title'=>$mod_strings['LBL_3_GROUP'],'link'=>'group_by_tab','key'=>'group_by_tab'));
	  array_push($tabs,array('title'=>$mod_strings['LBL_4_CHOOSE'],'link'=>'columns_tab','key'=>'columns_tab'));
	  array_push($tabs,array('title'=>$mod_strings['LBL_5_CHART_OPTIONS'],'link'=>'chart_options_tab','key'=>'chart_options_tab'));
	}

	$current_key = 'module_join_tab';
	$tab_panel= new SugarWidgetTabs($tabs,$current_key,'showReportTab');
	$smarty->assign('tab_panel_display', $tab_panel->display());

	template_reports_tables($smarty, $args);

	if( $reporter->report_type=='summary') {
		$summary_display = '';
	 	if ( $reporter->show_columns) {
	 		$column_display = '';
	 	} else {
	  		$column_display = 'none';
	 	} // else
	} else {
		 $summary_display = 'none';
		 $column_display = '';
	} // else

	$summary_join_selector = '&nbsp;<div style="padding-bottom:2px">'.$mod_strings['LBL_MODULE'].': <select onChange="viewJoinChanged(this);" id="view_join_summary" name="view_join_summary"></select></div>';

	$chooser_args_summary = array('id'=>'summary_table','title'=>$mod_strings['LBL_CHOOSE_SUMMARIES'].':','left_name'=>'display_summary','right_name'=>'hidden_summary','left_label'=>$mod_strings['LBL_DISPLAY_SUMMARIES'],'right_label'=>$summary_join_selector,'display'=>$summary_display,'onmoveleft'=>'reload_columns(\'join\')','onmoveright'=>'reload_columns(\'join\')');

	$join_selector = '&nbsp;<div style="padding-bottom:2px">'.$mod_strings['LBL_MODULE'].': <select onChange="viewJoinChanged(this);" id="view_join" name="view_join"></select></div>';

	$chooser_args = array('id'=>'columns_table','title'=>$mod_strings['LBL_CHOOSE_COLUMNS'].':','left_name'=>'display_columns','right_name'=>'hidden_columns','left_label'=>$mod_strings['LBL_DISPLAY_COLUMNS'],'right_label'=>$join_selector,'display'=>$column_display,'topleftcontent'=>$join_selector,'onmoveleft'=>'reload_columns(\'join\')','onmoveright'=>'reload_columns(\'join\')');

	$smarty->assign('template_grups_choosers1', template_groups_chooser($chooser_args_summary));

	if ($summary_display == 'none') {
		$smarty->assign('summary_display_style', "display:none");
	} // if
	if ($reporter->show_columns) {
		$smarty->assign('show_columns_reports', true);
	} // if
	$smarty->assign('column_display', $column_display);

	$smarty->assign('template_grups_choosers2', template_groups_chooser($chooser_args));
	template_reports_filters($smarty, $args);
	$smarty->assign('reporter_report_type', $args['reporter']->report_type);
	template_reports_group_by($smarty, $args);
	template_reports_chart_options($smarty, $args);
	$smarty->assign('md5_current_user_id', md5($current_user->id));
	$smarty->assign('args_image_path', $args['IMAGE_PATH']);
	template_reports_request_vars_js($smarty, $reporter,$args);
	$smarty->assign('cache_path', sugar_cached(''));

    // Set fiscal start date
    $admin = BeanFactory::newBean('Administration');
    $config = $admin->getConfigForModule('Forecasts', 'base');
    if (!empty($config['is_setup']) && !empty($config['timeperiod_start_date'])) {
        $smarty->assign("fiscalStartDate", $config['timeperiod_start_date']);
    }

    $smarty->assign('ENTROPY', mt_rand());
	echo $smarty->fetch("modules/Reports/templates/_template_reports_report.tpl");

ob_start();
?>
<script language="javascript">
if(typeof YAHOO != 'undefined') YAHOO.util.Event.addListener(window, 'load', load_page);
</script>
<?php
reportResults($reporter, $args);
} // fn

function reportResults(&$reporter, &$args) {
	ob_start();
	echo '<div id="report_results">';

	$do_chart = false;

	if ($reporter->report_type == 'summary' && ! empty($reporter->report_def['summary_columns'])) {
		if ($reporter->show_columns &&
			!empty($reporter->report_def['display_columns']) &&
	        !empty($reporter->report_def['group_defs'])) {

			template_summary_combo_view($reporter,$args);
			$do_chart = true;

	    } else if($reporter->show_columns &&
		          !empty($reporter->report_def['display_columns']) &&
	          	   empty($reporter->report_def['group_defs'])) {
			template_detail_and_total_list_view($reporter,$args);
		} else if (!empty($reporter->report_def['group_defs'])) {
			template_summary_list_view($reporter,$args);
			$do_chart = true;
		} else {
			template_total_view($reporter,$args);
		} // else
	} else if (!empty($reporter->report_def['display_columns'])) {
		template_list_view($reporter,$args);
	} // else if

	$searchArray = array("'", "\r\n", "\n");
	$replaceArray = array("\\'", "", "");
	$filterStringForUI = str_replace($searchArray, $replaceArray, $reporter->createFilterStringForUI());
	echo "<script>var filterString='" . htmlspecialchars($filterStringForUI) . "';</script>";
	if ($reporter->report_def['chart_type']== 'none') {
		$do_chart = false;
	}
	echo '</div>';
	$contents = ob_get_contents();
	ob_end_clean();

	if ($do_chart) {
	global $mod_strings;

	$reportChartButtonTitle = $mod_strings['LBL_REPORT_HIDE_CHART'];
	$reportChartDivStyle = "";
	if (isset($args['reportCache'])) {
		$reportCache = $args['reportCache'];
		if (!empty($reportCache->report_options_array)) {
			if (array_key_exists("showChart", $reportCache->report_options_array) && !$reportCache->report_options_array['showChart']) {
				$reportChartButtonTitle = $mod_strings['LBL_REPORT_SHOW_CHART'];
				$reportChartDivStyle = "display:none";
			}
		} // if
	} // if

	echo "<input class=\"button\" name=\"showHideChartButton\" id=\"showHideChartButton\" title=\"{$reportChartButtonTitle}\"
	type=\"button\" value=\"{$reportChartButtonTitle}\" onclick=\"showHideChart();\"><br/><br/>";

	echo "<script>function showHideChart() {
	var idObject = document.getElementById('record');
	var id = '';
	if (idObject != null) {
		id = idObject.value;
	} // if
	var chartId = document.getElementById(id + '_div');
	var showHideChartButton = document.getElementById('showHideChartButton');
	if (chartId.style.display == \"none\") {
		saveReportOptionsState('showChart', '1');
		chartId.style.display = \"\";
		showHideChartButton.title = \"{$mod_strings['LBL_REPORT_HIDE_CHART']}\";
		showHideChartButton.value = \"{$mod_strings['LBL_REPORT_HIDE_CHART']}\";
		loadCustomChartForReports();
	} else {
		chartId.style.display = 'none';
		saveReportOptionsState('showChart', '0');
		showHideChartButton.title = \"{$mod_strings['LBL_REPORT_SHOW_CHART']}\";
		showHideChartButton.value = \"{$mod_strings['LBL_REPORT_SHOW_CHART']}\";
	} // else
} </script>";

if (isset($reporter->saved_report->id) ) {
	$report_id = $reporter->saved_report->id;
} else {
	$report_id = InputValidation::getService()->getValidInputRequest('record', 'Assert\Guid', 'unsavedReport');
}

	echo "<div class='reportChartContainer' id='{$report_id}_div' style='{$reportChartDivStyle}'>";
     $chartDisplay = new ChartDisplay();
     $chartDisplay->setReporter($reporter);
     echo "<div align='center'>".$chartDisplay->legacyDisplay(null, false)."</div>";
	 echo "</div>";
	} // if

	print $contents;
} // fn

//////////////////////////////////////////////
// TEMPLATE:
// filters_top: string is filled up with the filter query string
// filters_top: table that holds the filter rows
//////////////////////////////////////////////
function template_reports_filters(&$smarty, &$args) {
	$reporter = $args['reporter'];
	global $mod_strings;
	$selectedAnd = "";
	$selectedOR = "";
	if(!empty($reporter->report_def['filters_combiner']) && $reporter->report_def['filters_combiner'] == 'AND') {
		$selectedAnd = 'selected';
	}
	if(!empty($reporter->report_def['filters_combiner']) && $reporter->report_def['filters_combiner'] == 'OR') {
		$selectedOR = 'selected';
	}
	$sort_by ="";
	$sort_dir ="";
	if (isset($reporter->report_def['sort_by'])) {
		$sort_dir = $reporter->report_def['sort_by'];
	} // if

	if (isset($reporter->report_def['sort_dir'])) {
		$sort_dir = $reporter->report_def['sort_dir'];
	} // if
	$smarty->assign('mod_strings', $mod_strings);
	$smarty->assign('selectedAnd', $selectedAnd);
	$smarty->assign('selectedOR', $selectedOR);

} // fn

//////////////////////////////////////////////
// TEMPLATE:
//////////////////////////////////////////////
function template_reports_group_by(&$smarty, &$args) {
	global $mod_strings;
	$smarty->assign('mod_strings', $mod_strings);

} // fn

//////////////////////////////////////////////
// TEMPLATE:
//////////////////////////////////////////////
function template_reports_chart_options(&$smarty, &$args) {
	$reporter = $args['reporter'];
	global $mod_strings;
	$chart_types = array(
		'none'=>$mod_strings['LBL_NO_CHART'],
		'hBarF'=>$mod_strings['LBL_HORIZ_BAR'],
		'vBarF'=>$mod_strings['LBL_VERT_BAR'],
		'pieF'=>$mod_strings['LBL_PIE'],
		'funnelF'=>$mod_strings['LBL_FUNNEL'],
		'lineF'=>$mod_strings['LBL_LINE'],
	);
	$chart_description = htmlentities($reporter->chart_description, ENT_QUOTES, 'UTF-8');
	$smarty->assign('mod_strings', $mod_strings);
	$smarty->assign('chart_description', $chart_description);
	$smarty->assign('chart_types', $chart_types);
	$smarty->assign('$report_def', $reporter->report_def);
	$smarty->assign('chart_description', $chart_description);

} // fn

//////////////////////////////////////////////
// TEMPLATE:
//////////////////////////////////////////////

function juliansort($a,$b)
{
 global $app_list_strings;
    $a = isset($app_list_strings['moduleList'][$a]) ? $app_list_strings['moduleList'][$a] : $a;
    $b = isset($app_list_strings['moduleList'][$b]) ? $app_list_strings['moduleList'][$b] : $b;
 if ($a > $b)
 {
  return 1;
 }
 return -1;
}

function get_select_related_html(&$args)
{
  global $global_json,$app_strings;

  if ( ! isset($args['form_name']))
  {
    $args['form_name'] = 'ReportsWizardForm';
  }

  $popup_request_data = array(
    'call_back_function' => 'set_return',
    'form_name' => $args['form_name'],
    'field_to_name_array' => array(
      'id' => $args['parent_id'],
      $args['real_parent_name'] => $args['parent_name'],
    ),
  );

  $request_data = $global_json->encode($popup_request_data);

    $sanitizedParentNameValue = htmlentities($args['parent_name_value'], ENT_QUOTES);
    $content = "<input class='sqsEnabled' autocomplete='off' id='{$args['parent_name']}' name='{$args['parent_name']}' type='text' value='{$sanitizedParentNameValue}'>&nbsp;<input id='{$args['parent_id']}' name='{$args['parent_id']}' type='hidden' value='{$args['parent_id_value']}'/></slot>";
  $content .= "<input title='{$app_strings['LBL_SELECT_BUTTON_TITLE']}' type='button' class='button' value='{$app_strings['LBL_SELECT_BUTTON_LABEL']}' name=btn1 ";

  if ( isset($args['tabindex']))
  {
    $content .= "tabindex='{$args['tabindex']}' ";
  }

  $content .= " onclick='open_popup(\"{$args['module']}\", 600, 400, \"\", true, false, $request_data);' />";

  return $content;

}


function js_setup(&$smarty) {
	global $global_json;
	$qsd = QuickSearchDefaults::getQuickSearchDefaults();
	$qsd->form_name = "ReportsWizardForm";
	$sqs_objects = array('ReportsWizardForm_assigned_user_name' => $qsd->getQSUser()); //, 'ReportsWizardForm_team_name_collection_0' => $qsd->getQSTeam());

    $quicksearch_js = '<script language="javascript">';
    $quicksearch_js.= "if(typeof sqs_objects == 'undefined'){ var sqs_objects = new Array; }";

    foreach($sqs_objects as $sqsfield=>$sqsfieldArray){
            $quicksearch_js .= "sqs_objects['$sqsfield']={$global_json->encode($sqsfieldArray)};";
    }

    $quicksearch_js .= '</script>';
	$smarty->assign('quicksearch_js', $quicksearch_js);
}


function template_reports_tables(&$smarty, &$args) {
	global $report_modules;
	global $mod_strings;
	global $app_list_strings;
	global $current_user;
	$reporter = $args['reporter'];

	$classname = "dataLabel";
	$smarty->assign('classname', $classname);
	global $ACLAllowedModules;
	uksort($ACLAllowedModules,"juliansort");
	$smarty->assign('ACLAllowedModulesjuliansort', $ACLAllowedModules);
	$smarty->assign('app_list_strings', $app_list_strings);
	$save_report_as = $mod_strings['LBL_UNTITLED'];
	if (! empty($reporter->name)) {
		$save_report_as = $reporter->name;
		$smarty->assign('save_report_as_template_reports_tables', $save_report_as);
	} // fn
	$isAdmin = false;
	if ($current_user->is_admin) {
		$isAdmin = true;
	} // if
	$smarty->assign('isAdmin', $isAdmin);
	if (!empty($_REQUEST['show_query']) && $isAdmin) {
		$smarty->assign('show_query', true);
	} // if
	if (! empty($reporter->saved_report)) {
		$focus = & $reporter->saved_report;
	} else {
		$focus = BeanFactory::newBean('Reports');
		$focus->assigned_user_name = (empty($_REQUEST['assigned_user_name']) ? '' : $_REQUEST['assigned_user_name']);
		$focus->assigned_user_id = (empty($_REQUEST['assigned_user_id']) ? '' : $_REQUEST['assigned_user_id']);
		$focus->team_name = (empty($_REQUEST['team_name']) ? '' : $_REQUEST['team_name']);
		$focus->team_id = (empty($_REQUEST['team_id']) ? '' : $_REQUEST['team_id']);
	}
	if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id = $current_user->id;
	if (empty($focus->assigned_user_name) && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;

	$assigned_user_html_def = array(
	    'parent_id'=>'assigned_user_id',
	    'parent_id_value'=>$focus->assigned_user_id,
	    'parent_name'=>'assigned_user_name',
	    'parent_name_value'=>$focus->assigned_user_name,
	    'real_parent_name'=>'user_name',
	    'module'=>'Users',
	);
	$assigned_user_html = get_select_related_html($assigned_user_html_def);
	$smarty->assign('assigned_user_html', $assigned_user_html);
	if (empty($focus->id) && empty($_REQUEST['team_name'])) {
	  $focus->team_name = $current_user->default_team_name;
	  $focus->team_id =  $current_user->default_team;
	} // if

	$team_html_def = array(
	    'parent_id'=>'team_id',
	    'parent_id_value'=>$focus->team_id,
	    'parent_name'=>'team_name',
	    'parent_name_value'=>$focus->team_name,
	    'real_parent_name'=>'name',
	    'module'=>'Teams',
	);
	$team_html = get_select_related_html($team_html_def);
	$smarty->assign('team_html', $team_html);
	if (empty( $reporter->report_def['report_type'] )) {
		$reporter->report_def['report_type']='tabular';
	}
	$smarty->assign('reporter_report_def_report_type', $reporter->report_def['report_type']);
	js_setup($smarty);
} // fn

/*
 * Check if user is allowed to export report
 *
 * @param array $args array of args that should contain the reporter object
 * @return boolean returns true or false
 */
function hasExportAccess($args = array())
{
    global $sugar_config, $current_user;

    // If reporter is not passed in just default to no access
    if (empty($args['reporter'])) {
        return false;
    }

    $is_owner =  true;
    if (isset($args['reporter']->saved_report) && $args['reporter']->saved_report->assigned_user_id != $current_user->id) {
        $is_owner = false;
    }

    if (// Exports disabled
        !(empty($sugar_config['disable_export']))
        // Report is not tabular
        || $args['reporter']->report_def['report_type'] != 'tabular'
        // User doesn't have rights to export the reported module
        || !SugarACL::checkAccess($args['reporter']->module, 'export', $is_owner?array("owner_override" => true):array())
        // Only admins can export, and the user doesn't have admin rights
        || (
            $sugar_config['admin_export_only']
            && !$current_user->isAdminForModule($args['reporter']->module)
            )
    ) {
        // User does not have export access, return false
        return false;
    }

    // User has export access, return true
    return true;
}
