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
 
{$PAGE_TITLE}
<head>
<title>{$MOD.LBL_SCHEDULE_EMAIL}</title>

{$STYLESHEET}

<script type="text/javascript" src='{sugar_getjspath file="$CACHE_DIR/include/javascript/sugar_grp1_jquery.js"}'></script>
<script type="text/javascript" src='{sugar_getjspath file="include/javascript/sugar_3.js"}'></script>
<script type="text/javascript" src='{sugar_getjspath file="$CACHE_DIR/include/javascript/sugar_grp1_yui.js"}'></script>
<script type="text/javascript" src='{sugar_getjspath file="$CACHE_DIR/include/javascript/sugar_grp1.js"}'></script>
<script type="text/javascript" src='{sugar_getjspath file="include/SugarFields/Fields/Datetimecombo/Datetimecombo.js"}'></script>
<script type="text/javascript" src="{$CACHE_DIR}/jsLanguage/{$CURRENT_LANGUAGE}.js?s={$JS_VERSION}&c={$JS_CUSTON_VERSION}&j={$JS_LANGUAGE_VERSION}"></script>
</head>
<body class='tabForm'>
<form action='index.php' name='add_schedule' method='POST'>
{sugar_csrf_form_token}
<table  width='100%'  id='schedule_table' border='0'>
<tr>
    <td scope="row" id="date_start_label" ><slot>{$MOD.LBL_START_DATE}: </slot></td>
    <td ><slot>
        <table  cellpadding="0" cellspacing="0">
            <tr>
                <td nowrap><input name='schedule_date_start' id='date_start_date' onchange="parseDate(this, '{$CALENDAR_DATEFORMAT}');combo_date_start.update();" tabindex='1' size='11' maxlength='10' type="text" disabled="">
                            <img src="index.php?entryPoint=getImage&themeName={$THEME}&imageName=jscalendar.gif" alt="{$CALENDAR_DATEFORMAT}"  id="jscal_trigger" align="absmiddle" >&nbsp;
                            <input type="hidden" id="date_start" name="date_start" value="{$DATE_START}">
                            <span id="schedule_time_section"></span>
                </td>
           </tr>
           <tr>
                <td nowrap><span class="dateFormat">{$USER_DATEFORMAT}</span>
                </td>
          </tr>
        </table></slot>
    </td>
    <td scope="row" ><slot>{$MOD.LBL_SCHEDULE_ACTIVE}: </td>
    <td ><slot><input type='checkbox' class="checkbox" name='schedule_active' id='schedule_active' {$SCHEDULE_ACTIVE_CHECKED}></slot></td>
</tr>
<tr>
    <td scope="row"><slot>{$MOD.LBL_TIME_INTERVAL}: </slot></td>
    <td ><slot><select name='schedule_time_interval' id='schedule_time_interval'>{$TIME_INTERVAL_SELECT}</select></slot></td>
    <td scope="row"><slot>{$MOD.LBL_NEXT_RUN}:</slot></td>
    <td ><slot>{$NEXT_RUN}</slot></td>
</tr>
<tr>
<td scope="row">&nbsp; </td>
<td >&nbsp;</td>
<td scope="row">&nbsp;</td>
<td ><input class="button" type='submit' name='update_schedule' value='{$MOD.LBL_UPDATE_SCHEDULE}' onclick="return check_form('add_schedule');"></td>
</tr>
<tr><td height='100%'></td></tr>
</table>
<input type='hidden' name='schedule_id' value='{$SCHEDULE_ID}'>
<input type='hidden' name='save_schedule_msi' value='true'>
<input type='hidden' name='schedule_type' value='{$SCHEDULE_TYPE|escape:'html':'UTF-8'}'>
<input type='hidden' name='refreshPage' value='{$REFRESH_PAGE|escape:'html':'UTF-8'}'>
<input type='hidden' name='module' value='Reports'>
<input type='hidden' name='action' value='add_schedule'>
<input type='hidden' name='to_pdf' value='true'>
<input type='hidden' name='id' value='{$RECORD|escape:'html':'UTF-8'}'>


</form>

<script type="text/javascript">

var combo_date_start = new Datetimecombo("{$DATE_START}", "date_start", "{$TIME_FORMAT}", "", '', '', true);
text = combo_date_start.html(false);
document.getElementById('schedule_time_section').innerHTML = text;
eval(combo_date_start.jsscript(''));

function update_date_start_available() {ldelim}
      YAHOO.util.Event.onAvailable("date_start_date", this.handleOnAvailable, this); 
{rdelim}

update_date_start_available.prototype.handleOnAvailable = function(me) 
{ldelim}
	Calendar.setup ({ldelim}
	onClose : update_date_start,
	inputField : "date_start_date",
	ifFormat : "{$CALENDAR_DATEFORMAT}",
	daFormat : "{$CALENDAR_DATEFORMAT}",
	button : "jscal_trigger",
	singleClick : true,
	step : 1,
	weekNumbers:false
	{rdelim});
	
	//Call update for first time to round hours and minute values
	combo_date_start.update();
{rdelim}

var obj_date_start = new update_date_start_available();

addToValidate('add_schedule',"date_start_date",'date',false,"Start Date");
addToValidateBinaryDependency('add_schedule',"date_start_hours", 'alpha', false, "{$APP.ERR_MISSING_REQUIRED_FIELDS} {$APP.LBL_HOURS}" ,"date_start_date");
addToValidateBinaryDependency('add_schedule', "date_start_minutes", 'alpha', false, "{$APP.ERR_MISSING_REQUIRED_FIELDS} {$APP.LBL_MINUTES}" ,"date_start_date");
addToValidateBinaryDependency('add_schedule', "date_start_meridiem", 'alpha', false, "{$APP.ERR_MISSING_REQUIRED_FIELDS} {$APP.LBL_MERIDIEM}","date_start_date");
</script>
{$TIMEDATE_JS}
</body>