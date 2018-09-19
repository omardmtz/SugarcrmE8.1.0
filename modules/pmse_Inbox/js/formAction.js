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
var w, hp;
// This var should be used locally to point to global App variable in sidecar and BWC views
var _App;
if(App){
    _App = App;
}
else{
    _App = parent.SUGAR.App;
    // This should define App variable when some function is used in BWC and reference it.
    App = parent.SUGAR.App;
}

// Get the global PMSE classes variable.
var PMSE = PMSE || {};

var confirmAdhocReassign = function()
{
    $.ajax({
        url: './?module=ProcessMaker&action=routeCase&to_pdf=1&current_assigned_user_id=' + $("#cas_current_user_id").val() + '&reassigned_to_user_id=' + $("#cas_user_id").val() + "&Type=Adhoc",
        async: false,
        method: 'POST',
        data: $('#showCaseForm').serialize()
    }).done(function(ajaxResponse) {
        window.location.href = "./index.php"
    });
};

var confirmReassign = function()
{
    $.ajax({
        url: './?module=ProcessMaker&action=reassignRecord&to_pdf=1&current_assigned_user_id=' + $("#cas_current_user_id").val() + '&reassigned_to_user_id=' + $("#cas_user_id").val() + '&Type=Reassign',
        async: false,
        method: 'POST',
        data: $('#showCaseForm').serialize()
    }).done(function(ajaxResponse) {
        w.close();
        //window.location.href = "./index.php"
    });
};
var reassignFormBWC = function(casId, casIndex, flowId, pmseInboxId, taskName, valuesA, valuesB, fullName)
{
    var value=new Object();
    value.moduleName = valuesA;
    value.beanId = valuesB;
    value.full_name = fullName;
    showForm(casId, casIndex, flowId, pmseInboxId, taskName, value, 'reassign');
};
/**
 * Open the reassignation form for the current case.
 *
 * @deprecated This will be removed on future versions.
 * Use showForm() and send it the same parameters in the same order plus the constant 'reassign' string as last parameter.
 */
var reassignForm = function(casId, casIndex, flowId, pmseInboxId, taskName, values) {
    _App.logger.warn('reassignForm() is deprecated, it will be removed in a future release. ' +
    'Use showForm() using the same parameters in the same order plus constant "reassign" string as last parameter instead.');
    showForm(casId, casIndex, flowId, pmseInboxId, taskName, values, 'reassign');
};

var showForm = function(casId, casIndex, flowId, pmseInboxId, taskName, values, type, model)
{
    var formView = _App.controller.layout.getComponent("bwc"),
        openModal = function () {
            showModalWindow(casId, casIndex, type, flowId, pmseInboxId, taskName, values, model);
        };

    if (!_.isUndefined(formView) && formView.hasUnsavedChanges()) {
        _App.alert.show('reassign_confirmation', {
            level: 'confirmation',
                messages: translate('LBL_PMSE_ALERT_REASSIGN_UNSAVED_FORM', 'pmse_Inbox'),
                onConfirm: function () {
                    formView.$('iframe').get(0).contentWindow.EditView.reset();
                    formView.revertBwcModel();
                    openModal();
                },
            onCancel: $.noop
        });
    } else {
        openModal();
    }
};
var adhocFormBWC = function(casId, casIndex, flowId, pmseInboxId, taskName, valuesA,valuesB){
    var value=new Object();
    value.moduleName = valuesA;
    value.beanId = valuesB;
    showForm(casId, casIndex, flowId, pmseInboxId, taskName, value, 'adhoc');
};

/**
 * Open the adhoc form for the current case.
 *
 * @deprecated This will be removed on future versions.
 * Use showForm() and send it the same parameters in the same order plus the constant 'adhoc' string as last parameter.
 */
