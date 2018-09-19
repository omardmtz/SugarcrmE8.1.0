{*
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
*}
<input type="hidden" name="wl_datetime" value="true" />
<input type="hidden" name="field_name" value="{$vardef.name}" />
{html_select_date prefix="wl_date_" time=$date_start month_format="%m" end_year="+5" field_order=$field_order}<br />
{html_select_time prefix="wl_time_" time=$time_start use_24_hours=$use_meridian display_seconds=false minute_interval="15"}