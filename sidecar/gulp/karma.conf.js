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

const webpack = require('webpack');
const path = require('path');

module.exports = function(config) {
    config.set({
        basePath: '../',

        files: [
            {
                pattern: 'tests/fixtures/*',
                included: false,
                served: true,
                watched: false,
            },

            'node_modules/sinon/pkg/sinon.js',
            'node_modules/jasmine-sinon/lib/jasmine-sinon.js',

            {pattern: 'tests/index.js', watched: false},
        ],

        preprocessors: {
            'tests/index.js': ['webpack', 'sourcemap'],
        },

        frameworks: [
            'jasmine',
        ],
        plugins: [
            'karma-webpack',
            'karma-sourcemap-loader',
            'karma-jasmine',
            'karma-chrome-launcher',
            'karma-firefox-launcher',
            'karma-safari-launcher',
            'karma-sauce-launcher',
            'karma-coverage',
            'karma-junit-reporter',
        ],
        reportSlowerThan: 500,
        browserDisconnectTimeout: 5000,
        browserDisconnectTolerance: 5,
        sauceLabs: {
            testName: 'Sidecar Karma Tests',
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
            sl_safari: {
                base: 'SauceLabs',
                browserName: 'safari',
                platform: 'OS X 10.11',
                version: '9.0',
            },
            sl_firefox: {
                base: 'SauceLabs',
                browserName: 'firefox',
                platform: 'Linux',
                version: 54.0,
            },
            sl_ie: {
                base: 'SauceLabs',
                browserName: 'internet explorer',
                platform: 'Windows 7',
                version: '11.0',
            },
        },
        webpack: {
            devtool: 'inline-source-map',
            module: {
                rules: [
                    {
                        test: /\.js$/,
                        exclude: /(node_modules|lib)/,
                        use: {
                            loader: 'babel-loader',
                            options: {
                                presets: ['es2015'],
                            },
                        },
                    },
                    {
                        test: /\.js$/,
                        include: [
                            path.resolve('src'),
                            /lib\/sugar.*/,
                        ],
                        use: {
                            loader: 'istanbul-instrumenter-loader',
                            options: {
                                esModules: true,
                            },
                        },
                        enforce: 'pre',
                    },
                ],
            },
            plugins: [
                new webpack.DefinePlugin({
                    ZEPTO: JSON.stringify(process.env.ZEPTO),
                }),
            ],
            resolve: {
                modules: [
                    path.resolve(__dirname, '../src'),
                    path.resolve(__dirname, '../lib'),
                    path.resolve(__dirname, '../node_modules'),
                ],
            },
        },
        webpackMiddleware: {
            stats: 'errors-only',
        },
    });
};