var adhocForm = function(casId, casIndex, flowId, pmseInboxId, taskName, values) {
    _App.logger.warn('adhocForm() is deprecated, it will be removed in a future release. ' +
    'Use showForm() using the same parameters in the same order plus the constant "adhoc" string as last parameter instead.');
    showForm(casId, casIndex, flowId, pmseInboxId, taskName, values, 'adhoc');
};

var claim_case = function(cas_id, cas_index, full_name, idInbox){
    var value = {};
    value.cas_id = cas_id;
    value.cas_index = cas_index;
    value.full_name = full_name;
    value.beanId = idInbox;
    var pmseInboxUrl = _App.api.buildURL('pmse_Inbox/engine_claim','',{},{});
    _App.api.call('update', pmseInboxUrl, value,{
        success: function (){
            if(!_App.router.refresh()){
                window.location.reload();
            }
        }
    });
};

var getUserSearchURL = function (url, flowId) {
    return url+'/users/'+ flowId + '?filter={%TERM%}&max_num={%PAGESIZE%}&offset={%OFFSET%}';
};

var showModalWindow = function (casId, casIndex, wtype, flowId, pmseInboxId,taskName,values, model) {
    var f,
        w,
        combo_users,
        items,
        proxy,
        textArea,
        url,
        wtitle,
        wWidth,
        wHeight,
        casIdField,
        casIndexField,
        combo_type,
        casFlowId,
        casInboxId,
        task_Name,
        user_Name,
        module_Name,
        bean_Id,
        full_Name,
        valAux,
        reassignForm;

    module_Name = new HiddenField({
        name: 'moduleName',
        value: values.moduleName
    });
    bean_Id = new HiddenField({
        name: 'beanId',
        value: values.beanId
    });
    if(values.name)
    {
        valAux=values.name;
    }else{
        valAux=values.full_name;
    }
    full_Name = new HiddenField({
        name: 'full_name',
        value: valAux
    });
    task_Name = new HiddenField({
        name: 'taskName',
        value: taskName
    });

    casIdField = new HiddenField({
        name: 'cas_id',
        value: casId
    });

    casIndexField = new HiddenField({
        name: 'cas_index',
        value: casIndex
    });
    casFlowId = new HiddenField({
        name: 'idFlow',
        value: flowId
    });

    casInboxId = new HiddenField({
        name: 'idInbox',
        value: pmseInboxId
    });
    combo_type = new ComboboxField({
        name: 'adhoc_type',
        label: translate('LBL_PMSE_FORM_LABEL_TYPE', 'pmse_Inbox'),
        options: [
            {text: 'Round Trip', value: 'ROUND_TRIP'},
            {text: 'One Way', value: 'ONE_WAY'}
        ],
        initialValue: 'ROUND_TRIP',
        required: true
    });

    textArea = new TextareaField({
        name: 'adhoc_comment',
        label: translate('LBL_PMSE_FORM_LABEL_NOTE', 'pmse_Inbox'),
        fieldWidth: '300px',
        fieldHeight: '100px'
    });
    user_Name = new HiddenField({
        name: 'user_name',
        value: ''
    });

    reassignForm = new HiddenField({
        name: 'reassign_form',
        value: true
    });

    if (wtype === 'reassign') {
        url = 'pmse_Inbox/AdhocReassign';
        wtitle = translate('LBL_PMSE_TITLE_AD_HOC', 'pmse_Inbox');
        wWidth = 550;
        wHeight = 300;

        combo_users = new SearchableCombobox({
            label: translate('LBL_PMSE_FORM_LABEL_USER', 'pmse_Inbox'),
            name: 'adhoc_user',
            submit: true,
            required: true,
            searchMore: {
                module: "Users",
                fields: ["id"]
            },
            searchURL: getUserSearchURL(url, flowId),
            searchValue: 'id',
            searchLabel: 'full_name',
            placeholder: translate('LBL_PA_FORM_COMBO_ASSIGN_TO_USER_HELP_TEXT', 'pmse_Project'),
            helpTooltip: {
                message: translate('LBL_PMSE_FORM_TOOLTIP_SELECT_USER', 'pmse_Inbox')
            }
        });

        items = [
            casIdField,
            casIndexField,
            casFlowId,
            casInboxId,
            combo_users,
            combo_type,
            textArea,
            task_Name,
            user_Name,
            module_Name,
            bean_Id,
            full_Name,
            reassignForm
        ];
        combo_users.setName('adhoc_user');
        textArea.setName('not_content');
    } else {
        // If wtype is set to user selection, change the tooltip msg
        url = 'pmse_Inbox/ReassignForm';
        wtitle = translate('LBL_PMSE_TITLE_REASSIGN', 'pmse_Inbox');
        wWidth = 500;
        wHeight = 250;

        combo_users = new SearchableCombobox({
            label: translate('LBL_PMSE_FORM_LABEL_USER', 'pmse_Inbox'),
            name: 'adhoc_user',
            submit: true,
            required: true,
            searchMore: {
                module: "Users",
                fields: ["id"]
            },
            searchURL: getUserSearchURL(url, flowId),
            searchValue: 'id',
            searchLabel: 'full_name',
            placeholder: translate('LBL_PA_FORM_COMBO_ASSIGN_TO_USER_HELP_TEXT', 'pmse_Project'),
            helpTooltip: {
                message: translate('LBL_PMSE_FORM_TOOLTIP_CHANGE_USER', 'pmse_Inbox')
            }
        });

        items = [
            casIdField,
            casIndexField,
            casFlowId,
            casInboxId,
            combo_users,
            textArea,
            task_Name,
            user_Name,
            module_Name,
            bean_Id,
            full_Name
        ];
        combo_users.setName('reassign_user');
        textArea.setName('reassign_comment');
    }
    flowId = (flowId) ? flowId : urlCase.id;
    proxy = new SugarProxy({
        url: url,
        uid : '',
        callback: null
    });
    f = new PMSE.Form({
        items: items,
        closeContainerOnSubmit: true,
        buttons: [
            {
                jtype: 'normal',
                caption: translate('LBL_PMSE_BUTTON_SAVE', 'pmse_Inbox'),
                cssClasses: ['btn', 'btn-primary'],
                handler: function () {
                    if (f.validate()) {
                        _App.alert.show('upload', {level: 'process', title: 'LBL_SAVING', autoClose: false});
                        var cbDate = combo_users.getSelectedText();
                        var auid = combo_users.value;
                        if (combo_users.name == 'reassign_user') {
                            items[6].setValue(cbDate);
                        } else {
                            items[7].setValue(cbDate);
                        }
                        var urlIni = _App.api.buildURL(url, null, null);
                        attributes = {
                            data: f.getData()
                        };
                        $(w.html).remove();
                        _App.api.call('update', urlIni, attributes, {
                            success: function (response) {
                                _App.alert.dismiss('upload');
                                _App.alert.show('pmse_reassign_success', {
                                    autoClose: true,
                                    level: 'success',
                                    messages: translate('LBL_PMSE_ALERT_REASSIGN_SUCCESS', 'pmse_Inbox')
                                });
                                w.close();
                                if (wtype == 'reassign') {
                                    _App.router.redirect('Home');
                                } else {
                                    if ($('#assigned_user_name').length) {
                                        $("#assigned_user_name").val(cbDate);
                                        $('#assigned_user_id').val(auid);
                                        var formView = _App.controller.layout.getComponent('bwc');
                                        if (!_.isUndefined(formView)) {
                                            formView.revertBwcModel();
                                        }
                                    } else {
                                        if (model) {
                                            model.fetch();
                                        }
                                    }
                                }
                            },
                            error: function (error) {
                                _App.alert.dismiss('upload');
                                var message = (error && error.message) ? error.message : 'EXCEPTION_FATAL_ERROR';
                                _App.alert.show('pmse_reassign_error', {
                                    level: 'error',
                                    messages: message
                                });
                            }
                        });
                    }
                }
            },
            {
                jtype: 'normal',
                caption: translate('LBL_PMSE_BUTTON_CANCEL', 'pmse_Inbox'),
                cssClasses: ['btn btn-invisible btn-link'],
                handler: function () {
                    w.close();
                }
            }
        ],
        labelWidth: 300,
        callback : {
            'loaded': function (data) {
                casIdField.setValue(casId);
                casIndexField.setValue(casIndex);
                f.setProxy(proxy);
            }
        }
    });
    w = new PMSE.Window({
        width: wWidth,
        height: wHeight,
        modal: true,
        title: wtitle
    });
    w.addPanel(f);
    w.show();
};


