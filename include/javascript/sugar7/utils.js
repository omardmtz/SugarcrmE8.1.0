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
(function(app) {
    app.events.on("app:init", function() {

        var tooltipTemplate = Handlebars.compile('<div class="tooltip">' +
            '<div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>');

        var $tooltipTemplate = $(tooltipTemplate());

        /**
         * Sets the `parent_type`, `parent_id`, and `parent_name` attributes of
         * the model using the data from the parent.
         *
         * The attributes are updated asynchronously if the parent's name isn't
         * known and must be retrieved. The attributes cannot be set if the
         * user does not have `list` access to the parent's module.
         *
         * @param {Data.Bean} model
         * @param {Data.Bean} parent
         */
        function setParentAttributes(model, parent) {
            var fields = app.metadata.getModule(model.module, 'fields');
            var modules;
            var name;

            modules = _.chain(app.lang.getAppListStrings(fields.parent_type.options))
                .keys()
                .filter(function(module) {
                    return app.acl.hasAccess('list', module);
                })
                .value();

            if (!_.contains(modules, parent.module)) {
                return;
            }

            function setAttributes(parent) {
                model.set('parent', _.extend({type: parent.module}, parent.toJSON()));
                model.set('parent_type', parent.module);
                model.set('parent_id', parent.get('id'));
                model.set('parent_name', parent.get('name'));
            }

            if (_.isEmpty(parent.get('id'))) {
                return;
            }

            if (_.isEmpty(parent.get('name')) && !app.utils.isNameErased(parent)) {
                // If the name field is empty but other fields are present,
                // then it may be possible to construct the name. We try to
                // do that before making an unnecessary request.
                name = app.utils.getRecordName(parent);

                if (!_.isEmpty(name)) {
                    // We were able to determine the name.
                    parent.set('name', name);
                    setAttributes(parent);
                } else {
                    // We need more data from this record.
                    parent.fetch({
                        showAlerts: false,
                        success: setAttributes,
                        fields: ['name']
                    });
                }
            } else {
                setAttributes(parent);
            }
        }

        /**
         * Populates an email with data from a case. This is used when
         * composing an email that is related to a case.
         *
         * The subject of the email becomes "[CASE:<case_number>] <case_name>".
         * Contacts related to the case are added as recipients in the To
         * field.
         *
         * @param {Data.Bean} email
         * @param {Data.Bean} aCase
         */
        function prepopulateEmailWithCase(email, aCase) {
            var config;
            var keyMacro = '%1';
            var caseMacro;
            var subject;
            var contacts;

            if (aCase.module !== 'Cases') {
                return;
            }

            config = app.metadata.getConfig();
            caseMacro = config.inboundEmailCaseSubjectMacro;
            subject = caseMacro + ' ' + aCase.get('name');
            subject = subject.replace(keyMacro, aCase.get('case_number'));

            email.set('name', subject);

            if (email.get('to_collection').length === 0) {
                // no addresses, attempt to populate from contacts relationship
                contacts = aCase.getRelatedCollection('contacts');
                contacts.fetch({
                    relate: true,
                    success: function(data) {
                        var records = data.map(function(record) {
                            var parentName = app.utils.getRecordName(record);

                            // Don't set email_address_id. It will be set when
                            // the email is archived.
                            return app.data.createBean('EmailParticipants', {
                                _link: 'to',
                                parent: _.extend({type: record.module}, app.utils.deepCopy(record.attributes)),
                                parent_type: record.module,
                                parent_id: record.get('id'),
                                parent_name: parentName
                            });
                        });

                        email.get('to_collection').add(records);
                    },
                    fields: ['id', 'full_name', 'email']
                });
            }
        }

        /**
         * Populates attributes on an email from the data provided.
         *
         * @param {Date.Bean} email
         * @param {Object} data Attributes to set on the email.
         * @param {string} [data.name] Sets the subject of the email.
         * @param {string} [data.description_html] Sets the HTML body of the
         * email.
         * @param {Array|Data.BeanCollection|Data.Bean} [data.from_collection]
         * The sender to add to the From field.
         * @param {Array|Data.BeanCollection|Data.Bean} [data.from] A more
         * convenient alternative name for the sender.
         * @param {Array|Data.BeanCollection|Data.Bean} [data.to_collection]
         * The recipients to add to the To field.
         * @param {Array|Data.BeanCollection|Data.Bean} [data.to] A more
         * convenient alternative name for the To recipients.
         * @param {Array|Data.BeanCollection|Data.Bean} [data.cc_collection]
         * The recipients to add to the CC field.
         * @param {Array|Data.BeanCollection|Data.Bean} [data.cc] A more
         * convenient alternative name for the CC recipients.
         * @param {Array|Data.BeanCollection|Data.Bean} [data.bcc_collection]
         * The recipients to add to the BCC field.
         * @param {Array|Data.BeanCollection|Data.Bean} [data.bcc] A more
         * convenient alternative name for the BCC recipients.
         * @param {Array|Data.BeanCollection|Data.Bean} [data.attachments_collection]
         * The attachments to attach to the email.
         * @param {Array|Data.BeanCollection|Data.Bean} [data.attachments] A
         * more convenient alternative name for the attachments.
         * @param {Data.Bean} [data.related] A convenience for relating a
         * parent record to the email.
         * @param {boolean} [data.skip_prepopulate_with_case] Prevent
         * prepopulating case data in the email.
         * @return {Object} Any key-values pairs that could not be assigned are
         * returned so the caller can decide what to do with them.
         */
        function prepopulateEmailForCreate(email, data) {
            var fields = app.metadata.getModule('Emails', 'fields');
            var fieldNames = _.keys(fields);
            var fieldMap = {
                attachments: 'attachments_collection',
                from: 'from_collection',
                to: 'to_collection',
                cc: 'cc_collection',
                bcc: 'bcc_collection'
            };
            var nonFieldData = {};

            _.each(data, function(value, fieldName) {
                var fieldDefs;

                fieldName = fieldMap[fieldName] || fieldName;
                fieldDefs = fields[fieldName] || {};

                if (fieldName === 'related') {
                    if (data.skip_prepopulate_with_case !== true) {
                        prepopulateEmailWithCase(email, value);
                    }

                    setParentAttributes(email, value);
                } else if (fieldDefs.type === 'collection') {
                    if (value instanceof app.BeanCollection) {
                        value = value.models;
                    }

                    email.get(fieldName).add(value);
                } else if (_.contains(fieldNames, fieldName)) {
                    email.set(fieldName, value);
                } else {
                    nonFieldData[fieldName] = value;
                }
            });

            return nonFieldData;
        }

        app.utils = _.extend(app.utils, {
            /**
             * If Forecasts is not setup, it will convert commit_stage into a spacer field and show no-data
             *
             * @param {Object} panels
             */
            hideForecastCommitStageField: function(panels) {
                var config = app.metadata.getModule('Forecasts', 'config'),
                    isSetup = (config && config.is_setup);
                if (!isSetup) {
                    _.each(panels, function(panel) {
                        // use _.every so we can break out after we found the commit_stage field
                        _.every(panel.fields, function(field, index) {
                            if (field.name == 'commit_stage') {
                                panel.fields[index] = {
                                    'name': 'spacer',
                                    'label': field.label,
                                    'span': 6,
                                    'readonly': true
                                };
                                return false;
                            }
                            return true;
                        }, this);
                    }, this);
                }
            },

            /**
             * Takes two Forecasts models and returns HTML for the history log
             *
             * @param {Backbone.Model} oldestModel the oldest model by date_entered
             * @param {Backbone.Model} newestModel the most recent model by date_entered
             * @param {boolean} isRepWorksheet Are we showing the history log on the rep worksheet?
             * @return {Object}
             */
            createHistoryLog: function(oldestModel, newestModel, isRepWorksheet) {
                var labels = [];

                if (_.isEmpty(oldestModel)) {
                    oldestModel = new Backbone.Model({
                        best_case: 0,
                        likely_case: 0,
                        worst_case: 0,
                        date_entered: ''
                    });
                    labels.push('LBL_COMMITTED_HISTORY_SETUP_FORECAST');
                } else {
                    labels.push('LBL_COMMITTED_HISTORY_UPDATED_FORECAST');
                }

                var fields = ['worst', 'likely', 'best'],
                    final = [],
                    num_shown = 0,
                    config = app.metadata.getModule('Forecasts', 'config'),
                    aclModule = (isRepWorksheet) ? config.forecast_by : newestModel.module;

                _.each(fields, function(field) {
                    var fieldName = field + '_case';
                    if (config['show_worksheet_' + field] && app.acl.hasAccess('view', aclModule, null, fieldName)) {
                        var diff = this.getDifference(oldestModel, newestModel, fieldName);
                        num_shown++;

                        if (diff != 0) {
                            var direction = this.getDirection(diff);
                            final.push(
                                this.gatherLangArgsByParams(
                                    direction,
                                    this.getArrowDirectionSpan(direction),
                                    diff,
                                    newestModel,
                                    fieldName
                                )
                            );
                            labels.push('LBL_COMMITTED_HISTORY_' + field.toUpperCase() + '_CHANGED');
                        } else {
                            final.push([]);
                            labels.push('LBL_COMMITTED_HISTORY_' + field.toUpperCase() + '_SAME');
                        }
                    }
                }, this);

                var lang_string_key = 'LBL_COMMITTED_HISTORY_' + num_shown + '_SHOWN',
                    hb = Handlebars.compile('{{{str key module args}}}');

                final = this.parseArgsAndLabels(final, labels);

                //Compile the language string for the log
                var text = hb({'key': lang_string_key, 'module': 'Forecasts', 'args': final});

                // need to tell Handlebars not to escape the string when it renders it, since there might be
                // html in the string, args returned for testing purposes
                return {
                    'text': new Handlebars.SafeString(text),
                    'text2': app.date(newestModel.get('date_modified')).formatUser(false, app.user)
                };
            },

            /**
             * Returns an array of three args for the html for the arrow,
             * the difference (amount changed), and the new value
             *
             * @param {String} dir direction of the arrow, LBL_UP/LBL_DOWN
             * @param {String} arrow HTML for the arrow string
             * @param {Number} diff difference between the new model and old model
             * @param {Backbone.Model} model the newestModel being used so we can get the current caseStr
             * @param {String} attrStr the attr string to get from the newest model
             * @return {Object}
             */
            gatherLangArgsByParams: function(dir, arrow, diff, model, attrStr) {
                return {
                    'direction' : new Handlebars.SafeString(app.lang.get(dir, 'Forecasts') + arrow),
                    'from' : app.currency.formatAmountLocale(Math.abs(diff)),
                    'to' : app.currency.formatAmountLocale(model.get(attrStr))
                };
            },

            /**
             * checks the direction class passed in to determine what span to create to show the appropriate arrow
             * or lack of arrow to display on the
             * @param {String} directionClass class being used for the label ('LBL_UP' or 'LBL_DOWN')
             * @return {String}
             */
            getArrowDirectionSpan: function(directionClass) {
                return directionClass == 'LBL_UP' ? '&nbsp;<i class="fa fa-arrow-up font-green"></i>' :
                    directionClass == 'LBL_DOWN' ? '&nbsp;<i class="fa fa-arrow-down font-red"></i>' : '';
            },

            /**
             * Centralizes our forecast type switch.
             *
             * @param {Boolean} isManager
             * @param {Boolean} showOpps
             * @return {String} 'Direct' or 'Rollup'
             */
            getForecastType: function(isManager, showOpps) {
                /**
                 * Three cases exist when a row is showing commitLog icon:
                 *
                 * Manager - showOpps=1 - isManager=1 => Mgr's Opportunities row - forecast_type = 'Direct'
                 * Manager - showOpps=0 - isManager=1 => Mgr has another mgr in their MgrWkst - forecast_type = 'Rollup'
                 * Rep     - showOpps=0 - isManager=0 => Sales Rep (not a manager) row - forecast_type = 'Direct'
                 */
                return (!showOpps && isManager) ? 'Rollup' : 'Direct';
            },

            /**
             * Parses through labels array and adds the proper args in to the string
             *
             * @param {Array} argsArray of args (direction arrow html, amount difference and the new amount)
             * @param {Array} labels of lang key labels to use
             * @return {Array}
             */
            parseArgsAndLabels: function(argsArray, labels) {
                var retArgs = {},
                    argsKeys = ['first', 'second', 'third'],
                    hb = Handlebars.compile('{{{str key module args}}}');

                // labels should have one more item in its array than argsArray
                // because of the SETUP or UPDATED label which has no args
                if ((argsArray.length + 1) != labels.length) {
                    // SOMETHING CRAAAAZY HAPPENED!
                    var msg = 'ForecastsUtils.parseArgsAndLabels() :: ' +
                            'argsArray and labels params are not the same length';
                    app.logger.error(msg);
                    return null;
                }

                // get the first argument off the label array
                retArgs.intro = hb({'key': _.first(labels), 'module': 'Forecasts', 'args': []});

                // get the other values, with out the first value
                labels = _.last(labels, labels.length - 1);

                // loop though all the other values
                _.each(labels, function(label, index) {
                    retArgs[argsKeys[index]] = hb({'key': label, 'module': 'Forecasts', 'args': argsArray[index]});
                });

                return retArgs;
            },

            /**
             * Returns the difference between the newest model and the oldest
             *
             * @param {Backbone.Model} oldModel
             * @param {Backbone.Model} newModel
             * @param {String} attr the attribute key to get from the models
             * @return {*}
             */
            getDifference: function(oldModel, newModel, attr) {
                return (app.math.isDifferentWithPrecision(newModel.get(attr), oldModel.get(attr))) ?
                    app.math.getDifference(newModel.get(attr), oldModel.get(attr)) : 0;
            },


            /**
             * Returns the proper direction label to use
             *
             * @param {Number} difference the amount of difference between newest and oldest models
             * @return {String} LBL_UP, LBL_DOWN, or ''
             */
            getDirection: function(difference) {
                return difference > 0 ? 'LBL_UP' : (difference < 0 ? 'LBL_DOWN' : '');
            },

            /**
             * Returns the subpanel list with link module name and corresponding LBL_
             *
             * @param {module} string
             * @return {Object} The subpanel list
             */
            getSubpanelList: function(module) {
                var list = {},
                    subpanels = app.metadata.getModule(module),
                    comps;

                // Get the subpanel metadata for layouts...
                if (subpanels && subpanels.layouts) {
                    subpanels = subpanels.layouts.subpanels;
                    if (subpanels && subpanels.meta) {
                        // If this module defines itself as using dynamic
                        // subpanels then let it do it's thing
                        if (subpanels.meta.dynamic) {
                            comps = this.getDynamicSubpanelList(module);
                            if (comps && comps.meta) {
                                comps = comps.meta.components || [];
                            }
                        } else {
                            // Otherwise use what we have in metadata
                            comps = subpanels.meta.components || [];
                        }
                    }
                }

                // If there are components, traverse them and get what is needed
                if (comps) {
                    _.each(comps, function(comp) {
                        if (comp.context && comp.context.link) {
                            list[comp.label] = comp.context.link;
                        } else {
                            app.logger.warning("Subpanel's subpanels.meta.components "
                                + "has component with no context or context.link");
                        }
                    });
                }

                return list;
            },

            /**
             * Gets a list of subpanel metadata for a module.
             * @return {object}
             */
            getDynamicSubpanelList: function(module) {
                var dSubpanels = this.getDynamicSubpanelMetadata(module);
                return {meta: dSubpanels};
            },

            /**
             * Gets dynamic subpanel metadata for a module from the relationships
             * for that module that are marked as dynamic subpanels
             * @return {object}
             */
            getDynamicSubpanelMetadata: function(module) {
                // Get our relationship metadata for validation
                var rels = app.metadata.get().relationships,
                    // Get the fields for the tags module to get the links from
                    fields = app.metadata.getModule(module).fields,
                    // Need the modules for validation as well
                    modules = app.metadata.getModules(),
                    // Declaring the components array for return
                    comps = [],
                    // Because javascript
                    self = this;

                // Loop over each field and get the link fields from the field def
                _.each(fields, function(fDefs, fName) {
                    var relModule,
                        relDefName,
                        spLabel;

                    // If this field is a link type and there is a relationship for it...
                    if (fDefs.type && fDefs.type === 'link' && fDefs.relationship) {
                        relDefName = fDefs.relationship;
                        // If the relationship exists and supports dynamic subpanels...
                        if (self.hasDynamicSubpanelDef(rels, relDefName, module)) {
                            relModule = rels[relDefName].lhs_module;
                            // And the left side module is in the module list...
                            if (modules[relModule]) {
                                // Add handling for modules whose subpanel layouts are not just 'subpanel'
                                var layoutName = 'subpanel';
                                if (modules[relModule].additionalProperties
                                    && modules[relModule].additionalProperties.dynamic_subpanel_name) {
                                    layoutName = modules[relModule].additionalProperties.dynamic_subpanel_name;
                                }
                                // Add it to the components array
                                comps.push({
                                    context: {link: fDefs.name},
                                    label: self.getDynamicSubpanelLabel(module, relModule),
                                    layout: layoutName
                                });
                            }
                        }
                    }
                });

                return {components: comps};
            },

            /**
             * Gets the label for the Subpanel from the module or related module
             *
             * @param {string} module The parent module
             * @param {string} relModule The relate module
             * @return {string} The label for the subpanel
             */
            getDynamicSubpanelLabel: function(module, relModule) {
                // Define the label index we need to check for a named module
                var lIndex = 'LBL_' + relModule.toUpperCase() + '_SUBPANEL_TITLE';
                // Definitions to apply in order to find the label
                var stackDef = [
                    // Start with the default subpanel title for the related module first to pick
                    // up renamed module names
                    {key: 'LBL_DEFAULT_SUBPANEL_TITLE', mod: relModule},

                    // Try the parent module to see any hardcoded subpanel titles
                    {key: lIndex, mod: module},

                    // Try getting the label from the related module
                    {key: lIndex, mod: relModule},

                    // Lastly, try getting the label from the related module name
                    {key: 'LBL_MODULE_NAME', mod: relModule}
                ];
                // The label to return
                var label;

                // Loop through stackDef and break if we find a label
                for (var i in stackDef) {
                    if (label = app.lang.getModString(stackDef[i].key, stackDef[i].mod)) {
                        return label;
                    }
                }

                // If we couldn't find a label, return the related module
                return relModule;
            },

            /**
             * Checks if a relationship def supports dynamic subpanel loading
             *
             * @param {object} rels The relationship collection
             * @param {string} relDefName The name of the current relationship
             * @param {string} module The module to check against
             * @return {Boolean}
             */
            hasDynamicSubpanelDef: function(rels, relDefName, module) {
                return rels[relDefName]
                       && rels[relDefName].dynamic_subpanel
                       && rels[relDefName].lhs_module
                       && rels[relDefName].rhs_module
                       && rels[relDefName].rhs_module === module;
            },

            /**
             * Returns TRUE if any of the related fields associated with this link are required,
             * which would make this link required.  Returns FALSE otherwise.
             *
             * @param {String} module Parent module name
             * @param {String} link Link name
             * @return {Boolean}
             */
            isRequiredLink: function(module, link){
                var relatedFields = app.data.getRelateFields(module, link),
                    requiredField = _.some(relatedFields, function(field){
                        return field.required === true;
                    }, this);
                return requiredField;
            },

            /**
             * Contains a list of column names from metadata and maps them to correct config param
             * e.g. 'likely_case' column is controlled by the Forecast config.show_worksheet_likely param
             * Used by forecastsWorksheetManager, forecastsWorksheetManagerTotals
             *
             * @property tableColumnsConfigKeyMapManager
             * @private
             */
            _tableColumnsConfigKeyMapManager: {
                'likely_case': 'show_worksheet_likely',
                'likely_case_adjusted': 'show_worksheet_likely',
                'best_case': 'show_worksheet_best',
                'best_case_adjusted': 'show_worksheet_best',
                'worst_case': 'show_worksheet_worst',
                'worst_case_adjusted': 'show_worksheet_worst'
            },

            /**
             * Contains a list of column names from metadata and maps them to correct config param
             * e.g. 'likely_case' column is controlled by the Forecast config.show_worksheet_likely param
             * Used by forecastsWorksheet, forecastsWorksheetTotals
             *
             * @property tableColumnsConfigKeyMapRep
             * @private
             */
            _tableColumnsConfigKeyMapRep: {
                'likely_case': 'show_worksheet_likely',
                'best_case': 'show_worksheet_best',
                'worst_case': 'show_worksheet_worst'
            },

            /**
             * Function checks the proper _tableColumnsConfigKeyMap___ for the key and returns the config setting
             *
             * @param key {String} table key name (eg: 'likely_case')
             * @param viewName {String} the name of the view calling the function (eg: 'forecastsWorksheet')
             * @return {*}
             */
            getColumnVisFromKeyMap: function(key, viewName) {
                var moduleMap = {
                        'forecastsWorksheet': 'rep',
                        'forecastsWorksheetTotals': 'rep',
                        'forecastsWorksheetManager': 'mgr',
                        'forecastsWorksheetManagerTotals': 'mgr'
                    },
                    whichKeyMap,
                    keyMap,
                    returnValue;

                // which key map to use from the moduleMap
                whichKeyMap = moduleMap[viewName];

                // get the proper keymap
                keyMap = (whichKeyMap === 'rep') ? this._tableColumnsConfigKeyMapRep
                                                 : this._tableColumnsConfigKeyMapManager;

                returnValue = app.metadata.getModule('Forecasts', 'config')[keyMap[key]];
                // If we've been passed a value that doesn't exist in the keymaps
                if(!_.isUndefined(returnValue)) {
                    // convert it to boolean
                    returnValue = returnValue == 1
                } else {
                    // if return value was null (not found) then set to true
                    returnValue = true;
                }
                return returnValue;
            },

            /**
             * If the passed in User is a Manager, then get his direct reportees, and then set the user
             * on the context, if they are not a Manager, just set user to the context
             * @param selectedUser
             * @param context
             */
            getSelectedUsersReportees: function(selectedUser, context) {
                if(selectedUser.is_manager) {
                    // make sure the reportee's array is there
                    if(_.isUndefined(selectedUser.reportees)) {
                        selectedUser.reportees = [];
                    }
                    var url = app.api.buildURL('Users', 'filter'),
                        post_args = {
                            "filter": [
                                {
                                    'reports_to_id': selectedUser.id,
                                    'status' : 'Active'
                                }
                            ],
                            'fields': 'full_name',
                            'max_num': 1000
                        },
                        options = {};
                    options.success = _.bind(function(resp, status, xhr) {
                        _.each(resp.records, function(user) {
                            selectedUser.reportees.push({id: user.id, name: user.full_name});
                        });
                        this.set("selectedUser", selectedUser)
                    }, context);
                    app.api.call("create", url, post_args, options);
                } else {
                    // update context with selected user which will trigger checkRender
                    context.set("selectedUser", selectedUser);
                }
            },

            /**
             * Makes sure that Sales Stage Won/Lost values from the database Forecasts config settings
             * exist in the sales_stage_dom
             *
             * @returns {Boolean} if forecasts is configured to run ok
             */
            checkForecastConfig: function() {
                var forecastConfigOK = true,
                    cfg = app.metadata.getModule('Forecasts', 'config') || {},
                    salesWonVals = cfg.sales_stage_won,
                    salesLostVals = cfg.sales_stage_lost,
                    salesWonLostVals = cfg.sales_stage_won.concat(cfg.sales_stage_lost),
                    domVals = app.lang.getAppListStrings('sales_stage_dom');

                if(salesWonVals.length == 0 || salesLostVals.length == 0 || _.isEmpty(domVals)) {
                    forecastConfigOK = false;
                } else {
                    forecastConfigOK = _.every(salesWonLostVals, function(val) {
                        return (val != '' && _.has(domVals, val));
                    }, this);
                }

                return forecastConfigOK;
            },

            /**
             * Returns if the current browser has touch events
             *
             * @return {boolean}
             */
            isTouchDevice: function() {
                return Modernizr.touch;
            },

            /**
             * Builds a route for module in either bwc or new sidecar.
             *
             * This overrides the normal router to check first if the module
             * is in BWC or not. If not, this will fallback to default
             * {@link Core.Routing#buildRoute}.
             *
             * @inheritdoc
             * @param {Boolean} inBwc If `true` it will force bwc, if `false`
             * it will force sidecar, if not defined, will use metadata
             * information on module. This is a temporary param (hack) and will
             * be removed after we change all the views/layouts to be the ones
             * pointing if it should be loaded in BWC or not.
             *
             * @override Core.Routing#buildRoute
             * @see Bwc#buildRoute()
             */
            customBuildRoute: function(moduleOrContext, id, action, inBwc) {
                var module, moduleMeta;

                // Since _.isString(undefined) returns false,
                // the following block prevent going getter block
                if (_.isEmpty(moduleOrContext)) {
                    return '';
                }

                if (_.isString(moduleOrContext)) {
                    module = moduleOrContext;
                } else {
                    module = moduleOrContext.get('module');
                }

                if (_.isEmpty(module) || !app.bwc) {
                    return '';
                }

                moduleMeta = app.metadata.getModule(module) || {};
                if (inBwc === false || (_.isUndefined(inBwc) && !moduleMeta.isBwcEnabled)) {
                    return '';
                }

                return app.bwc.buildRoute(module, id, app.bwc.getAction(action));
            },

            /**
             * Adds `bwcFrame=1` to the URL if it's not there.
             *
             * @param {string} url The original url.
             * @return {string} The url with the iframe mark.
             */
            addIframeMark: function(url) {
                var parts = url.split('?');
                if (parts[1] && parts[1].indexOf('bwcFrame=1') != -1) return url;
                return parts[0] + '?' + (parts[1] ? parts[1] + '&bwcFrame=1' : 'bwcFrame=1');
            },

            /**
             * Removes `bwcFrame=1` from the URL if it's there.
             *
             * @param {string} url The original url.
             * @return {string} The url without the iframe mark.
             */
            rmIframeMark: function(url) {
                var parts = url.split('?');
                if (!parts[1]) {
                    return url;
                }
                // scan and drop bwcFrame=1
                return parts[0] + '?' + _.reduce(parts[1].split('&'), function(acc, item) {
                    if (item == 'bwcFrame=1') {
                        return acc;
                    } else {
                        return acc ? acc + '&' + item : item;
                    }
                }, '');
            },

            /**
             * Returns a collection of subpanel models from the LHS context
             * Only tested in Record View!
             *
             * @param ctx the LHS context
             * @param {String} module the name of the module to look for
             * @returns {*} returns the collection or undefined
             */
            getSubpanelCollection: function(ctx, module) {
                var retCollection = undefined,
                    mdl = _.find(ctx.children, function(child) {
                        return (child.get('module') == module);
                    });
                if(mdl && _.has(mdl.attributes, 'collection')) {
                    retCollection = mdl.get('collection');
                }

                return retCollection;
            },
            /**
             * Extracts the full record name from a model.
             *
             * @param {Data.Bean} model The model concerned.
             * @return {string} The record name.
             */
            getRecordName: function(model) {
                var metadata = app.metadata.getModule(model.module) || {};
                var format;
                var name;

                if (!_.isEmpty(metadata.nameFormat)) {
                    // Defaulting the format avoids having to fix numerous
                    // tests because the preference isn't set.
                    format = app.user.getPreference('default_locale_name_format') || 's f l';
                    name = app.utils.formatNameModel(model.module, model.attributes, format);
                } else if (model.module === 'Documents' && model.has('document_name')) {
                    name = model.get('document_name');
                }

                return name || model.get('full_name') || model.get('name') || '';
            },

            /**
             * Returns the first email address that matches the options or an
             * empty string if none exist.
             *
             * An assumption is made that the associated email addresses will
             * be found as an array on the model's `email` attribute.
             *
             * @param {Data.Bean} model
             * @param {Object} [options]
             * @param {Boolean} [options.primary_address]
             * @param {Boolean} [options.invalid_email]
             * @param {Boolean} [options.opt_out]
             * @return {string}
             */
            getEmailAddress: function(model, options) {
                var addresses;

                options || (options = {});
                addresses = model.get('email');

                return _.chain(addresses).findWhere(options).pick('email_address').values().first().value() || '';
            },

            /**
             * Returns the first valid email address associated with the model
             * or an empty string if none exist.
             *
             * An email address is considered valid if its `invalid_email`
             * property is `false`. The email address with `true`
             * for the `primary_address` property is tested first.
             *
             * This method is implemented to simulate the server-side method
             * SugarEmailAddress::getPrimaryAddress().
             *
             * @param {Data.Bean} model
             * @return {string}
             */
            getPrimaryEmailAddress: function(model) {
                return app.utils.getEmailAddress(model, {primary_address: true, invalid_email: false}) ||
                    app.utils.getEmailAddress(model, {invalid_email: false});
            },

            /**
             * Resolve data conflict on bean update. Open a drawer to pick between data in the database
             * or the data currently in the bean.
             * @param error
             * @param model
             * @param callback
             */
            resolve409Conflict: function(error, model, callback) {
                app.drawer.open({
                    layout: 'resolve-conflicts',
                    context: {
                        dataInDb: error.payload.record,
                        modelToSave: model
                    }
                }, callback);
            },

            /**
             * Returns the Forecasts not setup message with link to config if isAdmin is true
             *
             * @param {boolean} isAdmin is the user an admin
             * @returns {string} a translated string with a link to Forecasts config if user is an admin
             */
            getForecastNotSetUpMessage: function(isAdmin) {
                var langKey = (isAdmin) ? 'LBL_DASHLET_FORECAST_NOT_SETUP_ADMIN' : 'LBL_DASHLET_FORECAST_NOT_SETUP',
                    msg = app.lang.get(langKey, 'Forecasts');
                if(isAdmin) {
                    var linkText = app.lang.get('LBL_DASHLET_FORECAST_CONFIG_LINK_TEXT', 'Forecasts');
                    msg += '  <a href="#Forecasts/config">' + linkText + '</a>';
                }

                return msg;
            },

            /**
             * Convert a raw file size into a human readable size
             *
             * @param {int} size
             * @return {string}
             */
            getReadableFileSize: function(size) {
                var i = -1;
                var units = ['K', 'M', 'G', 'T'];

                if (_.isEmptyValue(size) || size < 1) {
                    size = 0;
                } else if (size < 1000) {
                    //Not displaying bytes, round up to 1K
                    size = 1000;
                }

                do {
                    size = Math.round(size / 1000);
                    i++;
                } while (size >= 1000 && i < (units.length - 1));

                return size + units[i];
            },

            /**
             * gets window location parameters by name regardless of case
             * @param {String} name name of parameter being searched for
             * @param {String} queryString
             * @returns {String}
             */
            getWindowLocationParameterByName: function (name, queryString) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)', 'g'),
                    matchResults = queryString.match(regex);
                if (matchResults && matchResults.length > 0) {
                    var results = regex.exec(matchResults[matchResults.length - 1]);
                }
                return (results === undefined || results === null) ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            },

            /**
             * Javascript equivalent to the Sugar `isTruthy` utility function.
             *
             * @param {string|boolean} value The value to test.
             * @return {boolean} `true` if the value to test is roughly
             *   equivalent to `true` (in a Sugar context).
             */
            isTruthy: function(value) {
                if (_.isString(value)) {
                    value = value.toLowerCase();
                }
                return value === true
                    || value === 'true'
                    || value === 1
                    || value === '1'
                    || value === 'on'
                    || value === 'yes';
            },

            /**
             * Returns the set of fields on a model that are components of the name field.
             * @param {app.data.Bean} model
             * @param {string} (optional) format "s f l t" style name format string.
             * @return Array names of fields that make up the user formated name for this model.
             */
            getFullnameFieldComponents: function(model, format) {
                var components = [];
                var nameDef = model.fields.name;

                if (!nameDef) {
                    return components;
                }

                if (nameDef.type === 'fullname') {
                    format = format || app.user.getPreference('default_locale_name_format');
                    var metadata = app.metadata.getModule(model.module) || {fields: {}};
                    var formatMap = metadata.nameFormat || {};

                    if (_.isEmpty(formatMap)) {
                        return ['name'];
                    }
                    _.each(format.split(''), function(letter) {
                        if (formatMap[letter]) {
                            components.push(formatMap[letter]);
                        }
                    });

                    return components;
                } else if (nameDef.fields) {
                    return nameDef.fields;
                }

                return ['name'];
            },

            /**
             * Returns true if the name or fields that make up the name have been erased
             * and all name components are empty
             * @param model
             * @return {boolean}
             */
            isNameErased: function(model) {
                var erasedFields = model.get('_erased_fields');

                if (_.isEmpty(erasedFields)) {
                    return false;
                }

                //Otherwise check for the name/fullname components
                var nameComponents = this.getFullnameFieldComponents(model);
                var anyNameFieldErased =  _.some(nameComponents, function(field) {
                    return _.contains(erasedFields, field);
                });

                //Ensure that the name is empty before marking it erased
                return anyNameFieldErased && _.every(nameComponents.concat('name'), function(field) {
                    return !model.get(field);
                });
            },

            /**
             * Populates the data on the email and then opens the specified
             * Emails layout in a drawer.
             *
             * @param {string} layout Use `create` for creating an archived
             * email and `compose-email` for creating a draft/sending an email.
             * @param {Object} [data] Any data to populate on the model before
             * opening the drawer. The keys `create` and `module` are reserved.
             * Any key-value pairs that can't be populated on the model will
             * become attributes on the drawer's context.
             * @param {Data.Bean} [data.model] Can be used to specify a model
             * for the drawer's context. The model must be an Emails model. If
             * not specified, a new Emails model will be created for the
             * context and the data will be populated on that new model.
             * @param {Function} [onClose] The callback that is called when the
             * drawer closes.
             */
            openEmailCreateDrawer: function(layout, data, onClose) {
                var context = {
                    create: true,
                    module: 'Emails'
                };

                data = data || {};

                if (data.model && data.model instanceof app.Bean && data.model.module === context.module) {
                    // Use the provided model for the context.
                    context.model = data.model;
                } else {
                    // Create a new model for the context.
                    context.model = app.data.createBean(context.module);
                }

                // We're done with the provided model. Remove it so nothing
                // funky happens while populating the email.
                data = _.omit(data, 'model');

                // Populate the email with the rest of the data. Any data that
                // can't be mapped to an Emails field will be returned so it
                // can become an attribute on the context.
                data = prepopulateEmailForCreate(context.model, data);

                // Merge context over the remaining data so that it is
                // impossible for anyone to override `create` and `module`.
                context = _.extend(data, context);

                app.drawer.open({
                    layout: layout,
                    context: context
                }, onClose);
            },

            /**
             * Get all of the links on the primary module that relate records
             * to the related module.
             *
             * @param {string} primaryModule The module with the link fields.
             * @param {string} relatedModule The module on the other side of
             * the link fields we care about.
             * @return {Array}
             */
            getLinksBetweenModules: function(primaryModule, relatedModule) {
                var fields = app.metadata.getModule(primaryModule).fields;

                return _.chain(fields).filter(function(field) {
                    return field.type && field.type === 'link';
                }).filter(function(link) {
                    return app.data.getRelatedModule(primaryModule, link.name) === relatedModule;
                }, this).value();
            }
        });
    });
})(SUGAR.App);
