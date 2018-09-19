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
 * @class View.Views.Base.BwcView
 * @alias SUGAR.App.view.views.BaseBwcView
 * @extends View.View
 */
({
    className: 'bwc-frame',
    // Precompiled regex (note-regex literal causes errors but RegExp doesn't)
    moduleRegex: new RegExp('module=([^&]*)'),
    idRegex: new RegExp('record=([^&]*)'),
    actionRegex: new RegExp('action=([^&]*)'),

    plugins: ['Editable', 'LinkedModel'],

    /**
     * Enabled actions for warning unsaved changes.
     */
    warnEnabledBwcActions: [
        'editview', 'config'
    ],

    /**
     * The URL to a BWC view to be used in the iFrame element (template).
     *
     * See {@link #_renderHtml} on how this is created and then kept in sync
     * with the iFrame.
     *
     * @property {string}
     */
    url: '',

    /**
     * Sets the current URL of this view to point to a bwc link.
     *
     * See {@link #_setCurrentUrl} on how this is kept in sync with the current
     * view url and window hash.
     *
     * @property {string}
     * @private
     */
    _currentUrl: '',

    initialize: function(options) {
        // If (for some reason) we're trying to directly access old Home/Dashboards, for redirect to sidecar #Home
        var url = options.context.get('url');
        if (url && (url.search(/module=Home.*action=index/) > -1 || url.search(/action=index.*module=Home/) > -1)) {
            app.router.navigate('#Home', {trigger: true});
            return;
        }

        app.events.on("api:refreshtoken:success", this._refreshSession, this);

        this._super('initialize', [options]);
        this.bwcModel = app.data.createBean('bwc');

        // because loadView disposes the old layout when the bwc iFrame is no
        // longer in the DOM, it causes a memory leak unless we unbind it
        // before the new layout is loaded.
        app.before('app:view:load', this.unbindDom, this);
    },

    /**
     * @inheritdoc
     *
     * Inspect changes on current HTML input elements with initial values.
     */
    hasUnsavedChanges: function() {
        var bwcWindow = this.$('iframe').get(0).contentWindow;
        //if bwcModel is empty, then it should return false (since it's not in enabled actions)
        // or we couldnt find a edit view to compare or the view doesn't want to be compared
        if (_.isEmpty(this.bwcModel.attributes) || _.isUndefined(bwcWindow.EditView) || $(bwcWindow.EditView).data('disablebwchaschanged')) {
            return false;
        }
        // some forms may still be doing async loading after document ready
        // do not compare if the loading is not done yet
        if (!_.isUndefined(bwcWindow.asyncLoading) && bwcWindow.asyncLoading) {
            return false;
        }
        var newAttributes = this.serializeObject(bwcWindow.EditView);
        return !_.isEmpty(this.bwcModel.changedAttributes(newAttributes));
    },

    /**
     * Retrieves form's input values in object format
     *
     * @param {HTMLElement} theForm form element.
     * @return {Object} key-value paired object.
     */
    serializeObject: function(theForm) {
        var formArray = $(theForm).serializeArray();
        return _.reduce(formArray, function(acc, field) {
            acc[field.name] = field.value;
            return acc;
        }, {});
    },

    /**
     * @inheritdoc
     *
     * Override {@link View.View#_render} method to
     * extend ACL check for Administration module in BWC mode.
     * Allow access to Administration if user has admin access to any
     * module only, if not - show error message and navigate to home.
     */
    _render: function() {
        if (this.module === 'Administration' &&
            !app.acl.hasAccessToAny('admin') &&
            !app.acl.hasAccessToAny('developer')
        ) {
            app.logger.info(
                'Current user does not have access to this module view. name: ' +
                    this.name + ' module:' + this.module
            );
            app.error.handleRenderError(this, 'view_render_denied');
            app.router.navigate('#Home', {trigger: true});
            return;
        }
        app.view.View.prototype._render.call(this);
        return this;
    },

    /**
     * Render the iFrame and listen for content changes on it.
     *
     * Every time there is an update on the iFrame, we:
     * - clear any '.bwc.sugarcrm' event (namespace for any bind in this view);
     * - update the controller context to mach our bwc module (if exists);
     * - update our url to match the current iFrame location in bwc way;
     * - rewrite links for sidecar modules;
     * - rewrite links that go for new windows;
     * - memorize the form input elements in order to warn unsaved changes;
     * - update the context model to mach our current bwc module (if exists);
     *
     * @private
     */
    _renderHtml: function() {
        var self = this;

        this.url = app.utils.addIframeMark(this.context.get('url') || 'index.php?module=' + this.module + '&action=index');
        app.view.View.prototype._renderHtml.call(this);

        this.$('iframe').on('load', function() {
            //In order to update current location once bwc link is clicked.
            self.url = 'index.php' + this.contentWindow.location.search;
            self._setCurrentUrl();

            if (this.contentWindow.$ === undefined) {
                // no jQuery available, graceful fallback
                return;
            }

            $(this.contentWindow).one('beforeunload', _.bind(self.unbindDom, self));

            self._setModule(this.contentWindow);
            self._setBwcModel(this.contentWindow);
            self._setModel(this.contentWindow);
            self._rewriteLinksForSidecar(this.contentWindow);
            self._rewriteNewWindowLinks(this.contentWindow);
            self._cloneBodyClasses(this.contentWindow);

            $('html', this.contentWindow.document).on('click.bwc.sugarcrm', function() {
                app.bwc.trigger('clicked');
            });
        });
    },

    /**
     * Clone classes, added by Modernizr, "top frame" into "bwc frame";
     * necessary for various overrides on iPhone and Android.
     */
    _cloneBodyClasses: function(contentWindow) {
        contentWindow.$('html').addClass($('html').prop('class'));
    },
    /**
     * Update the controller context to mach our bwc module.
     *
     * @param {HTMLElement} contentWindow iframe window.
     * @private
     */
    _setModule: function(contentWindow) {
        var module = this.moduleRegex.exec(contentWindow.location.search);
        module = (_.isArray(module)) ? module[1] : null;

        if (!module) {
            // try and strip module off the page if its not set on location
            if (contentWindow.$ && contentWindow.$('input[name="module"]') && contentWindow.$('input[name="module"]').val()) {
                module = contentWindow.$('input[name="module"]').val();
            } else {
                return;
            }

        }
        // on BWC import we want to try and take the import module as the module
        if (module === 'Import') {
            var importModule = /import_module=([^&]*)/.exec(contentWindow.location.search);
            if (!_.isNull(importModule) && !_.isEmpty(importModule[1])) {
                module = importModule[1];
            } else if (contentWindow.$ &&
                contentWindow.$('input[name="import_module"]') &&
                contentWindow.$('input[name="import_module"]').val()) {

                // try and strip import module off the page if its not set on location
                module = contentWindow.$('input[name="import_module"]').val();
            }
        }
        // update bwc context
        var app = window.parent.SUGAR.App;
        app.controller.context.set('module', module);
        app.events.trigger('app:view:change', this.layout, {module: module});
    },

    /**
     * Memorize the form input elements if current page contains edit form.
     *
     * @param {HTMLElement} contentWindow iframe window.
     * @private
     */
    _setBwcModel: function(contentWindow) {
        var action = this.actionRegex.exec(contentWindow.location.search);
        action = (_.isArray(action)) ? action[1].toLowerCase() : null;

        var EditViewGrid = contentWindow.document.getElementById('EditViewGrid');
        if (EditViewGrid) {
            contentWindow.EditView = EditViewGrid;
        }

        //once edit page is entered, the page is navigated without action query string.
        //Therefore, if current page contains 'EditView' form, bind the action as 'editview'.
        if (contentWindow.EditView) {
            action = 'editview';
        }

        var attributes = {};
        if (_.contains(this.warnEnabledBwcActions, action)) {
            attributes = this.serializeObject(contentWindow.EditView);
        }
        this.resetBwcModel(attributes);
    },

    /**
     * Populates the context model with API data.
     * `this.model` is a link for `this.context.model`.
     *
     * @param {HTMLElement} contentWindow iframe window.
     * @private
     */
    _setModel: function(contentWindow) {
        var action = this.actionRegex.exec(contentWindow.location.search);
        action = (_.isArray(action)) ? action[1].toLowerCase() : null;

        if (action !== 'detailview') {
            return;
        }

        var id = this.idRegex.exec(this._currentUrl);
        if (!_.isArray(id)) {
            return;
        }

        this.model.set('id', id[1]);
        this.model.module = this.context.get('module');
        this.model.fetch();
    },

    /**
     * @inheritdoc
     *
     * Opens the appropriate sidecar create layout in a drawer.
     *
     * @param {String} module Module name.
     * @param {String} link Link name.
     */
    openCreateDrawer: function(module, link) {
        var parentModel = this.context.get('model'),
            model = this.createLinkModel(parentModel, link),
            self = this;
        app.drawer.open({
            layout: 'create',
            context: {
                create: true,
                module: model.module,
                model: model
            }
        }, function(context, model) {
            if (!model) {
                return;
            }
            // Reload the BWC to update subpanels.
            self.$('iframe').get(0).contentWindow.location.reload(true);
        });
    },

    /**
     * Opens the Compose Email drawer, passing in the parent model to which the
     * email should be related, as well other prefills, like the subject and
     * body.
     *
     * @param {Object} [options] Data for the email from the compose package.
     * @param {Object} [options.subject] Populate the email with this subject.
     * @param {Object} [options.body] Populate the email with this body.
     * @param {Object} [options.to] Populate the email with these recipients.
     * @param {Object} [options.cc] Populate the email with these recipients.
     * @param {Object} [options.attachments] Populate the email with these
     * attachments.
     */
    openComposeEmailDrawer: function(options) {
        var prepopulate = {
            related: this.context.get('model')
        };

        options = app.utils.deepCopy(options) || {};

        if (!_.isEmpty(options.subject)) {
            prepopulate.name = options.subject;
        }

        if (!_.isEmpty(options.body)) {
            prepopulate.description_html = options.body;
        }

        _.each(['to', 'cc'], function(field) {
            if (!_.isArray(options[field])) {
                return;
            }

            prepopulate[field] = [];

            _.each(options[field], function(data) {
                var bean = app.data.createBean('EmailParticipants', {
                    _link: field,
                    email_address_id: data.email_address_id,
                    email_address: data.email_address
                });

                if (data.parent_type && data.parent_id) {
                    bean.set({
                        parent: {
                            _acl: {},
                            type: data.parent_type,
                            id: data.parent_id,
                            name: data.parent_name || ''
                        },
                        parent_type: data.parent_type,
                        parent_id: data.parent_id,
                        parent_name: data.parent_name || ''
                    });
                }

                prepopulate[field].push(bean);
            });
        });

        if (!_.isEmpty(options.attachments)) {
            prepopulate.attachments = [];

            _.each(options.attachments, function(attachment) {
                var bean = app.data.createBean('Notes', {
                    _link: 'attachments',
                    upload_id: attachment.id,
                    name: attachment.filename,
                    filename: attachment.filename
                });

                prepopulate.attachments.push(bean);
            });
        }

        app.utils.openEmailCreateDrawer(
            'compose-email',
            prepopulate,
            _.bind(function(context, model) {
                // Reload the BWC window to update subpanels.
                if (model) {
                    this.$('iframe').get(0).contentWindow.location.reload(true);
                }
            }, this)
        );
    },

    /**
     * Opens the Archive Email drawer, passing in the parent model to relate to
     * Reloads the BWC page if email created so it appears in the subpanel
     */
    openArchiveEmailDrawer: function() {
        app.utils.openEmailCreateDrawer(
            'create',
            {
                related: this.context.get('model')
            },
            _.bind(function(model) {
                if (model) {
                    // Reload the BWC window to update subpanels.
                    this.$('iframe').get(0).contentWindow.location.reload(true);
                }
            }, this)
        );
    },

    /**
     * Update current window location based on the {@link #url} property.
     *
     * Confirms that the sidecar hash is always matching the url in the iFrame
     * prefixed by`#bwc/` hash (for proper routing handling).
     *
     * @private
     */
    _setCurrentUrl: function() {
        this._currentUrl = app.utils.rmIframeMark('#bwc/' + this.url);
        window.parent.location.hash = this._currentUrl;
    },

    /**
     * Revert model attributes with the current form elements.
     */
    revertBwcModel: function() {
        var bwcWindow = this.$('iframe').get(0).contentWindow;
        var newAttributes = this.serializeObject(bwcWindow.EditView);
        this.resetBwcModel(newAttributes);
    },

    /**
     * Reset model attributes with the initial attributes.
     *
     * @param {Object} key-value pair attributes.
     */
    resetBwcModel: function(attr) {
        this.bwcModel.clear({
            silent: true
        }).set(attr);
    },

    /**
     * Gets the sidecar url based on a given bwc hyperlink.
     * @param {String} href the bwc hyperlink.
     * @return {String} the new sidecar hyperlink (empty string if unable to convert).
     */
    convertToSidecarUrl: function(href) {
        var module = this.moduleRegex.exec(href),
            id = this.idRegex.exec(href),
            action = this.actionRegex.exec(href);

        module = (_.isArray(module)) ? module[1] : null;
        if (!module) {
            return '';
        }
        //Route links for BWC modules through bwc/ route
        if (app.metadata.getModule(module).isBwcEnabled) {
            //Remove any './' nonsense in existing hrefs
            href = href.replace(/^.*\//, '');
            return "bwc/" + href;
        }
        id = (_.isArray(id)) ? id[1] : null;
        action = (_.isArray(action)) ? action[1] : '';
        // fallback to sidecar detail view
        if (action.toLowerCase() === 'detailview') {
            action = '';
        }

        if (!id && action.toLowerCase() === 'editview') {
            action = 'create';
        }
        return app.router.buildRoute(module, id, action);
    },

    /**
     * Rewrites old link element to the new sidecar router.
     *
     * This adds an event to all the links that are converted and don't open in
     * a new tab/window. Therefore it is imperative that you take memory leaks
     * precautions. See {@link #unbindDom} for more information.
     *
     * The reason why we don't use an `onclick="..."` attribute, is simply due
     * to requirements of tracking the event and stop propagation, which would
     * be extremely difficult to support cross browser.
     *
     * @param {HTMLElement} The link `<a>` to rewrite into a sidecar url.
     */
    convertToSidecarLink: function(elem) {
        elem = $(elem);
        //Relative URL works better on all browsers than trying to include origin
        var baseUrl = app.config.siteUrl || window.location.pathname;
        var href = elem.attr('href');
        var module = this.moduleRegex.exec(href);
        var dataSidecarRewrite = elem.attr('data-sidecar-rewrite');
        var action = this.actionRegex.exec(href);

        if (
            !_.isArray(module) ||
            _.isEmpty(module[1]) ||
            _.isUndefined(app.metadata.getModule(module[1])) ||
            module[1] === "Administration" || // Leave Administration module links alone for 7.0
            href.indexOf("javascript:") === 0 || //Leave javascript alone (this is mostly BWC links)
            dataSidecarRewrite === 'false' ||
            (_.isArray(action) && action[1] === 'sugarpdf') //Leave PDF downloads for bwc modules
        ) {
            return;
        }
        var sidecarUrl = this.convertToSidecarUrl(href);
        elem.attr('href', baseUrl + '#' + sidecarUrl);
        elem.data('sidecarProcessed', true);

        if (elem.attr('target') === '_blank') {
            return;
        }

        app.logger.debug('Bind event in BWC view');

        elem.on('click.bwc.sugarcrm', function(e) {
            if (e.button !== 0 || e.ctrlKey || e.metaKey) {
                return;
            }
            e.stopPropagation();
            parent.SUGAR.App.router.navigate(sidecarUrl, {trigger: true});
            return false;
        });
        app.accessibility.run(elem, 'click');
    },

    /**
     * Rewrites old error elements to the new one pop-ups.
     *
     * @param {Object} $errors DOM elements containing errors to rewrite into standard errors.
     */
    convertToSidecarErrors: function($errors) {
        if ($errors.length === 0) {
            return;
        }

        $errors.hide();
        var errorMessages = _.map($errors, function(error) {
            return $(error).text();
        });
        app.alert.show('delete-error', {
            level: 'error',
            messages: errorMessages
        });
    },

    /**
     * Allow BWC modules to rewrite their links when using their own ajax
     * calls.
     *
     * *ATTENTION:* This method might cause memory leaks if not used properly.
     * Make sure that {@link #unbindDom} is being used and cleaning up any
     * events that this view is creating (use {@link Utils.Logger.levels}
     * `debug` level to track all the events being created and check if the
     * ones being cleared by {@link #unbindDom} match.
     */
    rewriteLinks: function() {
        app.logger.warn('Possible memory leak on BWC code');
        var frame = this.$('iframe').get(0).contentWindow;
        this._rewriteLinksForSidecar(frame);
        this._rewriteNewWindowLinks(frame);
    },

    /**
     * Rewrite old links on the frame given to the new sidecar router.
     *
     * This will match all hrefs that contain "module=" on it and if the module
     * isn't blacked listed, then rewrite into sidecar url.
     * Since iFrame needs full URL to sidecar urls (to provide copy paste urls,
     * open in new tab/window, etc.) this will check what is the base url to
     * apply to that path.
     *
     * See `include/modules.php` for the list (`$bwcModules`) of modules not
     * sidecar ready.
     *
     * This method is private because it binds data and might cause memory
     * leaks. Please use this with caution and with {@link #unbindDom}.
     *
     * @param {Window} frame The `contentWindow` of the frame to rewrite links.
     * @private
     */
    _rewriteLinksForSidecar: function(frame) {
        var self = this;

        frame.$('a[href*="module="]').each(function(i, elem) {
            self.convertToSidecarLink(elem);
        });
    },

    /**
     * Rewrite new window links (`target=_blank`) on the frame given to the new
     * sidecar with bwc url.
     *
     * This will match all `"target=_blank"` links that aren't already pointing
     * to sidecar already and make them sidecar bwc compatible. This will
     * assume that all links to sidecar modules are already rewritten.
     *
     * @param {Window} frame The `contentWindow` of the frame to rewrite links.
     * @private
     */
    _rewriteNewWindowLinks: function(frame) {
        var ieOrigin,
            baseUrl,
            $links = frame.$('a[target="_blank"]').not('[href^="http"]').not('[href*="entryPoint=download"]');

        // for IE 10 & below, which does not have window.location.origin
        if (!window.location.origin) {
            ieOrigin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
        }
        baseUrl = app.config.siteUrl || (window.location.origin || ieOrigin) + window.location.pathname;

        $links.each(function(i, elem) {
            var $elem = $(elem);
            if ($elem.data('sidecarProcessed')) {
                return;
            }
            $elem.attr('href', baseUrl + '#bwc/' + $elem.attr('href'));
        });
    },

    /**
     * Unbinds all events that were hooked in this view with the `bwc.sugarcrm`
     * namespace into links (`<a>` anchor tags).
     *
     * Only unbinds if the content window has jQuery and the `iframe` is
     * loaded.
     * To avoid memory leaks, please always confirm that this function is
     * called when any event is added to the `iframe` from this view or
     * sidecar.
     *
     * Example:
     *
     *     // write some `methodWithBind` that binds click events in bwc
     *     // elements in this bwc view.
     *     // call that method from within the bwc view like:
     *     parent.SUGAR.App.view.views.BaseBwcView.prototype.methodWithBind();
     *     // memory leak will happen if `methodWithBind` doesn't use the
     *     // `.bwc.sugarcrm` namespace.
     *
     * If the BWC view is replacing it's current html with a new one, it should
     * also call this method before replacing the contents, so that it won't
     * cause memory leak.
     */
    unbindDom: function() {
        var bwcWindow = this.$('iframe').get(0).contentWindow;
        if (!bwcWindow || bwcWindow.$ === undefined) {
            return;
        }

        this.confirmMemLeak(bwcWindow.document);

        $('a', bwcWindow.document).off('.bwc.sugarcrm');
        $('html', bwcWindow.document).off('.bwc.sugarcrm');
    },

    confirmMemLeak: function(target) {
        app.logger.debug(function() {
            var registered = _.reduce($('a', target), function(memo, el) {
                var events = $._data(el, 'events');
                return memo + _.where(_.flatten(events), {namespace: 'bwc.sugarcrm'}).length;
            }, 0);

            return 'Clear ' + registered + ' event(s) in `bwc.sugarcrm`.';
        });
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        app.events.off("api:refreshtoken:success", this._refreshSession, this);

        this.unbindDom();
        app.offBefore(null, null, this);
        if (this.bwcModel) {
            this.bwcModel.off();
            this.bwcModel = null;
        }
        this._super('_dispose');
    },

    /**
     * Refreshes session on server side
     */
    _refreshSession: function() {
        app.bwc.login();
    }
})
