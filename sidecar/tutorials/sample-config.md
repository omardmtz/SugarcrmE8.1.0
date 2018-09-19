
This is an example of a config that can be used in a sidecar application.

```
(function(app) {

    /**
     * Application configuration.
     */
    app.augment('config', {

        /**
         * Application identifier.
         * @type {string}
         */
        appId: 'portal',

        /**
         * Application environment. Possible values: `dev`, `test`, `prod`
         * @type {string}
         */
        env: 'dev',

        /**
         * Flag indicating whether to output Sugar API debug information.
         * @type {boolean}
         */
        debugSugarApi: true,

        /**
         * Logger configuration.
         * @type {Object} logger
         * @property {string} logger.level The logger level
         * @property {string} logger.formatter The formatter to use. Defaults to
         *   `SimpleFormatter`.
         * @property {string} logger.consoleWriter The writer to use for the client
         *   side. Defaults to `ConsoleWriter`.
         * @property {string} logger.serverWriter The writer to use for the server
         *  side. Defaults to `ServerWriter`.
         */
        logger: {
            level: 'DEBUG',
        },

        /**
         * Sugar REST server URL.
         *
         * The URL can be relative or absolute.
         * @type {string}
         */
        serverUrl: '../../sugarcrm/rest/v10',

        /**
         * Sugar site URL.
         *
         * The URL can be relative or absolute.
         * @type {string}
         */
        siteUrl: '../../sugarcrm',

        /**
         * Minimal server version a client is compatible with.
         * @type {string}
         */
        minServerVersion: '6.6',

        /**
         * Server request timeout (in seconds).
         * @type {number}
         */
        serverTimeout: 30,

        /**
         * Max query result set size.
         * @type {number}
         */
        maxQueryResult: 20,

        /**
         * Max search query result set size (for global search)
         * @type {number}
         */
        maxSearchQueryResult: 3,

        /**
         * A list of routes that don't require authentication (in addition to `login`).
         * @type {Array}
         */
        unsecureRoutes: ['signup', 'error'],

        /**
         * Platform name.
         * @type {string}
         */
        platform: 'portal',

        /**
         * Default module to load for the home route (index).
         * If not specified, the framework loads `home` layout for the module `Home`.
         */
        defaultModule: 'Cases',

        /**
         * A list of metadata types to fetch by default.
         * @type {Array}
         */
        metadataTypes: [],

        /**
         * The field and direction to order by.
         *
         * For list views, the default ordering.
         *
         *     orderByDefaults: {
         *         moduleName: {
         *             field: '<name_of_field>',
         *             direction: '(asc|desc)'
         *         }
         *     }
         *
         * @type {Object}
         */
        orderByDefaults: {
            Cases: {
                field: 'case_number',
                direction: 'asc',
            },
            Bugs: {
                field: 'bug_number',
                direction: 'asc',
            },
            Notes: {
                field: 'date_modified',
                direction: 'desc',
            },
        },

        /**
         * Hash of addtional views of the format below to init and render on app start
         *
         *     additionalComponents: {
         *         viewName: {
         *             target: 'CSSDomselector'
         *         }
         *     }
         *
         * @type {Object}
         */
        additionalComponents: {
            header: {
                target: '#header',
            },
            footer: {
                target: '#footer',
            },
        },

        /**
         * Alerts element selector.
         * @type {string}
         */
        alertsEl: '#alerts',

        /**
         * Alert dismiss timeout in milliseconds.
         * @type {number}
         */
        alertAutoCloseDelay: 9000,

        /**
         * Client ID for oAuth
         * Defaults to sugar other values are support_portal
         * @type {Array}
         */
        clientID: 'sugar',

        /**
         * Syncs config from server on app start
         * Defaults to true otherwise set to false
         * @type {boolean}
         */
        syncConfig: true,

        /**
         * Loads css dinamically when the app inits
         * Defaults to false otherwise set 'url' or 'text'
         * @type {string}
         */
        loadCss: false,

        /**
         * Offline configuration.
         */
        offline: {

            /**
             * Flag indicating if offline mode is enabled.
             */
            enabled: false,

            debug: {
                /**
                 * Ignore app.config.offlineEnabled setting.
                 */
                ignoreEnabled: true,
                /**
                 * Render debug settings view.
                 */
                render: true,
                /**
                 * Drop db on app sync.
                 */
                forceMigrate: true,
                /**
                 * Force offline connection.
                 */
                online: true,
                /**
                 * Disable sync manager.
                 */
                syncManagerEnabled: true,
                /**
                 * Disable status monitor.
                 */
                monitorStatus: false,
                /**
                 * Emulate out-of-space situation.
                 */
                outOfSpace: false,
            },

            /**
             * Minimum server version that is required by offline mode.
             */
            minServerVersion: '7.1.5',

            /**
             * Flag indicating if HTTP throttling is enabled for read requests.
             */
            httpThrottlingEnabled: true,

            /**
             * HTTP throttling timeout.
             *
             * Repeated requests are ignored for the specified interval.
             */
            httpThrottleTimeout: 5000, //(msec)

            /**
             * Size of HTTP throttling cache.
             */
            httpThrottleCacheSize: 10,

            /**
             * List of modules to exclude from offline access.
             */
            exclude: [],

            /**
             * Defines configuration for the 'static' data pre-fetching.
             */
            prefetch: {

                /**
                 * Flag indicating if prefetching is enabled during app.sync.
                 */
                enabled: true,

                /**
                 * Specified number of records from static modules are prefetched from server after app sync.
                 */
                staticModules: {
                    Users: 20,
                    Teams: 20,
                    ProductCategories: 20,
                    ProductTypes: 40,
                    ProductTemplates: 20,
                    Manufacturers: 20,
                },

                /**
                 * Default max threshold prefetched records per module
                 */
                maxThreshold: 20,

                /**
                 * Prefetching page size.
                 */
                pageSize: 20,

                /**
                 * Relationships prefetch section
                 */
                relationships: {
                    pageSize: 20,
                    maxThreshold: 100,
                },
            },

            /**
             * Storage provider.
             */
            storageProvider: 'sql',

            /**
             * Storage provider.
             */
            storageAdapter: 'webSql',

            /**
             * Database size, in megabytes.
             */
            storageSize: 5,

            /**
             * Records purge interval (days).
             */
            defaultPurgeInterval: 7,

            /**
             * Synchronization configuration.
             */
            syncManager: {

                /**
                 * Flag indicating if synchronization is enabled.
                 */
                enabled: true,

                /**
                 * Period of synchronization iteration (seconds).
                 */
                syncTimeout: 10,

                /**
                 * List of synchronization workers.
                 */
                executors: {

                    /**
                     * 'What is New?' synchronization.
                     */
                    wins: {

                        /**
                         * Flag indicating if the worker is enabled.
                         */
                        enabled: true,
                        /**
                         * Synchronization interval (seconds).
                         */
                        interval: 30,
                        /**
                         * Synchronization priority (lowest value gets higher priority).
                         */
                        priority: 1,
                        /**
                         * Maximum number of records to synchronize.
                         */
                        preSyncMaxRecords: 100,
                        /**
                         * Page size for batch records update.
                         */
                        syncPageSize: 20,
                        /**
                         * Page size for 'What is New?' request.
                         */
                        preSyncPageSize: 20,
                    },

                    /**
                     * Transaction Log synchronization.
                     */
                    txLog: {

                        /**
                         * Flag indicating if the worker is enabled.
                         */
                        enabled: true,

                        /**
                         * Synchronization interval (seconds).
                         */
                        interval: 10,
                        /**
                         * Synchronization priority (lowest value gets higher priority).
                         */
                        priority: 0,
                    },
                },
            },

            /**
             * Maximum size of result set for mixed collection (search).
             */
            maxMixedCollectionQueryResult: 30,

            /**
             * Offline status monitor interval, ms
             */
            monitorTimeout: 2000,

            /**
             * How ofter a user will see the cleanup confirmation dialog, ms
             */
            cleanupConfirmationInterval: 60000,
        },

    }, false);

})(SUGAR.App);
```
