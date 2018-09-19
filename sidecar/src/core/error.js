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
 * Error manager for XHR errors.
 *
 * This module allows you to provide custom handling depending on the
 * xhr error.
 * Below is the exhaustive list of functions you can implement, with the
 * description of the error it will handle:
 *
 * ### Authentication error handler functions
 *
 * OAuth2 uses 400 error to catch all authentication errors; see:
 * http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-5.2
 *
 * **handleInvalidGrantError**
 *
 * The provided authorization grant is invalid, expired, revoked, does
 * not match the redirection URI used in the authorization request, or
 * was issued to another client. Note that the server implementation
 * will override invalid_grant as needs_login as a special case (see below).
 *
 * **handleNeedLoginError**
 *
 * The server shall use this in place of invalid_grant to tell client to
 * handle error specifically as caused due to invalid credentials being
 * supplied. The reason server needs to use this is because an invalid_grant
 * oauth error may also be caused by invalid or expired token.
 * Using needs_login allows all clients to provide proper messaging to end
 * user without the need for extra logic.
 *
 * **handleInvalidClientError**
 *
 * Client authentication failed (e.g. unknown client, no client
 * authentication included, multiple client authentications included,
 * or unsupported authentication method).
 *
 * **handleInvalidRequestError**
 *
 * The request is missing a required parameter, includes an unsupported
 * parameter or parameter value, repeats a parameter, includes multiple
 * credentials, utilizes more than one mechanism for authenticating the
 * client, or is otherwise malformed.
 *
 * **handleUnauthorizedClientError**
 *
 * The authenticated client is not authorized to use this authorization
 * grant type.
 *
 * **handleUnsupportedGrantTypeError**
 *
 * The authorization grant type is not supported by the authorization
 * server.
 *
 * **handleInvalidScopeError**
 *
 * The requested scope is invalid, unknown, malformed, or exceeds the scope
 * granted by the resource owner.
 *
 * ### Other error handler functions.
 *
 * **handleTimeoutError**
 *
 * **handleUnspecified400Error**
 *
 * **handleUnauthorizedError**
 *
 * **handleForbiddenError**
 *
 * **handleNotFoundError**
 *
 * **handleMethodNotAllowedError**
 *
 * **handleMethodConflictError**
 *
 * **handleHeaderPreconditionFailed**
 *
 * **handleValidationError**
 *
 * **handleMethodFailure**
 *
 * **ErrorhandleServerError**
 *``
 * ### Example of usage:
 *```
 * const Handlers = {
 *     handleTimeoutError: function() {
 *         console.log('The request has timed out.')
 *     },
 *     handleServerError: function() {
 *         console.log('Something went wrong with the server, please contact
 *         your admin');
 *     }
 * }
 *
 * const ErrorHandler = _.extend(require('core/error'), Handlers);
 *```
 *
 * @module Core/Error
 */
const Alert = require('view/alert');
const Utils = require('utils/utils');
const Language = require('core/language');

/**
 * Calls the given custom handler callback, or falls back to
 * {@link module:Core/Error#handleStatusCodesFallback the default one}
 *
 * @param {Api.HttpError} error AJAX error.
 * @param {Function} fn The custom handler callback.
 * @param {Object} params The params for the custom callback.
 * @private
 */
function callCustomHandler(error, fn, params) {
    if (fn) {
        fn.apply(ErrorHandler, _.union([error], params || []));
    } else {
        ErrorHandler.handleStatusCodesFallback(error);
    }
};

/**
 * Calls appropriate authentication error handler.
 *
 * @param {Api.HttpError} error HTTP error.
 * @param {Function} [alternativeCallback] If this does not match an
 *   expected oauth error then this callback will be called (if provided).
 * @private
 */
function handleFineGrainedError(error, alternativeCallback) {
    var handlerName = 'handle' + Utils.classify(error.code) + 'Error';
    (ErrorHandler[handlerName] || alternativeCallback || ErrorHandler.handleStatusCodesFallback).call(ErrorHandler, error);
};

