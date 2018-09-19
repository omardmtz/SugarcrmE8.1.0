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


class SugarACLForecastWorksheets extends SugarACLStrategy
{
    /**
     * @var RevenueLineItem|Opportunity|SugarBean
     */
    protected static $forecastByBean;

    /**
     * Run the check Access for this custom ACL helper.
     *
     * @param string $module
     * @param string $view
     * @param array $context
     * @return bool
     */
    public function checkAccess($module, $view, $context)
    {
        if ($module != 'ForecastWorksheets') {
            return false;
        }

        if ($view == 'team_security') {
            // Let the other modules decide
            return true;
        }

        // Let's make it a little easier on ourselves and fix up the actions nice and quickly
        $view = SugarACLStrategy::fixUpActionName($view);
        $bean = $this->getForecastByBean();
        $current_user = $this->getCurrentUser($context);

        if (empty($view) || empty($current_user->id)) {
            return true;
        }

        if ($view == 'field') {
            // Opp Bean, Amount Field = Likely Case on worksheet
            if ($bean instanceof Opportunity && $context['field'] == 'likely_case') {
                $context['field'] = 'amount';
            }

            // always set the bean to the context
            $context['bean'] = $bean;
            // make sure the user has access to the field
            return $bean->ACLFieldAccess($context['field'], $context['action'], $context);
        }

        return true;
    }

    /**
     * Return the bean for what we are forecasting by
     *
     * @return RevenueLineItem|Opportunity|SugarBean
     */
    protected function getForecastByBean()
    {
        if (!(static::$forecastByBean instanceof SugarBean)) {
            /* @var $admin Administration */
            $admin = BeanFactory::newBean('Administration');
            $settings = $admin->getConfigForModule('Forecasts');

            // if we don't have the forecast_by from the db, grab the defaults that we use on set.
            if (empty($settings['forecast_by'])) {
                $settings = ForecastsDefaults::getDefaults();
            }

            $bean = $settings['forecast_by'];

            static::$forecastByBean = BeanFactory::newBean($bean);
        }

        return static::$forecastByBean;
    }
}
