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


class ViewFts extends SugarView
{
    private $fullView = FALSE;
    private $templateName = '';
    private $rsTemplateName = 'fts_full_rs.tpl';

    public function __construct()
    {
        $this->fullView = !empty($_REQUEST['full']) ? TRUE : FALSE;

        if($this->fullView)
        {
            $this->options = array('show_title'=> true,'show_header'=> true,'show_footer'=> true,'show_javascript'=> true,'show_subpanels'=> false,'show_search'=> false);
            $this->templateName = 'fts_full.tpl';
        }
        else
        {
            $this->options = array('show_title'=> false,'show_header'=> false,'show_footer'=> false,'show_javascript'=> false,'show_subpanels'=> false,'show_search'=> false);
            $this->templateName = 'fts_spot.tpl';
        }
        parent::__construct();

    }
    /**
     * @see SugarView::display()
     */
    public function display($return = false, $encode = false)
    {
        $offset = $this->request->getValidInputRequest('offset', array('Assert\Type' => array('type' => 'numeric')), 0);
        $resultSetOnly = !empty($_REQUEST['rs_only']) ? $_REQUEST['rs_only'] : FALSE;
        $refreshModuleFilter = !empty($_REQUEST['refreshModList']) ? $_REQUEST['refreshModList'] : FALSE;

        $limit = ( !empty($GLOBALS['sugar_config']['max_spotresults_initial']) ? $GLOBALS['sugar_config']['max_spotresults_initial'] : 10 );
        $indexOffset = $offset / $limit;
        $moduleFilter = false;

        if (!empty($_REQUEST['m'])) {
            $assert = is_array($_REQUEST['m']) ? 'Assert\All' : 'Assert\Delimited';
            $moduleFilter = $this->request->getValidInputRequest('m', array($assert => array('constraints' => 'Assert\Mvc\ModuleName')));
        }

        $disabledModules = $this->request->getValidInputRequest(
            'disabled_modules', 
            array('Assert\Delimited' => array('constraints' => 'Assert\Mvc\ModuleName'))
        );

        //If no modules have been passed in then lets check user preferences.
        if ($moduleFilter === false) {
            $moduleFilter = SugarSearchEngineMetadataHelper::getUserEnabledFTSModules();
        }
        $filteredModules =  $this->getFilterModules($moduleFilter, $disabledModules);
        $append_wildcard = !empty($_REQUEST['append_wildcard']) ? $_REQUEST['append_wildcard'] : false;
        $options = array('current_module' => $this->module, 'moduleFilter' => $moduleFilter, 'append_wildcard' => $append_wildcard);

        if( $this->fullView || $refreshModuleFilter)
        {
            $options['apply_module_facet'] = TRUE;
        }

        $searchEngine = SugarSearchEngineFactory::getInstance();
        $queryString = $this->request->getValidInputRequest('q');
        $trimmed_query = trim($queryString);
        $rs = $searchEngine->search($trimmed_query, $offset, $limit, $options);
        if($rs == null)
        {
            $totalHitsFound = 0;
            $totalTime = 0;
            $hitsByModule = array();
        }
        else
        {
            $totalHitsFound = $rs->getTotalHits();
            $totalTime = $rs->getTotalTime();
            $hitsByModule = $rs->getModuleFacet();
        }
        $query_encoded = urlencode($trimmed_query);

        if (count($filteredModules['enabled']) != count($moduleFilter)) {
            // if there is a full module list we need to check "Show all"
            $this->ss->assign('moduleFilter', $moduleFilter);
        }
        $showMoreDivStyle = ($totalHitsFound > $limit) ? '' : "display:none;";
        $this->ss->assign('showMoreDivStyle', $showMoreDivStyle);
        $this->ss->assign('indexOffset', $indexOffset);
        $this->ss->assign('offset', $offset);
        $this->ss->assign('limit', $limit);
        $this->ss->assign('totalHits', $totalHitsFound);
        $this->ss->assign('totalTime', $totalTime);
        $this->ss->assign('queryEncoded', $query_encoded);
        $this->ss->assign('resultSet', $rs);
        $this->ss->assign('APP_LIST', $GLOBALS['app_list_strings']);
        $template = SugarAutoLoader::existingCustomOne("include/MVC/View/tpls/{$this->templateName}");
        $rsTemplate = SugarAutoLoader::existingCustomOne("include/MVC/View/tpls/{$this->rsTemplateName}");
        $this->ss->assign('rsTemplate', $rsTemplate);

        if( $this->fullView )
        {
            $this->ss->assign(
                'filterModules',
                $this->filterModuleListByTypes($filteredModules['enabled'], $hitsByModule, $moduleFilter)
            );
            if($resultSetOnly)
            {
                $out = array('results' => $this->ss->fetch($rsTemplate), 'totalHits' => $totalHitsFound, 'totalTime' => $totalTime);
                if( $refreshModuleFilter )
                    $out['mod_filter'] = $this->ss->fetch('include/MVC/View/tpls/fts_modfilter.tpl');

                return $this->sendOutput(json_encode($out));
            }

            $this->ss->assign('enabled_modules', json_encode($filteredModules['enabled']));
            $this->ss->assign('disabled_modules', json_encode($filteredModules['disabled']));
        }

        $contents = $this->ss->fetch($template);
        return $this->sendOutput($contents, $return, $encode);

    }

