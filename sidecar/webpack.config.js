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
const devMode = process.env.DEV;

module.exports = {
    devtool: 'source-map',

    entry: {
        sidecar: [
            'entry.js',
        ],
    },

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
        ],
    },

    output: {
        path: path.resolve(__dirname, 'minified'),
        filename: '[name].min.js',
        sourceMapFilename: '[name].min.js.map',

        // map the path correctly to avoid being inside of webpack://
        devtoolModuleFilenameTemplate: 'sidecar:///[resourcePath]',
        devtoolFallbackModuleFilenameTemplate: 'sidecar:///[resourcePath]?[hash]',
    },

    plugins: [
        new webpack.DefinePlugin({
            ZEPTO: JSON.stringify(false),
        }),

        new webpack.optimize.UglifyJsPlugin({
            compress: !devMode,
            mangle: devMode ? false : true,
            sourceMap: true,
        }),
    ],

    resolve: {
        modules: [
            path.join(__dirname, 'src'),
            path.join(__dirname, 'lib'),
            path.join(__dirname, 'node_modules'),
        ],
    },
};
