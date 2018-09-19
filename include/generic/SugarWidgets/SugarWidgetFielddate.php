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
class SugarWidgetFieldDate extends SugarWidgetFieldDateTime
{
    function displayList($layout_def)
    {
        // i guess qualifier and column_function are the same..
        if (! empty($layout_def['column_function'])) {
            $func_name = 'displayList'.$layout_def['column_function'];
            if ( method_exists($this,$func_name)) {
                $display = $this->$func_name($layout_def);
                return $display;
            }
        }
        $content = $this->displayListPlain($layout_def);
		return $content;
    }

    function queryFilterBefore($layout_def)
    {
        return $this->queryDateOp($this->_get_column_select($layout_def), $layout_def['input_name0'], "<", "date");
    }

    function queryFilterAfter($layout_def)
    {
        return $this->queryDateOp($this->_get_column_select($layout_def), $layout_def['input_name0'], ">", "date");
    }

    function queryFilterNot_Equals_str($layout_def)
    {
        $column = $this->_get_column_select($layout_def);

        return "($column IS NULL OR " . $this->queryDateOp($column, $layout_def['input_name0'], '!=', "date") . ")\n";
    }

    function queryFilterOn($layout_def)
    {
        return $this->queryDateOp($this->_get_column_select($layout_def), $layout_def['input_name0'], "=", "date");
    }

    protected function queryDay($layout_def, SugarDateTime $day)
    {
        $layout_def['input_name0'] = $day->asDbDate(false);

        return $this->queryFilterOn($layout_def);
    }

    protected function queryMonth($layout_def, $month)
    {
        $end = clone($month);
        $end->setDate($month->year, $month->month, $month->days_in_month);

        return $this->get_start_end_date_filter($layout_def, $month, $end);
    }

    protected function now()
    {
        global $timedate;
        return $timedate->getNow(true);
    }

    /**
     * Formats a DateTime object as string for given widget
     *
     * @param SugarDateTime $date - Date to be formatted for widget
     * @return string date formatted for widget type
     */
    protected function formatDate($date)
    {
        return $date->asDbDate(false);
    }
}
