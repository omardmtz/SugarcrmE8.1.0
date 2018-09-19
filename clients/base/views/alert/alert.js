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
 * @class View.Views.Base.AlertView
 * @alias SUGAR.App.view.views.BaseAlertView
 * @extends View.View
 */
({
    className: 'alert-wrapper', //override default class

    events: {
        'click [data-action=cancel]': 'cancelClicked',
        'click [data-action=confirm]': 'confirmClicked',
        'click [data-action=close]': 'closeClicked',
        'click a': 'linkClick'
    },

    LEVEL: {
        PROCESS: 'process',
        SUCCESS: 'success',
        WARNING: 'warning',
        INFO: 'info',
        ERROR: 'error',
        CONFIRMATION: 'confirmation'
    },

    /**
     * Initialize alert view.
     *
     * @param {Object} options Options to be passed to the alert view.
     * @param {boolean} options.closeable: boolean flag indicating if the alert
     *   can be closed by the user. Note that non-"info" alerts are closeable by
     *   default if this setting is not specified.
     * @param {Function} options.onConfirm: Handler of action Confirm for
     *   confirmation alerts.
     * @param {Function} options.onCancel: Handler of action Cancel for
     *   confirmation alerts.
     * @param {Function} options.onLinkClicked: Handler for click actions on a
     *   link inside the alert.
     * @param {Function} options.onClose: Handler for the close event on the (x).
     * @param {Object} options.templateOptions: Augment template context with
     *   custom object.
     *
     * @override
     */
    initialize: function(options) {
        app.plugins.attach(this, 'view');

        this.options = options || {};
        this.options.confirm || (this.options.confirm = {});
        this.options.cancel || (this.options.cancel = {});

        this.onConfirm = this.options.onConfirm || this.options.confirm.callback;
        this.confirmLabel = this.options.confirm.label || 'LBL_CONFIRM_BUTTON_LABEL';
        this.onCancel = this.options.onCancel || this.options.cancel.callback;
        this.cancelLabel = this.options.cancel.label || 'LBL_CANCEL_BUTTON_LABEL';
        this.onLinkClick = this.options.onLinkClick;
        this.onClose = this.options.onClose;
        this.templateOptions = this.options.templateOptions;
        this.name = 'alert';
    },

    /**
     * Gets selector for DOM elements that need to be clicked in order to close an alert.
     * @return {Object} jQuery/Zepto selector of the close button.
     */
    getCloseSelector: function() {
        return this.$('.close');
    },

    /**
     * Renders the custom alert view template. Binds `Esc` and `Return` keys for
     * confirmation alerts.
     *
     * @override
     */
    render: function() {
       var options = this.options;

        if (!this.triggerBefore('render')) {
            return false;
        }
        if (_.isUndefined(options)) {
            return this;
        }

        if (options.messages) {
            var messageSize = _.reduce(options.messages, function(memo, message) {
                return memo + message.length;
            }, 0);
            this.templateOptions = this.templateOptions || {};
            this.templateOptions.hasBigMessage = (messageSize > 80);
        }

        var template = this._getAlertTemplate(options, this.templateOptions);

        this.$el.html(template);

        if (options.level === 'confirmation') {
            this.bindCancelAndReturn();
        }

        this.trigger('render');
    },

    /**
     * Dismiss the alert when user clicks `cancel`
     */
    cancel: function() {
        this.trigger('dismiss');
        app.alert.dismiss(this.key);
    },

    /**
     * Executes assigned handlers when user clicks `cancel`.
     *
     * @param {Event} [event]
     */
    cancelClicked: function(event) {
        this.cancel();
        app.events.trigger('alert:cancel:clicked');
        if (_.isFunction(this.onCancel)) {
            this.onCancel(event);
        }
    },

    /**
     * Executes assigned handlers when user clicks `confirm`.
     *
     * @param {Event} [event]
     */
    confirmClicked: function(event) {
        this.cancel();
        app.events.trigger('alert:confirm:clicked');
        if (_.isFunction(this.onConfirm)) {
            this.onConfirm(event);
        }
    },

    /**
     * Fired when a link is clicked
     *
     * @param {Event} event
     */
    linkClick: function(event) {
        if (_.isFunction(this.onLinkClick)) {
            this.onLinkClick(event);
        }
    },

    /**
     * Fired when the close (x) is clicked
     * @param {Event} event
     */
    closeClicked: function(event) {
        if (_.isFunction(this.onClose)) {
            this.onClose(event);
        }
        app.alert.dismiss(this.key);
    },
    /**
     * Gets the HTML string for alert given options.
     *
     * @param {Object} [options] The options object passed to the alert object
     *   when it was created. See {@link #initialize} documentation to know the
     *   available options.
     * @param {string|string[]} [options.messages] The message(s) to be
     *   displayed in the alert dialog.
     * @param {Object} [templateOptions] Optional template options to be passed
     *   to the template.
     * @return {string} The generated template.
     * @private
     */
    _getAlertTemplate: function(options, templateOptions) {
        options = options || {};
        var alert = this._getAlertProps(options);
        var template = alert.templateName ? app.template.getView(alert.templateName) : app.template.empty;
        var seed = _.extend({}, {
            alertClass: alert.cssClass,
            alertIcon: alert.icon,
            title: this.getTranslatedLabels(alert.title),
            messages: this.getTranslatedLabels(options.messages),
            closeable: _.isUndefined(options.closeable) || options.closeable,
            alert: this
        }, templateOptions);

        return template(seed);
    },

    /**
     * From the given `options`, this method returns an object with
     * corresponding alert properties.
     *
     * @private
     * @param {Object} [options] Alert options like `title`, `level`, etc.
     * @param {string} [options.level] Alert level e.g. 'success', 'error' etc.
     * @param {string} [options.title] Custom alert title to be used.
     * @return {Object} Alert properties to be used when rendering the alert
     *   template.
     */
    _getAlertProps: function(options) {
        var title = options.title || '';
        var defaultTemplateName = this.name + '.error';

        switch (options.level) {
            case this.LEVEL.PROCESS:
                // Remove ellipsis from the end of the string.
                title = title.substr(-3) === '...' ? title.substr(0, title.length - 3) : title;

                return {
                    title: title || 'LBL_ALERT_TITLE_LOADING',
                    templateName: this.name + '.process',
                    cssClass: 'alert-process',
                    icon: ''
                };
            case this.LEVEL.SUCCESS:
                return {
                    title: title || 'LBL_ALERT_TITLE_SUCCESS',
                    templateName: defaultTemplateName,
                    cssClass: 'alert-success',
                    icon: 'fa-check-circle'
                };
            case this.LEVEL.WARNING:
                return {
                    title: title || 'LBL_ALERT_TITLE_WARNING',
                    templateName: defaultTemplateName,
                    cssClass: 'alert-warning',
                    icon: 'fa-exclamation-triangle'
                };
            case this.LEVEL.INFO:
                return {
                    title: title || 'LBL_ALERT_TITLE_NOTICE',
                    templateName: defaultTemplateName,
                    cssClass: 'alert-info',
                    icon: 'fa-info-circle'
                };
            case this.LEVEL.ERROR:
                return {
                    title: title || 'LBL_ALERT_TITLE_ERROR',
                    templateName: defaultTemplateName,
                    cssClass: 'alert-danger',
                    icon: 'fa-exclamation-circle'
                };
            case this.LEVEL.CONFIRMATION:
                return {
                    title: title || 'LBL_ALERT_TITLE_WARNING',
                    templateName: this.name + '.confirmation',
                    cssClass: 'alert-warning',
                    icon: 'fa-exclamation-triangle'
                };
            default:
                return {
                    title: title,
                    cssClass: '',
                    icon: 'fa-info-circle'
                };
        }
    },

    /**
     * Get CSS classes given alert level
     *
     * @deprecated Deprecated since 7.8. Will be removed in 7.9.
     * @param {string} level
     * @return {string}
     */
    getAlertClasses: function(level) {
        app.logger.warn('The View.Views.Base.AlertView#getAlertClasses has been deprecated since 7.8.0 and will be ' +
            'removed in 7.9.');

        this._getAlertProps({level: level}).cssClass;
    },

    /**
     * Get the default title given alert level
     *
     * @deprecated Deprecated since 7.8. Will be removed in 7.9.
     * @param {string} level
     * @return {string}
     */
    getDefaultTitle: function(level) {
        app.logger.warn('The View.Views.Base.AlertView#getDefaultTitle has been deprecated since 7.8.0 and will be ' +
            'removed in 7.9.');

        this._getAlertProps({level: level}).title;
    },

    /**
     * Return translated text, given a string or an array of strings.
     * @param {String/Array} stringOrArray
     * @return {String/Array}
     */
    getTranslatedLabels: function(stringOrArray) {
        var result;

        if (_.isArray(stringOrArray)) {
            result = _.map(stringOrArray, function(text) {
                return new Handlebars.SafeString(app.lang.get(text));
            });
        } else {
            result = new Handlebars.SafeString(app.lang.get(stringOrArray));
        }

        return result;
    },

    /**
     * Remove br tags after alerts which are needed to stack alerts vertically.
     */
    close: function() {
        this.unbindCancelAndReturn();
        this.$el.next('br').remove();
        this.dispose();
    },

    /**
     * Used by confirmation alerts so pressing `Esc` will Cancel, pressing
     * `Return` will Confirm
     */
    bindCancelAndReturn: function() {
        app.shortcuts.saveSession();
        app.shortcuts.createSession([
            'Alert:Confirm',
            'Alert:Cancel'
        ], this);

        app.shortcuts.register({
            id: 'Alert:Confirm',
            keys: 'enter',
            component: this,
            description: 'LBL_SHORTCUT_ALERT_CONFIRM',
            handler: function() {
                this.$('[data-action=confirm]').click();
            }
        });

        app.shortcuts.register({
            id: 'Alert:Cancel',
            keys: 'esc',
            component: this,
            description: 'LBL_SHORTCUT_ALERT_CANCEL',
            handler: function() {
                this.$('[data-action=cancel]').click();
            }
        });
    },

    /**
     * Unbind keydown event
     */
    unbindCancelAndReturn: function() {
        if (this.level === 'confirmation') {
            app.shortcuts.restoreSession();
        }
    },

    /**
     * @override
     */
    bindDataChange: function() {
    }
})
