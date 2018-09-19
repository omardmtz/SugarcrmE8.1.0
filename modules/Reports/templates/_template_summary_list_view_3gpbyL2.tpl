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
	$columnDataFor3rdGroup = array();
	$grandTotal = array();
	getColumnDataAndFillRowsFor3By3GPBY($reporter, $header_row, $rowsAndColumnsData, $columnDataFor2ndGroup, $columnDataFor3rdGroup, $maximumCellSize, $legend, $grandTotal);
	$headerColumnNameArray = getHeaderColumnNamesForMatrix($reporter, $header_row, $columnDataFor2ndGroup);
	$totalColumns = count($headerColumnNameArray) + count($columnDataFor3rdGroup) - 1;
	$this->assign('legend', implode(",", $legend));
	$maximumCellSize = $maximumCellSize - $reporter->addedColumns;
{/php}

<B>{$mod_strings.LBL_REPORT_DATA_COLUMN_ORDERS}</B> {$legend}
<br/>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView">
<tr height="20">
{php}
	$columnDataFor3rdGroup[] = 'Total';
	for ($i = 0 ; $i < (count($headerColumnNameArray) -1) ; $i++) {
		$this->assign('headerColumnName', $headerColumnNameArray[$i]);
		$headerColumnClassName = "reportlistViewMatrixThS1";
		if ($i == (count($headerColumnNameArray) - 2)) {
			$headerColumnClassName = "reportlistViewMatrixThS2";
			$this->assign('headerColumnName', "Grand Total");
		} // if
		$this->assign('headerColumnClassName', $headerColumnClassName);
		if ($i == 0 || ($i == (count($headerColumnNameArray) - 2))) {
		$this->assign('topLevelRowSpan', 4);
		if (empty($columnDataFor2ndGroup)) {
			$this->assign('topLevelRowSpan', 0);
		} // if
{/php}
	<th scope="col" align='center' rowspan="{$topLevelRowSpan}" class="{$headerColumnClassName}" valign=middle wrap>{$headerColumnName}</td>
{php}
		} else {
			$this->assign('topLevelColSpan', (count($columnDataFor3rdGroup) * count($columnDataFor2ndGroup)));

{/php}
			<th scope="col" align='center' colspan="{$topLevelColSpan}" class="{$headerColumnClassName}" valign=middle wrap>{$headerColumnName}</td>
{php}

		} // else
	} // for
{/php}
</tr>

{php}
	if (!empty($columnDataFor2ndGroup)) {
{/php}
<tr height="20">
{php}
	for ($i = 0 ; $i < count($columnDataFor2ndGroup) ; $i++) {
		$headerColumn2ClassName = "reportlistViewMatrixThS1";
		$this->assign('headerColumn2ClassName', $headerColumn2ClassName);
		$cellData = $columnDataFor2ndGroup[$i];
		if (empty($cellData)) {
			$cellData = "&nbsp;";
		} // if
		$this->assign('topLevelColSpan', count($columnDataFor3rdGroup));
		$this->assign('columnDataFor2ndGroup', $cellData);
{/php}
	<th scope="col" colspan="{$topLevelColSpan}" align='center' class="{$headerColumn2ClassName}" valign=middle wrap>{$columnDataFor2ndGroup}</td>
{php}
	} // for
{/php}
</tr>
{php}
	} // if
{/php}

