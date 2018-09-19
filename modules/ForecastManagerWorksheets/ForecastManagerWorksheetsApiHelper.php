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


class ForecastManagerWorksheetsApiHelper extends SugarBeanApiHelper
{
    /**
     * Formats the bean so it is ready to be handed back to the API's client. Certain fields will get extra processing
     * to make them easier to work with from the client end.
     *
     * @param $bean SugarBean|ForecastManagerWorksheet The bean you want formatted
     * @param $fieldList array Which fields do you want formatted and returned (leave blank for all fields)
     * @param $options array Currently no options are supported
     * @return array The bean in array format, ready for passing out the API to clients.
     */
    public function formatForApi(SugarBean $bean, array $fieldList = array(), array $options = array())
    {
        $data = parent::formatForApi($bean, $fieldList, $options);
        $sq = new SugarQuery();
        $sq->select('date_modified');
        $sq->from($bean)->where()
            ->equals('assigned_user_id', $bean->assigned_user_id)
            ->equals('user_id', $bean->user_id)
            ->equals('draft', 0)
            ->equals('timeperiod_id', $bean->timeperiod_id);
        $beans = $sq->execute();

        $data['show_history_log'] = 0;
        if (empty($beans) && !empty($bean->fetched_row['date_modified'])) {
            /* @var $tp TimePeriod */
            $tp = BeanFactory::getBean('TimePeriods', $bean->timeperiod_id);

            // When reportee has committed but manager has not
            // make sure that the reportee actually has a commit for the timeperiod,
            // this is to handle the case where the manager saves draft before the reportee can commit
            $sq = new SugarQuery();
            $sq->select('id');
            $sq->from(BeanFactory::newBean('ForecastWorksheets'))->where()
                ->equals('assigned_user_id', $bean->user_id)
                ->equals('draft', 0)
                ->queryAnd()
                ->gte('date_closed_timestamp', $tp->start_date_timestamp)
                ->lte('date_closed_timestamp', $tp->end_date_timestamp);
            $worksheets = $sq->execute();

            if (!empty($worksheets)) {
                $data['show_history_log'] = 1;
            }
        } else {
            if (!empty($beans)) {
                $fBean = $beans[0];
                $committed_date = $bean->db->fromConvert($fBean["date_modified"], "datetime");

                if (strtotime($committed_date) < strtotime($bean->fetched_row['date_modified'])) {
                    $db = DBManagerFactory::getInstance();
                    // find the differences via the audit table
                    // we use a direct query since SugarQuery can't do the audit tables...
                    $sql = sprintf(
                        "SELECT field_name, before_value_string, after_value_string FROM %s
                        WHERE parent_id = %s AND date_created >= " . $db->convert('%s', 'datetime'),
                        $bean->get_audit_table_name(),
                        $db->quoted($bean->id),
                        $db->quoted($committed_date)
                    );

                    $results = $db->query($sql);

                    // get the setting for which fields to compare on
                    /* @var $admin Administration */
                    $admin = BeanFactory::newBean('Administration');
                    $settings = $admin->getConfigForModule('Forecasts', 'base');

                    while ($row = $db->fetchByAssoc($results)) {
                        $field = substr($row['field_name'], 0, strpos($row['field_name'], '_'));
                        if ($settings['show_worksheet_' . $field] == "1") {
                            // calculate the difference to make sure it actually changed at 2 digits vs changed at 6
                            $diff = SugarMath::init($row['after_value_string'], 6)->sub(
                                $row['before_value_string']
                            )->result();
                            // due to decimal rounding on the front end, we only want to know about differences greater
                            // of two decimal places.
                            // todo-sfa: This hardcoded 0.01 value needs to be changed to a value determined by userprefs
                            if (abs($diff) >= 0.01) {
                                $data['show_history_log'] = 1;
                                break;
                            }
                        }
                    }
                }
            }
        }
        if (!empty($bean->user_id)) {
            $data['is_manager'] = User::isManager($bean->user_id);
        }

        return $data;
    }
}
