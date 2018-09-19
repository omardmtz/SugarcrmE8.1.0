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
<link type="text/css" href="{sugar_getjspath file='modules/ProcessMaker/css/jcore.libraries.css'}" rel="stylesheet" />
<link type="text/css" href="{sugar_getjspath file='modules/ProcessMaker/css/jcore.adam-ui.css'}" rel="stylesheet" />
<link type="text/css" href="{sugar_getjspath file='modules/ProcessMaker/css/jcore.adam.css'}" rel="stylesheet" />
<div style="background-color:#e8e8f0;padding:7px">
<a href="{$siteUrl}/index.php?action=engine&module=ProcessMaker" style="background-color:#f8f8f0;padding:2px">engine</a> &nbsp;
<a href="{$siteUrl}/index.php?action=projectlist&module=ProcessMaker">projects</a> &nbsp;
<a href="{$siteUrl}/index.php?action=scheduler&module=ProcessMaker">scheduler</a> &nbsp;
{*<a href="{$siteUrl}/index.php?action=projectlist&module=ProcessMaker">gearman</a> &nbsp;*}
</div>
</div>
<br>

<form action="index.php?module=ProcessMaker&action=projectsavestartevent" method="POST">
{sugar_csrf_form_token}
    <input type="hidden" name="prj_id" id="prj_id" value="{$prj_id}"/>
    <input type="hidden" name="pro_id" id="pro_id" value="{$pro_id}"/>

<table cellpadding='0' cellspacing='0' width='450px' border='0' class='list view'>
    <tr class="pagination"  role=”presentation”>
        <td align='right' colspan="2">
            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                <tr >
                    <td align='left' colspan="2">
                        <h2>#{$caseData.cas_id}. {$caseData.cas_title}</h2>
                    </td>
                    <td align='right'><a href="javascript:ShowLog();">Show Log</a></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr >
        <td scope='row' align='right' valign="top" >
            Flow Status
        </td>
        <td scope='row' align='left' valign="top" >
            {$caseData.cas_flow_status}
        </td>
    </tr>
    <tr >
        <td scope='row' align='right' valign="top" >
            Module
        </td>
        <td scope='row' align='left' valign="top" >
            {$caseData.cas_sugar_module}
        </td>
    </tr>
    <tr >
        <td scope='row' align='right' valign="top" >
            Record
        </td>
        <td scope='row' align='left' valign="top" >
            {$caseData.cas_sugar_object_id}
        </td>
    </tr>
    <tr >
        <td scope='row' align='right' valign="top" >
            Action
        </td>
        <td scope='row' align='left' valign="top" >
            {$caseData.cas_sugar_action}
        </td>
    </tr>
    <tr >
        <td scope='row' align='right' valign="top" >
            Case flow index
        </td>
        <td scope='row' align='left' valign="top" >
            {$caseData.cas_index}
        </td>
    </tr>

</table>
</form>
<script type="text/javascript">
    var SBPM_CASE_ID = '{$caseData.cas_id}';
</script>
<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/pmse.libraries.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/pmse.jcore.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/pmse.ui.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/pmse_Inbox/js/get_process_image.js'}"></script>