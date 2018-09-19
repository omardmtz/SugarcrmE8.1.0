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
// To access the global variable containing PMSE classes.
var PMSE = PMSE || {};
(function(app) {
    app.events.on('app:init', function() {

        /**
         * ProcessActions plugin is for process records that requires to use panels
         * which contains history, status or notes within list and record views.
         *
         */
        app.plugins.register('ProcessActions', ['view', 'layout'], {
            /**
             * Show history for process by cas_id
             *
             * @param {integer} cas_id related to process.
             */
            getHistory: function(cas_id) {
                var logPanel, w2, label;
                var pmseHistoryUrl = app.api.buildURL('pmse_Inbox/historyLog', null, {id: cas_id});

                logPanel = new HistoryPanel({
                    logType: 'difList',
                    items: [],
                    callback: {
                        'loaded': function(data) {
                            var i, newLog, duration;

                            app.api.call('read', pmseHistoryUrl, {}, {
                                success: function(logs) {
                                    if (logs) {
                                        for (i = 0; i < logs.result.length; i += 1) {
                                            var log = logs.result[i];

                                            var end_date = log.end_date;
                                            var delegate_date = log.delegate_date;

                                            label = log.data_info;
                                            if (log.completed) {
                                                duration = '<strong>( ';
                                                if (end_date) {
                                                    duration += app.date(end_date).fromNow();
                                                } else {
                                                    duration += app.date(delegate_date).fromNow();
                                                }
                                                duration += ' )</strong>';
                                            } else {
                                                duration = '<strong>';
                                                duration += app.lang.get('LBL_PMSE_HISTORY_LOG_NO_YET_STARTED', 'pmse_Inbox');
                                                duration += '</strong>';
                                            }

                                            var pictureUrl = app.api.buildFileURL({
                                                module: 'Users',
                                                id: log.cas_user_id,
                                                field: 'picture'
                                            });

                                            newLog = {
                                                name: 'log' + i,
                                                label: label,
                                                user: log.user,
                                                showUser: log.show_user,
                                                startDate: app.date(delegate_date).formatUser(),
                                                picture: (log.script) ? log.image : pictureUrl,
                                                duration: duration,
                                                completed: log.completed,
                                                script: (log.script) ? log.script : false
                                            };

                                            logPanel.addLog(newLog);
                                        }
                                    }
                                    app.alert.dismiss('upload');
                                    w2.html.style.display = 'inline';
                                },
                                error: function(error) {
                                    app.alert.dismiss('upload');
                                    var message = (error && error.message) ? error.message : 'EXCEPTION_FATAL_ERROR';
                                    app.alert.show('error_history', {
                                        level: 'error',
                                        messages: message
                                    });
                                }
                            });
                        }
                    }
                });

                w2 = new PMSE.Window({
                    width: 800,
                    height: 350,
                    modal: true,
                    title: '# ' + cas_id + ': ' + app.lang.get('LBL_PMSE_TITLE_HISTORY', 'pmse_Inbox')
                });

                w2.addPanel(logPanel);
                w2.show();
                w2.html.style.display = 'none';
                app.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoClose: false});
            },

            /**
             * Shows current status of a process by cas_id.
             *
             * @param {integer} cas_id related to process.
             */
            showStatus: function(cas_id) {
                var url, w, hp, img, ih, iw, a;
                var id = cas_id;
                url = app.api.buildFileURL({
                    module: 'pmse_Inbox',
                    id: id,
                    field: 'id'
                }, {cleanCache: true});
                app.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});

                img = new Image();
                img.src = url;
                img.onload = function() {
                    if (img.width < 760) {
                        ih = img.height;
                        iw = img.width;
                    } else {
                        ih = parseInt(img.height * (760 / img.width), 10);
                        iw = 760;
                    }
                    a = '<img width="' + iw + '" src="' + img.src + '" />';
                    hp = new HtmlPanel({
                        source: a,
                        scroll: ((ih + 45) > 400)
                    });

                    w = new PMSE.Window({
                        width: iw + 40,
                        height: ((ih + 45) < 400) ? ih + 45 : 400,
                        modal: true,
                        title: app.lang.get('LBL_PMSE_TITLE_IMAGE_GENERATOR_OBJ', 'pmse_Inbox', {'id': id})
                    });
                    w.addPanel(hp);
                    w.show();
                    app.alert.dismiss('upload');
                };
            },
            /**
             * Show notes panel for determined process by cas_id
             *
             * @param {integer} caseId related to process.
             * @param {integer} caseIndex related to bpm process.
             */
            showNotes: function(caseId, caseIndex) {
                var w, np, notesTextArea, proxy, log, newLog, pictureUrl, i, url;
                url = app.api.buildURL('pmse_Inbox/note_list/' + caseId);

                notesTextArea = new TextareaField({
                    name: 'notesTextArea',
                    label: '',
                    fieldWidth: '80%'
                });

                app.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
                np = new NotePanel({
                    items: [notesTextArea],
                    caseId: caseId,
                    caseIndex: caseIndex,
                    callback: {
                        'loaded': function(data) {
                            app.api.call('read', url, {}, {
                                success: function(notes) {
                                    for (i = 0; i < notes.rowList.length; i += 1) {
                                        log = notes.rowList[i];
                                        pictureUrl = app.api.buildFileURL({
                                            module: 'Users',
                                            id: log.not_user_id,
                                            field: 'picture'
                                        });

                                        var currentDate = Date.parse(notes.currentDate);
                                        var dateEntered = Date.parse(log.date_entered);
                                        if (isNaN(currentDate)) {
                                            currentDate = Date.parse(notes.currentDate.replace(/\s/g, 'T'));
                                        }
                                        if (isNaN(dateEntered)) {
                                            dateEntered = Date.parse(log.date_entered.replace(/\s/g, 'T'));
                                        }
                                        newLog = {
                                            name: 'log',
                                            label: log.not_content,
                                            user: log.last_name,
                                            picture: pictureUrl,
                                            duration: '<strong> ' + app.date(log.date_entered).fromNow() + ' </strong>',
                                            startDate: app.date(log.date_entered).formatUser(),
                                            logId: log.id
                                        };
                                        np.addLog(newLog);

                                    }
                                    app.alert.dismiss('upload');
                                },
                                error: function(error) {
                                    app.alert.dismiss('upload');
                                    var message = (error && error.message) ? error.message : 'EXCEPTION_FATAL_ERROR';
                                    app.alert.show('error_note', {
                                        level: 'error',
                                        messages: message
                                    });
                                }
                            });
                        }
                    }
                });
                w = new PMSE.Window({
                    width: 800,
                    height: 380,
                    modal: true,
                    title: app.lang.get('LBL_PMSE_TITLE_PROCESS_NOTES', 'pmse_Inbox') + ' # ' + caseId
                });

                w.addPanel(np);
                w.show();
            },

            /**
             * Show form to reassign user or change assigned user
             *
             * @param {id} casId
             * @param {id} casIndex
             * @param {string} wtype
             * @param {id} flowId
             * @param {id} pmseInboxId
             * @param {string} taskName
             * @param {Object} [values]
             */
            showForm: function (casId, casIndex, wtype, flowId, pmseInboxId, taskName, values) {
                showModalWindow(casId, casIndex, wtype, flowId, pmseInboxId, taskName, values, this.recordModel);
            }
        });
    });
})(SUGAR.App);
