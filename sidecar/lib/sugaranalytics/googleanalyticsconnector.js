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
    var optOut;

    app.analytics = app.analytics || {};
    app.analytics.connectors = app.analytics.connectors || {};

    // Use with newer Universal GA clients
    app.analytics.connectors.GoogleAnalytics  = {

        /* initialize()
         *
         * - called on app:init, load the analytics api.
         *
         * @member SUGAR.analytics.connectors.GoogleAnalytics
         */
        initialize: function() {
            // this code is taken directly from Google's analytics.js API
            // https://developers.google.com/analytics/devguides/collection/analyticsjs/advanced
            /* jshint ignore:start */
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            /* jshint ignore:end */
        },

        /* start(id, options)
         *
         * - called on app:start, prepare or open the connection to the analytics system.
         *
         * @param {string} id Tracking id for the analytics system.
         * @param {Object} options SUGAR.App.config.analytics configuration.
         * @member SUGAR.analytics.connectors.GoogleAnalytics
         */
        start: function (id, options) {
            options.gaOptions = options.gaOptions || {};
            _.defaults(options.gaOptions, {
                cookieDomain: 'none',
                forceSSL: true,
            });
            ga('create', id, options.gaOptions);
            this.enableIPAnonymization();
            optOut = options.optOut;
            if (optOut) {
                window['ga-disable-' + id] = true;
            }
        },
        /* trackPageView(pageURI)
         *
         * - track a change of page.
         *
         * @param {string} pageURI URI of the page viewed.
         * @member SUGAR.analytics.connectors.GoogleAnalytics
         */
        trackPageView: function(pageUri) {
            ga('send', 'pageview', pageUri);
        },
        /* trackEvent()
         *
         * - track an event on the page.
         *
         * @param {Object} event Google Analytics event to track.
         * @param {string} event.category Category of the event.
         * @param {string} event.action Action of the event.
         * @param {string} event.label Always set to the route the user is on.
         * @param {int} [event.value] Value of the event.
         * @member SUGAR.analytics.connectors.GoogleAnalytics
         */
        trackEvent: function(event) {
            ga('send', 'event', event.category, event.action, event.label, event.value);
        },

        /**
         * Set tracker params.
         * @param {string} key The param name.
         * @param {Mixed} value The value to send to the tracker.
         */
        set: function(key, value) {
            ga('set', key, value);
        },

        /**
         * Enable IP anonymization on tracker.
         *
         * The last octet of client's (sender) IPv4 will be set to 0 (or the
         * last 80 bits of IPv6 will be set to 0)
         * Reference: https://support.google.com/analytics/answer/2763052
         */
        enableIPAnonymization: function() {
            this.set('anonymizeIp', true);
        }
    };
})(SUGAR.App);
