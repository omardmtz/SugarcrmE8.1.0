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
({
    extendsFrom: 'RecordlistView',

    initialize: function (options) {
        this._super("initialize", [options]);
        this.collection.on('data:sync:complete', this.loaded, this);
    },

    loaded: function () {
        _.each(this.fields, function (field) {
            if(field.name === 'assigned_user') {
                this.auser = field.value;
                setTimeout(function () {
                    var spans = document.getElementsByClassName('select2-chosen');
                    for (var i=0;i<spans.length;i++) {
                        if (spans[i].innerText && (spans[i].innerText != app.lang.get('LBL_PMSE_FILTER', 'pmse_Inbox'))) {
                            spans[i].innerText = this.auser;
                        }
                        else if (spans[i].textContent && (spans[i].textContent != app.lang.get('LBL_PMSE_FILTER', 'pmse_Inbox'))) {
                            spans[i].textContent = this.auser;
                        }
                    }
                },2000);
            }
        });
    }
})