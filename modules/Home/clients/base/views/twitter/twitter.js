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
 * @class View.Views.Base.Home.TwitterView
 * @alias SUGAR.App.view.views.BaseHomeTwitterView
 * @extends View.View
 */
({
    plugins: ['Dashlet', 'RelativeTime', 'Connector'],
    limit : 20,
    events: {
        'click .connect-twitter': 'onConnectTwitterClick',
        'click .create-case': 'createCase'
    },

    initDashlet: function() {
        this.initDashletConfig();
        var serverInfo = app.metadata.getServerInfo();
        this.tweet2case = serverInfo.system_tweettocase_on ? true : false;
        var limit = this.settings.get('limit') || this.limit;
        this.settings.set('limit', limit);
        this.cacheKey = 'twitter.dashlet.current_user_cache';
        var currentUserCache = app.cache.get(this.cacheKey);
        if (currentUserCache && currentUserCache.current_twitter_user_name) {
            self.current_twitter_user_name = currentUserCache.current_twitter_user_name;
        }
        if (currentUserCache && currentUserCache.current_twitter_user_pic) {
            self.current_twitter_user_pic = currentUserCache.current_twitter_user_pic;
        }
        this.caseCreateAcl = app.acl.hasAccess('edit','Cases');
    },

    initDashletConfig: function() {
        this.moduleType = app.controller.context.get('module');
        this.layoutType = app.controller.context.get('layout');
        // if config view override with module specific
        if (this.meta.config && this.layoutType === 'record') {
            this.dashletConfig = app.metadata.getView(this.moduleType, this.name) || this.dashletConfig;
            // if record view that's not the Home module's record view, disable twitter name settings config
            if (this.moduleType !== 'Home' &&
                this.dashletConfig.config &&
                this.dashletConfig.config.fields) {
                // get rid of the twitter name field
                this.dashletConfig.config.fields = _.filter(this.dashletConfig.config.fields,
                    function(field) {
                        return field.name !== 'twitter';
                    });
            }
        }
    },

    onConnectTwitterClick: function(event) {
        if ( !_.isUndefined(event.currentTarget) ) {
            event.preventDefault();
            var href = this.$(event.currentTarget).attr('href');
            app.bwc.login(false, function(response){
                window.open(href);
            });
        }
    },
    /**
     * opens case create drawer with case attributes prefilled
     * @param event
     */
    createCase: function (event) {
        var module = 'Cases';
        var layout = 'create';
        var self = this;

        // set up and open the drawer
        app.drawer.reset();
        app.drawer.open({
            layout: layout,
            context: {
                create: true,
                module: module
            }
        }, function (refresh, createModelPointer) {
            if (refresh) {
                var collection = app.controller.context.get('collection');
                if (collection && collection.module === module) {
                    collection.fetch({
                        //Don't show alerts for this request
                        showAlerts: false
                    });
                }
            }
        });

        var createLayout = _.last(app.drawer._components),
            tweetId = this.$(event.target).data('url').split('/');
            tweetId = tweetId[tweetId.length-1];
        var createValues = {
            'source':'Twitter',
            'name': app.lang.get('LBL_CASE_FROM_TWITTER_TITLE', 'Cases') + ' ' + tweetId +' @'+ this.$(event.target).data('screen_name'),
            'description': app.lang.get('LBL_TWITTER_SOURCE', 'Cases') +' '+  this.$(event.target).data('url')
        };
        // update the create models values
        this.createModel = createLayout.model;
        if (this.model) {
            if(this.model.module == 'Accounts') {
                createValues.account_name = this.model.get('name');
                createValues.account_id = this.model.get('id');
            } else {
                createValues.account_name = this.model.get('account_name');
                createValues.account_id = this.model.get('account_id');
            }
        }

        this.setCreateModelFields(this.createModel, createValues);

        this.createModel.on('sync', _.once(function (model) {
            // add activity stream on save
            var activity = app.data.createBean('Activities', {
                activity_type: "post",
                comment_count: 0,
                data: {
                    value: app.lang.get('LBL_TWITTER_SOURCE') +' '+ self.$(event.target).data('url'),
                    tags: []
                },
                tags: [],
                value: app.lang.get('LBL_TWITTER_SOURCE') +' '+ self.$(event.target).data('url'),
                deleted: "0",
                parent_id: model.id,
                parent_type: "Cases"
            });

            activity.save();

            //relate contact if we can find one
            var contacts = app.data.createBeanCollection('Contacts');
            var options = {
                filter: [
                    {
                        "twitter": {
                            "$equals": self.$(event.target).data('screen_name')
                        }
                    }
                ],
                success: function (data) {
                    if (data && data.models && data.models[0]) {
                        var url = app.api.buildURL('Cases', 'contacts', {id: self.createModel.id, relatedId: data.models[0].id, link: true});
                        app.api.call('create', url);
                    }
                }
            };
            contacts.fetch(options);
        }));
    },
    /**
     * sets fields on model according to acls
     * @param model
     * @param fields
     * @return {Mixed}
     */
    setCreateModelFields: function(model, fields) {
        var action = 'edit', module = 'Cases', ownerId = app.user.get('id');
        _.each(fields, function(value, fieldName) {
            if(app.acl.hasAccess(action, module, ownerId, fieldName)) {
                model.set(fieldName, value);
            }
        });

        return model;
    },
    _render: function () {
        if (this.tweets || this.meta.config) {
            app.view.View.prototype._render.call(this);
        }
    },

    bindDataChange: function(){
        if(this.model) {
            this.model.on('change', this.loadData, this);
        }
    },

    /**
     * Gets twitter name from one of various fields depending on context
     * @return {string} twitter name
     */
    getTwitterName: function() {
        var mapping = this.getConnectorModuleFieldMapping('ext_rest_twitter', this.moduleType);
        var twitter =
                this.model.get('twitter') ||
                this.model.get(mapping.name) ||
                this.model.get('name') ||
                this.model.get('account_name') ||
                this.model.get('full_name');
        //workaround because home module actually pulls a dashboard instead of an
        //empty home model
        if (this.layoutType === 'records' || this.moduleType === 'Home') {
            twitter = this.settings.get('twitter');
        }
        if (!twitter) {
            return false;
        }
        twitter = twitter.replace(/ /g, '');
        this.twitter = twitter;
        return twitter;
    },

    /**
     * Load twitter data
     *
     * @param {object} options
     * @return {boolean} `false` if twitter name could not be established
     */
    loadData: function(options) {
        if (this.disposed || this.meta.config) {
            return;
        }

        this.screen_name = this.settings.get('twitter') || false;
        this.tried = false;

        if (this.viewName === 'config') {
            return false;
        }
        this.loadDataCompleteCb = options ? options.complete : null;
        this.connectorCriteria = ['eapm_bean', 'test_passed'];
        this.checkConnector('ext_rest_twitter',
            _.bind(this.loadDataWithValidConnector, this),
            _.bind(this.handleLoadError, this),
            this.connectorCriteria);
    },

    /**
     * With a valid connector, load twitter data
     *
     * @param {object} connector - connector will have been validated already
     */
    loadDataWithValidConnector: function(connector) {
        if (!this.getTwitterName()) {
            if (_.isFunction(this.loadDataCompleteCb)) {
                this.loadDataCompleteCb();
            }
            return false;
        }

        var limit = parseInt(this.settings.get('limit'), 10) || this.limit,
            self = this;

        var currentUserUrl = app.api.buildURL('connector/twitter/currentUser','','',{connectorHash:connector.connectorHash});
        if (!this.current_twitter_user_name) {
            app.api.call('READ', currentUserUrl, {},{
                success: function(data) {
                    app.cache.set(self.cacheKey, {
                        'current_twitter_user_name': data.screen_name,
                        'current_twitter_user_pic': data.profile_image_url
                    });
                    self.current_twitter_user_name = data.screen_name;
                    self.current_twitter_user_pic = data.profile_image_url;
                    if (!this.disposed) {
                        self.render();
                    }
                },
                complete: self.loadDataCompleteCb
            });
        }

        var url = app.api.buildURL('connector/twitter','',{id:this.twitter},{count:limit,connectorHash:connector.connectorHash});
        app.api.call('READ', url, {},{
            success: function (data) {
                if (self.disposed) {
                    return;
                }
                var tweets = [];
                if (data.success !== false) {
                    _.each(data, function (tweet) {
                        var time = new Date(tweet.created_at.replace(/^\w+ (\w+) (\d+) ([\d:]+) \+0000 (\d+)$/,
                                '$1 $2 $4 $3 UTC')),
                            date = app.date.format(time, 'Y/m/d H:i:s'),
                        // retweeted tweets are sometimes truncated so use the original as source text
                            text = tweet.retweeted_status ? 'RT @'+tweet.retweeted_status.user.screen_name+': '+tweet.retweeted_status.text : tweet.text,
                            sourceUrl = tweet.source,
                            id = tweet.id_str,
                            name = tweet.user.name,
                            tokenText = text.split(' '),
                            screen_name = tweet.user.screen_name,
                            profile_image_url = tweet.user.profile_image_url_https,
                            j,
                            rightNow = new Date(),
                            diff = (rightNow.getTime() - time.getTime())/(1000*60*60*24),
                            useAbsTime = diff > 1;

                        // Search for links and turn them into hrefs
                        for (j = 0; j < tokenText.length; j++) {
                            if (tokenText[j].charAt(0) == 'h' && tokenText[j].charAt(1) == 't') {
                                tokenText[j] = "<a class='googledoc-fancybox' href=" + '"' + tokenText[j] + '"' + "target='_blank'>" + tokenText[j] + "</a>";
                            }
                        }

                        text = tokenText.join(' ');
                        tweets.push({id: id, name: name, screen_name: screen_name, profile_image_url: profile_image_url, text: text, source: sourceUrl, date: date, useAbsTime: useAbsTime});
                    }, this);
                }

                self.tweets = tweets;
                if (!this.disposed) {
                    self.template = app.template.get(self.name + '.Home');
                    self.render();
                }
            },
            error: function(data) {
                if (self.tried === false) {
                    self.tried = true;
                    // twitter get returned error, so re-get connectors
                    var name = 'ext_rest_twitter';
                    var funcWrapper = function () {
                        self.checkConnector(name,
                            _.bind(self.loadDataWithValidConnector, self),
                            _.bind(self.handleLoadError, self),
                            self.connectorCriteria);
                    };
                    self.getConnectors(name, funcWrapper);
                }
                else {
                    self.handleLoadError(null);
                }
            },
            complete: self.loadDataCompleteCb
        });
    },

    /**
     * Error handler for if connector validation errors at some point
     *
     * @param {object} connector
     */
    handleLoadError: function(connector) {
        if (this.disposed) {
            return;
        }
        this.showGeneric = true;
        this.errorLBL = app.lang.get('ERROR_UNABLE_TO_RETRIEVE_DATA');
        this.template = app.template.get(this.name + '.twitter-need-configure.Home');
        if (connector === null) {
            //Connector doesn't exist
            this.errorLBL = app.lang.get('LBL_ERROR_CANNOT_FIND_TWITTER') + this.twitter;
        }
        else if (!connector.test_passed && connector.testing_enabled) {
            //OAuth failed
            this.needOAuth = true;
            this.needConnect = false;
            this.showGeneric = false;
            this.showAdmin = app.acl.hasAccess('admin', 'Administration');
        }
        else if (!connector.eapm_bean) {
            //Not connected
            this.needOAuth = false;
            this.needConnect = true;
            this.showGeneric = false;
            this.showAdmin = app.acl.hasAccess('admin', 'Administration');
        }
        app.view.View.prototype._render.call(this);
        if (_.isFunction(this.loadDataCompleteCb)) {
            this.loadDataCompleteCb();
        }
    },

    _dispose: function() {
        if (this.model) {
            this.model.off('change', this.loadData, this);
        }

        app.view.View.prototype._dispose.call(this);
    }
})
