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
 * Class AbstractForecastHooks
 *
 * This is a Forecast Logic Hook Base class, this can be used so we can just have the different Logic Hooks extend
 * this class so we only have this code once.
 */
abstract class AbstractForecastHooks
{
    public static $settings;

    /**
     * Utility Method to make sure Forecast is setup and usable
     *
     * @return bool
     */
    public static function isForecastSetup()
    {
        static::loadForecastSettings();
        return static::$settings['is_setup'] == 1;
    }

    /**
     * Get the currently configured forecast closed sales stages
     *
     * @return array
     */
    public static function getForecastClosedStages()
    {
        static::loadForecastSettings();

        // get all possible closed stages
        $stages = array_merge(
            (array)static::$settings['sales_stage_won'],
            (array)static::$settings['sales_stage_lost']
        );

        return $stages;
    }

    /**
     * Utility method to load Forecast Settings
     *
     * @param bool $reload      Forecast Reload the settings
     */
    protected static function loadForecastSettings($reload = false)
    {
        /* @var $admin Administration */
        if (empty(static::$settings) || $reload === true) {
            static::$settings = Forecast::getSettings($reload);
        }
    }
}
