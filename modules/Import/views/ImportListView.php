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



class ImportListView
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var array
     */
    protected $headerColumns = array();

    /**
     * @var Sugar_Smarty
     */
    private $ss;

    /**
     * @var string
     */
    private $tableID;

    /**
     * @var Paginatable
     */
    private $dataSource;

    /**
     * @var int
     */
    private $recordsPerPage;

    /**
     * @var int
     */
    private $maxColumns;

    /**
     * Create a list view object that can display a data source which implements the Paginatable interface.
     *
     * @throws Exception
     * @param  Paginatable $dataSource
     * @param  array $params
     * @param string $tableIdentifier
     */
    public function __construct($dataSource, $params, $tableIdentifier = '')
    {
        global $sugar_config;

        $this->ss = new Sugar_Smarty();
        $this->tableID = $tableIdentifier;

        $this->dataSource = $dataSource;
        $this->headerColumns = $this->dataSource->getHeaderColumns();

        if( !isset($params['offset']) )
            throw new Exception("Missing required parameter offset for ImportListView");
        else
            $this->dataSource->setCurrentOffset($params['offset']);

        $this->recordsPerPage = isset($params['totalRecords']) ? $params['totalRecords'] : ($sugar_config['list_max_entries_per_page'] + 0);
        $this->data = $this->dataSource->loadDataSet($this->recordsPerPage)->getDataSet();
        $this->maxColumns = $this->getMaxColumnsForDataSet();
    }

    /**
     * Display the list view like table.
     *
     * @param bool $return True if we should return the content rather than echoing.
     * @return
     */
    public function display($return = FALSE)
    {
        global $app_strings,$mod_strings;

        $navStrings = array('next' => $app_strings['LNK_LIST_NEXT'],'previous' => $app_strings['LNK_LIST_PREVIOUS'],'end' => $app_strings['LNK_LIST_END'],
                            'start' => $app_strings['LNK_LIST_START'],'of' => $app_strings['LBL_LIST_OF']);
        $this->ss->assign('navStrings', $navStrings);
        $this->ss->assign('pageData', $this->generatePaginationData() );
        $this->ss->assign('tableID', $this->tableID);
        $this->ss->assign('colCount', count($this->headerColumns));
        $this->ss->assign('APP',$app_strings);
        $this->ss->assign('rowColor', array('oddListRow', 'evenListRow'));
        $this->ss->assign('displayColumns',$this->headerColumns);
        $this->ss->assign('data', $this->data);
        $this->ss->assign('maxColumns', $this->maxColumns);
        $this->ss->assign('MOD', $mod_strings);
        $contents = $this->ss->fetch('modules/Import/tpls/listview.tpl');
        if($return)
            return $contents;
        else
            echo $contents;
    }

    /**
     * For the data set that was loaded, find the max count of entries per row.
     *
     * @return int
     */
    protected function getMaxColumnsForDataSet()
    {
        $maxColumns = 0;
        foreach($this->data as $data)
        {
            if(count($data) > $maxColumns)
                $maxColumns = count($data);
        }
        return $maxColumns;
    }

    /**
     * Generate the pagination data.
     *
     * @return array
     */
    protected function generatePaginationData()
    {
        $currentOffset = $this->dataSource->getCurrentOffset();
        $totalRecordsCount = $this->dataSource->getTotalRecordCount();
        $nextOffset =  $currentOffset+ $this->recordsPerPage;
        $nextOffset = $nextOffset > $totalRecordsCount ? 0 : $nextOffset;
        $lastOffset = floor($totalRecordsCount / $this->recordsPerPage) * $this->recordsPerPage;
        $previousOffset = $currentOffset - $this->recordsPerPage;
        $offsets = array('totalCounted'=> true, 'total' => $totalRecordsCount, 'next' => $nextOffset,
                         'last' => $lastOffset, 'previous' => $previousOffset,
                         'current' => $currentOffset, 'lastOffsetOnPage' => count($this->data) + $this->dataSource->getCurrentOffset() );

        $pageData = array('offsets' => $offsets);
        return $pageData;

    }



}
