{*
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
*}
{php}
	require_once('modules/Reports/templates/templates_reports.php');
	$reporter = $this->get_template_vars('reporter');
	$args = $this->get_template_vars('args');
	$header_row = $this->get_template_vars('header_row');
	$got_row = 0;
	$maximumCellSize = 0;
	$rowsAndColumnsData = array();
	$legend = array();
	$columnDataFor2ndGroup = array();
	$columnDataArray = getColumnDataAndFillRowsFor2By2GPBY($reporter, $header_row, $rowsAndColumnsData, $columnDataFor2ndGroup, 1, $maximumCellSize, $legend);
	$headerColumnNameArray = getHeaderColumnNamesForMatrix($reporter, $header_row, $columnDataFor2ndGroup);
	$columnNameArray = getColumnNamesForMatrix($reporter, $header_row, $columnDataFor2ndGroup);
	$totalColumns = count($headerColumnNameArray) + count($columnDataFor2ndGroup) - 1;
	$this->assign('legend', $legend);
	$maximumCellSize = $maximumCellSize - $reporter->addedColumns;
	$this->assign('legend', implode(",", $legend));
{/php}

<B>{$mod_strings.LBL_REPORT_DATA_COLUMN_ORDERS}</B> {$legend}
<br/>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView">
<tr height="20">
{php}
	for ($i = 0 ; $i < count($headerColumnNameArray) ; $i++) {
		$this->assign('headerColumnName', $headerColumnNameArray[$i]);
		$headerColumnClassName = "reportlistViewMatrixThS1";
		if ($i == (count($headerColumnNameArray) - 1)) {
			$headerColumnClassName = "reportlistViewMatrixThS2";
		} // if
		$this->assign('headerColumnClassName', $headerColumnClassName);
		if ($i == 1) {
		$this->assign('topLevelColSpan', count($columnDataFor2ndGroup));
{/php}
	<th scope="col" align='center' colspan="{$topLevelColSpan}" class="{$headerColumnClassName}" valign=middle wrap>{$headerColumnName}</td>
{php}
		} else {
		$rowSpan = 2;
		if (count($columnDataFor2ndGroup) == 0) {
			$rowSpan = 1;
		} // if
		$this->assign('topLevelRowSpan', $rowSpan);
{/php}
	<th scope="col" align='center' rowspan="{$topLevelRowSpan}" class="{$headerColumnClassName}" valign=middle wrap>{$headerColumnName}</td>

{php}
		} // else
	} // for
{/php}
</tr>
{php}
	if ($totalColumns > 2) {
{/php}
<tr height="20">
{php}
	$count = 0;
	for ($i = 0 ; $i < $totalColumns ; $i++) {
		if ($i == 0 || $i == ($totalColumns -1)) {
			continue;
		}  else {
		$headerColumn2ClassName = "reportlistViewMatrixThS1";
		$this->assign('headerColumn2ClassName', $headerColumn2ClassName);
		$cellData = $columnDataFor2ndGroup[$count];
		if (empty($cellData)) {
			$cellData = "&nbsp;";
		} // if
		$this->assign('columnDataFor2ndGroup', $cellData);
		$count++;
{/php}
	<th scope="col" align='center' class="{$headerColumn2ClassName}" valign=middle wrap>{$columnDataFor2ndGroup}</td>
{php}
		} // else
	} // for
{/php}
</tr>
{php}
	} // if
{/php}
{php}
	// iteration for the group by data
	for ($i = 0 ; $i < count($rowsAndColumnsData) ; $i++) {
		$rowData = $rowsAndColumnsData[$i];
		for ($k = 0 ; $k < $maximumCellSize ; $k++) {
{/php}
		<tr height="20">
{php}
		for ($j = 0 ; $j < count($columnNameArray) ; $j++) {
			$cellData = "&nbsp;";
			if ($j == 0 ) {
				if ($k != 0) {
					continue;
				} // if
				if (isset($rowData[$columnNameArray[$j]])) {
					$cellData = $rowData[$columnNameArray[$j]];
				} // if
				if (empty($cellData)) {
					$cellData = "&nbsp;";
				} // if
				$this->assign('cellData', $cellData);
				$this->assign('rowSpanForData', $maximumCellSize);
				$dataCellClass = "reportlistViewMatrixRightEmptyData";
				if ($i == (count($rowsAndColumnsData)-1)) {
					$dataCellClass = "reportlistViewMatrixRightEmptyData1";
				} // if
				$this->assign('dataCellClass', $dataCellClass);
{/php}
	<th scope="col" ROWSPAN='{$rowSpanForData}' align='center' class="{$dataCellClass}" valign=middle wrap>{$cellData}</td>
{php}
			} else {
				$cellData = "&nbsp;";
				if (isset($rowData[$columnNameArray[$j]])) {
					$cellDataArray = $rowData[$columnNameArray[$j]];
					if (is_array($cellDataArray)) {
						$arrayKeys = array_keys($cellDataArray);
						$cellData = $cellDataArray[$arrayKeys[$k]];
						if ($j == 1) {
							//$cellData = $arrayKeys[$k] . " = " . $cellData;
						} // if
						//$cellData = "&nbsp;";
					} else {
						$cellData = "&nbsp;";
					} // else
				} // if
				$this->assign('cellData', $cellData);
				$dataCellClass = "reportGroupByDataMatrixEvenListRowS1";
				if ($j == (count($columnNameArray)-1)) {
					$dataCellClass = "reportGroupByDataMatrixEvenListRowS2";
				} // if
				if ($i == (count($rowsAndColumnsData)-1)) {
					if ($k == ($maximumCellSize -1)) {
						$dataCellClass = "reportGroupByDataMatrixEvenListRowS3";
						if ($j == (count($columnNameArray)-1)) {
							$dataCellClass = "reportGroupByDataMatrixEvenListRowS4";
						} // if
					} // if
				} // if

				$this->assign('dataCellClass', $dataCellClass);
{/php}
	<td scope="col" align='center' class="{$dataCellClass}" valign=middle wrap>{$cellData}</td>
{php}
			} // else
		} // for
{/php}
</tr>
{php}
		} // for
{/php}
{php}
	} // for
	if (empty($rowsAndColumnsData)) {
{/php}
<tr height="20">
{php}
		$emptyRowColumns = 2; // This is for 1 group by and 1 grand total
		if (count($columnDataFor2ndGroup) == 0) {
			$emptyRowColumns = $emptyRowColumns + 1;
		} else {
			$emptyRowColumns = $emptyRowColumns + count($columnDataFor2ndGroup);
		} // else
		for ($j = 0 ; $j < $emptyRowColumns ; $j++) {
{/php}
<td scope="col" align='center' class="reportGroupByDataMatrixEvenListRowS4" valign=middle wrap>No results</td>
{php}
		} // for
	} // if
{/php}
</tr>
</table>
{php}
template_query_table($reporter);
{/php}

