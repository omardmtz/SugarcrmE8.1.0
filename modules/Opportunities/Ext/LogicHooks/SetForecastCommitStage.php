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
 * Before we save an opportunity, check if we need to set the commit stage
 */
$hook_array['before_save'][] = array(
    1,
    'setCommitStageIfEmpty',
    'modules/Forecasts/ForecastHooks.php',
    'ForecastHooks',
    'setCommitStageIfEmpty',
);