function onSubmit(e) {
    var result2 = true,
        i,
        ele,
        msg = '<div>',
        mp = new MessagePanel({
            title: 'Warning',
            wtype: 'Warning'
        }),
        restClient;
    if (RECLAIMCASE){
        //TODO RECLAIM CASE
        restClient = new RestClient ();
        restClient.setRestfulBehavior(SUGAR_REST);
        if (!SUGAR_REST) {
            restClient.setBackupAjaxUrl(SUGAR_AJAX_URL);
        }
        restClient.getCall({
            url: SUGAR_URL + '/rest/v10/CrmData/validateReclaimCase',
            id: '',
            data: {cas_id: SBPM_CASE_ID, cas_index: SBPM_CASE_INDEX},
            success: function (xhr, response) {
                result = response.result;
                if (!result) {
                    mp.setTitle('Error');
                    mp.setMessageType('Error');
                    mp.setButtons([
                        {
                            jtype: 'normal',
                            caption: translate('LBL_PMSE_BUTTON_OK'),
                            handler: function () {
                                location.href = SUGAR_URL;
                            }
                        }
                    ]);
                    if (response.message) {
                        mp.setMessage(response.message);
                    } else {
                        mp.setMessage(translate('LBL_PMSE_LABEL_PLEASELOGINAGAIN'));
                    }
                    mp.show();
                    result2 = false;

                }
            },
            failure: function (xhr, response) {
                mp.setTitle('Error');
                mp.setMessageType('Error');
                mp.setMessage(translate('LBL_PMSE_LABEL_ERROR_GENERIC'));
                mp.show();
                result2 = false;
            }
        });

    } else {
        if (PMVAL) {
            for (i = 0; i < PMVAL.length; i += 1) {
                ele = document.getElementById(PMVAL[i]);
                if (ele && ele.value) {
                    if (ele.value.trim && ele.value.trim() == '') {
                        $(ele).addClass('required');
                        msg += PMVAL[i] + '<br>';
                        result2 = false;
                    }
                }
            }
        }
        if (!result2) {
            mp.setMessage('The following fields are required and must be properly filled:' + msg);
            mp.show();
        }
    }

    return result2;
};

function confirmAction(obj){
    sbtn = obj.id;
    switch (sbtn) {
        case 'ApproveBtn':
            msg = app.lang.get('LBL_PA_PROCESS_APPROVE_QUESTION', 'pmse_Inbox');
            break;
        case 'RejectBtn':
            msg = app.lang.get('LBL_PA_PROCESS_REJECT_QUESTION', 'pmse_Inbox');
            break;
        default:
            msg = app.lang.get('LBL_PA_PROCESS_ROUTE_QUESTION', 'pmse_Inbox');
    }
    app.alert.show('confirm', {
        level: 'confirmation',
        messages: msg,
        autoClose: false,
        onConfirm: function(){
            btn = $('#EditView :input[id="' + sbtn + '"]');
            btn.prop('type', 'submit');
            btn.show();
            app.btSubmitClicked = true;
            btn.click();
            app.btSubmitClicked = false;
        },
        onCancel: function(){
            app.btSubmitClicked = false;
        }
    });
};

$(function () {
    $('#showCaseForm').attr("novalidate", "novalidate").on('submit', onSubmit);

});
