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
 * @class View.Views.Base.MassupdateView
 * @alias SUGAR.App.view.views.BaseMassupdateView
 * @extends View.View
 */
({
    events: {
        'click [data-action="add"]' : 'addItem',
        'click [data-action="remove"]' : 'removeItem',
        'click .btn[name=update_button]' : 'saveClicked',
        'click .btn.cancel_button' : 'cancelClicked'
    },
    visible: false,
    fieldOptions: null,
    fieldValues: null,
    defaultOption: null,
    fieldPlaceHolderTag: '[name=fieldPlaceHolder]',
    massUpdateViewName: 'massupdate-progress',
    className: 'extend',

    /**
     * Default settings used when none are supplied through metadata.
     *
     * Supported settings:
     * - {Number} mass_delete_chunk_size Number of records per chunk while
     *   performing mass delete.
     * - {Number} mass_update_chunk_size Number of records per chunk while
     *   performing mass update.
     * - {Number} max_records_to_merge Default number of records we can merge.
     *
     * Example:
     * <pre><code>
     * // ...
     * 'settings' => array(
     *     'mass_delete_chunk_size' => 20,
     *     'mass_update_chunk_size' => 20,
     *     'max_records_to_merge' => 5,
     *     //...
     * ),
     * //...
     * </code></pre>
     *
     * If 'mass_delete_chunk_size' or 'mass_update_chunk_size' aren't supplied
     * default values fallback to 'app.config.massDeleteChunkSize' and
     * 'app.config.massUpdateChunkSize' respectively.
     *
     * @property {Object}
     * @protected
     */
    _defaultSettings: {
        max_records_to_merge: 5,
        mass_delete_chunk_size: 20,
        mass_update_chunk_size: 500
    },

    /**
     * @inheritdoc
     *
     * Try to find the `massupdate` template
     * falls back to `edit` when it does not exist
     */
    fallbackFieldTemplate: 'edit',

    /**
     * @inheritdoc
     * Retrieves metadata from sugarTemplate and then able to override it from
     * the core metadata. `panels` will only be supported on the core metadata.
     *
     * Each module can override the massupdate default metadata definitions.
     * To do this, please use the following path:
     * `{custom/,}modules/{module}/clients/{platform}/view/massupdate/massupdate.php`
     *
     * Core massupdate metadata (non-module based) doesn't support the `panels` and
     * `fields` properties, since we don't support generic default fields to be added.
     * Keep that in mind when defining your metadata on:
     * `{custom/,}clients/{platform}/view/massupdate/massupdate.php`
     */
    initialize: function(options) {
        var genericMeta = _.omit(app.metadata.getView(null, options.name), 'panels');
        options.meta = _.extend(
            {},
            genericMeta,
            options.meta
        );
        this.fieldValues = [{}];
        this.setMetadata(options);

        // FIXME massupdate shouldn't mess with the main context (should have own context)
        // this is a hack to prevent the massupdate fields to be added to the
        // context
        this.dataView = true;

        this._super('initialize', [options]);
        this._initSettings();

        this.setDefault();

        this.delegateListFireEvents();
        this.before('render', this.isVisible);

        //event register for preventing actions
        // when user escapes the page without confirming deleting
        app.routing.before("route", this.beforeRouteDelete, this);
        $(window).on("beforeunload.delete" + this.cid, _.bind(this.warnDeleteOnRefresh, this));
    },

    /**
     * Initialize settings, default settings are used when none are supplied
     * through metadata.
     *
     * @return {View.Views.BaseMassupdateView} Instance of this view.
     * @protected
     */
    _initSettings: function() {

        var configSettings = {};
        if (app.config.massActions) {
            if (app.config.massActions.massDeleteChunkSize) {
                configSettings.mass_delete_chunk_size = app.config.massActions.massDeleteChunkSize;
            }
            if (app.config.massActions.massUpdateChunkSize) {
                configSettings.mass_update_chunk_size = app.config.massActions.massUpdateChunkSize;
            }
        }

        this._settings = _.extend(
            {},
            this._defaultSettings,
            configSettings,
            this.meta && this.meta.settings || {}
        );

        return this;
    },

    delegateListFireEvents: function() {
        this.layout.on("list:massupdate:fire", this.show, this);
        this.layout.on("list:massaction:hide", this.hide, this);
        this.layout.on("list:massdelete:fire", this.warnDelete, this);
        this.layout.on("list:massexport:fire", this.massExport, this);
        this.layout.on("list:updatecalcfields:fire", this.updateCalcFields, this);
    },

    /**
     * Filter and patch the mass update fields.
     *
     * If the view definition contains an empty list of fields, it will pull all
     * the fields from the module metadata and add those with `massupdate = true`
     *
     * @param {Object} options The options object passed in 'initialize'.
     * @deprecated This will be removed on future versions.
     * Please see {@link view.fields.BaseBoolField} how you should define
     * your fields to be rendered on with a massupdate template.
     */
    //FIXME: remove this method when SC-2554 is implemented
    setMetadata: function(options) {
        var fieldList,
            massFields = [],
            metadataModule = app.metadata.getModule(options.module);
        if (!metadataModule) {
            app.logger.error('Failed to get module ' + options.module);
            return;
        }
        options.meta.panels = options.meta.panels || [{fields: []}];
        fieldList = metadataModule.fields;

        if (!_.isEmpty(options.meta.panels[0].fields)) {
            fieldList = _.map(options.meta.panels[0].fields, function(fieldDef) {
                var def = _.extend({}, fieldList[fieldDef.name], fieldDef);
                return def;
            });
        }
        _.each(fieldList, function(field) {
            // Only fields that are marked with massupdate set to true AND
            // that are not readonly should be used
            if (!field.massupdate || field.readonly) {
                return;
            }

            //we clone the metadata definition
            //to make sure we don't change the original metadata
            //FIXME we should not be faking metadata - (SC-2554)
            var cloneField = app.utils.deepCopy(field);
            cloneField.label = cloneField.label || cloneField.vname;
            if (cloneField.type === 'multienum') {
                cloneField.type = 'enum';
            }
            massFields.push(cloneField);
        });
        options.meta.panels[0].fields = massFields;
    },

    _render: function() {
        var result = app.view.View.prototype._render.call(this),
            self = this;

        if (this.$(".select2.mu_attribute")) {
            this.$(".select2.mu_attribute")
                .select2({
                    width: '100%',
                    minimumResultsForSearch: 5
                })
                .on("change", function(evt) {
                    var $el = $(this),
                        name = $el.select2('val'),
                        index = $el.data('index');
                    var option = _.find(self.fieldOptions, function(field){
                        return field.name == name;
                    });
                    self.replaceUpdateField(option, index);
                    self.placeField($el);
                });
            this.$(".select2.mu_attribute").each(function(){
                self.placeField($(this));
            });
        }

        if(this.fields.length == 0) {
            this.hide();
        }
        return result;
    },
    isVisible: function() {
        return this.visible;
    },
    placeField: function($el) {
        var name = $el.select2('val'),
            index = $el.data('index'),
            fieldEl = this.getField(name).$el;

        if($el.not(".disabled") && fieldEl) {
            var holder = this.$(this.fieldPlaceHolderTag + "[index=" + index + "]");
            this.$("#fieldPlaceHolders").append(holder.children());
            holder.html(fieldEl);
        }
    },
    addItem: function(evt) {
        if(!$(evt.currentTarget).hasClass("disabled")) {
            this.addUpdateField();
            // this will not be called in an async process so no need to
            // check for the view to be disposed
            this.render();
        }
    },
    removeItem: function(evt) {
        var index = $(evt.currentTarget).data('index');
        this.removeUpdateField(index);
        // this will not be called in an async process so no need to
        // check for the view to be disposed
        this.render();
    },
    addUpdateField: function() {
        this.fieldValues.splice(this.fieldValues.length - 1, 0, this.defaultOption);
        this.defaultOption = null;
        this.setDefault();
    },

    /**
     * Removes the field value at the provided index.
     *
     * @param {integer} index
     */
    removeUpdateField: function(index) {
        var fieldValue = this.fieldValues[index];

        if (_.isUndefined(fieldValue)) {
            return;
        }
        // If the fieldValue has a name, we need to remove it from the model and
        // the fieldValues object.
        if (fieldValue.name) {
            this.model.unset(fieldValue.name);
            // For relate fields, we need to clear fieldValue.id_name.
            // Note that if fieldValue.id_name is undefined, this is still safe.
            this.model.unset(fieldValue.id_name);
            this.fieldValues.splice(index, 1);
        // If the fieldValue does not have a name, reset the default option to
        // the last item, which should be empty
        } else {
            var removed = this.fieldValues.splice(index - 1, 1);
            this.defaultOption = removed[0];
        }

        // If there is a populate_list (i.e. this is a relate field)
        // clear the related data.
        // Fixme: This should be cleaned up on the relate field. See TY-651
        if (!_.isUndefined(fieldValue.populate_list)) {
            _.each(fieldValue.populate_list, function(key) {
                this.model.unset(key);
            }, this);
        }
        this.setDefault();
    },

    replaceUpdateField: function(selectedOption, targetIndex) {
        var fieldValue = this.fieldValues[targetIndex];

        if(fieldValue.name) {
            this.model.unset(fieldValue.name);
            this.fieldOptions.push(fieldValue);
            this.fieldValues[targetIndex] = selectedOption;
        } else {
            this.model.unset(this.defaultOption.name);
            this.fieldOptions.push(this.defaultOption);
            this.defaultOption = selectedOption;
        }
    },
    setDefault: function() {
        var assignedValues = _.pluck(this.fieldValues, 'name');
        if(this.defaultOption) {
            assignedValues = assignedValues.concat(this.defaultOption.name);
        }
        //remove the attribute options that has been already assigned
        this.fieldOptions = (this.meta) ? _.reject(_.flatten(_.pluck(this.meta.panels, 'fields')), function(field){
            return (field) ? _.contains(assignedValues, field.name) : false;
        }) : [];
        //set first item as default
        this.defaultOption = this.defaultOption || this.fieldOptions.splice(0, 1)[0];
    },

    /**
     * Create the Progress view unless it is initialized.
     * Return the progress view component in the same layout.
     *
     * @return {Backbone.View} MassupdateProgress view component.
     */
    getProgressView: function() {
        var progressView = this.layout.getComponent(this.massUpdateViewName);
        if (!progressView) {
            progressView = app.view.createView({
                context: this.context,
                type: this.massUpdateViewName,
                layout: this.layout
            });
            this.layout._components.push(progressView);
            this.layout.$el.append(progressView.$el);
        }
        progressView.render();
        return progressView;
    },

    /**
     * Create massupdate collection against the parent module.
     * Design the model synchronizing progressively.
     *
     * @param {String} baseModule parent module name.
     * @return {Backbone.Collection} Massupdate collection.
     */
    getMassUpdateModel: function(baseModule) {
        var massModel = this.context.get('mass_collection'),
            progressView = this.getProgressView(),
            massCollection = massModel ? _.extend({}, massModel, {
                defaultMethod: 'update',
                module: 'MassUpdate',
                baseModule: baseModule,

                /**
                 * Maximum number of retrial attempt.
                 *
                 * @property
                 */
                maxAllowAttempt: 3,

                /**
                 * Chunk for each execution.
                 *
                 * @property
                 */
                chunks: null,

                /**
                 * Discarded records due to the permission.
                 *
                 * @property
                 */
                discards: [],

                /**
                 * Current trial attempt number.
                 *
                 * @property
                 */
                attempt: 0,

                /**
                 * Pause status.
                 * If current job is on progress,
                 * the next queue will be paused.
                 *
                 * @property
                 */
                paused: false,

                /**
                 * Number of records per chunk, defaults to '20'.
                 *
                 * @property {Number} chunkSize Number of records.
                 * @protected
                 */
                 _chunkSize: 20,

                /**
                 * Number of update failures
                 *
                 * @property {Number} numFailures Number of failures.
                 * @protected
                 */
                 numFailures: 0,

                /**
                 * Set number of records per chunk.
                 *
                 * @param {Number} chunkSize Number of records.
                 */
                setChunkSize: function(chunkSize) {
                    this._chunkSize = parseInt(chunkSize, 10);
                },

                /**
                 * Reset mass job.
                 */
                resetProgress: function() {
                    massModel.reset();
                    this.length = 0;
                },

                /**
                 * Update current progress job.
                 */
                updateProgress: function() {
                    this.remove(this.chunks.splice(0));
                    massModel.length = this.length;
                },

                /**
                 * Update the next chunk queue.
                 */
                updateChunk: function() {
                    if (!this.chunks) {
                        this.chunks = this.slice(0, this._chunkSize);
                        this.trigger('massupdate:start');
                    }
                    if (_.isEmpty(this.chunks)) {
                        this.chunks = this.slice(0, this._chunkSize);
                    }
                },

                /**
                 * Resume the job from the previous paused status.
                 */
                resumeFetch: function() {
                    if (!this._pauseOptions) {
                        return;
                    }
                    this.paused = false;
                    this.trigger('massupdate:resume');
                    this.fetch(this._pauseOptions);
                },

                /**
                 * Request pausing mass job.
                 */
                pauseFetch: function() {
                    this.paused = true;
                },

                /**
                 * @inheritdoc
                 * Instead of fetching entire set,
                 * split entire set into small chunks
                 * and repeat fetching until entire set is completed.
                 */
                sync: function(default_method, model, options) {
                    if (model.paused) {
                        this._pauseOptions = options;
                        this.trigger('massupdate:pause');
                        return;
                    }
                    this.method = options.method;

                    //split set into chunks.
                    this.updateChunk();
                    var callbacks = {
                            success: function(data, response) {
                                model.numFailures += data.failed;
                                model.updateProgress();
                                model.trigger('massupdate:done');
                                if (model.length === 0) {
                                    model.trigger('massupdate:end');
                                    if (_.isFunction(options.success)) {
                                        //setting data to null since backbone reset will add the data object to the collection
                                        //using the respoonse as options for callback
                                        options.status = response.status;
                                        options.success();
                                    }
                                } else {
                                    model.fetch(options);
                                }
                            },
                            error: function(xhr) {
                                model.attempt++;
                                model.trigger('massupdate:fail');
                                if (model.attempt <= model.maxAllowAttempt) {
                                    model.fetch(options);
                                } else if (_.isFunction(options.error)) {
                                    model.trigger('massupdate:end');
                                    options.error(xhr);
                                }
                            },
                            complete: function(xhr) {
                                model.trigger('massupdate:always');
                                if (_.isFunction(options.complete)) {
                                    options.complete(xhr);
                                }
                            }
                    };
                    var method = options.method || this.defaultMethod;
                    var data = this.getAttributes(options.attributes, method);

                    if (_.isEmpty(data.massupdate_params.uid)) {
                        // No records to update, end the mass update.
                        model.trigger('massupdate:end');
                        return;
                    }
                    var url = app.api.buildURL(baseModule, this.module, data, options.params);
                    app.api.call(method, url, data, callbacks);
                },

                /**
                 * Convert collection attributes into MassUpdate API format.
                 * @param {Object} attributes Collection attributes.
                 * @return {Object} MassUpdate data format.
                 */
                getAttributes: function(attributes, action) {
                    return {
                        massupdate_params: _.extend({
                            'uid': this.getAvailableList(action)
                        }, attributes)
                    };
                },

                /**
                 * Check the access role for entire selection.
                 * Return only available model ids and store the discarded ids.
                 *
                 * @param action
                 * @return {Array} List of available model ids.
                 */
                getAvailableList: function(action) {
                    var action2permission = {
                            'update': 'edit',
                            'delete': 'delete'
                        },
                        list = [];
                    _.each(this.chunks, function(model) {
                        if (app.acl.hasAccessToModel(action2permission[action], model) !== false) {
                            list.push(model.id);
                        } else {
                            this.discards.push(model.id);
                        }
                    }, this);
                    return list;
                }
            }) : null;
        progressView.initCollection(massCollection);
        return massCollection;
    },

    /**
     * Popup dialog message to confirm delete action
     */
    warnDelete: function() {
        this._modelsToDelete = this.getMassUpdateModel(this.module);
        this._modelsToDelete.setChunkSize(this._settings.mass_delete_chunk_size);

        this._targetUrl = Backbone.history.getFragment();
        //Replace the url hash back to the current staying page
        if (this._targetUrl !== this._currentUrl) {
            app.router.navigate(this._currentUrl, {trigger: false, replace: true});
        }

        this.hideAll();

        app.alert.show('delete_confirmation', {
            level: 'confirmation',
            messages: app.lang.get('NTC_DELETE_CONFIRMATION_MULTIPLE', this.module),
            onConfirm: _.bind(this.deleteModels, this),
            onCancel: _.bind(function() {
                app.analytics.trackEvent('click', 'mass_delete_cancel');
                this._modelsToDelete = null;
                app.router.navigate(this._targetUrl, {trigger: false, replace: true});
            }, this)
        });
    },

    /**
     * Popup browser dialog message to confirm delete action
     *
     * @return {String} the message to be displayed in the browser dialog
     */
    warnDeleteOnRefresh: function() {
        if (this._modelsToDelete) {
            return app.lang.get('NTC_DELETE_CONFIRMATION_MULTIPLE');
        }
    },

    /**
     * Delete the model once the user confirms the action
     */
    deleteModels: function() {
        var self = this,
            collection = self._modelsToDelete;
        var lastSelectedModels = _.clone(collection.models);

        app.analytics.trackEvent('click', 'mass_delete_confirm');
        if(collection) {
            // massupdate:end could be triggered without triggering success event on collection.
            // For example, when we user has no permissions to perform delete.
            // That's why we need to clear modelsToDelete when massupdate:end triggered too.
            collection.once('massupdate:end', function() {
                self._modelsToDelete = null;
            }, this);

            collection.fetch({
                //Don't show alerts for this request
                showAlerts: false,
                method: 'delete',
                error: function() {
                    app.alert.show('error_while_mass_update', {
                        level:'error',
                        title: app.lang.get('ERR_INTERNAL_ERR_MSG'),
                        messages: ['ERR_HTTP_500_TEXT_LINE1', 'ERR_HTTP_500_TEXT_LINE2']
                    });
                },
                success: function(data, response, options) {
                    self.layout.trigger("list:records:deleted", lastSelectedModels);
                    var redirect = self._targetUrl !== self._currentUrl;
                    if (options.status === 'done') {
                        //TODO: Since self.layout.trigger("list:search:fire") is deprecated by filterAPI,
                        //TODO: Need trigger for fetching new record list
                        self.layout.context.reloadData({showAlerts: false});
                    } else if (options.status === 'queued') {
                        app.alert.show('jobqueue_notice', {level: 'success', title: app.lang.get('LBL_MASS_UPDATE_JOB_QUEUED'), autoClose: true});
                    }
                    self._modelsToDelete = null;
                    if (redirect) {
                        self.unbindBeforeRouteDelete();
                        //Replace the url hash back to the current staying page
                        app.router.navigate(self._targetUrl, {trigger: true});
                    }
                }
            });
        }
    },

    /**
     * Pre-event handler before current router is changed
     *
     * @return {Boolean} true to continue routing, false otherwise
     */
    beforeRouteDelete: function () {
        if (this._modelsToDelete) {
            this.warnDelete(this._modelsToDelete);
            return false;
        }
        return true;
    },

    /**
     * Called to allow admins to resave records and update thier calculated fields.
     */
    updateCalcFields: function() {
        this.hideAll();
        this.save(true);
    },

    /**
     * Performs mass export on selected records
     */
    massExport: function() {
        this.hideAll();
        var massExport = this.context.get("mass_collection");

        if (massExport) {
            app.alert.show('massexport_loading', {level: 'process', title: app.lang.get('LBL_LOADING')});

            app.api.exportRecords({
                    module: this.module,
                    uid: massExport.pluck('id')
                },
                this.$el,
                {
                    complete: function(data) {
                        app.alert.dismiss('massexport_loading');
                    }
                });
        }
    },

    /**
     * Called to start the massupdate process. Checks for validation errors
     * before sending down the modified attributes and starting the job queue.
     *
     * @param {Boolean} [forCalcFields=false] Causes the massupdate model to
     *   fetch with empty attributes, prior to saving the records.
     */
    save: function(forCalcFields) {
        forCalcFields = !!forCalcFields;
        var massUpdate = this.getMassUpdateModel(this.module),
            self = this;

        massUpdate.setChunkSize(this._settings.mass_update_chunk_size);

        this.once('massupdate:validation:complete', function(validate) {
            var errors = validate.errors,
                emptyValues = validate.emptyValues,
                confirmMessage = app.lang.get('LBL_MASS_UPDATE_EMPTY_VALUES'),
                attributes = validate.attributes || this.getAttributes();

            this.$(".fieldPlaceHolder .error").removeClass("error");
            this.$(".fieldPlaceHolder .help-block").hide();

            if (_.isEmpty(errors)) {
                confirmMessage += '<br>[' + emptyValues.join(',') + ']<br>' + app.lang.get('LBL_MASS_UPDATE_EMPTY_CONFIRM') + '<br>';
                if (massUpdate) {
                    var fetchMassupdate = _.bind(function() {
                        var successMessages = this.buildSaveSuccessMessages(massUpdate);
                        massUpdate.fetch({
                            //Show alerts for this request
                            showAlerts: true,
                            attributes: attributes,
                            error: function() {
                                app.alert.show('error_while_mass_update', {
                                    level: 'error',
                                    title: app.lang.get('ERR_INTERNAL_ERR_MSG'),
                                    messages: ['ERR_HTTP_500_TEXT_LINE1', 'ERR_HTTP_500_TEXT_LINE2']
                                });
                            },
                            success: function(data, response, options) {
                                self.hide();
                                if (options.status === 'done') {
                                    //TODO: Since self.layout.trigger("list:search:fire") is deprecated by filterAPI,
                                    //TODO: Need trigger for fetching new record list
                                    self.collection.fetch({
                                        //Don't show alerts for this request
                                        showAlerts: false,
                                        remove: true,
                                        // Boolean coercion.
                                        relate: !!self.layout.collection.link
                                    });
                                } else if (options.status === 'queued') {
                                    app.alert.show('jobqueue_notice', {level: 'success', messages: successMessages[options.status], autoClose: true});
                                }
                            }
                        });
                    }, this);
                    if (emptyValues.length === 0) {
                        fetchMassupdate.call(this);
                    } else {
                        app.alert.show('empty_confirmation', {
                            level: 'confirmation',
                            messages: confirmMessage,
                            onConfirm: fetchMassupdate
                        });
                    }
                }
            } else {
                this.handleValidationError(errors);
            }
        }, this);

        if (forCalcFields) {
            this.trigger('massupdate:validation:complete', {
                errors: [],
                emptyValues: [],
                attributes: {}
            });
        } else {
            this.checkValidationError();
        }
    },

    /**
     * Build dynamic success messages to be displayed if the API call is successful
     * This is overridden by massaddtolist view which requires different success messages
     *
     * @param massUpdateModel - contains the attributes of what records are being updated (used by override in massaddtolist)
     */
    buildSaveSuccessMessages: function(massUpdateModel) {
        return {
            done: app.lang.get('LBL_MASS_UPDATE_SUCCESS'),
            queued: app.lang.get('LBL_MASS_UPDATE_JOB_QUEUED')
        };
    },

    /**
     * By default attributes are retrieved directly off the model, but broken out to allow for manipulation before handing off to the API
     */
    getAttributes: function() {
        var values = [this.defaultOption].concat(this.fieldValues),
            attributes = [],
            fieldFilter = function(field) {
                return field && field.name;
            };
        values = _.chain(values)
            //Grab the field arrays from any fields that have child fields
            //and merge them with the top level field list
            .union(_.chain(values)
                .pluck("fields")
                .compact()
                .flatten()
                .value()
            )
            //Remove any dupes or empties
            .uniq(fieldFilter)
            .filter(fieldFilter)
            .value();

        _.each(values, function(value) {
            attributes = _.union(attributes,
                _.values(_.pick(value, 'name', 'id_name'))
            );
            //FIXME: remove these hard coded conditions (SC-2836)
            if (value.name === 'parent_name') {
                attributes.push('parent_id', 'parent_type');
            } else if (value.name === 'team_name') {
                attributes.push('team_name_type');
            } else if (value.name === 'tag') {
                attributes.push('tag_type');
            } else if (value.isMultiSelect) {
                attributes.push(value.name + '_replace');
            }
        }, this);
        return _.pick(this.model.attributes, attributes);
    },

    /**
     * Get fields to validate.
     * @return {Object}
     * @private
     */
    _getFieldsToValidate: function() {
        var fields = _.initial(this.fieldValues).concat(this.defaultOption);
        return _.filter(fields, function(f) {
            return f.name;
        })
    },

    checkValidationError: function() {
        var self = this,
            emptyValues = [],
            errors = {},
            validator = {},
            i = 0;

        var fieldsToValidate = this._getFieldsToValidate();

        if (_.size(fieldsToValidate)) {
            _.each(fieldsToValidate, function(field) {
                i++;
                validator = {};
                validator[field.name] = field;
                field.required = (_.isBoolean(field.required) && field.required) || (field.required && field.required == 'true') || false;
                var value = this.model.get(field.name);
                // check if value represents emptiness
                if ((!_.isBoolean(value) && !value) || (_.isArray(value) && value.length === 0)) {
                    // If value is empty, but it's being appended, don't add it to empty values
                    // use == because the value may be a string
                    var appendCheck = this.model.get(field.name + '_type');
                    if (!appendCheck || appendCheck == 0) {
                        emptyValues.push(app.lang.get(field.label, this.model.module));
                        //don't set model if field is a relate collection
                        if (!field.relate_collection) {
                            this.model.set(field.name, '', {silent: true});
                            if (field.id_name) {
                                this.model.set(field.id_name, '', {silent: true});
                            }
                        }
                    }
                }
                this.model._doValidate(validator, errors, function(didItFail, fields, errors, callback) {
                    if (i === _.size(fieldsToValidate)) {
                        self.trigger('massupdate:validation:complete', {
                            errors: errors,
                            emptyValues: emptyValues
                        });
                    }
                });
            }, this);
        } else {
            this.trigger('massupdate:validation:complete', {
                errors: errors,
                emptyValues: emptyValues
            });
        }

        return;
    },
    handleValidationError: function(errors) {
        var self = this;
        _.each(errors, function (fieldErrors, fieldName) {
            var field = self.getField(fieldName);
            if (!_.isUndefined(field)) {
                var fieldEl = field.$el,
                    errorEl = fieldEl.find('.help-block');
                fieldEl.addClass('error');
                if(errorEl.length == 0) {
                    errorEl = $('<span>').addClass('help-block');
                    errorEl.appendTo(fieldEl);
                }
                errorEl.show().html('');
                _.each(fieldErrors, function (errorContext, errorName) {
                    errorEl.append(app.error.getErrorString(errorName, errorContext));
                });
            }
        });
    },
    show: function() {
        this.hideAll();
        this.visible = true;
        this.defaultOption = null;
        this.model.clear();
        var defaults = _.extend({}, this.model._defaults, this.model.getDefault());
        this.model.set(defaults);
        this.setDefault();

        var massModel = this.context.get('mass_collection');
        massModel.off(null, null, this);
        massModel.on('add remove reset massupdate:estimate', this.setDisabled, this);
        massModel.on('massupdate:start massupdate:end', this.setDisabledOnUpdate, this);

        // show will be called only on context.trigger("list:massupdate:fire").
        // therefore this should never be called in a situation in which
        // the view is disposed.
        this.$el.show();
        this.render();

        this.createShortcutSession();
        this.registerShortcuts();
    },
    /**
     * Hide all views that make up the list mass action section (ie. massupdate, massaddtolist)
     */
    hideAll: function() {
        this.layout.trigger("list:massaction:hide");
    },
    hide: function() {
        if (this.disposed) {
            return;
        }
        this.visible = false;
        this.$el.hide();

        this.clearAndRestorePreviousShortcuts();
    },
    /**
     * Create new shortcut session.
     */
    createShortcutSession: function() {
        app.shortcuts.saveSession();
        app.shortcuts.createSession([
            'MassUpdate:Add',
            'MassUpdate:Remove',
            'MassUpdate:Cancel',
            'MassUpdate:Update'
        ], this);
    },
    /**
     * Register shortcuts for mass update inline drawer.
     */
    registerShortcuts: function() {
        app.shortcuts.register({
            id: 'MassUpdate:Add',
            keys: '+',
            component: this,
            description: 'LBL_SHORTCUT_MASS_UPDATE_ADD',
            handler: function() {
                this.$('[data-action=add]').last().click();
            }
        });
        app.shortcuts.register({
            id: 'MassUpdate:Remove',
            keys: '-',
            component: this,
            description: 'LBL_SHORTCUT_MASS_UPDATE_REMOVE',
            handler: function() {
                this.$('[data-action=remove]').last().click();
            }
        });
        app.shortcuts.register({
            id: 'MassUpdate:Cancel',
            keys: ['esc', 'mod+alt+l'],
            component: this,
            description: 'LBL_SHORTCUT_MASS_UPDATE_CANCEL',
            callOnFocus: true,
            handler: function() {
                this.$('a.cancel_button').click();
            }
        });
        app.shortcuts.register({
            id: 'MassUpdate:Update',
            keys: ['mod+s', 'mod+alt+a'],
            component: this,
            description: 'LBL_SHORTCUT_MASS_UPDATE_SAVE',
            callOnFocus: true,
            handler: function() {
                this.$('[name=update_button]:not(.disabled)').click();
            }
        });
    },
    /**
     * Clear shortcuts and restore previous shortcut session.
     */
    clearAndRestorePreviousShortcuts: function() {
        var activeShortcutSession = app.shortcuts.getCurrentSession();
        if (activeShortcutSession && (activeShortcutSession.layout === this)) {
            app.shortcuts.restoreSession();
        }
    },
    setDisabledOnUpdate: function() {
        var massUpdate = this.context.get('mass_collection');
        if (massUpdate.length == 0) {
            this.$('.btn[name=update_button]').removeClass('disabled');
        } else {
            this.$('.btn[name=update_button]').addClass('disabled');
        }
    },
    setDisabled: function() {
        var massUpdate = this.context.get('mass_collection');
        if (massUpdate.isEmpty() || massUpdate.fetched === false) {
            this.$('.btn[name=update_button]').addClass('disabled');
        } else {
            this.$('.btn[name=update_button]').removeClass('disabled');
        }
    },
    saveClicked: function(evt) {
        if(this.$(".btn[name=update_button]").hasClass("disabled") === false) {
            this.save();
        }
    },
    cancelClicked: function(evt) {
        this.hide();
    },
    unbindData: function() {
        var massModel = this.context.get("mass_collection");
        if (massModel) {
            massModel.off(null, null, this);
        }
        app.view.View.prototype.unbindData.call(this);
    },

    /**
     * Detach the event handlers for warning delete
     */
    unbindBeforeRouteDelete: function() {
        app.routing.offBefore("route", this.beforeRouteDelete, this);
        $(window).off("beforeunload.delete" + this.cid);
    },

    _dispose: function() {
        this.unbindBeforeRouteDelete();
        this.$('.select2.mu_attribute').select2('destroy');
        app.view.View.prototype._dispose.call(this);
    }
})
