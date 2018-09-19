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

const commander = require('commander');
const os = require('os');
const gulp = require('gulp');

const filesToDoc = ['src/**/*.js', 'lib/(sugar*)/**/*.js'];

gulp.task('lint', function() {
    const defaultFiles = ['*.js', 'gulp/**/*.js', 'src/**/*.js', 'tests/**/*.js', 'lib/(sugar*)/**/*.js'];
    commander
        .option('--files <list>', 'Files to lint')
        .parse(process.argv);

    let files = defaultFiles;
    const eslint = require('gulp-eslint');

    if (commander.files) {
        files = commander.files.split(',');
    }

    return gulp.src(files)
        .pipe(eslint())
        .pipe(eslint.format('unix'));
        // FIXME SC-5268: add a fail reporter
        // .pipe(eslint.failAfterError());
});

gulp.task('jsdoc', function(cb) {
    const eslint = require('gulp-eslint');
    const gutil = require('gulp-util');
    const jsdoc = require('@sugarcrm/gulp-jsdoc3');
    const jsdocConfig = require('./jsdoc.json');
    const rimraf = require('rimraf').sync;

    rimraf('docs');

    commander
        .option('--verbose', 'Output JSDoc warnings')
        .parse(process.argv);

    gulp.src(filesToDoc)
        .pipe(commander.verbose ? eslint({
            rules: {
                'valid-jsdoc': [
                    'error',
                    {
                        requireReturn: false,
                        prefer: {
                            return: 'return',
                        },
                    },
                ],
            },
            useEslintrc: false,
            parser: 'babel-eslint',
        }) : gutil.noop())
        .pipe(commander.verbose ? eslint.format('unix') : gutil.noop())
        .pipe(jsdoc(jsdocConfig, cb));
});

gulp.task('build', function(done) {
    commander
        .option('--dev', 'Do not mangle the output.')
        .parse(process.argv);

    if (commander.dev) {
        process.env.DEV = 1;
    }

    const webpack = require('webpack');
    return webpack(require('./webpack.config.js'), function(err) {
        return done(err ? err : undefined);
    });
});

gulp.task('karma', function(done) {
    const {Server} = require('karma');
    const path = require('path');

    // get command-line arguments (only relevant for karma tests)
    commander
        .option('--zepto', 'Use zepto instead of jQuery')
        .option('-d, --dev', 'Set Karma options for debugging')
        .option('--coverage', 'Enable code coverage')
        .option('--ci', 'Enable CI specific options')
        .option('--path <path>', 'Set base output path')
        .option('--port <port>', 'Set Karma server port')
        .option('--manual', 'Start Karma and wait for browser to connect (manual tests)')
        .option('--browsers <list>',
            'Comma-separated list of browsers to run tests with',
            function(val) {
                return val.split(',');
            }
        )
        .option('--sauce', 'Run IE 11 tests on SauceLabs. Not compatible with --dev option')
        .parse(process.argv);

    if (commander.zepto) {
        process.env.ZEPTO = 1;
    }

    // set up default Karma options
    const karmaOptions = {
        configFile: path.join(__dirname, '/gulp/karma.conf.js'),
        browsers: ['ChromeHeadless'],
        autoWatch: false,
        singleRun: true,
        reporters: ['dots'],
        port: commander.port,
    };

    let karmaPath = commander.path || os.tmpdir();
    karmaPath += '/karma/sidecar';

    if (commander.browsers) {
        karmaOptions.browsers = commander.browsers;
    }

    if (commander.coverage) {

        karmaOptions.reporters.push('coverage');

        karmaOptions.coverageReporter = {
            reporters: [
                {
                    type: 'cobertura',
                    dir: karmaPath + '/coverage-xml',
                    file: 'cobertura-coverage.xml',
                    subdir: function() {
                        return '';
                    },
                },
                {
                    type: 'html',
                    dir: karmaPath + '/coverage-html',
                },
            ],
        };

        process.stdout.write('Coverage reports will be generated to: ' + karmaPath + '\n');
    }

    if (commander.ci) {
        karmaOptions.reporters.push('junit');

        karmaOptions.junitReporter = {
            outputDir: karmaPath,
            outputFile: 'test-results.xml',
            useBrowserName: false,
        };
    }

    if (commander.manual) {
        karmaOptions.browsers = [];
        karmaOptions.singleRun = false;
        karmaOptions.autoWatch = true;
    } else if (commander.dev) {
        karmaOptions.autoWatch = true;
        karmaOptions.singleRun = false;
        if (!commander.browsers) {
            karmaOptions.browsers = ['Chrome'];
        }
    } else if (commander.sauce) {
        // --dev isn't supported for --sauce
        karmaOptions.reporters.push('saucelabs');
        karmaOptions.browsers = ['sl_ie'];

        // sauce is slower than local runs...
        karmaOptions.reportSlowerThan = 2000;

        // and 60 seconds of timeout seems to be normal...
        karmaOptions.browserNoActivityTimeout = 60000;
    }

    new Server(karmaOptions, function(exitStatus) {
        // Karma's return status is not compatible with gulp's streams
        // See: http://stackoverflow.com/questions/26614738/issue-running-karma-task-from-gulp
        // or: https://github.com/gulpjs/gulp/issues/587 for more information
        done(exitStatus ? 'There are failing unit tests' : undefined);
    }).start();
});

gulp.task('default', ['build']);

gulp.task('watch-docs', function() {
    gulp.watch(filesToDoc, ['jsdoc']);
});
