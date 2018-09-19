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
 * $Id: view.detail.php
 * Description: This file is used to override the default Meta-data DetailView behavior
 * to provide customization specific to the Campaigns module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class OpportunitiesViewEdit extends ViewEdit
{

    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
    }


    public function display()
    {
        global $app_list_strings;
        $json = getJSONobj();
        $prob_array = $json->encode($app_list_strings['sales_probability_dom']);
        $prePopProb = '';
        if (empty($this->bean->id) && empty($_REQUEST['probability'])) {
            $prePopProb = 'document.getElementsByName(\'sales_stage\')[0].onchange();';
        }

        $admin = BeanFactory::newBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts');
        $wonStages = $json->encode($settings['sales_stage_won']);

        $probability_script = <<<EOQ
	<script>
	prob_array = $prob_array;
	var sales_stage = document.getElementsByName('sales_stage')[0];
	if(sales_stage) {

        var probability = document.getElementsByName('probability')[0];

        won_stages = $wonStages;
        var best_case = document.getElementsByName('best_case')[0];
        var worst_case = document.getElementsByName('worst_case')[0];
        var amount = document.getElementsByName('amount')[0];

        if(won_stages.indexOf(sales_stage.value) > -1) {
            if(best_case) {
                best_case.value = amount.value;
                best_case.setAttribute("readonly", "true");
            }
            if(worst_case) {
                worst_case.value = amount.value;
                worst_case.setAttribute("readonly", "true");
            }
        }
        sales_stage.onchange = function() {
            if(typeof(sales_stage.value) != "undefined"
                && prob_array[sales_stage.value]
                && typeof(probability) != "undefined"
            ) {
                probability.value = prob_array[sales_stage.value];
                SUGAR.util.callOnChangeListers(probability);
            }

            if(won_stages.indexOf(sales_stage.value) > -1) {
                if(best_case) {
                    best_case.value = amount.value;
                    best_case.setAttribute("readonly", "true");
                }
                if(worst_case) {
                    worst_case.value = amount.value;
                    worst_case.setAttribute("readonly", "true");
                }
            } else if(typeof(sales_stage.value) != "undefined") {
                if(best_case) {
                    best_case.removeAttribute("readonly");
                }
                if(worst_case) {
                    worst_case.removeAttribute("readonly");
                }
            }
        };
        amount.onchange = function() {
            if(won_stages.indexOf(sales_stage.value) > -1) {
                if(best_case) {
                    best_case.value = amount.value;
                }
                if(worst_case) {
                    worst_case.value = amount.value;
                }
            }
        };
	}
	$prePopProb
	</script>
EOQ;

        $this->ss->assign('PROBABILITY_SCRIPT', $probability_script);
        parent::display();
    }
}