{php}
	if (!empty($columnDataFor2ndGroup)) {
{/php}

<tr height="20">
{php}
	for ($i = 0 ; $i < count($columnDataFor2ndGroup) ; $i++) {
		$headerColumn2ClassName = "reportlistViewMatrixThS1";
		$this->assign('headerColumn2ClassName', $headerColumn2ClassName);
		$cellData = $headerColumnNameArray[2];
		if (empty($cellData)) {
			$cellData = "&nbsp;";
		} // if
		$this->assign('topLevelColSpan', count($columnDataFor3rdGroup));
		$this->assign('columnDataFor2ndGroup', $cellData);
{/php}
	<th scope="col" colspan="{$topLevelColSpan}" align='center' class="{$headerColumn2ClassName}" valign=middle wrap>{$columnDataFor2ndGroup}</td>
{php}
	} // for
{/php}

</tr>

<tr height="20">
{php}
	for ($i = 0 ; $i < count($columnDataFor2ndGroup) ; $i++) {
		for ($j = 0 ; $j < count($columnDataFor3rdGroup) ; $j++) {
			$headerColumn2ClassName = "reportlistViewMatrixThS1";
			$this->assign('headerColumn2ClassName', $headerColumn2ClassName);
			$cellData = $columnDataFor3rdGroup[$j];
			if (empty($cellData)) {
				$cellData = "&nbsp;";
			} // if
			$this->assign('columnDataFor3rdGroup', $cellData);
{/php}
	<th scope="col" align='center' class="{$headerColumn2ClassName}" valign=middle wrap>{$columnDataFor3rdGroup}</td>
{php}
		} // for
	} // for
{/php}

</tr>

{php}
	// iteration for the group by data
	$columnDataFor2ndGroup[] = 'Total';
	$columnDataFor3rdGroup1 = $columnDataFor3rdGroup;
	$noofrows = $maximumCellSize;
	for ($i = 0 ; $i < count($rowsAndColumnsData) ; $i++) {
		$rowData = $rowsAndColumnsData[$i];
		$set1stGroupBy = true;
		for ($k = 0 ; $k < $maximumCellSize ; $k++) {
{/php}
			<tr height="20">
{php}
			for ($j = 0 ; $j < count($columnDataFor2ndGroup) ; $j++) {
				$topLevelGpByData = "&nbsp;";
				$set2ndstGroupBy = true;
				if ($j == (count($columnDataFor2ndGroup) -1)) {
					$columnDataFor3rdGroup1 = array();
					$columnDataFor3rdGroup1[] = 'Total';
				} else {
					$columnDataFor3rdGroup1 = $columnDataFor3rdGroup;
				} // else
				for ($m = 0 ; $m < count($columnDataFor3rdGroup1) ; $m++) {
					if ($set1stGroupBy) {
						if (isset($rowData[$headerColumnNameArray[0]])) {
							$topLevelGpByData = $rowData[$headerColumnNameArray[0]];
							if (empty($topLevelGpByData)) {
								$topLevelGpByData = "&nbsp;";
							} // if
						} // if
						$this->assign('topLevelGpByData', $topLevelGpByData);
						$this->assign('rowSpanForTopGpByData', $noofrows);
						$dataCellClass = "reportlistViewMatrixRightEmptyData";
						$this->assign('dataCellClass', $dataCellClass);
{/php}
						<td scope="col" align='center' rowspan={$rowSpanForTopGpByData} class="{$dataCellClass}" valign=middle wrap>{$topLevelGpByData}</td>
{php}
						$set1stGroupBy = false;
					} // if

					$topLevelGp3ByData = "&nbsp;";
					if (isset($rowData[$columnDataFor2ndGroup[$j]])) {
						if (isset($rowData[$columnDataFor2ndGroup[$j]][$columnDataFor3rdGroup1[$m]])) {
							$cellDataArray = $rowData[$columnDataFor2ndGroup[$j]][$columnDataFor3rdGroup1[$m]];
							$arrayKeys = array_keys($cellDataArray);
							$cellData = $cellDataArray[$arrayKeys[$k]];
							$topLevelGp3ByData = $cellData;
							if (empty($topLevelGp3ByData)) {
								$topLevelGp3ByData = "&nbsp;";
							} // if
						} // if
					} // if

					$this->assign('topLevelGp3ByData', $topLevelGp3ByData);
					$dataCellClass = "reportGroupByDataMatrixEvenListRowS1";
					if ($j == (count($columnDataFor2ndGroup)-1)) {
						$dataCellClass = "reportGroupByDataMatrixEvenListRowS2";
					} // if
					if ($j == 0) {
						//$dataCellClass = "reportlistViewMatrixRightEmptyData11";
					} // if
					$this->assign('dataCellClass', $dataCellClass);
{/php}
					<td scope="col" align='center' class="{$dataCellClass}" valign=middle wrap>{$topLevelGp3ByData}</td>
{php}

				} // for
			} // for

{/php}
</tr>
{php}
		} // for
	} // for
	$setGrandTotalString = true;
	$columnDataFor3rdGroup1 = $columnDataFor3rdGroup;

	for ($k = 0 ; $k < $maximumCellSize ; $k++) {
{/php}
	<tr height="20">
{php}
	for ($j = 0 ; $j < count($columnDataFor2ndGroup) ; $j++) {
		if ($j == (count($columnDataFor2ndGroup) - 1)) {
			$columnDataFor3rdGroup1 = array();
			$columnDataFor3rdGroup1[] = 'Total';
		} else {
			$columnDataFor3rdGroup1 = $columnDataFor3rdGroup;
		} // else

		for ($m = 0 ; $m < count($columnDataFor3rdGroup1) ; $m++) {
			$grandTotalString = $grandTotal[$headerColumnNameArray[0]];
			$this->assign('grandTotalData', $grandTotalString);
			$dataCellClass = "reportlistViewMatrixRightEmptyData1";
			$this->assign('dataCellClass', $dataCellClass);
			if ($setGrandTotalString) {
				$setGrandTotalString = false;
				//$this->assign('colSpanFor2ndGpByData', 1 + (count($columnDataFor3rdGroup) * (count($columnDataFor2ndGroup) - 1)));
				$this->assign('rowSpanFor2ndGpByData', $maximumCellSize);
{/php}
	<td scope="col" align='center' rowspan="{$rowSpanFor2ndGpByData}" class="{$dataCellClass}" valign=middle wrap>{$grandTotalData}</td>
{php}
			} // if
			$grandTotalData = "&nbsp;";
			if ($j == (count($columnDataFor2ndGroup) - 1)) {
				if (isset($grandTotal[$columnDataFor3rdGroup1[$m]])) {
					$cellDataArray = $grandTotal[$columnDataFor3rdGroup1[$m]];
					$arrayKeys = array_keys($cellDataArray);
					$cellData = $cellDataArray[$arrayKeys[$k]];
					$grandTotalData = $cellData;
					if (empty($grandTotalData)) {
						$grandTotalData = "&nbsp;";
					} // if

				} // if
			} else if (isset($grandTotal[$columnDataFor2ndGroup[$j]][$columnDataFor3rdGroup1[$m]])) {
				$cellDataArray = $grandTotal[$columnDataFor2ndGroup[$j]][$columnDataFor3rdGroup1[$m]];
				$arrayKeys = array_keys($cellDataArray);
				$cellData = $cellDataArray[$arrayKeys[$k]];
				$grandTotalData = $cellData;
				if (empty($grandTotalData)) {
					$grandTotalData = "&nbsp;";
				} // if
			} // else
			$this->assign('grandTotalData', $grandTotalData);
			$dataCellClass = "reportGroupByDataMatrixEvenListRowS1";
			if ($j == (count($columnDataFor2ndGroup)-1)) {
				$dataCellClass = "reportGroupByDataMatrixEvenListRowS2";
			} // if
			if ($k == ($maximumCellSize -1)) {
				$dataCellClass = "reportGroupByDataMatrixEvenListRowS3";
				if ($m == (count($columnDataFor3rdGroup1)-1)) {
					if ($j == (count($columnDataFor2ndGroup)-1)) {
						$dataCellClass = "reportGroupByDataMatrixEvenListRowS4";
					} // if
				} // if
			} // if
			//$dataCellClass = "reportlistViewMatrixRightEmptyData";
			$this->assign('dataCellClass', $dataCellClass);
{/php}
	<td scope="col" align='center' class="{$dataCellClass}" valign=middle wrap>{$grandTotalData}</td>
{php}
		} // for
	} // for
{/php}
	</tr>
{php}
	} // for
{/php}
{php}
	} else {
{/php}
<tr height="20">
{php}
		for ($j = 0 ; $j < 3 ; $j++) {
{/php}
<td scope="col" align='center' class="reportGroupByDataMatrixEvenListRowS4" valign=middle wrap>No results</td>
{php}
		} // for
{/php}
</tr>
{php}
	} // else
{/php}
</table>

{php}
template_query_table($reporter);
{/php}
