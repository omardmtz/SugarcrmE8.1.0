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

module.exports = function(config) {
    var sauceLaunchers = {
        sl_safari: {
            base: 'SauceLabs',
            browserName: 'safari',
            platform: 'OS X 10.11',
            version: '9.0'
        },
        sl_firefox: {
            base: 'SauceLabs',
            browserName: 'firefox',
            platform: 'Linux',
            version: 44.0
        },
        sl_ie: {
            base: 'SauceLabs',
            browserName: 'internet explorer',
            platform: 'Windows 7',
            version: 11.0
        }
    };

    var dockerLaunchers = {
        docker_chrome: {
            base: 'Chrome',
            flags: ['--no-sandbox']
        }
    };

    config.set({
        basePath: '../',
        frameworks: [
            'jasmine'
        ],
        specReporter: {
            suppressSkipped: true,
        },
        plugins: [
            'karma-chrome-launcher',
            'karma-coverage',
            'karma-firefox-launcher',
            'karma-jasmine',
            'karma-junit-reporter',
            'karma-sauce-launcher',
            'karma-spec-reporter',
            'karma-safari-launcher'
        ],
        proxies: {
            '/clients': '/base/clients',
            // FIXME we need a better way to load fixtures that is stable
            // it should be:
            // '/tests': '/base/tests',
            // so that we can provide all test requests in the correct /base/ path
            '/fixtures': '/base/tests/unit-js/fixtures',
            '/tests/modules': '/base/tests/unit-js/modules',
            '/include': '/base/include',
            '/modules': '/base/modules',
            '/portal2': '/base/portal2'
        },
        reportSlowerThan: 500,
        browserDisconnectTimeout: 5000,
        browserDisconnectTolerance: 5,
        sauceLabs: {
            testName: 'Mango Karma Tests'
        },
        customLaunchers: Object.assign(sauceLaunchers, dockerLaunchers)
    });
};