    /**
     * Given the enable module list and a facet result set for the last query, add
     * a count to the filter module list.
     *
     * @param $modulelist
     * @param $facetResults
     * @param $moduleFilter array list of searched modules
     * @return mixed
     */
    protected function filterModuleListByTypes($modulelist, $facetResults, $moduleFilter)
    {
        if($facetResults === FALSE)
            return $modulelist;

        foreach($modulelist as &$moduleEntry)
        {
            if( isset($facetResults[$moduleEntry['module']]) )
                $moduleEntry['count'] = $facetResults[$moduleEntry['module']];
            else
            {
                if (empty($moduleFilter) || in_array($moduleEntry['module'], $moduleFilter)) {
                    $moduleEntry['count'] = 0;
                }
                else
                {
                    $moduleEntry['count'] = '';
                }
            }
        }

        return $modulelist;
    }


    protected function sendOutput($contents, $return = false, $encode = false)
    {
        if($encode)
            $contents = json_encode(array('results' => $contents));
        if($return)
            return $contents;
        else
            echo $contents;
    }

    /**
     * Get the enabled and disabled modules for the datatable
     *
     * @param $moduleFilter array Requested modules for search
     * @param $disabledModules array Requested modules for disable in search
     * @return array
     */
    protected function getFilterModules($moduleFilter, $disabledModules)
    {
        $filteredEnabled = SugarSearchEngineMetadataHelper::getUserEnabledFTSModules();
        $userDisabled = $GLOBALS['current_user']->getPreference('fts_disabled_modules');
        $userDisabled = explode(",", $userDisabled);

        // Filter by System enabled FTS modules
        $systemEnabledModules = SugarSearchEngineMetadataHelper::getSystemEnabledFTSModules();
        $userDisabled = array_intersect_key($systemEnabledModules, array_flip($userDisabled));
        $filteredEnabled = array_intersect_key($systemEnabledModules, array_flip($filteredEnabled));

        $userDisabled = $this->translateModulesList($userDisabled);
        $filteredEnabled = $this->translateModulesList($filteredEnabled);
        sort($filteredEnabled);

        if (!empty($moduleFilter)) {
            foreach ($filteredEnabled as $key => $info) {
                if (!in_array($info['module'], $moduleFilter) && in_array($info['module'], $disabledModules)) {
                    unset($filteredEnabled[$key]);
                    // its not enabled, its disabled
                    $userDisabled = $info;
                }
            }
        }

        return array('enabled' => $filteredEnabled, 'disabled' => $userDisabled);
    }

    /**
     * Translate a list of modules to the format expected by our YUI datatables.
     *
     * @param $module
     * @return array
     */
    protected function translateModulesList($module)
    {
        $modulesTranslated = array();
        asort($module);
        foreach($module as $m)
        {
            $moduleName = isset($GLOBALS['app_list_strings']['moduleList'][$m]) ? $GLOBALS['app_list_strings']['moduleList'][$m] : $m;
            $modulesTranslated[] = array('module'=> $m, 'label' => $moduleName);
        }
        return $modulesTranslated;
    }
}

