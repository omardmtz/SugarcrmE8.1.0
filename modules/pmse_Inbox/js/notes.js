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
var showNotes,
    addNotes,
    deleteNotes,
    addRow,
    deleteRow;

showNotes = function (caseId, caseIndex, noEdit) {
    var f, w, np, notesTextArea, proxy, log, newLog, pictureUrl, i, _App;
    if(App){ _App = App; } else { _App = parent.SUGAR.App; }

    url = _App.api.buildURL('pmse_Inbox/note_list/' + caseId);

    notesTextArea = new TextareaField({
        name: 'notesTextArea',
        label: '',
        fieldWidth: '80%'
    });

    _App.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
    np = new NotePanel({
        items :[notesTextArea],
        caseId : caseId,
        caseIndex : caseIndex,
        callback :{
            'loaded': function (data) {
                _App.api.call('read', url, {}, {
                    success: function (notes) {
                        for (i = 0 ; i < notes.rowList.length; i += 1) {
                            log = notes.rowList[i];
                            pictureUrl = _App.api.buildFileURL({
                                module: 'Users',
                                id: log.not_user_id,
                                field: 'picture'
                            });

                            var currentDate = Date.parse(notes.currentDate);
                            var dateEntered = Date.parse(log.date_entered);
                            if (isNaN(currentDate)) {
                                currentDate = Date.parse(notes.currentDate.replace(/\s/g, "T"));
                            }
                            if (isNaN(dateEntered)) {
                                dateEntered = Date.parse(log.date_entered.replace(/\s/g, "T"));
                            }
                            newLog = {
                                name: 'log' ,
                                label: log.not_content,
                                user:  log.last_name,
                                picture : pictureUrl,
                                duration: '<strong> ' +  _App.date(log.date_entered).fromNow() + ' </strong>',
                                startDate: _App.date(log.date_entered).formatUser(),
                                logId: log.id
                            };
                            np.addLog(newLog);

                        }
                        _App.alert.dismiss('upload');
                    },
                    error: function (sugarHttpError) {

                    }
                });
            }}
    });
    w = new PMSE.Window({
        width: 800,
        height: 380,
        modal: true,
        title: translate('LBL_PMSE_TITLE_PROCESS_NOTES', 'pmse_Inbox') + ' # ' + caseId
    });

    w.addPanel(np);
    w.show();
};

addNotes = function (casId, caseIndex) {
    var txtNote = document.getElementById('txtNote'),
        countNotes = document.getElementById('countNotes'),
        strNote = txtNote.value.trim(),
        reg,
        e;
    txtNote.style.borderColor = '#CCCCCC';
    if (strNote === '') {
        txtNote.style.borderColor = 'red';
        txtNote.focus();
        return false;
    }
    reg = /<[^\s]/g;
    strNote = strNote.trim();
    e = reg.test(strNote);
    if (e) {
        strNote = strNote.replace(/</g, '< ');
    }
    $.ajax({
        url: "./index.php?module=ProcessMaker&action=addNotes&to_pdf=1",
        async: false,
        data: {not_content: strNote, cas_id: casId, cas_index: caseIndex},
        dataType: 'json',
        type: 'POST'
    })
        .done(function (ajaxResponse) {
            if (ajaxResponse.success) {
                addRow(ajaxResponse.data);
                txtNote.value = '';
                countNotes.style.display = 'block';
                countNotes.innerHTML = parseInt(countNotes.innerHTML, 10) + 1;
            }
        });
};

deleteNotes = function (id) {
    var countNotes = document.getElementById('countNotes');
    $.ajax({
        url: "./index.php?module=ProcessMaker&action=deleteNotes&to_pdf=1",
        async: false,
        data: {not_id: id},
        dataType: 'json',
        type: 'POST'
    })
        .done(function (ajaxResponse) {
            if (ajaxResponse.success) {
                deleteRow(id);
                countNotes.innerHTML = parseInt(countNotes.innerHTML, 10) - 1;
            }
        });
};

addRow = function (args) {
    var table = document.getElementById('tblNotes'),
        rowCount = table.rows.length,
        row = table.insertRow(rowCount),
        lastRow,
        col1,
        pic,
        html;
    if(App){ _App = App; } else { _App = parent.SUGAR.App; }
    if (rowCount == 0) {
        row.className = 'oddListRowS1';
    } else {
        lastRow = table.rows[rowCount - 1];
        if (lastRow.getAttribute('class') === 'evenListRowS1') {
            row.className = 'oddListRowS1';
        } else {
            row.className = 'evenListRowS1';
        }
    }

    row.id = args.not_id;

    if (args.user_picture == null) {
        pic = 'modules/ProcessMaker/img/default_user.png';
    } else {
        pic = 'index.php?entryPoint=download&amp;id=' + args.user_picture + '&amp;type=SugarFieldImage&amp;isTempFile=1';
    }

    html = '<div>';
    html += '<div style="float: left; margin-right: 3px; width: 50px; height: 50px;">';
    html += '<img style="max-width: 50px; max-height: 50px;" src="' + pic + '">';
    html += '</div>';
    html += '<div style="float: left; margin-right: 3px;">';
    html += '<strong>' + args.user_name + '</strong><br>';
    html += args.not_content + '<br>';
    html += '<span style="font-size: 11px; color: #7e7e7e;">' + _App.date(args.date).formatUser() + '</span>';
    html += '</div>';
    html += '<div style="float: right; text-align: right;">';
    html += _App.date(args.date).fromNow() + '<br>';
    html += '<a href="javascript:deleteRow(\'' + args.not_id + '\');">' + translate('LBL_PMSE_LABEL_DELETE') + '</a>';
    html += '</div>';
    html += '<div class="clear"></div>';
    html += '</div>';

    col1 = row.insertCell(0);
    col1.innerHTML = html;

};

deleteRow =  function (id) {
    try {
        var table = document.getElementById('tblNotes'),
            rowCount = table.rows.length,
            row,
            i;
        for (i = 0; i < rowCount; i++) {
            row = table.rows[i];
            if (null != row.id && '' != row.id && id == row.id) {
                if (rowCount <= 1) {
                    break;
                }
                table.deleteRow(i);
                rowCount--;
                i--;
            }
        }
    } catch (e) {
        console.log(e);
    }
};
