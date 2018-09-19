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

const AlertView = require('view/alert-view');
const Utils = require('utils/utils');
const ViewManager = require('view/view-manager');

/**
 * Alert.
 *
 * Interface for creating alerts via show and dismiss. Also, this module
 * keeps an internal dictionary of alerts created which can later be accessed
 * by key. This is useful so that client code can dismiss a particular alert.
 *
 * Note that the client application may provide custom implementation of the
 * {@link View/AlertView} class. This implementation will be in charge
 * of rendering the alert to its UI.
 *
 * At minimum, a client app must provide:
 *
 * 1. `#alerts` element in its `index.html`.
 * 2. A precompiled template called `alert`. The template can be compiled at
 *    application start-up:
 *    `app.template.compile('alert', 'template body...');`
 *
 * @module View/Alert
 */
// Dictionary of alerts
var _alerts = {};

/**
 * @alias module:View/Alert
 */
const Alert = {
    //When this flag is set to true, it prevents any new alert to be shown.
    //This is useful for `confirmation` type alerts because you want the
    //user to focus and confirm/cancel the action and don't bother him with
    //anything else.
    preventAnyAlert: false,

    /**
     * Initializes this module at application start-up.
     */
    init: function() {
        /**
         * Alert element selector.
         *
         * The default value is `$('#alerts')`. Override using {@link Config#alertsEl} setting.
         * @type {Object}
         */
        this.$alerts = $('#alerts');

        /**
         * Alert view class.
         *
         * The default value is {@link View/AlertView}.
         * However, if a `ViewManager.[Capitalized-appId]AlertView` or
         * `ViewManager.[Capitalized-platform]AlertView` class exists, it
         * will be used instead.
         *
         * @type {Function}
         * @memberOf module:View/Alert
         * @name klass
         */
        this.klass = AlertView;

        if (SUGAR.App.config) {
            this.$alerts = $(SUGAR.App.config.alertsEl).length ? $(SUGAR.App.config.alertsEl) : this.$alerts;

            this.klass = ViewManager[Utils.capitalize(SUGAR.App.config.appId) + 'AlertView'] ||
                ViewManager.views[Utils.capitalize(SUGAR.App.config.platform) + 'AlertView'] ||
                ViewManager.views.BaseAlertView ||
                ViewManager[Utils.capitalize(SUGAR.App.config.platform) + 'AlertView'] ||
                this.klass;
        }

        // Check for existing alerts and convert them into AlertView.
        _.each(this.$alerts.find('.alert-wrapper'), function(el, i) {
            var key = 'init-' + i;
            _alerts[key] = this._create(key, { el: el });
        }, this);
    },

    /**
     * Displays an alert message and adds to the internal dictionary of alerts.
     * Use supplied key later to dismiss the alert. Caller is responsible for
     * using language translation before calling!
     *
     * To create and close alerts, this function instantiates the
     * {@link View/AlertView} class specified by the
     * {@link View.Alert#klass} property and places the alert instance in the
     * DOM as the first element in the {@link View.Alert#$alerts} element.
     *
     * The {@link View/AlertView} class provides boilerplate implementation
     * for a typical single alert view. You can customize alert behavior by
     * extending the {@link View/AlertView} class.
     * At minimum, you have to make sure that a pre-compiled template named
     * `'alert'` exists.
     *
     * In case we are showing a `confirmation` alert, we want to dismiss all
     * other existing alerts and prevent any other alerts.
     *
     * Examples:
     * ```
     * const Alert = require('view/alert');
     * var a1 = Alert.show('delete_warning', {
     *     level: 'warning',
     *     title: 'Warning',
     *     messages: 'Are you sure you want to delete this record?',
     *     autoclose: true
     * });
     *
     * var a2 = Alert.show('internal_error', {
     *     level: 'error',
     *     messages: ['Internal Error', 'Response code: 500']
     * })
     * ```
     *
     * @param {string} key Index into the cache of defined alert views.
     * @param {Object} [options] The options specified here are handled by
     *   the framework.
     * @param {string} [options.level] Alert level. `alert-[level]` class
     *   will be added to the alert view.
     * @param {boolean} [options.autoClose] Boolean flag indicating if the
     *   alert must be closed after dismiss delay: See
     *   {@link Config#alertAutoCloseDelay} setting.
     * @param {string|string[]} [options.messages] Messages.
     * @param {string} [options.title] The title of the alert.
     *   It is displayed in bold.
     * @return {View/AlertView} AlertView instance.
     */
    show: function(key, options) {
        if (!this.$alerts || !this.$alerts.length) {
            return null;
        }

        if (this.preventAnyAlert) return null;

        options = _.extend({
            level: 'info',
            autoClose: false
        }, options || {});

        if (options.level === 'confirmation') {
            this.dismissAll();
            this.preventAnyAlert = true;
        }

        if (options.messages) {
            options.messages = _.isString(options.messages) ? [options.messages] : options.messages;
        }

        var alert = _alerts[key];
        // Create a new alert view if it doesn't exist
        if (!alert) {
            alert = this._create(key, options);
            _alerts[key] = alert;
        }

        alert.level = options.level;

        // Initialize autoclose timer
        if (!!options.autoClose) this._setAutoCloseTimer(alert, options.onAutoClose, options.autoCloseDelay);

        alert.render();

        return alert;
    },

    /**
     * Creates an instance of the {@link View/AlertView} class and places
     * the view in the DOM.
     *
     * @param {string} key Alert ID.
     * @param {Object} options Options.
     * @return {View/AlertView} Instance of alert view class.
     * @private
     */
    _create: function(key, options) {
        var alert = new this.klass(options);
        alert.key = key;
        alert.$el.prependTo(this.$alerts);
        return alert;
    },

    /**
     * Sets the timeout to dismiss the alert view.
     *
     * @param {Object} alert the alert to auto close
     * @param {Function} onAutoClose Callback to call on the autoclose.
     * @param {number} [autoCloseDelay] The time after which the alert may be
     *  dismissed.
     * @private
     */
    _setAutoCloseTimer: function(alert, onAutoClose, autoCloseDelay) {
        if (alert.timerId) clearTimeout(alert.timerId);
        alert.timerId = setTimeout(_.bind(function() {
            if(_.isFunction(onAutoClose)) {
                onAutoClose(alert.key); //callback for when the timeout occurs and the alert is closing
            }
            this.dismiss(alert.key);

        }, this), autoCloseDelay || SUGAR.App.config.alertAutoCloseDelay || 5000);
    },

    /**
     * Removes an alert message by key.
     *
     * @param {string} key The key provided when previously calling show.
     * @param {Object} [options] Options.
     * @method
     */
    dismiss: function(key, options) {
        this.preventAnyAlert = false;
        var alert = _alerts[key];
        if (!alert) return;
        if (alert.timerId) clearTimeout(alert.timerId);
        alert.dispose();
        delete _alerts[key];
    },

    /**
     * Removes all alert messages with a given level.
     *
     * @param {string} [level] Level of alerts to dismiss. If not specified,
     *   all alerts are dismissed.
     * @param {Object} [options] Dismissal options.
     */
    dismissAll: function(level, options) {
        _.each(_alerts, function(alert, key) {
            if (!level || alert.level == level) {
                this.dismiss(key, options);
            }
        }, this);
    },

    /**
     * Gets an alert with a given key.
     *
     * @param {string} key The key of the alert to retrieve.
     * @return {View/AlertView} Alert view with the specified key.
     */
    get: function(key) {
        return _alerts[key];
    },

    /**
     * Gets alerts that are currently displayed.
     *
     * @return {Object} All alerts.
     */
    getAll: function() {
        return _alerts;
    }
};

module.exports = Alert;
