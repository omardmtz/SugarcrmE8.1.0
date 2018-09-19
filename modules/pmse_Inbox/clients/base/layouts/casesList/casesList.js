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
    /**
     * @class ComposeAddressbookLayout
     * @extends Layout
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['ProcessActions']);
        app.view.Layout.prototype.initialize.call(this, options);
        this.collection.sync = this.sync;
//        this.collection.allowed_modules = ['Cases Title', 'Process Name', 'Status', 'Owner'];
        this.collection.allowed_modules = [
            app.lang.get('LBL_STATUS_COMPLETED', options.module),
            app.lang.get('LBL_STATUS_TERMINATED', options.module),
            app.lang.get('LBL_STATUS_IN_PROGRESS', options.module),
            app.lang.get('LBL_STATUS_CANCELLED', options.module),
            app.lang.get('LBL_STATUS_ERROR', options.module)];
        this.context.on('compose:addressbook:search', this.search, this);
        this.context.on('case:status', this.viewStatus, this);
        this.context.on('case:history', this.viewHistory, this);
        this.context.on('case:notes', this.viewNotes, this);
        this.context.on('case:execute', this.executeCase, this);
        this.context.on('case:reassign', this.executeReassign, this);
        this.context.on('list:cancelCase:fire', this.cancelCases, this);
//        this.context.on('list:executeCase:fire', this.executeCases, this);
    },
    viewStatus: function(model){
        this.showStatus(model.get('cas_id'));
    },
    viewHistory: function(model){
        this.getHistory(model.get('cas_id'));
    },
    viewNotes: function(model){
        this.showNotes(model.get('cas_id'), 1);
    },
    executeCase: function(model){
        app.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
        this.executeCasesList([model.get('cas_id')]);
    },
    cancelCases: function(model){
        var self=this;

        var msg=app.lang.get('LBL_PMSE_CANCEL_MESSAGE', this.module);
        msg=msg.replace('[]',model.get('cas_title'));
        msg=msg.replace('{}',model.get('cas_id'));

        app.alert.show('cancelCase-id', {
            level: 'confirmation',
            messages:msg,
//            messages:app.lang.get('LBL_CANCEL_MESSAGE', this.module)+model.get('cas_title')+' with Cas Id: '+model.get('cas_id')+'?',
            autoClose: false,
            onConfirm: function(){
                app.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
                var massCollection=self.context.get('mass_collection');
                var value = self.model.attributes;
//        value.cas_id = this.buildVariablesString(massCollection);
                value.cas_id = [model.get('cas_id')];
                var pmseInboxUrl = app.api.buildURL(self.module + '/cancelCases','',{},{});
                app.api.call('update', pmseInboxUrl, value,{
                    success: function(data)
                    {
                        self.reloadList();
                        app.alert.dismiss('upload');
//                        window.location.reload();
                    }
                });
            },
            onCancel: function(){
                app.alert.dismiss('cancelCase-id');
            }
        });
    },
//    executeCases: function(model){
//        app.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
//        var massCollection=this.context.get('mass_collection');
//        this.executeCasesList(this.buildVariablesString(massCollection));
//    },
    executeCasesList: function(idCases){
        var self=this;
        var value = this.model.attributes;
        value.cas_id = idCases;
        var pmseInboxUrl = app.api.buildURL(this.module + '/reactivateFlows','',{},{});
        app.api.call('update', pmseInboxUrl, value,{
            success: function(data)
            {
                self.reloadList();
                app.alert.dismiss('upload');
//                window.location.reload();
            }
        });
    },

    executeReassign: function(model) {
        app.drawer.open({
            layout: 'reassignCases',
            context: {
                module: 'pmse_Inbox',
                parent: this.context,
                cas_id: model.get('cas_id')
            }
        });
    },

    buildVariablesString: function(recipients) {
        var listIdCases = [],count=0;
        _.each(recipients.models, function(model) {
            listIdCases[count++]=model.attributes.cas_id
        });
        return currentValue = listIdCases;
    },
    /**
     * Calls the custom Mail API endpoint to search for email addresses.
     *
     * @param method
     * @param model
     * @param options
     */
    sync: function(method, model, options) {
        var callbacks,
            url;

        options = options || {};

        // only fetch from the approved modules
        if (_.isEmpty(options.module_list)) {
            options.module_list = ['all'];
        } else {
            options.module_list = _.intersection(this.allowed_modules, options.module_list);
//            options.module_list = this.allowed_modules;
        }

        // this is a hack to make pagination work while trying to minimize the affect on existing configurations
        // there is a bug that needs to be fixed before the correct approach (config.maxQueryResult vs. options.limit)
        // can be determined
        app.config.maxQueryResult = app.config.maxQueryResult || 20;
        options.limit = options.limit || app.config.maxQueryResult;

        options = app.data.parseOptionsForSync(method, model, options);

        callbacks = app.data.getSyncCallbacks(method, model, options);
        this.trigger('data:sync:start', method, model, options);

//        url = app.api.buildURL('pmse_Project', 'caseslist/find', null, options.params);
        url = app.api.buildURL('pmse_Inbox', 'casesList', null, options.params);
        app.api.call('read', url, null, callbacks);
    },
    /**
     * Adds the set of modules and term that should be used to search for recipients.
     *
     * @param {Array} modules
     * @param {String} term
     */
    search: function(modules, term) {
        // reset offset to 0 on a search. make sure that it resets and does not update.
        this.context.set('query', term);
        this.context.set('module_list', modules);
        var sortOptions = this.context.get('sortOptions') || {};
        sortOptions.query = term;
        sortOptions.module_list = modules;
        sortOptions.offset = 0;
        sortOptions.update = false;
        this.context.resetLoadFlag({recursive: false});
        this.context.set('skipFetch', false);
        this.context.loadData(sortOptions);
    },
    reloadList: function() {
        this.context.reloadData({
            recursive:false,
        });
    }
})