/**
 * @alias module:Core/Error
 */
const ErrorHandler = {
    /**
     * Binds a `window.onerror` callback to log error using sidecar's logger.
     */
    init: function() {
        this.enableOnError();

        Object.defineProperty(this, 'remoteLogging', {
            get: function() {
                SUGAR.App.logger.warn('`Core.Error#remoteLogging` property has been deprecated since 7.10.');
                return false;
            },
            configurable: true
        });
    },

    /**
     * Sets properties and binds a `window.onerror` callback to log error using
     * the logger.
     *
     * @param {Object} opts A hash of options.
     * @deprecated since 7.10.
     */
    initialize: function(opts) {
        SUGAR.App.logger.warn('The function `Core.Error#initialize` is deprecated in 7.10. ' +
        'Please do not use it. The function initializing the component is `Core.Error#init`');
        opts = opts || {};

        this.statusCodes = (opts.statusCodes) ? _.extend(this.statusCodes, opts.statusCodes) : this.statusCodes;

        if (!opts.disableOnError) {
            this.enableOnError();
        }
    },

    /**
     * A hashmap of status codes mapping to their handler functions.
     *
     * Each status code calls a specific method that you need to implement if
     * you want a custom handling. If you do not implement it,
     * {@link module:Core/Error.handleStatusCodesFallback} will be used.
     *
     * @class
     * @name Core/Error.StatusCodes
     */

    /**
     * @type {Core/Error.StatusCodes}
     */
    statusCodes: {
        /**
         * If the error has `textStatus` property set to `timeout`, calls
         * `handleTimeoutError`.
         *
         * @param {Api.HttpError} error The AJAX error.
         * @param {Data/Bean|Data/BeanCollection} model The model or collection
         *   on which the request was made.
         * @param {Object} options A hash of options.
         * @memberOf Core/Error.StatusCodes
         */
        '0': function(error, model, options) {
            if (error.textStatus === 'timeout') {
                callCustomHandler(error, this.handleTimeoutError);
            } else {
                // TODO: Need invalid url, and any other possible status: 0 conditions
                this.handleStatusCodesFallback(error, model, options);
            }
        },

        /**
         * Authentication or bad request error.
         *
         * Calls one of the authentication errors handlers, based on the error
         * code. Please check the list of possible handlers function in the
         * description of this module. If no authentication error handler is
         * implemented, it calls `handleUnspecified400Error`.
         *
         * @param {Api.HttpError} error HTTP error.
         * @memberOf Core/Error.StatusCodes
         */
        '400': function(error) {
            handleFineGrainedError(error, this.handleUnspecified400Error);
        },

        /**
         * Unauthorized.
         *
         * Calls one of the authentication errors handlers, based on the error
         * code. Please check the list of possible handlers function in the
         * description of this module. If no authentication error handler is
         * implemented, it calls `handleUnauthorizedError`.
         *
         * @param {Api.HttpError} error HTTP error.
         */
        '401': function(error) {
            handleFineGrainedError(error, this.handleUnauthorizedError);
        },

        /**
         * Forbidden.
         *
         * Calls `handleForbiddenError`.
         *
         * @param {Api.HttpError} error HTTP error.
         * @memberOf Core/Error.StatusCodes
         */
        '403': function(error) {
            callCustomHandler(error, this.handleForbiddenError);
        },

        /**
         * Not found.
         *
         * Calls `handleNotFoundError`.
         *
         * @param {Api.HttpError} error HTTP error.
         * @memberOf Core/Error.StatusCodes
         */
        '404': function(error) {
            callCustomHandler(error, this.handleNotFoundError, _.rest(arguments));
        },

        /**
         * Method not allowed.
         *
         * Calls `handleMethodNotAllowedError`.
         *
         * @param {Api.HttpError} error HTTP error.
         * @memberOf Core/Error.StatusCodes
         */
        '405': function(error) {
            callCustomHandler(error, this.handleMethodNotAllowedError);
        },

        /**
         * Conflict.
         *
         * Calls `handleMethodConflictError`.
         *
         * @param {Api.HttpError} error HTTP error.
         * @memberOf Core/Error.StatusCodes
         */
        '409': function(error) {
            callCustomHandler(error, this.handleMethodConflictError);
        },

        /**
         * Precondition failed.
         *
         * Calls `handleHeaderPreconditionFailed`.
         *
         * @param {Api.HttpError} error HTTP error.
         * @memberOf Core/Error.StatusCodes
         */
        '412': function(error) {
            callCustomHandler(error, this.handleHeaderPreconditionFailed);
        },

        /**
         * Precondition failure.
         *
         * Clients can optionally sniff the error property in JSON for finer
         * grained determination; the following values may be:
         * missing_parameter, invalid_parameter
         *
         * Calls `handleValidationError`.
         *
         * @param {Api.HttpError} error HTTP error.
         * @param {Data/Bean|Data/BeanCollection} model The model or collection
         *   on which the request was made.
         * @memberOf Core/Error.StatusCodes
         */
        '422': function(error, model) {
            error.model = model;
            callCustomHandler(error, this.handleValidationError, _.rest(arguments));
        },

        /**
         * Request Method Failure.
         *
         * Calls `handleMethodFailureError`.
         *
         * @param {Api.HttpError} error HTTP error.
         * @param {Data/Bean|Data/BeanCollection} model The model or
         *   collection on which the request was made.
         * @memberOf Core/Error.StatusCodes
         */
        '424': function(error, model) {
            error.model = model;
            callCustomHandler(error, this.handleMethodFailureError, _.rest(arguments));
        },

        /**
         * Internal server error
         *
         * Calls `handleServerError`.
         *
         * @param {Api.HttpError} error HTTP error.
         * @memberOf Core/Error.StatusCodes
         */
        '500': function(error) {
            callCustomHandler(error, this.handleServerError);
        },

        /**
         * Bad Gateway
         *
         * Calls `handleServerError`.
         *
         * @param {Api.HttpError} error HTTP error.
         * @memberOf Core/Error.StatusCodes
         */
        '502': function(error) {
            callCustomHandler(error, this.handleServerError);
        },

        /**
         * Service Unavailable
         *
         * Calls `handleServerError`.
         *
         * @param {Api.HttpError} error HTTP error.
         * @memberOf Core/Error.StatusCodes
         */
        '503': function(error) {
            callCustomHandler(error, this.handleServerError);
        }
    },

    /**
     * Maps validator names to error labels.
     */
    errorName2Keys: {
        'maxValue': 'ERROR_MAXVALUE',
        'minValue': 'ERROR_MINVALUE',
        'maxLength': 'ERROR_MAX_FIELD_LENGTH',
        'minLength': 'ERROR_MIN_FIELD_LENGTH',
        'datetime': 'ERROR_DATETIME',
        'required': 'ERROR_FIELD_REQUIRED',
        'email': 'ERROR_EMAIL',
        'primaryEmail': 'ERROR_PRIMARY_EMAIL',
        'duplicateEmail': 'ERROR_DUPLICATE_EMAIL',
        'number': 'ERROR_NUMBER',
        'isBefore': 'ERROR_IS_BEFORE',
        'isAfter': 'ERROR_IS_AFTER',
        'greaterThan': 'ERROR_IS_GREATER_THAN',
        'lessThan': 'ERROR_IS_LESS_THAN'
    },

    /**
     * Returns error strings given a error key and context.
     *
     * @param {string} errorKey The error key we want to get the error message
     *   from.
     * @param {Object} [context] The template context to pass to the
     *   string/template.
     * @return {string} The i18n error string associated with the given
     *   `errorKey` and filled in by the `context`.
     */
    getErrorString: function(errorKey, context) {
        var module = context.module || '';
        return Language.get(this.errorName2Keys[errorKey] || errorKey, module, context);
    },

    /**
     * Handles validation errors.
     *
     * By default this just pipes the error to the error logger.
     *
     * @param {Api.HttpError} error The AJAX error.
     */
    handleValidationError: function(error) {
        var errors = error.responseText;
        // TODO: Right now doesn't stringify the error, add it in when we finalize the
        // structure of the error.

        // TODO: Likely, we'll have a 'Saving...' alert, etc., and so we just dismiss all
        // since we don't know the alert key. Ostensibly, validation errors will show
        // field by field; so feedback will be provided as appropriate.
        Alert.dismissAll();

        _.each(errors, function(fieldError, key) {
            var errorMsg = '';
            if (_.isObject(fieldError)) {
                _.each(fieldError, function(result, fieldName) {
                    errorMsg += '(Message) ' + this.getErrorString(fieldName, result) + '\n';
                }, this);
            } else {
                errorMsg = fieldError;
            }
            SUGAR.App.logger.debug("validation failed for field `" + key + "`:\n" + errorMsg);
        }, this);
    },

    /**
     * Handles http errors returned from AJAX calls.
     *
     * This method calls the relevant handler function using
     * {Core.Error#statusCodes}
     *
     * @param {Api.HttpError} error AJAX error.
     * @param {Data/Bean|Data/BeanCollection} model The model or collection on
     *   which the request was made.
     * @param {Object} options A hash of options.
     */
    handleHttpError: function(error, model, options) {
        // We use `ErrorHandler` instead of `this` because this function is set to
        // `Api.defaultErrorHandler`. We want to make sure the scope is always
        // the Core.Error object.
        if (ErrorHandler.statusCodes[error.status]) {
            ErrorHandler.statusCodes[error.status].call(ErrorHandler, error, model, options);
        } else {
            // TODO: Default catch all error code handler
            // Temporarily going to the handleStatusCodesFallback handler but will probably need
            // to go to a sensible "all other errors" type of handler.
            ErrorHandler.handleStatusCodesFallback(error);
        }
    },

    /**
     * Handles unhandled javascript exceptions which are reported via
     * `window.onerror` event.
     *
     * The default implementation logs the error with level `FATAL`.
     *
     * @param {string} message Error message.
     * @param {string} url URL of script.
     * @param {string} line Line number of script.
     */
    handleUnhandledError: function(message, url, line) {
        SUGAR.App.logger.fatal(message + ' at ' + url + ' on line ' + line);
    },

    /**
     * This is the fallback error handler if the custom status code specific
     * handler is not implemented. To define custom error handlers, you should
     * include your script from index page and do something like:
     *
     * @param {string} error The AJAX error.
     */
    handleStatusCodesFallback: function(error) {
        SUGAR.App.logger.error(error.toString());
    },

    /**
     * Handles render related errors.
     *
     * @param {View/Component} component The component that triggered
     *   the error.
     * @param {string} method The method that caught the error. Example:
     *   `_renderHtml`.
     * @param {string} [additionalInfo] Any additional information relevant
     *   for that particular method.
     */
    handleRenderError: function(component, method, additionalInfo) {
        var id = 'render_error_' + component.module + '_' + component.name;
        var level = 'error'; //Default message level
        var title;
        var messages;
        // TODO: Add corresponding language agnostic app strings for title/message and use that instead.
        switch (method) {
            case '_renderHtml':
                title = Language.get('ERR_RENDER_FAILED_TITLE');
                messages = [Language.get('ERR_RENDER_FAILED_MSG'),
                            Language.get('ERR_CONTACT_TECH_SUPPORT')];
                break;
            case '_renderField':
                title = Language.get('ERR_RENDER_FIELD_FAILED_TITLE');
                messages = [Utils.formatString(Language.get('ERR_RENDER_FIELD_FAILED_MSG'),
                    [additionalInfo.name]), Language.get('ERR_CONTACT_TECH_SUPPORT')];
                break;
            case 'view_render_denied':
                title = Language.get('ERR_NO_VIEW_ACCESS_TITLE');
                level = 'warning';  // This isn't an application error, this is ACL enforcement.
                var module = Language.getModuleName(component.module, {plural: true});
                messages = [Utils.formatString(Language.get('ERR_NO_VIEW_ACCESS_MSG'),[module])];
                break;
            case 'layout_render':
                title = Language.get('ERR_LAYOUT_RENDER_TITLE');
                messages = [Language.get('ERR_LAYOUT_RENDER_MSG')];
                break;
            default:
                // This shouldn't happen
                title = Language.get('ERR_GENERIC_TITLE');
                messages = [Language.get('ERR_INTERNAL_ERR_MSG'),
                            Language.get('ERR_CONTACT_TECH_SUPPORT')];
                SUGAR.App.logger.error('handleRenderError called for render error caught in ' + method + ', but we have no corresponding handler!');
                break;
        }

        Alert.show(id, {
            level: level,
            title: title,
            messages: messages
        });
    },

    /**
     * Binds a custom handler for `window.onerror` event. Does nothing if it has
     * already been overloaded.
     *
     * Calls the provided `handler`, or falls back to
     * {Core.Error#handleUnhandledError} and then calls the original handler
     * if defined.
     *
     * @param {Function} handler Callback function to call on error.
     * @param {Object} context The scope of the handler.
     * @return {boolean} `false` if onerror has already been overloaded.
     */
    enableOnError: function(handler, context) {
        var originalHandler;
        var self = this;

        if (this.overloaded) {
            return false;
        }

        originalHandler = window.onerror;

        window.onerror = function(mesg, url, line) {
            if (handler) {
                handler.call(context);
            } else {
                self.handleUnhandledError(mesg, url, line);
            }

            if (originalHandler) {
                originalHandler();
            }
        };

        this.overloaded = true;

        return true;
    },


    /**
     * Inserts call stack string to Error message.
     * `window.onerror` handler is not provided with Error object
     * (4th argument) [in Safari][1].
     *
     * [1]: https://bugs.webkit.org/show_bug.cgi?id=55092
     *
     * @param {string|Error} error Error text or Error object.
     * @param {boolean} [skipThrow=false] If `true`, skips exception
     *   raising.
     * @return {Error} Error object with stack trace inserted into
     *   `Error.message` property.
     * @deprecated since 7.10.
     */
    throwErrorWithCallStack: function(error, skipThrow) {
        SUGAR.App.logger.warn('The function `Core.Error#throwErrorWithCallStack` is deprecated in 7.10. ' +
        'Please do not use it.');

        if (_.isString(error)) {
            error = new Error(error);
        }

        error.message = error.message + '; ' + error.stack;

        if (skipThrow) {
            return error;
        }

        throw error;
    },

    _callCustomHandler: function(error, fn, params) {
        if (!SUGAR.App.config.sidecarCompatMode) {
            SUGAR.App.logger.error('Core.Error#_callCustomHandler is a private method that you are not allowed ' +
                'to access. Please use only the public API.');
            return;
        }

        SUGAR.App.logger.warn('Core.Error#_callCustomHandler is a private method that you should not access. ' +
            'You will NOT be allowed to access it in the next release. Please update your code to rely on the public ' +
            'API only.');

        return callCustomHandler(error, fn, params);
    },

    _handleFineGrainedError: function(error, alternativeCallback) {
        if (!SUGAR.App.config.sidecarCompatMode) {
            SUGAR.App.logger.error('Core.Error#_handleFineGrainedError is a private method that you are not allowed ' +
                'to access. Please use only the public API.');
            return;
        }

        SUGAR.App.logger.warn('Core.Error#_handleFineGrainedError is a private method that you should not access. ' +
            'You will NOT be allowed to access it in the next release. Please update your code to rely on the public ' +
            'API only.');

        return handleFineGrainedError(error, alternativeCallback);
    }
};

module.exports = ErrorHandler;

