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

const BeforeEvent = require('core/before-event');
const Events = require('core/events');

/**
 * Manages routing behavior.
 *
 * The default implementation provides `before` and `after` callbacks that are
 * executed before and after a route gets triggered.
 *
 * @module Core/Routing
 * @mixes Core/BeforeEvent
 */
/**
 * @alias module:Core/Routing
 */
const Routing = {
    /**
     * Checks if a user is authenticated before triggering a route.
     *
     * @param {string} route Route name.
     * @param {Array} [args] Route parameters.
     * @return {boolean} `true` if the route should be triggered; `false`
     *   otherwise.
     */
    beforeRoute: function (route, args) {
        if (!this.triggerBefore('route', { route:route, args:args })) {
            return false;
        }

        // skip this check for all white-listed routes (SUGAR.App.config.unsecureRoutes)]
        if (_.indexOf(SUGAR.App.config.unsecureRoutes, route) >= 0) {
            return true;
        }

        // Check if a user is un-authenticated and redirect him to login
        if (!SUGAR.App.api.isAuthenticated()) {
            SUGAR.App.router.login();
            return false;
        } else if (!SUGAR.App.isSynced) {
            Backbone.history.stop();
            SUGAR.App.sync();
            return false;
        }

        return true;
    },

    /**
     * Gets called after a route gets triggered.
     *
     * The default implementation does nothing.
     *
     * @param {string} route Route name.
     * @param {Array} [args] Route parameters.
     */
    after: function (route, args) {
        // Do nothing
    },

    /**
     * Creates a router instance, attaches it to the App object and starts it.
     *
     * @fires 'router:start'
     */
    start: function() {
        var opts = {};
        SUGAR.App.augment("router", new SUGAR.App.Router(opts), false);
        Events.trigger("router:start", SUGAR.App.router);
        SUGAR.App.router.init();
        SUGAR.App.router.start();
    },

    /**
     * Internal use only - for unit testing Routers.
     *
     * Disables `Backbone.history` temporarily.
     *
     * @deprecated since 7.10. Please use {@link Core.Router#stop} instead.
     */
    stop: function() {
        SUGAR.App.logger.warn('`Core.Routing#stop` method is deprecated since 7.10. Please use ' +
            '`Core.Router#stop` instead.');
        SUGAR.App.router.stop();
    }
};

//Mix in the beforeEvents
_.extend(Routing, BeforeEvent);

module.exports = Routing;
