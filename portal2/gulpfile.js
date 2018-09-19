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

var _ = require('lodash');
var commander = require('commander');
var fs = require('fs');
var gulp = require('gulp');
var os = require('os');

/**
 * A function that returns an object from a given JSON filename, which will also strip comments.
 *
 * @param {string} filename Filename to parse.
 * @return {Object} Parsed file.
 */
function readJSONFile(filename) {
    var stripJsonComments = require('strip-json-comments');
    return JSON.parse(stripJsonComments(fs.readFileSync(filename, 'utf8')));
}

/**
 * A function that returns list of first party files.
 *
 * @return {Array} List of files to be documented.
 */
function getFirstPartyFiles() {
    return [
        'portal2/lib/sugar.searchahead.js',
        'portal2/portal.js',
        'portal2/user.js',
        'portal2/error.js',
        'clients/portal/**/*.js',
        'modules/**/clients/portal/**/*.js',
    ];
}

function splitByCommas(val) {
    return val.split(',');
}

gulp.task('karma', function(done) {

    const { Server } = require('karma');

    // get command-line arguments (only relevant for karma tests)
    commander
        .option('-d, --dev', 'Set Karma options for debugging')
        .option('--coverage', 'Enable code coverage')
        .option('--ci', 'Enable CI specific options')
        .option('--path <path>', 'Set base output path')
        .option('--browsers <list>',
            'Comma-separated list of browsers to run tests with',
            splitByCommas
        )
        .parse(process.argv);

    // set up default Karma options
    var baseFiles = readJSONFile('gulp/assets/base-files.json');
    var tests = readJSONFile('gulp/assets/default-tests.json');

    var karmaAssets = _.flatten([
        baseFiles,
        tests
    ], true);

    var karmaOptions = {
        files: karmaAssets,
        configFile: __dirname + '/gulp/karma.conf.js',
        browsers: ['ChromeHeadless'],
        autoWatch: false,
        singleRun: true,
        reporters: ['dots'],
    };

    var path = commander.path || os.tmpdir();
    path += '/karma/portal';

    karmaOptions.preprocessors = {};
    _.each(getFirstPartyFiles(), function(value) {
        karmaOptions.preprocessors[value] = [];
    });

    if (commander.browsers) {
        karmaOptions.browsers = commander.browsers;
    }

    if (commander.coverage) {
        _.each(karmaOptions.preprocessors, function(value, key) {
            karmaOptions.preprocessors[key].push('coverage');
        });

        karmaOptions.reporters.push('coverage');

        karmaOptions.coverageReporter = {
            reporters: [
                {
                    type: 'cobertura',
                    dir: path + '/coverage-xml',
                    file: 'cobertura-coverage.xml',
                    subdir: function() {
                        return '';
                    }
                },
                {
                    type: 'html',
                    dir: path + '/coverage-html',
                },
            ],
        };

        process.stdout.write('Coverage reports will be generated to: ' + path + '\n');
    }

    if (commander.ci) {
        karmaOptions.reporters.push('junit');

        karmaOptions.junitReporter = {
            outputDir: path,
            outputFile: 'test-results.xml',
            useBrowserName: false,
        };
    }

    if (commander.dev) {
        karmaOptions.autoWatch = true;
        karmaOptions.singleRun = false;
        if (!commander.browsers) {
            karmaOptions.browsers = ['Chrome'];
        }
    }

    new Server(karmaOptions, function (exitStatus) {
        // Karma's return status is not compatible with gulp's streams
        // See: http://stackoverflow.com/questions/26614738/issue-running-karma-task-from-gulp
        // or: https://github.com/gulpjs/gulp/issues/587 for more information
        done(exitStatus ? 'There are failing unit tests' : undefined);
    }).start();
});
