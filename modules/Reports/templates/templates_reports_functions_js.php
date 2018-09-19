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

//////////////////////////////////////////////
// TEMPLATE:
//////////////////////////////////////////////
function template_reports_functions_js(&$args)
{
global $mod_strings;
?>
<script language="javascript">
var image_path = "<?php echo $args['IMAGE_PATH']; ?>";
var lbl_and = "<?php echo $mod_strings['LBL_AND']; ?>";
var lbl_select = "<?php echo $mod_strings['LBL_SELECT']; ?>";
var lbl_remove = "<?php echo $mod_strings['LBL_REMOVE']; ?>";
var lbl_missing_fields = "<?php echo $mod_strings['LBL_MISSING_FIELDS']; ?>";
var lbl_at_least_one_display_column = "<?php echo $mod_strings['LBL_AT_LEAST_ONE_DISPLAY_COLUMN']; ?>";
var lbl_at_least_one_summary_column = "<?php echo $mod_strings['LBL_AT_LEAST_ONE_SUMMARY_COLUMN']; ?>";
var lbl_missing_input_value  = "<?php echo $mod_strings['LBL_MISSING_INPUT_VALUE']; ?>";
var lbl_missing_second_input_value = "<?php echo $mod_strings['LBL_MISSING_SECOND_INPUT_VALUE']; ?>";
var lbl_nothing_was_selected = "<?php echo $mod_strings['LBL_NOTHING_WAS_SELECTED']; ?>"
var lbl_none = "<?php echo $mod_strings['LBL_NONE']; ?>";
var lbl_outer_join_checkbox = "<?php echo $mod_strings['LBL_OUTER_JOIN_CHECKBOX']; ?>";
var lbl_add_related = "<?php echo $mod_strings['LBL_ADD_RELATE']; ?>";
var lbl_del_this = "<?php echo $mod_strings['LBL_DEL_THIS']; ?>";
var lbl_alert_cant_add = "<?php echo $mod_strings['LBL_ALERT_CANT_ADD']; ?>";
var lbl_related_table_blank = "<?php echo $mod_strings['LBL_RELATED_TABLE_BLANK']; ?>";
var lbl_optional_help = "<?php echo $mod_strings['LBL_OPTIONAL_HELP']; ?>";
</script>
<?php echo getVersionedScript('include/javascript/report_additionals.js');
}
?>
