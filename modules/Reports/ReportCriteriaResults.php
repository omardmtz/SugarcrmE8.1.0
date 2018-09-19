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

global $theme;

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

require_once('modules/Reports/index.php');
require_once('modules/Reports/templates/templates_reports.php');
require_once('modules/Reports/templates/templates_reports_index.php');
require_once('modules/Reports/templates/templates_pdf.php');
require_once('modules/Reports/templates/templates_export.php');

require_once('modules/Reports/config.php');
global $current_language, $report_modules, $modules_report, $current_user, $app_strings, $mod_strings;


$reporterName = InputValidation::getService()->getValidInputRequest('save_report_as');

$args = array();
$jsonObj = getJSONobj();
if (isset($_REQUEST['id']) && !isset($_REQUEST['record'])) {
	$saved_report_seed = BeanFactory::newBean('Reports');
	$saved_report_seed->retrieve($_REQUEST['id'], false);

    if (empty($saved_report_seed->id)) {
        sugar_die($app_strings['ERROR_NO_RECORD']);
    }

	// do this to go through the transformation
	$reportObj = new Report($saved_report_seed->content);
	$saved_report_seed->content = $reportObj->report_def_str;

	$savedReportContent = $jsonObj->decode($saved_report_seed->content);
	$returnArray = hasReportFilterModified($_REQUEST['id'], $savedReportContent['filters_def']);
	$reportCache = $returnArray['reportCache'];
	if (!empty($reportCache->id) && !$returnArray['isModified']) {
		$savedReportContent['filters_def'] = $reportCache->contents_array['filters_def'];
		$saved_report_seed->content = $jsonObj->encode($savedReportContent);
	} // if
	if ($returnArray['isModified']) {
		$args['warnningMessage'] = $mod_strings['LBL_REPORT_FILTER_MODIFIED_MESSAGE'];
	} // if
	if (!isset($args['warnningMessage']) && !empty($reportCache->id)) {
		if (strtotime($reportCache->date_modified) < strtotime($saved_report_seed->date_modified)) {
			$args['warnningMessage'] = $mod_strings['LBL_REPORT_MODIFIED_MESSAGE'];
		}
	} // if
	$args['reporter'] = new Report($saved_report_seed->content);
	$args['reporter']->saved_report = $saved_report_seed;
		$savedReportContent = $jsonObj->decode($saved_report_seed->content);
		$newArray = array();
		$newArray['filters_def'] = $savedReportContent['filters_def'];
		$reportCache = saveReportFilters($saved_report_seed->id, $jsonObj->encode($newArray));

	if ( isset($_REQUEST['filter_key']) && isset($_REQUEST['filter_value'])) {
		$new_filter = array();
		list($new_filter['table_name'],$new_filter['name']) = explode(':',$_REQUEST['filter_key']);
		$new_filter['qualifier_name'] = 'is';
		$new_filter['input_name0'] = array($_REQUEST['filter_value']);

		if ( ! is_array($args['reporter']->report_def['filters_def'])) {
			$args['reporter']->report_def['filters_def'] = array();
		} // if
		array_push($args['reporter']->report_def['filters_def'],$new_filter);
		$args['reporter']->report_def['chart_type'] = 'none';
		$args['reporter']->chart_type = 'none';
	} // if

	$args['reporter']->is_saved_report = true;
	$args['reporter']->saved_report_id = $saved_report_seed->id;
	$args['reportCache'] = $reportCache;
}
else if (isset($_REQUEST['record'])){
    $saved_report_seed = BeanFactory::newBean('Reports');
    $saved_report_seed->retrieve($_REQUEST['record'], false);

    if (empty($saved_report_seed->id)) {
        sugar_die($app_strings['ERROR_NO_RECORD']);
    }

    // do this to go through the transformation
    $reportObj = new Report($saved_report_seed->content);
    $saved_report_seed->content = $reportObj->report_def_str;
	$report_def = InputValidation::getService()->getValidInputRequest('report_def', null, array());

    if (!empty($_REQUEST['reset_filters'])) {
        $newArray = array();
        $newArray['filters_def'] = $reportObj->report_def['filters_def'];
        $reportCache = saveReportFilters($_REQUEST['record'], $jsonObj->encode($newArray));
        $args['reportCache'] = $reportCache;
    }
    else if ( ! empty($_REQUEST['report_def'])) {
        $resuestFilterDef = $jsonObj->decode($report_def);
        $savedReportContent = $jsonObj->decode($saved_report_seed->content);
        $savedReportContent['filters_def'] = $resuestFilterDef['filters_def'];
        if (isset($resuestFilterDef['order_by'])) {
                $savedReportContent['order_by'] = $resuestFilterDef['order_by'];
        } // if
        if (isset($resuestFilterDef['summary_order_by'])) {
                $savedReportContent['summary_order_by'] = $resuestFilterDef['summary_order_by'];
        } // if
        $report_def = $jsonObj->encode($savedReportContent);
        $args['reporter'] =  new Report($report_def);
        //if (hasRuntimeFilter($args['reporter'])) {
        $newArray = array();
        $newArray['filters_def'] = $resuestFilterDef['filters_def'];
        $reportCache = saveReportFilters($_REQUEST['record'], $jsonObj->encode($newArray));
                $args['reportCache'] = $reportCache;

        if (!empty($reporterName)) {
                $args['reporter']->name = $reporterName;
        } // if
    }
    if (!isset($args['reporter'])) {
            $args['reporter'] = new Report($saved_report_seed->content);
    }

    $args['reporter']->is_saved_report = true;
    $args['reporter']->saved_report = &$saved_report_seed;
    $args['reporter']->saved_report_id = $saved_report_seed->id;

} else if(!empty($_REQUEST['addtofavorites']) && $_REQUEST['addtofavorites']) {
        $current_favorites = $current_user->getPreference('favorites', 'Reports');
        if(empty($current_favorites)) $current_favorites = array();
        $current_favorites[$_REQUEST['report_id']] = true;
        $current_user->setPreference('favorites', $current_favorites, 0, 'Reports');
		sugar_die('');
} else if(!empty($_REQUEST['removefromfavorites']) && $_REQUEST['removefromfavorites']) {
        $current_favorites = $current_user->getPreference('favorites', 'Reports');
        if(empty($current_favorites)) $current_favorites = array();
		if(isset($current_favorites[$_REQUEST['report_id']])) {
        	unset($current_favorites[$_REQUEST['report_id']]);
		} // if
        $current_user->setPreference('favorites', $current_favorites, 0, 'Reports');
        sugar_die('');
} else if(!empty($_REQUEST['report_options'])) {
	$reportOptionsArray = array();
    foreach(array('showDetails', 'showChart', 'expandAll') as $param) {
        $value = InputValidation::getService()->getValidInputRequest($param);
        if (!empty($value)) {
            $reportOptionsArray[$param] = $value;
        }
    }
	updateReportOptions($_REQUEST['report_id'], $reportOptionsArray);
} else {
	$report_def = InputValidation::getService()->getValidInputRequest('report_def', null, array());
	if (!empty($report_def)) {
		$panels_def = html_entity_decode($_REQUEST['panels_def']);
		$filters_def = html_entity_decode($_REQUEST['filters_defs']);
       	$args['reporter'] =  new Report($report_def, $filters_def, $panels_def);

    	if (!empty($reporterName)) {
         	$args['reporter']->name = $reporterName;
    	}
	} else {
		$reporter = new Report();
        $args['reporter'] = $reporter;
	}
}

