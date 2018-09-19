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



class ViewSpot extends ViewAjax
{
    /**
     * Constructor
     *
     * @see SugarView::__construct()
     */
    public function __construct()
    {
        $options = $this->options;
        parent::__construct();
        $this->options = $options;
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {

		$offset = -1;
        $modules = array();

		if(!empty($_REQUEST['zoom']) )
        {
			$modules = array($_REQUEST['zoom']);
			if(isset($_REQUEST['offset'])){
				$offset = $_REQUEST['offset'];
			}
		}

        $limit = ( !empty($GLOBALS['sugar_config']['max_spotresults_initial']) ? $GLOBALS['sugar_config']['max_spotresults_initial'] : 5 );
        if($offset !== -1)
        {
            $limit = ( !empty($GLOBALS['sugar_config']['max_spotresults_more']) ? $GLOBALS['sugar_config']['max_spotresults_more'] : 20 );
        }

        $options = array('current_module' => $this->module, 'modules' => $modules);

        $searchEngine = SugarSearchEngineFactory::getInstance('', array(), true);

        $trimmed_query = trim($this->request->getValidInputRequest('q'));
        $rs = $searchEngine->search($trimmed_query, $offset, $limit, $options);
        $formattedResults = $this->formatSearchResultsToDisplay($rs, $offset,$trimmed_query);

        $query_encoded = urlencode($trimmed_query);
        $displayMoreForModule = $formattedResults['displayMoreForModule'];
        $displayResults = $formattedResults['displayResults'];

        $ss = new Sugar_Smarty();
        $ss->assign('displayResults', $displayResults);
        $ss->assign('displayMoreForModule', $displayMoreForModule);
        $ss->assign('appStrings', $GLOBALS['app_strings']);
        $ss->assign('appListStrings', $GLOBALS['app_list_strings']);
        $ss->assign('queryEncoded', $query_encoded);
        $ss->assign('test', "#bwc/index.php?module=Home&action=UnifiedSearch&search_form=false&advanced=false&query_string=".$query_encoded);

        echo $ss->fetch(SugarAutoLoader::existingCustomOne('include/SearchForm/tpls/SugarSpot.tpl'));
    }


    protected function formatSearchResultsToDisplay($results, $offset, $trimmedQuery)
    {
        $displayResults = array();
        $displayMoreForModule = array();
        //$actions=0;
        if($results == null) $results = array();
        foreach($results as $m=>$data)
        {
            if(empty($data['data']))
            {
                continue;
            }

            $countRemaining = $data['pageData']['offsets']['total'] - count($data['data']);
            if($offset > 0)
            {
                $countRemaining -= $offset;
            }

            if($countRemaining > 0)
            {
                $displayMoreForModule[$m] = array('query'=>$trimmedQuery,
                    'offset'=>++$data['pageData']['offsets']['next'],
                    'countRemaining'=>$countRemaining);
            }

            foreach($data['data'] as $row)
            {
                $name = '';

                //Determine a name to use
                if(!empty($row['NAME']))
                {
                    $name = $row['NAME'];
                }
                else if(!empty($row['DOCUMENT_NAME']))
                {
                    $name = $row['DOCUMENT_NAME'];
                }
                else
                {
                    $foundName = '';
                    foreach($row as $k=>$v)
                    {
                        if(strpos($k, 'NAME') !== false)
                        {
                            if(!empty($row[$k]))
                            {
                                $name = $v;
                                break;
                            }
                            else if(empty($foundName))
                            {
                                $foundName = $v;
                            }
                        }
                    }

                    if(empty($name))
                    {
                        $name = $foundName;
                    }
                }

                $displayResults[$m][$row['ID']] = $name;
            }
        }

        return array('displayResults' => $displayResults, 'displayMoreForModule' => $displayMoreForModule);
    }
}

