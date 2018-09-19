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


class ForecastWorksheetHooks extends AbstractForecastHooks
{

    /**
     * This method, just set the date_modified to the value from the db, vs the user formatted value that sugarbean sets
     * after it has been retrieved
     *
     * @param ForecastWorksheet $worksheet
     * @param string $event
     * @param array $params
     */
    public static function fixDateModified(ForecastWorksheet $worksheet, $event, $params = array())
    {
        if (isset($worksheet->fetched_row['date_modified'])) {
            $worksheet->date_modified = $worksheet->fetched_row['date_modified'];
        }
    }

    /**
     * @param ForecastWorksheet $bean
     * @param string $event
     * @param array $args
     * @return bool
     */
    public static function checkRelatedName($bean, $event, $args)
    {

        if ($event == 'before_save') {
            if (empty($bean->account_id) && !empty($bean->account_name)) {
                $bean->account_name = '';
            }

            if (empty($bean->opportunity_id) && !empty($bean->opportunity_name)) {
                $bean->opportunity_name = '';
            }

            // if we are in a delete operation, don't update the date modified
            if (isset($bean->fetched_row['date_modified']) &&
                (SugarBean::inOperation('delete') || SugarBean::inOperation('saving_related'))) {
                $bean->date_modified = $bean->fetched_row['date_modified'];
            }
        }
        return true;
    }

    /**
     * After the relationship is deleted, we need to also delete the name field,
     * the reason we have to do it this way is that the one to many relationship
     * is it doesn't resave the bean, but just runs a query to unset the id field
     *
     * @param ForecastWorksheet $bean
     * @param string $event
     * @param array $args
     */
    public static function afterRelationshipDelete($bean, $event, $args)
    {
        if ($event == 'after_relationship_delete') {
            if ($bean->load_relationship($args['link'])) {
                $relationshipDef = $bean->{$args['link']}->getRelationshipObject()->def;
                $name_field = str_replace('_id', '_name', $relationshipDef['rhs_key']);
                if ($bean->getFieldDefinition($name_field)) {
                    $sql = "UPDATE {$bean->table_name} SET {$name_field} = NULL WHERE id = "
                        . $bean->db->quoted($bean->id);
                    $bean->db->query($sql);
                }
            }
        }
    }


    /**
     * @param ForecastWorksheet $bean
     * @param string $event
     * @param array $args
     * @return bool
     */
    public static function managerNotifyCommitStage($bean, $event, $args)
    {
        /**
         * Only run this logic hook when the following conditions are met
         *  - Bean is not a Draft Record
         *  - Bean is not a new Record
         *  - Forecast is Setup
         */
        if ($bean->draft === 0 && !empty($bean->fetched_row) && static::isForecastSetup()) {
            $forecast_by = self::$settings['forecast_by'];
            // make sure we have a bean of the one that we are forecasting by
            // and it's fetched_row commit_stage is equal to `include`
            // and it's updated commit_stage does not equal `include`
            if ($bean->parent_type === $forecast_by &&
                $bean->fetched_row['commit_stage'] === 'include' &&
                $bean->commit_stage !== 'include'
            ) {
                // send a notification to their manager if they have a manager
                /* @var $user User */
                $bean->load_relationship('assigned_user_link');
                $beans = $bean->assigned_user_link->getBeans();
                $user = array_shift($beans);
                if (!empty($user->reports_to_id)) {
                    $worksheet_strings = static::getLanguageStrings($bean->module_name);
                    $mod_strings = static::getLanguageStrings($bean->parent_type);

                    $notifyBean = static::getNotificationBean();
                    $notifyBean->assigned_user_id = $user->reports_to_id;
                    $notifyBean->type = 'information';
                    $notifyBean->created_by = $user->id;
                    $notifyBean->parent_type = $bean->parent_type;
                    $notifyBean->parent_id = $bean->parent_id;
                    $notifyBean->name = string_format($worksheet_strings['LBL_MANAGER_NOTIFY_NAME'], array($mod_strings['LBL_MODULE_NAME_SINGULAR']));
                    $notifyBean->description = string_format(
                        $worksheet_strings['LBL_MANAGER_NOTIFY'],
                        array(
                            $mod_strings['LBL_MODULE_NAME_SINGULAR'],
                            $bean->name
                        )
                    );
                    $notifyBean->save();

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Utility Method to return a Notifications Bean
     *
     * @return Notifications
     */
    public static function getNotificationBean()
    {
        return BeanFactory::newBean('Notifications');
    }

    /**
     * Utility method to return the module language strings
     *
     * @param string $module
     * @return array|null
     */
    public static function getLanguageStrings($module)
    {
        // If the session has a language set, use that
        if (!empty($_SESSION['authenticated_user_language'])) {
            $lang = $_SESSION['authenticated_user_language'];
        } else {
            global $current_language;
            $lang = $current_language;
        }

        return return_module_language($lang, $module);
    }
}
