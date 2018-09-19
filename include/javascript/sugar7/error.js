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
 * SugarCRM error handlers.
 */
(function(app) {
    app.error = _.extend(app.error);

    function backToLogin(bDismiss) {
        if (bDismiss) {
            app.alert.dismissAll();
        }

        if (app.api.isAuthenticated()) {
            app.api.resetAuth();
        }

        // trigger app:logout event to clear any existing data
        app.trigger('app:logout');

        app.router.login();
    }

    function showErrorPage(status, dismiss) {
        if(dismiss) {
            app.alert.dismissAll();
        }

        app.controller.loadView({
           layout: "error",
           errorType: status,
           module: "Error",
           create: true
       });
    }

    function alertUser(key,title,msg) {
        app.alert.show(key, {
            level: 'error',
            messages: app.lang.get(msg),
            title: app.lang.get(title)
        });
    }

    //Return the user to the login page when the sync fails
    app.events.on('app:sync:error', function(error) {
        if (error.status !== 0) {
            backToLogin(true);
        }
        // Sync can fail for many reasons such as server error, bad cache, auth, etc.
        // Server message to provides details.
        alertUser('sync_failure' , 'ERR_SYNC_FAILED', (error && error.message) || 'LBL_INVALID_412_RESPONSE');
    });

    // Displays an error alert if the app fails during its initialization.
    app.events.on('app:sync:public:error', function(error) {
        app.alert.dismissAll();
        alertUser('public_sync_failure', 'Unable to load the application.');
    });

    /**
     * This is caused by attempt to login with invalid creds. 
     */
    app.error.handleNeedLoginError = function(error) {
        backToLogin(true);
        // Login can fail for many reasons such as lock out, bad credentials, etc.  Server message to provides details.
        alertUser("needs_login_error" , "LBL_INVALID_CREDS_TITLE", error.message);
    };

    /**
     * This is caused by expired or invalid token. 
     */
    app.error.handleInvalidGrantError = function(error) {
        backToLogin(true);
        alertUser("invalid_grant_error", "LBL_INVALID_GRANT_TITLE", "LBL_INVALID_GRANT");
    };

    /**
     * Client authentication handler. 
     */
    app.error.handleInvalidClientError = function(error) {
        backToLogin(true);
        alertUser("invalid_client_error","LBL_AUTH_FAILED_TITLE","LBL_AUTH_FAILED");
    };

    /**
     * invalid_request handler for OAuth
     */
    app.error.handleInvalidRequestError = function(error) {
        backToLogin(true);
        alertUser("invalid_request_error", "LBL_INVALID_REQUEST_TITLE", "LBL_INVALID_REQUEST");
    };

    /**
     * 400 Invalid request handler to be used for all non-OAuth 400 errors.
     */
    app.error.handleUnspecified400Error = function(error) {
        showErrorPage('400');
    };

    /**
     * 0 Timeout error handler. If server doesn't respond within timeout.
     */
    app.error.handleTimeoutError = function(error) {
        app.alert.dismissAll();
        alertUser("timeout_error", "LBL_REQUEST_TIMEOUT_TITLE", "LBL_REQUEST_TIMEOUT");
    };

    /**
     * 401 Unauthorized error handler. 
     */
    app.error.handleUnauthorizedError = function(error) {
        backToLogin(true);
        alertUser("unauthorized_request_error", "LBL_UNAUTHORIZED_TITLE", "LBL_UNAUTHORIZED");
    };

    /**
     * 403 Forbidden error handler. 
     */
    app.error.handleForbiddenError = function(error) {
        var message;
        if(error.code != "not_authorized"){
            app.alert.dismissAll();
        }
        // If portal is not configured, return to login screen if necessary
        if(error.code == "portal_not_configured"){
            backToLogin(true);
        }
        //assume the server message should NOT be valid HTML and escape it.
        message = Handlebars.Utils.escapeExpression(error.message) || "LBL_RESOURCE_UNAVAILABLE";
        app.logger.error(app.lang.get(message));
    };
    
    /**
     * 404 Not Found handler.
     * If a model triggered the 404 but the model did not belong to the master layout,
     * this function will not handle that error.
     * Those errors should be handled by listeners on the model/collection and the views that
     * requested the data.
     */
    app.error.handleNotFoundError = function(error, model, options) {
        var layout = app.controller.layout || {};
        if ((options && options.context != layout.context)
            || (model && layout.context && layout.context.get("model") && layout.context.get("model") != model)
        ) {
            return;
        }
        if (!layout ||
            !_.isObject(layout.error) ||
            !_.isFunction(layout.error.handleNotFoundError) ||
            layout.error.handleNotFoundError(error, model, options) !== false
        ) {
            showErrorPage("404");
        }
    };

    /**
     * 405 Method not allowed handler.
     */
    app.error.handleMethodNotAllowedError = function(error) {
        backToLogin(true);
        alertUser("not_allowed_error", "LBL_METHOD_NOT_ALLOWED_TITLE", "LBL_METHOD_NOT_ALLOWED");
    };

    /**
     * 409 Handle conflict error.
     */
    app.error.handleMethodConflictError = function(error) {
        app.logger.error('Data conflict detected.');
    };

    /**
     * 422 Handle validation error
     */
    app.error.handleValidationError = function(error) {
        var layout = app.controller.layout,
            message;
        if( !_.isObject(layout.error) ||
            !_.isFunction(layout.error.handleValidationError) ||
            layout.error.handleValidationError(error) !== false
        ) {
            //Ignore errors triggered from models, they should be handled by the views.
            if (error instanceof app.data.beanModel) {
                return;
            }
            //assume the server message should NOT be valid HTML and escape it.
            message = Handlebars.Utils.escapeExpression(error.message) || "LBL_PRECONDITION_MISSING";
            alertUser("validation_error", "LBL_PRECONDITION_MISSING_TITLE", message);
            error.handled = true;
        }
    };

    /**
     * 412 Header precondition failure error.
     *
     * A re-sync of the application is only kicked off if:
     * - we are not already in the process of syncing,
     * - the 412 response is valid (i.e. the current hash differs from the
     *   server hash).
     */
    app.error.handleHeaderPreconditionFailed = function(error, b, c, d) {
        //Only kick off a sync if we are not already in the process of syncing
        if (!app.isSynced) {
            return;
        }
        if (!error || error.code !== 'metadata_out_of_date') {
            return;
        }
        var responseText = JSON.parse(error.responseText);
        var newHash = responseText && responseText.metadata_hash;
        var userHash = responseText && responseText.user_hash;
        var afterSync = error.request.state && error.request.state.loadingAfterSync;

        if (
            ((!newHash && afterSync) || newHash === app.metadata.getHash()) &&
            ((!userHash && afterSync) || userHash === app.user.get("_hash"))
        ) {
            app.logger.fatal('A request returned the error code "metadata_out_of_date" for no reason.');
            app.alert.show('invalid_412', {
                level: 'error',
                messages: [
                    'LBL_INVALID_412_RESPONSE'
                ]
            });
            app.api.resetAuth();
            app.router.refresh();
            return;
        }
        app.sync();
    };

    /**
     * 424 Method failure error.
     */
    app.error.handleMethodFailureError = function(error) {
        // TODO: For finer grained control we could sniff the {error: <code>} in the response text (JSON) for one of:
        // missing_parameter, invalid_parameter, request_failure
        error.handled = true;
        if (error.code == "request_failure") {
            showErrorPage("422");
        } else {
            alertUser("precondtion_failure_error", "LBL_PRECONDITION_MISSING_TITLE", "LBL_PRECONDITION_MISSING");
        }
    };
       
    /**
     * 500, 502, 503 Internal server error handler.
     */
    app.error.handleServerError = function(error) {
        if(error.payload && error.payload.url) {
            // Redirect admins instead of loading the error view.
            if (app.acl.hasAccess('admin','Administration')) {
                app.router.navigate(error.payload.url,{trigger: true, replace: true});
                return;
            }
        }
        app.controller.loadView({
            layout: "error",
            errorType: error.status || "500",
            module: "Error",
            error: error, 
            create: true
        });
    };

})(SUGAR.App);
