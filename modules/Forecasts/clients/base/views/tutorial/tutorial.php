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
/*********************************************************************************
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$viewdefs['Forecasts']['base']['view']['tutorial'] = array(
    'records' => array(
        'intro' =>'LBL_TOUR_FORECAST_INTRO',
        'version' =>1,
        'content' => array(
            array(
                'name' => '.topline [for="date_filter"]',
                'text' => 'LBL_TOUR_FORECASTS_TIMEPERIODS',
                'full' => true,
                'horizAdj'=> -15,
                'vertAdj'=> -15,
            ),
            array(
                'name' => '.topline .last-commit',
                'text' => 'LBL_TOUR_FORECASTS_COMMITS',
                'full' => true,
                'horizAdj'=> -20,
                'vertAdj'=> -20,
            ),
            array(
                'name' => '.editableColumn',
                'text' => 'LBL_TOUR_FORECASTS_INLINEEDIT',
                'full' => true,
            ),
            array(
                'name' => '.dashlets .forecast-details',
                'text' => 'LBL_TOUR_FORECASTS_PROGRESS',
                'full' => true,
                'horizAdj'=> -1,
                'vertAdj'=> -5,
            ),
            array(
                'name' => '.dashlets .forecasts-chart-wrapper',
                'text' => 'LBL_TOUR_FORECASTS_CHART',
                'full' => true,
                'horizAdj'=> -1,
                'vertAdj'=> -5,
            ),
        )
    ),
);
