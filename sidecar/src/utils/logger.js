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

/**
 * Application logger.
 *
 * Usage example:
 *
 * ```
 * const logger = require('utils/logger')({
 *     level: 'DEBUG',
 * });
 *
 * // Log a string message
 * logger.debug('Some debug message');
 *
 * // Log an object
 * let obj = { foo: 'bar' };
 * logger.info(obj);
 *
 * // Log a closure.
 * let a = 1;
 * logger.error(function() { return a; });
 * ```
 *
 * @module Core/Logger
 */

let config =  {
    level: 'error',
    formatter: 'SimpleFormatter',
    consoleWriter: 'ConsoleWriter',
    serverWriter: 'ServerWriter',
};

const writers = {
    /**
     * Default client-side log writer.
     * Outputs messages onto browser's console object.
     *
     * @private
     */
    ConsoleWriter: {
        /**
         * Writes a message with the given log level to the browser console.
         * The writer uses:
         *
         *  - `console.info`: `TRACE`, `DEBUG` and `INFO`.
         *  - `console.warn`: `WARN`.
         *  - `console.error`: `ERROR` and `FATAL`.
         *
         * @param {Utils.Logger.levels} level A logger level from logger.levels
         * @param {string} message The message to write.
         * @method
         */
         write: function(level, message) {
            // work around for browsers without console
            if (!window.console) window.console = {};
            if (!window.console.log) window.console.log = function() { };
            if (level.value <= Logger.levels.INFO.value) {
                console.log(message);
            }
            else if (level.value == Logger.levels.WARN.value) {
                console.warn(message);
            }
            else {
                console.error(message);
            }
        },
    },

    /**
     * Default server-side log writer.
     * Writes log messages to the server.
     *
     * @private
     */
    ServerWriter: {
        /**
         * Writes a log message with a given {@link Utils.Logger.levels}
         * level onto the server.
         *
         * @param {Utils.Logger.levels} level Logger level.
         * @param {string} message Log message.
         * @throws {string} Exceptions are thrown whether the returned
         *   status is `false` or an error occurred while processing the
         *   request.
         */
        write: function(level, message) {
            // FIXME: add support for other logger levels (SC-5483).
            if (level.value < Logger.levels.WARN.value) {
                return;
            }

            if (!SUGAR.App.api.isAuthenticated()) {
                return;
            }

            let write = config.writeToServer || false;
            if (!write || !message.trim().length) {
                return;
            }

            let url = SUGAR.App.api.buildURL(undefined, 'logger');
            let params = {
                level: level.name.toLowerCase(),
                message: message,
            };

            SUGAR.App.api.call('create', url, params, {
                success: function(data) {
                    if (!data.status) {
                        throw 'Failed to write log message {' + message + '} onto server';
                    }
                },
                error: function(e) {
                    throw e;
                }
            });
        },
    },
};

const formatters = {
    /**
     * Default formatter for log messages.
     * Formats a log message as a string with log level and UTC timestamp.
     * ```
     * const Logger = require('utils/logger');
     *
     * // Log a trace message
     * Logger.trace('Blah-blah');
     *
     * // Output
     * // TRACE[2012-1-26 2:38:23]: Blah-blah
     * ```
     *
     * @private
     */
    SimpleFormatter: {
        /**
         * Formats a log message by adding log level name and UTC timestamp.
         *
         * @param {Object} level Logging level.
         * @param {string} message The message to log.
         * @param {Date} date Logging timestamp.
         * @return {string} The formatted log message.
         */
        format: function(level, message, date) {
            let dateString = date.getUTCFullYear() + '-' + (date.getUTCMonth() + 1) + '-' + date.getUTCDate() +
                ' ' + date.getUTCHours() + ':' + date.getUTCMinutes() + ':' + date.getUTCSeconds();
            return level.name + '[' + dateString + ']: ' + message;
        },
    },
};

/**
 * @alias module:Core/Logger
 */
