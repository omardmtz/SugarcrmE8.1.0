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
// Get the global PMSE classes variable.
var PMSE = PMSE || {};
/**
 * That method builds the show History window
 * if ago options is enabled
 * @param {Number} caseId
 * @return {*}
 */
function showHistory(caseId, caseIndex) {
    var restClient, proxy, logPanel, w2, label, _App;
    if (App) {
        _App = App;
    } else {
        _App = parent.SUGAR.App;
    }

    var pmseHistoryUrl = _App.api.buildURL('pmse_Inbox/historyLog', null, {id:caseId});

    logPanel = new HistoryPanel({
        logType: 'difList',
        items: [ ],
        callback :{
            'loaded': function (data) {
                var i, newLog;

                _App.api.call('read', pmseHistoryUrl, {}, {
                    success: function (logs) {
                        if (logs) {
                            for (i = 0; i < logs.result.length; i += 1) {
                                var log = logs.result[i];

                                var end_date=log.end_date;
                                var delegate_date=log.delegate_date;

                                label = log.data_info;
                                if (log.completed) {
                                    if (end_date) {
                                        duration = '<strong>( ' + _App.date(end_date).fromNow() + ' )</strong>';
                                    } else {
                                        duration = '<strong>( ' + _App.date(delegate_date).fromNow() + ' )</strong>';
                                    }
                                } else {
                                    duration = '<strong>' + translate('LBL_PMSE_HISTORY_LOG_NO_YET_STARTED', 'pmse_Inbox') + '</strong>';
                                }

                                var pictureUrl = _App.api.buildFileURL({
                                    module: 'Users',
                                    id: log.cas_user_id,
                                    field: 'picture'
                                });

                                newLog = {
                                    name: 'log' + i,
                                    label: label,
                                    user: log.user,
                                    startDate: _App.date(delegate_date).formatUser(),
                                    picture : (log.script) ? log.image : pictureUrl,
                                    duration: duration,
                                    completed: log.completed,
                                    script: (log.script) ? log.script : false
                                };

                                logPanel.addLog(newLog);
                            }
                        }
                        _App.alert.dismiss('upload');
                        w2.html.style.display = 'inline';
                    }
                });


            }
        }
    });

    w2 = new PMSE.Window({
        width: 800,
        height: 350,
        modal: true,
        title: '# ' + caseId + ': ' + translate('LBL_PMSE_TITLE_HISTORY', 'pmse_Inbox')
    });

    w2.addPanel(logPanel);
    w2.show();
    w2.html.style.display = 'none';
    _App.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
}