// create report obj with the seed
$args['list_nav'] = '';
$args['upper_left'] = '';


// do saves, deletes, and publish
control($args);

$params = array();
if(!empty($_REQUEST['favorite']))
    $params[] = "<a href='index.php?module=Reports&action=index&favorite=1'>{$mod_strings['LBL_FAVORITES_TITLE']}</a>";
$star = '';
if(!empty($args['reporter']->saved_report->id)){
    $star = SugarFavorites::generateStar(SugarFavorites::isUserFavorite('Reports', $args['reporter']->saved_report->id), 'Reports', $args['reporter']->saved_report->id);
}
if (!empty($args['reporter']->name))
    $params[] = "{$args['reporter']->name}&nbsp;{$star}";

//Override the create url
$createURL = 'index.php?module=Reports&report_module=&action=index&page=report&Create+Custom+Report=Create+Custom+Report';
echo getClassicModuleTitle("Reports", $params, true, '', $createURL);

// show report interface
if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'report') {
    checkSavedReportACL($args['reporter'], $args);

    if (!empty($_REQUEST['to_pdf'])) {
        if (isset($args['reporter'])) {
            template_handle_pdf($args['reporter']);
        }
        return;
    }

    if (!empty($_REQUEST['to_csv'])) {
        if (!hasExportAccess($args)) {
            sugar_die($mod_strings['LBL_NO_EXPORT_ACCESS']);
        }
        template_handle_export($args['reporter']);
        return;
    }

    if (isset($_REQUEST['run_query']) && ($_REQUEST['run_query'] == 1)) {
        reportResults($args['reporter'], $args);
    } else {
        reportCriteriaWithResult($args['reporter'], $args);
    }

    if (!empty($_REQUEST['expanded_combo_summary_divs'])) {
        $expandDivs = explode(' ', $_REQUEST['expanded_combo_summary_divs']);
        foreach ($expandDivs as $divId) {
            str_replace(' ', '', $divId);
			if ($divId != "") {
                echo "<script>expandCollapseComboSummaryDiv('" . htmlspecialchars($divId, ENT_QUOTES, 'UTF-8') . "')
                    </script>";
			} // if
		} // foreach
	} // if
} // if
?>