const Logger = {
    /**
     * Logging levels.
     *
     * @private
     * @type {Object}
     */
    levels: {
        /**
         * Trace log level
         */
        TRACE: {
            value: 1,
            name: 'TRACE',
        },
        /**
         * Debug log level
         */
        DEBUG: {
            value: 2,
            name: 'DEBUG',
        },
        /**
         * Info log level
         */
        INFO: {
            value: 3,
            name: 'INFO',
        },
        /**
         * Warn log level
         */
        WARN: {
            value: 4,
            name: 'WARN',
        },
        /**
         * Error log level
         */
        ERROR: {
            value: 5,
            name: 'ERROR',
        },
        /**
         * Fatal log level
         */
        FATAL: {
            value: 6,
            name: 'FATAL',
        }
    },

    /**
     * Logs a message with the TRACE log level.
     *
     * @method
     * @param {string|Object|Function} message Message to log.
     */
    trace: function(message) {
        this.log(this.levels.TRACE, message);
    },

    /**
     * Logs a message with the DEBUG log level.
     *
     * @param {string|Object|Function} message Message to log.
     */
    debug: function(message) {
        this.log(this.levels.DEBUG, message);
    },

    /**
     * Logs a message with the INFO log level.
     *
     * @param {string|Object|Function} message Message to log.
     */
    info: function(message) {
        this.log(this.levels.INFO, message);
    },

    /**
     * Logs a message with the WARN log level.
     *
     * @param {string|Object|Function} message Message to log.
     */
    warn: function(message) {
        this.log(this.levels.WARN, message);
    },

    /**
     * Logs a message with the ERROR log level.
     *
     * @param {string|Object|Function} message Message to log.
     */
    error: function(message) {
        this.log(this.levels.ERROR, message);
    },

    /**
     * Logs a message with the FATAL log level.
     *
     * @param {string|Object|Function} message Message to log.
     */
    fatal: function(message) {
        this.log(this.levels.FATAL, message);
    },

    /**
     * Retrieves logger level based on system settings.
     *
     * @return {Object} Logger level or `ERROR` if none defined.
     */
    getLevel: function() {
        let level = config.level;

        if (!level) {
            return this.levels.ERROR;
        }

        level = level.toUpperCase();

        // FIXME this needs to be done after SC-5483 is implemented
        /*
        if (!this.levels[level]) {

            console.error('Your logger level is set to an invalid value. ' +
                'Please redefine it in Administration > System Settings. ' +
                'If you continue to see this warning, please ' +
                'contact your Admin.');
        }
        */

        return this.levels[level] || this.levels.ERROR;
    },

    /**
     * Logs a message with a given {@link Utils.Logger.levels} level.
     * If the message is an object, it will be serialized into a JSON string.
     * If the message is a function, it will evaluated in the logger's scope.
     *
     * @param {Utils.Logger.levels} level log level
     * @param {string|Object|Function} message log message
     */
    log: function(level, message) {
        try {
            let currentLevel = this.getLevel();
            if (level.value < currentLevel.value) {
                return;
            }

            message = message || '<none>';

            if (_.isFunction(message)) {
                message = message.call(this);
            }

            if (_.isObject(message)) {
                // Try to json-ify the object. It'll fail if it has circular
                // dependency
                try {
                    message = JSON.stringify(message);
                } catch (e) {
                    message = message.toString();
                }
            }

            const formatter = formatters[config.formatter] || formatters.SimpleFormatter;
            const consoleWriter = writers[config.consoleWriter] || writers.ConsoleWriter;
            const serverWriter = writers[config.serverWriter] || writers.ServerWriter;

            message = formatter.format(level, message, new Date());

            consoleWriter.write(level, message);
            serverWriter.write(level, message);
        } catch (e) {
            console.log('Failed to log message {' + message + '} due to exception: ' + e);
        }
    }
};

Object.defineProperty(Logger, 'SimpleFormatter', {
    get: function() {
        SUGAR.App.logger.warn('`Utils.Logger.SimpleFormatter` has been made private since 7.10 and you will not be able ' +
            'to access it in the next release. Please do not use it anymore');
        return formatters.SimpleFormatter;
    }
});

Object.defineProperty(Logger, 'ServerWriter', {
    get: function() {
        SUGAR.App.logger.warn('`Utils.Logger.ServerWriter` has been made private since 7.10 and you will not be able ' +
            'to access it in the next release. Please do not use it anymore');
        return writers.ServerWriter;
    }
});

Object.defineProperty(Logger, 'ConsoleWriter', {
    get: function() {
        SUGAR.App.logger.warn('`Utils.Logger.ConsoleWriter` has been made private since 7.10 and you will not be able ' +
            'to access it in the next release. Please do not use it anymore');
        return writers.ConsoleWriter;
    }
});

module.exports = function(options) {
    // Config should be immutable. So we should do :
    // config = _.extend(config, {default config}, options);
    // For now, to keep backward compatibility with 7.9, we do the following:
    config = options || {};
    return Logger;
}
