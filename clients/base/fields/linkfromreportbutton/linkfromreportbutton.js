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
/**
 * LinkFromReportButton allows user to select a report and relate records to
 * primary record.
 *
 * @class View.Fields.Base.LinkfromreportbuttonField
 * @alias SUGAR.App.view.fields.BaseLinkfromreportbuttonField
 * @extends View.Fields.Base.StickyRowactionField
 */
({
    extendsFrom: 'StickyRowactionField',
    events: {
        'click a[name=select_button]': 'openSelectDrawer'
    },

    /**
     * Event handler for the select button that opens a
     * {@link View.Layouts.Base.SelectionListLinkLayout} in  a drawer for linking
     * an existing record.
     */
    openSelectDrawer: function() {
        if (this.isDisabled()) {
            return;
        }

        var filteredModule = 'Reports',
            filterOptions = new app.utils.FilterOptions().config(this.def);

        var thisContextModule = this.context.get('module');
        if (thisContextModule !== filteredModule) {
            filterOptions.setLangModules([thisContextModule, filteredModule, 'Filters']);
        }

        app.drawer.open({
            layout: 'selection-list',
            context: {
                module: 'Reports',
                filterOptions: filterOptions.format()
            }
        }, _.bind(this.selectDrawerCallback, this));
    },

    /**
     * Process the report that was selected by the user.
     * @param {Object} model
     */
    selectDrawerCallback: function(model) {
        if (!model || _.isEmpty(model.id)) {
            return;
        }

        if (model.module != this.context.get('module')) {
            app.alert.show('listfromreport-warning', {
                level: 'warning',
                messages: app.lang.get('LBL_LINK_FROM_REPORT_WRONG_MODULE'),
                autoClose: true
            });
            return;
        }

        var recordListUrl = app.api.buildURL('Reports', 'record_list', {id: model.id}),
            self = this;

        app.alert.show('listfromreport_loading', {level: 'process', title: app.lang.get('LBL_LOADING')});

        app.api.call(
            'create',
            recordListUrl,
            null,
            {
                success: _.bind(self.linkRecordList, self),
                error: function(error) {
                    app.alert.dismiss('listfromreport_loading');
                    app.alert.show('server-error', {
                        level: 'error',
                        title: app.lang.get('ERR_INTERNAL_ERR_MSG'),
                        messages: ['ERR_HTTP_500_TEXT_LINE1', 'ERR_HTTP_500_TEXT_LINE2']
                    });
                }
            }
        );
    },

    /**
     * Links records from a report to the parent record
     * @param {Object} response
     */
    linkRecordList: function(response) {
        var parentModel = this.context.get('parentModel'),
            parentModule = parentModel.get('module') || parentModel.get('_module'),
            link = this.context.get('link'), action = 'link/' + link + '/add_record_list',
            url = app.api.buildURL(
                parentModule,
                action,
                {
                    id: parentModel.get('id'),
                    relatedId: response.id
                }
            );

        app.api.call('create', url, null, {
            success: _.bind(this.linkSuccessCallback, this),
            error: _.bind(this.linkErrorCallback, this),
            complete: function(data) {
                app.alert.dismiss('listfromreport_loading');
            }
        });
    },

    /**
     * Success callback function for api call
     * @param {Object} results
     */
    linkSuccessCallback: function(results) {
        var message, messageLevel;
        if (results.related_records.success.length > 0) {
            messageLevel = 'success';
            message = app.lang.get('LBL_LINK_FROM_REPORT_SUCCESS', null, {
                reportCount: results.related_records.success.length
            });
        } else {
            messageLevel = 'warning';
            message = app.lang.get('LBL_LINK_FROM_REPORT_NO_DATA');
        }

        app.alert.show('server-success', {
            level: messageLevel,
            messages: message,
            autoClose: true
        });

        this.context.resetLoadFlag();
        this.context.set('skipFetch', false);
        this.context.loadData();
    },

    /**
     * Error callback function for api call
     * @param {Object} error
     */
    linkErrorCallback: function(error) {
        app.alert.show('server-error', {
            level: 'error',
            title: app.lang.get('ERR_INTERNAL_ERR_MSG'),
            messages: ['ERR_HTTP_500_TEXT_LINE1', 'ERR_HTTP_500_TEXT_LINE2']
        });
    },

    /**
     * Returns false if current user does not have access to Reports module - ACL checks
     * @return {Boolean} true if allow access, false otherwise
     * @override
     */
    isDisabled: function() {
        return !app.acl.hasAccess('view', 'Reports');
    }
})
