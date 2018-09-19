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
    extendsFrom: 'RecordView',

    events: {
        'click .record-edit-link-wrapper': 'handleEdit',
        'click [data-action=scroll]': 'paginateRecord',
        'click .record-panel-header': 'togglePanel',
        'click .tab a': 'setActiveTab'
    },

    initialize: function(options) {
        options.meta = _.extend({}, app.metadata.getView(null, 'record'), options.meta);
        options.meta.hashSync = _.isUndefined(options.meta.hashSync) ? true : options.meta.hashSync;
        this._super('initialize', [options]);

        this.context.on('approve:case', this.approveCase, this);
        this.context.on('reject:case', this.rejectCase, this);
        this.context.on('cancel:case', this.cancelCase, this);
        this.context.on('button:cancel_button:click', this.cancelClicked, this);
        //event register for preventing actions
        // when user escapes the page without confirming deleting
        // add a callback to close the alert if users navigate from the page
        app.routing.before('route', this.dismissAlert, this);
        $(window).on('beforeunload.delete' + this.cid, _.bind(this.warnDeleteOnRefresh, this));

        this.delegateButtonEvents();

        if (this.createMode) {
            this.model.isNotEmpty = true;
        }

        this.noEditFields = [];
        // properly namespace SHOW_MORE_KEY key
        this.MORE_LESS_KEY = app.user.lastState.key(this.MORE_LESS_KEY, this);

        this.adjustHeaderpane = _.bind(_.debounce(this.adjustHeaderpane, 50), this);
        $(window).on('resize.' + this.cid, this.adjustHeaderpane);
    },

    approveCase: function(options){
        var self = this;
        var statusApprove = 'approve';
        url = App.api.buildURL('pmse_approve', null, {id: statusApprove});
        App.api.call('update', url, options.attributes, {
            success: function () {
            },
            error: function (err) {
            }
        });
        var redirect = options.module;
        app.router.navigate(redirect , {trigger: true, replace: true });
    },

    rejectCase: function(options){
        var self = this;
        var statusApprove = 'reject';
        url = App.api.buildURL('pmse_approve', null, {id: statusApprove});
        App.api.call('update', url, options.attributes, {
            success: function () {
            },
            error: function (err) {
            }
        });
        var redirect = options.module;
        app.router.navigate(redirect , {trigger: true, replace: true });
    },

    cancelCase: function(options){
        var redirect = options.module;
        app.router.navigate(redirect , {trigger: true, replace: true });
    },

    validationComplete: function(isValid) {
        if (isValid) {
            this.setButtonStates(this.STATE.VIEW);
            this.handleSave();
        }
    },


    _initButtons: function() {

        if (this.options.meta && this.options.meta.buttons) {
            _.each(this.options.meta.buttons, function(button) {
                this.registerFieldAsButton(button.name);
                if (button.buttons) {
                    var dropdownButton = this.getField(button.name);
                    if (!dropdownButton) {
                        return;
                    }
                    _.each(dropdownButton.fields, function(ddButton) {
                        this.buttons[ddButton.name] = ddButton;
                    }, this);
                }
            }, this);
        }
    },
    toggleViewButtons: function(isEdit) {
        this.$('.headerpane span[data-type="badge"]').toggleClass('hide', isEdit);
        this.$('.headerpane span[data-type="favorite"]').toggleClass('hide', isEdit);
        this.$('.headerpane span[data-type="follow"]').toggleClass('hide', isEdit);
        this.$('.headerpane .btn-group-previous-next').toggleClass('hide', isEdit);
    }

})
