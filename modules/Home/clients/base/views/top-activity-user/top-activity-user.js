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
 * @class View.Views.Base.Home.TopActivityUserView
 * @alias SUGAR.App.view.views.BaseHomeTopActivityUserView
 * @extends View.View
 */
({
    plugins: ['Dashlet', 'GridBuilder'],
    events: {
        'change select[name=filter_duration]': 'filterChanged'
    },
    /**
     * Track if current user is manager.
     */
    isManager: false,
    initDashlet: function(viewName) {
        this.collection = new app.BeanCollection();
        this.isManager = app.user.get('is_manager');
        if(!this.meta.config) {
            this.collection.on("reset", this.render, this);
        }
    },
    _mapping: {
        meetings: {
            icon: 'fa-comments',
            label: 'LBL_MOST_MEETING_HELD'
        },
        inbound_emails: {
            icon: 'fa-envelope',
            label: 'LBL_MOST_EMAILS_RECEIVED'
        },
        outbound_emails: {
            icon: 'fa-envelope-o',
            label: 'LBL_MOST_EMAILS_SENT'
        },
        calls: {
            icon: 'fa-phone',
            label: 'LBL_MOST_CALLS_MADE'
        }
    },
    loadData: function(params) {
        if(this.meta.config) {
            return;
        }
        var url = app.api.buildURL('mostactiveusers', null, null, {days: this.settings.get("filter_duration")}),
            self = this;
        app.api.call("read", url, null, {
            success: function(data) {
                if(self.disposed) {
                    return;
                }
                var models = [];
                _.each(data, function(attributes, module){
                    if(_.isEmpty(attributes)) {
                        return;
                    }
                    var model = new app.Bean(_.extend({
                        id: _.uniqueId('aui')
                    }, attributes));
                    model.module = module;
                    model.set("name", model.get("first_name") + ' ' + model.get("last_name"));
                    model.set("icon", self._mapping[module]['icon']);
                    var template = Handlebars.compile(app.lang.get(self._mapping[module]['label'], self.module));
                    model.set("label", template({
                        count: model.get("count")
                    }));
                    model.set("pictureUrl", app.api.buildFileURL({
                        module: "Users",
                        id: model.get("user_id"),
                        field: "picture"
                    }));
                    models.push(model);
                }, this);
                self.collection.reset(models);
            },
            complete: params ? params.complete : null
        });
    },
    filterChanged: function(evt) {
        this.loadData();
    },

    _dispose: function() {
        if(this.collection) {
            this.collection.off("reset", null, this);
        }
        app.view.View.prototype._dispose.call(this);
    }
})
