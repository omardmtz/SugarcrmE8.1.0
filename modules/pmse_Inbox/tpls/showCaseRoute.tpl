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
<link type="text/css" href="{sugar_getjspath file='modules/pmse_Project/css/jcore.libraries.css'}" rel="stylesheet" />
<link type="text/css" href="{sugar_getjspath file='modules/pmse_Project/css/jcore.adam-ui.css'}" rel="stylesheet" />
<link type="text/css" href="{sugar_getjspath file='modules/pmse_Project/css/jcore.adam.css'}" rel="stylesheet" />
<link type="text/css" href="{sugar_getjspath file='modules/pmse_Project/css/jcore.adam-ui.extend.css'}" rel="stylesheet" />
<link type="text/css" href="{sugar_getjspath file='modules/pmse_Project/css/pmse.css'}" rel="stylesheet" />
<!---------------  START WORKFLOW SHOWCASE ------------>
<form action="index.php?module=pmse_Inbox&action=routeCase" id="EditView" name="EditView" method="POST">
{sugar_csrf_form_token}
    <br><h2 id="showCaseTitle">{$caseData.name} &#124; {if isset($act_name)} {$act_name} {/if}
    <span {if $expected_time_warning}style="color:red;"{/if}>
        {if isset($expected_time)  && ($expected_time != "") && (isset($expected_time_message) )}
            [ {$expected_time_message} {$expected_time} ]
        {/if}
    </span>
    </h2><br>
    <input type="hidden" name="cas_id" id="cas_id" value="{$cas_id}"/>
    <input type="hidden" name="team_id" id="team_id" value="{$team_id}"/>
    <input type="hidden" name="moduleName" id="moduleName" value="{$caseData.cas_sugar_module}"/>
    <input type="hidden" name="beanId" id="beanId" value="{$caseData.cas_sugar_object_id}"/>
    <input type="hidden" name="idInbox" id="idInbox" value="{$idInbox}"/>
    <input type="hidden" name="cas_index" id="cas_index" value="{$cas_index}"/>
    <input type="hidden" name="cas_current_user_id" id="cas_current_user_id" value="{$cas_current_user_id}"/>
    <input type="hidden" name="act_adhoc_behavior" id="act_adhoc_behavior" value="{$act_adhoc_behavior}"/>
    <input type="hidden" name="act_adhoc_assignment" id="act_adhoc_assignment" value="{$act_adhoc_assignment}"/>
    <input type="hidden" name="taskContinue" id="taskContinue" value="{$task_continue}"/>
    <table width='100%'><tr>
        <td>
            {foreach from=$customButtons key='key' item='item'}
                {if $item.value=='Claim'}
                    <a href="{$item.onclick}" title="{$item.value}"><span class="btn">{$item.value}</span></a>
                {else}
                    <input id="{$item.id}" name="{$item.name}" type="{$item.type}" value={$item.value} onclick="{$item.onclick}">
                {/if}
            {/foreach}
        </td>
        <td align="right">
            {if $reclaimCaseByUser}
                <h2><span style="color:red;">[{sugar_translate label='LBL_PMSE_LABEL_UNASSIGNED'}]</span></h2>
            {/if}
            {if $noShowCase}
                {if $finished}
                    <h2><span style="color:red;">[{sugar_translate label='LBL_PMSE_LABEL_CASECOMPLETED'}]</span></h2>
                {else}
                    <h2><span style="color:red;">[{sugar_translate label='LBL_PMSE_LABEL_CASETAKEN'}]</span></h2>
                {/if}
            {/if}
            {if $expected_time_warning}
                <a href="javascript:void(0);" title="{$expected_time_message}"><span class="adam-icon-warning"></span></a>
            {/if}
            {if $act_reassign}
                {if !$reclaimCaseByUser}
                    <a href="javascript:adhocFormBWC({$caseData.cas_id}, {$caseData.cas_index}, '{$flowId|escape:'html':'UTF-8'}', '{$caseData.idInbox}', '{$nameTask}','{$caseData.cas_sugar_module}','{$caseData.cas_sugar_object_id}');" title="{sugar_translate label='LBL_PMSE_LABEL_CHANGE_OWNER'}"><span class="adam-icon-record_owner"></span></a>
                {/if}
            {/if}
            {if $act_adhoc}
                {if !$reclaimCaseByUser}
                    {*<li><a href="javascript:reassignForm({$caseData.cas_id}, {$caseData.cas_index});" title="{sugar_translate label='LBL_PMSE_LABEL_REASSIGN'}"><span class="adam-icon-reassing"></span></a></li>*}
                    <a href="javascript:reassignFormBWC({$caseData.cas_id}, {$caseData.cas_index}, '{$flowId|escape:'html':'UTF-8'}', '{$caseData.idInbox}', '{$nameTask}','{$caseData.cas_sugar_module}','{$caseData.cas_sugar_object_id}');" title="{sugar_translate label='LBL_PMSE_LABEL_REASSIGN'}"><span class="adam-icon-reassing"></span></a>
                {/if}
            {/if}
            <a href="javascript:showHistory({$caseData.cas_id});" title="{sugar_translate label='LBL_PMSE_LABEL_HISTORY'}"><span class="adam-icon-history"></span></a>
            <a href="javascript:showImage({$caseData.cas_id});" title="{sugar_translate label='LBL_PMSE_LABEL_STATUS'}"><span class="adam-icon-status"></span></a>
            {if $act_note}
                <a href="javascript:showNotes({$caseData.cas_id}, {$caseData.cas_index});" title="{sugar_translate label='LBL_PMSE_LABEL_NOTES'}"><span class="adam-icon-notes" style="position: relative;"><div id="countNotes" {if $totalNotes == 0} style="display:none;" {/if} >{$totalNotes}</div></span></a>
            {/if}
        </td>
    </tr></table>
<!---------------  END WORKFLOW SHOWCASE ------------>
<script type="text/javascript">
    var SBPM_CASE_ID = '{$caseData.cas_id}';
    var SBPM_CASE_INDEX = '{$caseData.cas_index}';
    var SBPM_HISTORICAL = "<a href='index.php?module=ProcessMaker&action=showPNG&case={$caseData.cas_id}' target='_blank'><img width='760' src='index.php?module=ProcessMaker&action=showPNG&case={$caseData.cas_id}' /></a>";
    var SUGAR_URL = '{$SUGAR_URL}';
    var SUGAR_AJAX_URL = '{$SUGAR_AJAX_URL}';
    var SUGAR_REST = {$SUGAR_REST};
    {if $validations}
        var PMVAL = {$validations};
    {/if}
    {if $reclaimCaseByUser}
        var RECLAIMCASE = true;
    {else}
        var RECLAIMCASE = false;
    {/if}

</script>
<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/pmse.utils.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/pmse.libraries.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/pmse.jcore.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/pmse.ui.min.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/pmse_Inbox/js/historyEntries.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/pmse_Inbox/js/formAction.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/pmse_Inbox/js/get_process_image.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/pmse_Inbox/js/notes.js'}"></script>
{if ($caseData.cas_sugar_module == "Documents")}
    <script type="text/javascript" src="{sugar_getjspath file="modules/Documents/documents.js"}"></script>
    <script type="text/javascript" src="{sugar_getjspath file="include/javascript/popup_parent_helper.js"}"></script>
{/if}
