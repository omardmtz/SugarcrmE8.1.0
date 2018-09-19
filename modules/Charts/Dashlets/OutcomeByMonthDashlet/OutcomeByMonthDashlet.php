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

 // $Id: MyPipelineBySalesStageDashlet.php 24275 2007-07-13 04:26:44Z awu $



class OutcomeByMonthDashlet extends DashletGenericChart
{
    public $obm_ids = array();
    public $obm_date_start;
    public $obm_date_end;
    
    /**
     * @see DashletGenericChart::$_seedName
     */
    protected $_seedName = 'Opportunities';

    /**
     * @see DashletGenericChart::__construct()
     */
    public function __construct(
        $id,
        array $options = null
        )
    {
        global $timedate;

		if(empty($options['obm_date_start']))
            $options['obm_date_start'] = $timedate->nowDbDate();

        if(empty($options['obm_date_end']))
            $options['obm_date_end'] = $timedate->asDbDate($timedate->getNow()->modify("+6 months"));

        parent::__construct($id,$options);
    }

    /**
     * @see DashletGenericChart::displayOptions()
     */
    public function displayOptions()
    {
        if (!isset($this->obm_ids) || count($this->obm_ids) == 0)
			$this->_searchFields['obm_ids']['input_name0'] = array_keys(get_user_array(false));

        return parent::displayOptions();
    }

    /**
     * @see DashletGenericChart::display()
     */
    public function display()
    {
        $currency_symbol = $GLOBALS['sugar_config']['default_currency_symbol'];
        if ($GLOBALS['current_user']->getPreference('currency')){

            $currency = BeanFactory::getBean('Currencies', $GLOBALS['current_user']->getPreference('currency'));
            $currency_symbol = $currency->symbol;
        }

        require("modules/Charts/chartdefs.php");
        $chartDef = $chartDefs['outcome_by_month'];

        $sugarChart = SugarChartFactory::getInstance();
        $sugarChart->setProperties('',
            translate('LBL_OPP_SIZE', 'Charts') . ' ' . $currency_symbol . '1' .translate('LBL_OPP_THOUSANDS', 'Charts'),
            $chartDef['chartType']);
        $sugarChart->base_url = $chartDef['base_url'];
        $sugarChart->group_by = $chartDef['groupBy'];
        $sugarChart->url_params = array();
        $sugarChart->getData($this->constructQuery());
        $sugarChart->is_currency = true;
        $sugarChart->data_set = $sugarChart->sortData($sugarChart->data_set, 'm', false, 'sales_stage', true, true);
        $xmlFile = $sugarChart->getXMLFileName($this->id);
        $sugarChart->saveXMLFile($xmlFile, $sugarChart->generateXML());
	
        return $this->getTitle('<div align="center"></div>') . 
            '<div align="center">' . $sugarChart->display($this->id, $xmlFile, '100%', '480', false) . '</div>'. $this->processAutoRefresh();
	}

    /**
     * @see DashletGenericChart::constructQuery()
     */
    protected function constructQuery()
    {
        $query = "SELECT sales_stage,".
            db_convert('opportunities.date_closed','date_format',array("'%Y-%m'"),array("'YYYY-MM'"))." as m, ".
            "sum(amount_usdollar/1000) as total, count(*) as opp_count FROM opportunities ";
        $this->getSeedBean()->add_team_security_where_clause($query);
        $query .= " WHERE opportunities.date_closed >= ".db_convert("'".$this->obm_date_start."'",'date') .
                        " AND opportunities.date_closed <= ".db_convert("'".$this->obm_date_end."'",'date') .
                        " AND opportunities.deleted=0";
        if (count($this->obm_ids) > 0)
            $query .= " AND opportunities.assigned_user_id IN ('" . implode("','",$this->obm_ids) . "')";
        $query .= " GROUP BY sales_stage,".
                        db_convert('opportunities.date_closed','date_format',array("'%Y-%m'"),array("'YYYY-MM'")) .
                    " ORDER BY m";

        return $query;
    }
}