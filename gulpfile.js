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
var filter = require('gulp-filter');
var glob = require('glob');
var gulp = require('gulp');
var gutil = require('gulp-util');
var os = require('os');
var todo = require('gulp-todo');
var insert = require('gulp-insert');
const path = require('path');
const execa = require('execa');

/**
 * A function that returns an object from a given JSON filename, which will also strip comments.
 *
 * @param {string} filename Filename to parse.
 *
 * @returns {Object} Parsed file.
 */
function readJSONFile(filename) {
    var stripJsonComments = require('strip-json-comments');
    return JSON.parse(stripJsonComments(fs.readFileSync(filename, 'utf8')));
}

/**
 * A function that returns list of first party files.
 *
 * @returns {Array} List of files to be documented.
 */
function getFirstPartyFiles() {
    return [
        'clients/base/**/*.js',
        'include/javascript/sugar7/**/*.js',
        'modules/**/clients/base/**/*.js',
    ];
}

function splitByCommas(val) {
    return val.split(',');
}

gulp.task('karma', function(done) {
    var Server = require('karma').Server;

    // get command-line arguments for karma tests
    commander
        .option('-d, --dev, --debug', 'Set Karma options for debugging')
        .option('--coverage', 'Enable code coverage')
        .option('--ci', 'Enable CI specific options')
        .option('--verbose', 'Show the running tests specifications')
        .option('--path <path>', 'Set base output path')
        .option('--manual', 'Start Karma and wait for browser to connect (manual tests)')
        .option('--team <name>', 'Filter by specified team', splitByCommas)
        .option('--file <path>', 'File or list of files to execute', splitByCommas)
        .option('--browsers <list>',
            'Comma-separated list of browsers to run tests with',
            splitByCommas
        )
        .option('--sauce', 'Run IE 11 tests on SauceLabs. Not compatible with --dev option')
        .parse(process.argv);

    // set up default Karma options
    var baseFiles = readJSONFile('gulp/assets/base-files.json');
    var tests = [];

    if (commander.team) {
        var teams = readJSONFile('../.mention-bot').alwaysNotifyForPaths;
        var team = _.findWhere(teams, {name: 'sugarcrm/eng-' + commander.team});

        if (!team) {
            return done('Cannot find the specified team');
        } else {
            process.stdout.write('Preparing tests for team `' + commander.team + '`...\n');
        }

        tests = _.reduce(team.files, function(memo, value) {
            if (!value.endsWith('**') && !value.endsWith('js')) {
                return memo;
            }

            if (value.endsWith('**')) {
                value = value + '/*.js';
            }

            if (value.startsWith('sugarcrm/tests/')) {
                value = value.replace(/^sugarcrm\//, '');
            } else {
                // TODO: As soon as most of the teams add their tests to mentionbot, we will remove this
                value = value.replace(/^sugarcrm/, 'tests/unit-js');
            }

            memo.push(value);
            return memo;
        }, []);

        // Need to filter these before passing to karma to avoid warnings
        tests = _.filter(tests, function(pattern) {
            return !_.isEmpty(glob.sync(pattern));
        });
    } else if (commander.file) {
        tests = commander.file;
    } else {
        tests = readJSONFile('gulp/assets/default-tests.json');
    }

    if (_.isEmpty(tests)) {
        return done('There are no tests defined for the current settings.');
    }

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
    path += '/karma';

    karmaOptions.preprocessors = {};
    _.each(getFirstPartyFiles(), function(value) {
        karmaOptions.preprocessors[value] = [];
    });

    if (commander.browsers) {
        karmaOptions.browsers = commander.browsers;
    }

    if (commander.verbose) {
        karmaOptions.reporters = ['spec'];
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
                    dir: path + '/coverage-html'
                }
            ]
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

// run the modern PHPUnit tests (i.e. testsunit/, not tests/)
gulp.task('test:unit:php', function(done) {
    var path = require('path');

    /**
     * Set up the environment for Jenkins.
     *
     * @param {string} workspace Base output directory.
     */
    function setUpCiConfiguration(workspace) {
        var rm = require('rimraf').sync;

        var testOutputPath = path.join(workspace, 'test-output');
        var junitOutputPath = path.join(workspace, 'junit');
        rm(testOutputPath);
        rm(junitOutputPath);
        fs.mkdirSync(testOutputPath);
        fs.mkdirSync(junitOutputPath);
    }

    commander
        .option('--ci', 'Set up CI-specific environment')
        .option('--path <path>', 'Set base output path')
        .option('--coverage', 'Enable code coverage')
        .parse(process.argv);

    var workspace = commander.path || process.env.WORKSPACE || os.tmpdir();
    var args = [];
    if (commander.ci) {
        setUpCiConfiguration(workspace);
        args.push(
            '-derror_log=' + path.join(workspace, 'test-output', 'php_errors.log'),
            '--log-tap', path.join(workspace, 'test-output', 'tap.txt'),
            '--log-junit', path.join(workspace, 'junit', 'phpunit.xml'),
            '--testdox-text', path.join(workspace, 'testdox.txt')
        );
    }

    if (commander.coverage) {
        args.push('--coverage-html', path.join(workspace, 'coverage'));
        process.stdout.write('Coverage reports will be generated to: ' + path.join(workspace, 'coverage') + '\n');
    }

    var phpunitPath = path.join('..', '..', 'vendor', 'bin', 'phpunit');
    var phpProcess = execa(phpunitPath, args, {
        maxBuffer: 1e6, // 1 MB
        cwd: 'tests/unit-php',
        reject: false,
    });
    phpProcess.stdout.pipe(process.stdout);
    phpProcess.stderr.pipe(process.stderr);
    phpProcess.then(function(result) {
        done(result.code ? 'There are failing unit tests' : undefined);
    });
});

// Task to run REST API tests on an installed instance
gulp.task('test:rest', function() {
    /**
     * Output usage text for this task and then exit.
     */
    function help() {
        cmd.outputHelp((text) => text.replace('Usage: gulp', 'Usage: gulp test:rest'));
        process.exit(1);
    }

    /**
     * Retrieve an asset from the gulp REST test assets folder.
     *
     * @param {string} assetName File name of the desired asset.
     * @return {Mixed} The result of parsing the asset as JSON.
     */
    function readAsset(assetName) {
        var path = require('path');
        var assetDirectory = 'gulp/assets/rest/';
        var asset = path.join(assetDirectory, assetName);
        return readJSONFile(asset);
    }

    /**
     * Retrieve a test suite or cluster from a REST test asset.
     * Aborts if the suite/cluster is not found.
     *
     * @param {string} assetName File name of the desired asset.
     * @param {string} suiteName Name of the test suite/cluster.
     * @return {string[]} A list of minimatch globs for tests in the suite/cluster.
     */
    function getSuiteFromAsset(assetName, suiteName) {
        var tests = readAsset(assetName)[suiteName];
        if (!tests) {
            console.error('The test suite/cluster ' + suiteName + ' cannot be found.');
            help();
        }
        return tests;
    }

    /**
     * Get a list of all desired tests based on the given options.
     *
     * @param {Object} options Options specifying which tests you want to run.
     * @param {string} [options.suite] Name of a test suite to run.
     * @param {string} [options.cluster] Name of a test cluster to run.
     * @param {boolean} [options.withoutSuite] If `true`, return tests not
     *   present in any test suite.
     * @param {boolean} [options.withoutCluster] If `true`, return tests not
     *   present in any test cluster.
     * @return {Array} A list of test file minimatch globs.
     */
    function getTestsToRun(options) {
        if (options.suite) {
            return getSuiteFromAsset('suites.json', options.suite);
        } else if (options.cluster) {
            return getSuiteFromAsset('clusters.json', options.cluster);
        } else {
            var baseTests = readAsset('default-tests.json');
            if (options.withoutSuite || options.withoutCluster) {
                var assetName = options.withoutSuite ? 'suites.json' : 'clusters.json';
                return _.union(baseTests, _.map(_.flatten(_.values(readAsset(assetName))), function(glob) {
                    return '!' + glob;
                }));
            }
            return baseTests;
        }
    }

    var mocha = require('gulp-spawn-mocha');
    var cmd = commander
        .option('--url <url>', 'Instance URL, ex: http://my.sugar.server/my/sugar/directory')
        .option('-u, --username <username>', 'Administrator username')
        .option('-p, --password <password>', 'Administrator password')
        .option('-s, --suite <suite>', 'Run only the specified test suite')
        .option('--without-suite', 'Print a list of all tests that aren\'t in a test suite')
        .option('-c, --cluster <cluster>', 'Run only the specified test cluster')
        .option('--without-cluster', 'Print a list of all tests that aren\'t in a test cluster')
        .option('--ci', 'Enable CI specific options')
        .option('--path <path>', 'Set base output path')
        .parse(process.argv);

    var testsToRun = getTestsToRun(commander);

    // don't actually run the tests for --without-suite or --without-cluster, just print out what tests are not included
    if (commander.withoutSuite || commander.withoutCluster) {
        var globby = require('globby');
        return globby(testsToRun).then(function(paths) {
            if (_.isEmpty(paths)) {
                process.stdout.write('All tests are currently part of a suite/cluster.' + os.EOL);
                process.exit(0);
            }
            process.stdout.write('Tests not part of any suite/cluster:' + os.EOL);
            process.stdout.write(paths.join(os.EOL) + os.EOL);
            process.exit(1);
        });
    }

    var env = {};

    env.THORN_SERVER_URL = commander.url || process.env.THORN_SERVER_URL;
    if (!env.THORN_SERVER_URL) {
        console.error('Either setting $THORN_SERVER_URL or the --url flag is required.');
        help();
    }
    env.THORN_SERVER_URL = env.THORN_SERVER_URL.replace(/\/+$/, '');

    if (commander.username) {
        env.THORN_ADMIN_USERNAME = commander.username;
    } else if (!process.env.THORN_ADMIN_USERNAME) {
        console.error('Either setting $THORN_ADMIN_USERNAME or the --username flag is required.');
        help();
    }

    if (commander.password) {
        env.THORN_ADMIN_PASSWORD = commander.password;
    } else if (!process.env.THORN_ADMIN_PASSWORD) {
        console.error('Either setting $THORN_ADMIN_PASSWORD or the --password flag is required.');
        help();
    }

    if (_.compact([commander.suite, commander.cluster, commander.withoutSuite, commander.withoutCluster]).length > 1) {
        console.error('The options --suite, --cluster, --without-suite, and --without-cluster are mutually exclusive.');
        help();
    }

    var options = {
        env: env,
        timeout: 15000,
        require: 'co-mocha',
        reporter: 'spec',
    };

    if (commander.ci) {
        var path = commander.path || process.env.WORKSPACE || os.tmpdir();
        path += '/test-rest';
        options.reporter = 'mocha-junit-reporter';
        options.reporterOptions = 'mochaFile=' + path + '/test-results.xml';

        options.bail = true;
        options.timeout = 60000;

        process.stdout.write('Test reports will be generated to: ' + path + '\n');
    }

    return gulp.src(testsToRun, {read: false})
        .pipe(mocha(options));
});

// confirm our files have the desired license header
gulp.task('check-license', function(done) {
    var options = {
        excludedExtensions: [
            'json',
            'swf',
            'log',
            // image files
            'gif',
            'jpeg',
            'jpg',
            'png',
            'ico',
            'tiff',
            // special system files
            'DS_Store',
            // Doc files
            'md',
            'txt',
            'pdf',
            // vector files
            'svg',
            'svgz',
            // font files
            'eot',
            'ttf',
            'woff',
            'otf',
            // stylesheets
            'less',
            'css',
            // VCard files
            'vcf',
            // Git quirks
            'git',
            'gitkeep',
            'gitignore',
            // calendar files
            'ics',
            // data files
            'csv',
            // archives
            'zip',
            // dotfiles
            'editorconfig',
            'npmrc',
            'jscsrc',
            'babelrc',
            // lock files
            'lock',
            // checksum lists
            'md5'
        ],
        licenseFile: 'LICENSE',
        // Add paths you want to exclude in the whiteList file.
        whiteList: 'gulp/assets/check-license/license-white-list.json'
    };

    var exec = require('child_process').exec;

    var licenseFile = options.licenseFile;
    var whiteList = options.whiteList;
    var excludedExtensions = options.excludedExtensions.join('|');

    //Prepares excluded files.
    var excludedFiles = JSON.parse(fs.readFileSync(whiteList, 'utf8'));
    excludedFiles = _.map(excludedFiles, function(f) {
        return './' + f;
    }).join('\\n');

    var pattern = fs.readFileSync(licenseFile).toString();
    pattern = pattern.trim();

    //Add '*' in front of each line.
    pattern = pattern.replace(/\n/g, '\n \*');
    //Add comment token at the beginning and the end of the text.
    pattern = pattern.replace(/^/, '/\*\n \*');
    pattern = pattern.replace(/$/, '\n \*/');
    //Put spaces after '*'.
    pattern = pattern.replace(/\*(?=\w)/g, '\* ');

    // Prepares the PCRE pattern.
    pattern = pattern.replace(/\*/g, '\\*');
    pattern = pattern.replace(/\n/g, '\\s');
    pattern = pattern.replace(/\(/g, '\\(');
    pattern = pattern.replace(/\)/g, '\\)');

    var cmdOptions = [
        '--buffer-size=10M',
        '-M',
        // The output will be a list of files that don't match the pattern.
        '-L',
        // Recursive mode.
        '-r',
        // Ignores case.
        '-i',
        // Excluded extensions.
        '--exclude="((.*)\.(' + excludedExtensions + '))"',
        // Pattern to match in each file.
        '"^' + pattern + '$"',
        // Directory where the command is executed.
        '.'
    ];

    var command = 'pcregrep ' + cmdOptions.join(' ') + '| grep -v -F "$( printf \'' + excludedFiles + '\' )"';

    // Runs the command.
    exec(command, {maxBuffer: 2000 * 1024}, function(error, stdout, stderr) {
        if (stderr.length !== 0) {
            done(stderr);
        } else if (stdout.length !== 0) {
            // Invalid license headers found
            done(stdout);
        } else {
            // All files have the exact license specified in `sugarcrm/LICENSE`
            done();
        }
    });
});

function getFilesToLint() {
    return _.union(
        ['**/*.js'],
        _.map(require('./.jscs.json').excludeFiles, function(str) {
            return '!' + str;
        })
    );
}

gulp.task('jscs', function() {
    var jscs = require('gulp-jscs');
    return gulp.src(getFilesToLint())
        .pipe(jscs())
        .pipe(jscs.reporter());
});

gulp.task('jshint', function() {
    var jshint = require('gulp-jshint');
    return gulp.src(getFilesToLint())
        .pipe(jshint())
        .pipe(jshint.reporter());
});

gulp.task('lint', ['jshint', 'jscs']);

gulp.task('lint:css', function() {
    var stylelint = require('gulp-stylelint');
    return gulp
        .src(['styleguide/less/**/*.less', '!styleguide/less/lib/**/*.less'])
        .pipe(stylelint({
            reporters: [
              {formatter: 'string', console: true},
            ],
        }));
});

gulp.task('find-todos', function() {
    var teams = require('./gulp/plugins/team/team.js');
    commander
        .option('--teams <list>', 'Choose teams to filter by', splitByCommas)
        .option('--path <path>', 'Set output path')
        .parse(process.argv);
    var destPath = commander.path || os.tmpdir();
    console.log('Results will be output to ' + destPath + '/TODO.md');
    var teamsChosen = commander.teams;
    try {
        return gulp.src('**/*.{js,php}')
            .pipe(!_.isEmpty(teamsChosen) ? filter(teams(teamsChosen)) : gutil.noop())
            .pipe(todo())
            .pipe(gulp.dest(destPath));
    } catch (e) {
        console.error(e.toString());
    }
});

gulp.task('copy-sucrose', function() {
    gulp.src([
            'node_modules/@sugarcrm/d3-sugar/build/d3-sugar.min.js'
        ])
        .pipe(insert.prepend(';'))
        .pipe(gulp.dest('include/javascript/d3-sugar/'));
    gulp.src([
            'node_modules/@sugarcrm/d3-sugar/LICENSE'
        ])
        .pipe(gulp.dest('include/javascript/d3-sugar/'));
    gulp.src([
            'node_modules/@sugarcrm/sucrose-sugar/build/sucrose.min.js',
            'node_modules/@sugarcrm/sucrose-sugar/LICENSE',
        ])
        .pipe(gulp.dest('include/javascript/sucrose/'));
    gulp.src([
            'node_modules/@sugarcrm/sucrose-sugar/src/less/**/*',
            '!node_modules/@sugarcrm/sucrose-sugar/src/less/**/sucrose*.less',
            '!node_modules/@sugarcrm/sucrose-sugar/src/less/**/tooltip.less',
            '!node_modules/@sugarcrm/sucrose-sugar/src/less/**/variables.less',
        ])
        .pipe(gulp.dest('styleguide/less/lib/sucrose/'));
    gulp.src([
            'node_modules/d3fc-rebind/build/d3fc-rebind.min.js',
            'node_modules/d3fc-rebind/LICENSE',
        ])
        .pipe(gulp.dest('include/javascript/d3fc-rebind/'));
});
