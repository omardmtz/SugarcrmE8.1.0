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



class SugarWidgetFieldDatePicker extends SugarWidgetFieldDateTime
{
	function displayInput($layout_def)
    {
        global $timedate;

        $cal_dateformat = $timedate->get_cal_date_format();
        $LBL_ENTER_DATE = translate('LBL_ENTER_DATE', 'Charts');
        $jscalendarImage = SugarThemeRegistry::current()->getImageURL('jscalendar.gif');
        $value = $timedate->swap_formats($layout_def['input_name0'], $timedate->dbDayFormat, $timedate->get_date_format());
        $str = <<<EOHTML
<input onblur="parseDate(this, '{$cal_dateformat}');" class="text" name="{$layout_def['name']}" size='12' maxlength='10' id='{$layout_def['name']}' value='{$value}'>
<!--not_in_theme!--><img src="$jscalendarImage" alt="{$LBL_ENTER_DATE}" id="{$layout_def['name']}_trigger" align="absmiddle">
<script type="text/javascript">
Calendar.setup ({
    inputField : "{$layout_def['name']}", ifFormat : "{$cal_dateformat}", showsTime : false, button : "{$layout_def['name']}_trigger", singleClick : true, step : 1, weekNumbers:false
});
</script>
EOHTML;

        return $str;
    }
}

