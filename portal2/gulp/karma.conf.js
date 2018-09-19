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
    config.set({
        basePath: '../../',
        frameworks: [
            'jasmine',
        ],
        plugins: [
            'karma-jasmine',
            'karma-chrome-launcher',
            'karma-firefox-launcher',
            'karma-safari-launcher',
            'karma-sauce-launcher',
            'karma-coverage',
            'karma-junit-reporter',
        ],
        proxies: {
            '/clients': '/base/clients',
            '/fixtures': '/base/tests/unit-js/fixtures',
            '/tests/modules': '/base/tests/unit-js/modules',
            '/include': '/base/include',
            '/modules': '/base/modules',
            '/portal2': '/base/portal2',
        },
        reportSlowerThan: 500,
        browserDisconnectTimeout: 5000,
        browserDisconnectTolerance: 5,
        sauceLabs: {
            testName: 'Portal Karma Tests',
        },
        customLaunchers: {
            dockerChromeHeadless: {
                base: 'ChromeHeadless',
                flags: ['--no-sandbox'],
            },
            docker_chrome: {
                base: 'Chrome',
                flags: ['--no-sandbox'],
            },
            sl_ie: {
                base: 'SauceLabs',
                browserName: 'internet explorer',
                platform: 'Windows 7',
                version: '11.0',
            },
        },
    });
};
