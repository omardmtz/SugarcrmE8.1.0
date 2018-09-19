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
    app.events.on('app:init', function() {
        /**
         * Is built to share knowledge base features among views.
         *
         * - Adds validate tasks to follow dependencies between status, active and expiration dates fields.
         * - Allows to inject KBContentTemplates templates into body fields.
         * - Extends a view with create article and revision functionality.
         */
        app.plugins.register('KBContent', ['view'], {

            events: {
                'click [name=template]': 'launchTemplateDrawer'
            },

            CONTENT_LOCALIZATION: 1,
            CONTENT_REVISION: 2,

            /**
             * Attach events to create localization and revisions.
             *
             * @param {Object} component
             * @param {Object} plugin
             * @return {void}
             */
            onAttach: function(component, plugin) {
                this.on('init', function() {
                    this._initKBListeners();
                    if ((this.tplName === 'list' ||
                        (!_.isUndefined(this.context) && this.context.get('isSubpanel') === true)) &&
                        this.tplName !== 'panel-top'
                    ) {
                        this.context.on('list:editrow:fire', _.bind(function(model, view) {
                            this._initValidationHandler(model);
                        }, this));
                        this.context.on('list:preview:fire', _.bind(function(model) {
                            this._initValidationHandler(model);
                        }, this));
                    } else {
                        this._initValidationHandler(this.model);
                    }
                });
            },

            /**
             * Initialize KB specific listeners with ability to override.
             * @private
             */
            _initKBListeners: function() {
                if (_.isFunction(Object.getPrototypeOf(this)._initKBListeners)) {
                    Object.getPrototypeOf(this)._initKBListeners.call(this);
                    return;
                }
                this.context.on('button:create_localization_button:click', this.createLocalization, this);
                this.context.on('button:create_revision_button:click', this.createRevision, this);
                this.context.on('button:create_article_button:click', this.createArticle, this);
            },

            /**
             * Handler to create localization.
             * @param {Data.Model} model Parent model.
             */
            createLocalization: function(model) {
                this.createRelatedContent(model, this.CONTENT_LOCALIZATION);
            },

            /**
             * Handler to create revision.
             * @param {Data.Model} model Parent model.
             */
            createRevision: function(model) {
                this.createRelatedContent(model, this.CONTENT_REVISION);
            },

            /**
             * Handler to create a new article.
             * @param {Data.Bean} model A record view model caused creation.
             */
            createArticle: function(model) {
                var module = 'KBContents',
                    fields = app.metadata.getModule(model.module).fields,
                    links = _.filter(fields, function(field) {
                        if (field.type !== 'link' || !field.relationship) {
                            return false;
                        }
                        return app.data.getRelatedModule(model.module, field.name) === module;
                    }),
                    bodyTmpl = app.template.getField('htmleditable_tinymce', 'create-article', module),
                    attrs = {name: model.get('name'), kbdocument_body: bodyTmpl({model: model})},
                    link, prefill, relatedFields,
                    self = this;
                if (links.length === 0) {
                    prefill = app.data.createBean(module, attrs);
                } else {
                    link = links.length === 1 ? links[0].name : 'kbcontents';
                    prefill = app.data.createRelatedBean(model, null, link, attrs);
                    relatedFields = app.data.getRelateFields(model.module, link);

                    if (!_.isEmpty(relatedFields)) {
                        _.each(relatedFields, function(field) {
                            var parentValue = model.get(field.rname);
                            prefill.set(field.name, parentValue);
                            prefill.set(field.id_name, model.get('id'));
                        }, this);
                    }
                }
                app.drawer.open({
                    layout: 'create',
                    context: {
                        create: true,
                        model: prefill,
                        module: module
                    }},
                    function(context, newModel) {
                        if (newModel !== undefined && links.length > 0) {
                            var viewContext = context.parent.parent || context.parent;
                            // find&reload target subpanel
                            var subPanel = self._findSubpanel(viewContext, link);
                            self._reloadSubpanel(subPanel, link);
                        }
                    }
                );
            },

            /**
             * Handler to create a new article from subpanel.
             */
            createArticleSubpanel: function() {
                var model = this.context.parent.get('model');
                this.createArticle(model);
            },

            /**
             * Creates revision or localization for KB.
             * @param {Data.Bean} parentModel Parent model object.
             * @param {Number} type Type of created content.
             */
            createRelatedContent: function(parentModel, type) {
                var self = this,
                    prefill = app.data.createBean('KBContents', {id: parentModel.get('id')});

                prefill.fetch({
                    success: function() {
                        var removeList = ['id', 'is_external'];

                        _.each(removeList, function(field) {
                            prefill.unset(field);
                        });

                        prefill.set('status', 'draft');
                        prefill.set('assigned_user_id', app.user.get('id'));
                        prefill.set('assigned_user_name', app.user.get('full_name'));

                        if (type === self.CONTENT_LOCALIZATION) {
                            self._onCreateLocalization(prefill, parentModel);
                        } else {
                            self._onCreateRevision(prefill, parentModel);
                        }
                    },
                    error: function() {
                        app.alert.show('server-error', {
                            level: 'error',
                            messages: 'ERR_GENERIC_SERVER_ERROR'
                        });
                    }
                });
            },

            /**
             * Method called on create localization.
             *
             * Setup additional model properties for localization.
             * If no available langs for localizations it shows alert message.
             *
             * @param {Data.Model} prefill New created model.
             * @param {Data.Model} parentModel Parent model.
             * @private
             */
            _onCreateLocalization: function(prefill, parentModel) {
                if (!this.checkCreateLocalization(parentModel)) {
                    app.alert.show('localizations', {
                        level: 'warning',
                        title: app.lang.get('LBL_CANNOT_CREATE_LOCALIZATION', 'KBContents'),
                        autoClose: false
                    });
                    return;
                }
                this.context.createAction = this.CONTENT_LOCALIZATION;
                prefill.set(
                    'related_languages',
                    this.getAvailableLangsForLocalization(parentModel),
                    {silent: true}
                );
                var language = this._getNextAvailableLanguage(parentModel);
                prefill.set('language', language);
                prefill.unset('kbarticle_id', {silent: true});
                this._openCreateRelatedDrawer(prefill, parentModel);
            },

            /**
             * Method called on create localization.
             *
             * Setup additional model properties for revision.
             *
             * @param {Data.Model} prefill New created model.
             * @param {Data.Model} parentModel Parent model.
             * @private
             */
            _onCreateRevision: function(prefill, parentModel) {
                this.context.createAction = this.CONTENT_REVISION;
                prefill.set('useful', parentModel.get('useful'));
                prefill.set('notuseful', parentModel.get('notuseful'));
                prefill.set(
                    'related_languages',
                    [parentModel.get('language')],
                    {silent: true}
                );
                prefill.set('revision', '');
                this.context.set('skipFetch', false);
                this._openCreateRelatedDrawer(prefill, parentModel);
            },

            /**
             * Open drawer for create form.
             * @param {Data.Model} prefill New created model.
             * @param {Data.Model} parentModel Parent model.
             * @private
             */
            _openCreateRelatedDrawer: function(prefill, parentModel) {
                var layoutDef = {
                    layout: 'create',
                    context: {
                        create: true,
                        model: prefill,
                        module: this.module,
                        copiedFromModelId: parentModel.get('id'),
                        parent: this.context,
                        createAction: this.context.createAction
                    }
                };

                if (this.context.loadDrawer == true) {
                    app.drawer.load(layoutDef);
                } else {
                    var self = this;
                    app.drawer.open(layoutDef, function(context, newModel) {
                        // it's necessary to find appropriate subpanel
                        var link = self._getLinkNameByContextAction(context.get('createAction')),
                            viewContext, subPanel;
                        if (link) {
                            // Just parent - header's create, parent.parent - subpanel's create.
                            viewContext = context.parent.parent || context.parent;
                            // reload model data to update at least related_languages
                            viewContext.resetLoadFlag();
                            viewContext.loadData();
                            // find&reload target subpanel
                            subPanel = self._findSubpanel(viewContext, link);
                            self._reloadSubpanel(subPanel, link);
                            context.set('createAction', null);
                            context.loadDrawer = null;
                        }
                    });
                }

                prefill.trigger('duplicate:field', parentModel);
            },

            /**
             * Prepares and triggers subpanel reload.
             *
             * @param {Core.Context} subPanel Subpanel object to reload.
             * @param {String} link Link name for the trigger.
             * @private
             */
            _reloadSubpanel: function(subPanel, link) {
                if (!subPanel) {
                    return;
                }
                subPanel.set('skipFetch', false);
                subPanel.set('collapsed', false);
                subPanel.parent.trigger('subpanel:reload', {links: [link]});
            },

            /**
             * Returns children subpanel.
             *
             * @param {Core.Context} context Context object that contains subpanels.
             * @param {String} link Link name to found an appropriate subpanel.
             * @returns {Core.Context|null}
             * @private
             */
            _findSubpanel: function(context, link) {
                var child = context.getChildContext({link: link});
                return child.get('isSubpanel') === true ? child : null;
            },

            /**
             * Returns Link Name by content constant.
             *
             * @param {Number} contextAction A Context Action constant value.
             * @returns {String|boolean}
             * @private
             */
            _getLinkNameByContextAction: function(contextAction) {
                switch (contextAction) {
                    case this.CONTENT_LOCALIZATION:
                        return 'localizations';
                    case this.CONTENT_REVISION:
                        return 'revisions';
                }
                return false;
            },

            /**
             * Checks if there are available lang for localization.
             *
             * @param {Data.Model} model Parent model.
             * @return {boolean} True on success otherwise false.
             */
            checkCreateLocalization: function(model) {
                var langs = this.getAvailableLangsForLocalization(model),
                    config = app.metadata.getModule('KBContents', 'config');

                if (!langs || !config['languages']) {
                    return true;
                }

                if (!config['languages'] || config['languages'].length == langs.length) {
                    return false;
                }

                return true;
            },

            /**
             * Returns next available language that can be used for localization.
             *
             * @param {Data.Model} model Parent model.
             * @return {string} Next available localization language.
             * @private
             */
            _getNextAvailableLanguage: function(model) {
                var usedLangs = this.getAvailableLangsForLocalization(model);
                var config = app.metadata.getModule('KBContents', 'config');

                var allLangs = _.map(config.languages, function(language) {
                    var langObject = _.omit(language, 'primary');
                    return _.first(_.keys(langObject));
                });

                var availableLangs = _.difference(allLangs, usedLangs);
                return _.first(availableLangs);
            },

            /**
             * Returns array of langs for that there is localization.
             * @param {Data.Model} model Parent model.
             * @return {Array} Array of langs.
             */
            getAvailableLangsForLocalization: function(model) {
                return model.get('related_languages') || [];
            },

            /**
             * Open the drawer with the KBContentTemplates selection list layout and override the
             * kbdocument_body field with selected template.
             */
            launchTemplateDrawer: function() {
                app.drawer.open({
                        layout: 'selection-list',
                        context: {
                            module: 'KBContentTemplates'
                        }
                    },
                    _.bind(function(model) {
                        if (!model) {
                            return;
                        }
                        var self = this;
                        var template = app.data.createBean('KBContentTemplates', { id: model.id });
                        template.fetch({
                            success: function(template) {
                                if (this.disposed === true) {
                                    return;
                                }
                                var replace = function() {
                                    self.model.set('kbdocument_body', template.get('body'));
                                };
                                if (!self.model.get('kbdocument_body')) {
                                    replace();
                                } else {
                                    app.alert.show('override_confirmation', {
                                        level: 'confirmation',
                                        messages: app.lang.get('LBL_TEMPATE_LOAD_MESSAGE', self.module),
                                        onConfirm: replace
                                    });
                                }
                            },
                            error: function() {
                                app.alert.show('template-load-error', {
                                    level: 'error',
                                    messages: app.lang.get('LBL_TEMPLATE_LOAD_ERROR', 'KBContentTemplates')
                                });
                            }
                        });
                    }, this)
                );
            },

            /**
             * Define custom validation tasks.
             *
             * @param {Object} model Bean model.
             */
            _initValidationHandler: function(model) {
                // to prevent multiply event subscription
                if (model._initValidationHandler === true) {
                    return;
                }
                model._initValidationHandler = true;

                // Copy model for list view records to not replace this.model.
                var _doValidateExpDateFieldPartial = _.partial(this._doValidateExpDateField, model),
                    _doValidateActiveDateFieldPartial = _.partial(this._doValidateActiveDateField, model),
                    _validationCompletePartial = _.partial(this._validationComplete, model),
                    _hideValidationAlert = _.partial(this._hideValidationAlert, model);

                // TODO: This needs an API instead. Will be fixed by SC-3369.
                app.error.errorName2Keys['expDateLow'] = 'ERROR_EXP_DATE_LOW';
                app.error.errorName2Keys['activeDateApproveRequired'] = 'ERROR_ACTIVE_DATE_APPROVE_REQUIRED';
                app.error.errorName2Keys['activeDateLow'] = 'ERROR_ACTIVE_DATE_LOW';
                app.error.errorName2Keys['activeDateEmpty'] = 'ERROR_ACTIVE_DATE_EMPTY';

                model.addValidationTask('exp_date_publish', _.bind(_doValidateExpDateFieldPartial, this));
                model.addValidationTask('active_date_approve', _.bind(_doValidateActiveDateFieldPartial, this));
                model.on('validation:complete', _validationCompletePartial, this);
                // this event is triggered by bean.revertAttributes method
                // which is called when inline Cancel button is clicked
                model.on('attributes:revert', _hideValidationAlert, this);
            },

            /**
             * Custom validator for the "exp_date" field.
             * Show error when expiration date is lower than publishing.
             *
             * @param {Object} model Bean.
             * @param {Object} fields Hash of field definitions to validate.
             * @param {Object} errors Error validation errors.
             * @param {Function} callback Async.js waterfall callback.
             */
            _doValidateExpDateField: function(model, fields, errors, callback) {
                var expFName = 'exp_date',
                    actFName = 'active_date',
                    fieldName = expFName,
                    expDate = model.get(fieldName),
                    publishingDate = model.get(actFName),
                    status = model.get('status'),
                    changed = model.changedAttributes(model.getSynced()) || {},
                    errorKeys = [];

                if (
                    (this._isPublishingStatus(status) &&
                    ((!changed.status || !this._isPublishingStatus(changed.status)) ||
                     (this._isMassUpdate() &&
                     (!publishingDate || app.date(publishingDate).isBefore(Date.now()))))
                    )
                ) {
                    publishingDate = app.date().formatServer(true);
                    model.set(actFName, publishingDate);
                }
                if (status !== 'expired' && expDate && publishingDate && app.date(expDate).isBefore(publishingDate)) {
                    if (!this.getField(fieldName) && this.getField(actFName)) {
                        fieldName = actFName;
                    }
                    errors[fieldName] = errors[fieldName] || {};
                    errors[fieldName].expDateLow = true;
                    errorKeys.push('expDateLow');
                }

                if (
                    (this.context.get('layout') === 'records' || this.context.get('isSubpanel') === true)
                    && !_.isUndefined(errors[fieldName])
                ) {
                    this._alertError(errorKeys);
                }

                callback(null, fields, errors);
            },

            /**
             * Custom validator for the "active_date" field.
             * Approved status requires publishing date.
             *
             * @param {Object} model Bean.
             * @param {Object} fields Hash of field definitions to validate.
             * @param {Object} errors Error validation errors.
             * @param {Function} callback Async.js waterfall callback.
             */
            _doValidateActiveDateField: function(model, fields, errors, callback) {
                var fieldName = 'active_date',
                    status = model.get('status'),
                    publishingDate = model.get(fieldName),
                    errorKeys = [];
                if (status == 'approved') {
                    if (publishingDate && app.date(publishingDate).isBefore(Date.now())) {
                        errors[fieldName] = errors[fieldName] || {};
                        errors[fieldName].activeDateLow = true;
                        errorKeys.push('activeDateLow');
                        if (
                            (this.context.get('layout') === 'records' || this.context.get('isSubpanel') === true) &&
                                !_.isEmpty(errors[fieldName]) && !this._isMassUpdate()
                        ) {
                            this._alertError(errorKeys);
                        }
                        callback(null, fields, errors);
                    } else if (!publishingDate && !this._isMassUpdate()) {
                        app.alert.show('save_without_publish_date_confirmation', {
                            level: 'confirmation',
                            messages: app.lang.get('LBL_SPECIFY_PUBLISH_DATE', 'KBContents'),
                            confirm: {
                                label: app.lang.get('LBL_YES')
                            },
                            cancel: {
                                label: app.lang.get('LBL_NO')
                            },
                            onConfirm: function() {
                                callback(null, fields, errors);
                            },
                            onCancel: _.bind(function() {
                                var field = this.getField(fieldName, model);
                                if (!_.isEmpty(field)) {
                                    var fieldElement = field.getFieldElement();

                                    if (_.isFunction(this.handleFieldError)) {
                                        this.handleFieldError(field, true);
                                    }

                                    if (fieldElement.find(field.fieldTag).length === 0) {
                                        fieldElement.closest('[data-name=' + fieldName + ']')
                                            .find('.record-edit-link-wrapper')
                                            .click();
                                    }
                                    _.defer(function() {
                                        field.$(field.fieldTag).focus();
                                        field.focus();
                                    });

                                }
                                // Emulate invalid validation to discard changes in components.
                                // Pass a stub in order not to decorate anything in case of no real errors.
                                errors.kbcontentsValidationCancel = {stub: true};
                                callback(null, fields, errors);
                            }, this)
                        });
                    } else if (!publishingDate) {
                        errors[fieldName] = errors[fieldName] || {};
                        errors[fieldName].activeDateEmpty = true;
                        callback(null, fields, errors);
                    } else {
                        callback(null, fields, errors);
                    }
                } else {
                    callback(null, fields, errors);
                }
            },

            /**
             * Check if massupdate in progress.
             * @return {boolean}
             * @private
             */
            _isMassUpdate: function() {
                return this.action === 'massupdate';
            },

            /**
             * Called whenever validation completes.
             * Change publishing and expiration dates to current on manual change.
             *
             * @param {Boolean} isValid
             */
            _validationComplete: function(model, isValid) {
                if (isValid) {
                    this._hideValidationAlert();
                    var changed = model.changedAttributes(model.getSynced());
                    var current = model.get('status');

                    if (current == 'expired') {
                        model.set('exp_date', app.date().formatServer(true));
                    } else if (
                        this._isPublishingStatus(current) &&
                        !(changed.status && this._isPublishingStatus(changed.status))
                    ) {
                        model.set('active_date', app.date().formatServer(true));
                    }
                }
            },

            /**
             * Hides validation error alert
             *
             * @private
             */
            _hideValidationAlert: function() {
                app.alert.dismiss('kb-validation-error');
            },

            /**
             * Alert error message.
             *
             * @param keys
             * @private
             */
            _alertError: function(keys) {
                var messages = [];

                _.each(keys, function(key) {
                    messages.push(app.lang.get(app.error.errorName2Keys[key], 'KBContents'));
                });

                if (messages.length > 0) {
                    app.alert.show('kb-validation-error', {
                        level: 'error',
                        messages: messages,
                        autoClose: false
                    });
                }
            },

            /**
             * Check if passed status is publishing status.
             *
             * @param {String} status Status field value.
             * @return {Boolean}
             */
            _isPublishingStatus: function(status) {
                return ['published'].indexOf(status) !== -1;
            },

            /**
             * @inheritdoc
             * Remove validation on the model.
             */
            onDetach: function() {
                if (this.model) {
                    this.model.removeValidationTask('exp_date_publish');
                    this.model.removeValidationTask('active_date_approve');
                }
            },

            /**
             * Need additional data while creating new revision/localization.
             * @see View.Views.Base.CreateView::saveAndCreate
             * @override
             */
            saveAndCreate: function() {
                var createAction = this.context.parent.createAction,
                    callback;
                if (!createAction) {
                    Object.getPrototypeOf(this).saveAndCreate.call(this);
                    return;
                }
                switch (createAction) {
                    case this.CONTENT_LOCALIZATION:
                        callback = this.createLocalization;
                        break;
                    case this.CONTENT_REVISION:
                        callback = this.createRevision;
                        break;
                }
                if (callback) {
                    this.initiateSave(_.bind(
                        function() {
                            this.context.loadDrawer = true;
                            if (this.hasSubpanelModels) {
                                // loop through subpanels and call resetCollection on create subpanels
                                _.each(this.context.children, function(child) {
                                    if (child.get('isCreateSubpanel')) {
                                        this.context.trigger('subpanel:resetCollection:' + child.get('link'), true);
                                    }
                                }, this);

                                // reset the hasSubpanelModels flag
                                this.hasSubpanelModels = false;
                            }
                            callback.call(this, this.model);
                        },
                        this
                    ));
                }
            }
        });
    });
})(SUGAR.App);
