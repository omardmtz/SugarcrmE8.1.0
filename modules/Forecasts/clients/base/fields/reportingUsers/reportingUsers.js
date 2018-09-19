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
 * @class View.Fields.Base.Forecasts.ReportingUsersField
 * @alias SUGAR.App.view.fields.BaseForecastsReportingUsersField
 * @extends View.Fields.Base.BaseField
 */
({

    /**
     * The JS Tree Object
     */
    jsTree: {},

    /**
     * The end point we need to hit
     */
    reporteesEndpoint: '',

    /**
     * Current end point hit
     */
    currentTreeUrl: '',

    /**
     * Current root It
     */
    currentRootId: '',

    /**
     * Selected User Storage
     */
    selectedUser: {},

    /**
     * Has the base init selected the proper user?  This is needed to prevent a double selectedUser change from fireing
     */
    initHasSelected: false,

    /**
     * Previous user
     */
    previousUserName: undefined,

    /**
     * Initialize the View
     *
     * @constructor
     * @param {Object} options
     */
    initialize: function(options) {
        app.view.Field.prototype.initialize.call(this, options);

        this.reporteesEndpoint = app.api.buildURL("Forecasts/reportees") + '/';

        this.selectedUser = this.context.get('selectedUser') || app.user.toJSON();
        this.currentTreeUrl = this.reporteesEndpoint + this.selectedUser.id;
        this.currentRootId = this.selectedUser.id;
    },

    /**
     * overriding _dispose to make sure custom added jsTree listener is removed
     * @private
     */
    _dispose: function() {
        if (app.user.get('is_manager') && !_.isEmpty(this.jsTree)) {
            this.jsTree.off();
        }
        app.view.Field.prototype._dispose.call(this);
    },

    /**
     * Only run the render if the user is a manager as that is the only time we want the tree to display.
     */
    render: function() {
        if (app.user.get('is_manager')) {
            app.view.Field.prototype.render.call(this);
        }
    },

    /**
     * Clean up any left over bound data to our context
     */
    unbindData: function() {
        app.view.Field.prototype.unbindData.call(this);
    },
    
    /**
     * set up event listeners
     */
    bindDataChange: function(){
        this.context.on("forecasts:user:canceled", function(){
            this.initHasSelected = false;
            this.selectJSTreeNode(this.previousUserName);
            this.initHasSelected = true;
        }, this);
    },

    /**
     * Function to give a final check before rendering to see if we really need to render
     * Any time the selectedUser changes on context we run through this function to
     * see if we should render the tree again
     *
     * @param context
     * @param selectedUser {Object} the current selectedUser on the context
     */
    checkRender: function(context, selectedUser) {
        // handle the case for user clicking MyOpportunities first
        this.selectedUser = selectedUser;
        if (selectedUser.showOpps) {
            var nodeId = (selectedUser.is_manager ? 'jstree_node_myopps_' : 'jstree_node_') + selectedUser.user_name;
            this.selectJSTreeNode(nodeId)
            // check before render if we're trying to re-render tree with a fresh root user
            // otherwise do not re-render tree
            // also make sure we're not re-rendering tree for a rep
        } else if (this.currentRootId != selectedUser.id) {
            if (selectedUser.is_manager) {
                // if user is a manager we'll be re-rendering the tree
                // no need to re-render the tree if not a manager because the dataset
                // stays the same

                this.currentRootId = selectedUser.id;
                this.currentTreeUrl = this.reporteesEndpoint + selectedUser.id;
                this.rendered = false;
                if (!this.disposed) {
                    this.render();
                }
            } else {
                // user is not a manager but if this event is coming from the worksheets
                // we need to "select" the user on the tree to show they're selected

                // create node ID
                var nodeId = 'jstree_node_' + selectedUser.user_name;

                // select node only if it is not the already selected node
                if (this.jsTree.jstree('get_selected').attr('id') != nodeId) {
                    this.selectJSTreeNode(nodeId)
                }
            }
        }
    },

    /**
     * Function that handles deselecting any selected nodes then selects the nodeId
     *
     * @param nodeId {String} the node id starting with "jstree_node_"
     */
    selectJSTreeNode: function(nodeId) {
        // jstree kept trying to hold on to the root node css staying selected when
        // user clicked a user's name from the worksheet, so explicitly causing a deselection
        this.jsTree.jstree('deselect_all');

        this.jsTree.jstree('select_node', '#' + nodeId);
    },


    /**
     * Recursively step through the tree and for each node representing a tree node, run the data attribute through
     * the replaceHTMLChars function.  This function supports n-levels of the tree hierarchy.
     *
     * @param data The data structure returned from the REST API Forecasts/reportees endpoint
     * @param ctx A reference to the view's context so that we may recursively call _recursiveReplaceHTMLChars
     * @return object The modified data structure after all the parent and children nodes have been stepped through
     * @private
     */
    _recursiveReplaceHTMLChars: function(data, ctx) {

        _.each(data, function(entry, index) {

            //Scan for the nodes with the data attribute.  These are the nodes we are interested in
            if (entry.data) {
                data[index].data = (function(value) {
                    return value.replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
                })(entry.data);

                if (entry.children) {
                    //For each children found (if any) then call _recursiveReplaceHTMLChars again.  Notice setting
                    //childEntry to an Array.  This is crucial so that the beginning _.each loop runs correctly.
                    _.each(entry.children, function(childEntry, index2) {
                        entry.children[index2] = ctx._recursiveReplaceHTMLChars([childEntry]);
                        if (childEntry.attr.rel == 'my_opportunities' && childEntry.metadata.id == app.user.get('id')) {
                            childEntry.data = app.utils.formatString(app.lang.get('LBL_MY_MANAGER_LINE', 'Forecasts'), [childEntry.data]);
                        }
                    }, this);
                }
            }
        }, this);

        return data;
    },

    /**
     * Renders JSTree
     * @param ctx
     * @param options
     * @protected
     */
    _render: function(ctx, options) {
        app.view.Field.prototype._render.call(this, ctx, options);

        var options = {};
        // breaking out options as a proper object to allow for bind
        options.success = _.bind(function(data) {
            this.createTree(data);
        }, this);

        app.api.call('read', this.currentTreeUrl, null, options);
    },

    createTree: function(data) {
        // make sure we're using an array
        // if the data coming from the endpoint is an array with one element
        // it gets converted to a JS object in the process of getting here
        if (!_.isArray(data)) {
            data = [ data ];
        }

        var treeData = this._recursiveReplaceHTMLChars(data, this),
            selectedUser = this.context.get('selectedUser'),
            nodeId = (selectedUser.is_manager && selectedUser.showOpps ? 'jstree_node_myopps_' : 'jstree_node_') + selectedUser.user_name;
        treeData.ctx = this.context;

        this.jsTree = $(".jstree-sugar").jstree({
            "plugins": ["json_data", "ui", "crrm", "types", "themes"],
            "json_data": {
                "data": treeData
            },
            "ui": {
                // when the tree re-renders, initially select the root node
                "initially_select": [ nodeId ]
            },
            "types": {
                "types": {
                    "types": {
                        "parent_link": {},
                        "manager": {},
                        "my_opportunities": {},
                        "rep": {},
                        "root": {}
                    }
                }
            }
        }).on("reselect.jstree", _.bind(function() {
                // this is needed to stop the double select when the tree is rendered
                this.initHasSelected = true;
            }, this))
        .on("select_node.jstree", _.bind(function(event, data) {
            if (this.initHasSelected) {
                this.previousUserName = (this.selectedUser.is_manager && this.selectedUser.showOpps ? 'jstree_node_myopps_' : 'jstree_node_') + this.selectedUser.user_name;

                var jsData = data.inst.get_json(),
                    nodeType = jsData[0].attr.rel,
                    userData = jsData[0].metadata,
                    showOpps = false;

                // if user clicked on a "My Opportunities" node
                // set this flag true
                if (nodeType == "my_opportunities" || nodeType == "rep") {
                    showOpps = true
                }

                var selectedUser = {
                    'id': userData.id,
                    'user_name': userData.user_name,
                    'full_name': userData.full_name,
                    'first_name': userData.first_name,
                    'last_name': userData.last_name,
                    'reports_to_id': userData.reports_to_id,
                    'reports_to_name': userData.reports_to_name,
                    'is_manager': (nodeType != 'rep'),
                    'is_top_level_manager': (nodeType != 'rep' && _.isEmpty(userData.reports_to_id)),
                    'showOpps': showOpps,
                    'reportees': []
                };

                this.context.trigger('forecasts:user:changed', selectedUser, this.context);
            }
        }, this));

        if (treeData) {
            var rootId = -1;

            if (treeData.length == 1) {
                // this case appears when "Parent" is not present
                rootId = treeData[0].metadata.id;
            } else if (treeData.length == 2) {
                // this case appears with a "Parent" link label in the return set
                // treeData[0] is the Parent link, treeData[1] is our root user node
                rootId = treeData[1].metadata.id;
            }

            this.currentRootId = rootId;
        }

        // add proper class onto the tree
        this.$el.find('#people').addClass("jstree-sugar");

    }
})
