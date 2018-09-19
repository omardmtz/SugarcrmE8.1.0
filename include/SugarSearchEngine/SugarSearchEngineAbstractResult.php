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
 * Abstract class to represent a result entry.
 *
 *                      !!! DEPRECATION WARNING !!!
 *
 * All code in include/SugarSearchEngine is going to be deprecated in a future
 * release. Do not use any of its APIs for code customizations as there will be
 * no guarantee of support and/or functionality for it. Use the new framework
 * located in the directories src/SearchEngine and src/Elasticsearch.
 *
 * @deprecated
 */
abstract class SugarSearchEngineAbstractResult implements SugarSearchEngineResult
{

    /**
     * @var SugarBean
     */
    protected $bean;

    public function getModuleName()
    {
        $moduleName = $this->getModule();
        if( isset($GLOBALS['app_list_strings']['moduleList'][$moduleName]) )
            return $GLOBALS['app_list_strings']['moduleList'][$moduleName];
        else
            return $moduleName;
    }

    public function getSummaryText()
    {
        if($this->bean !== FALSE)
            return $this->bean->get_summary_text();
    }

    public function __toString()
    {
        return __CLASS__ . " " . $this->getModule() . ": " . $this->getSummaryText() . " " . $this->getId();
    }


    /**
     *
     * @return integer
     */
    public function getScore()
    {
        return 0;
    }

}


