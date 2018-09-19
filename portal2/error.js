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
 * Portal specific error handlers.
 */
(function(app) {
    app.error = _.extend(app.error);

    function backToLogin(bDismiss) {
        if(bDismiss) app.alert.dismissAll();
        app.router.login();
    }

    function alertUser(key,title,msg) {
        app.alert.show(key, {
            level: 'error',
            title: app.lang.get(title),
            messages: app.lang.get(msg)
        });
    }

    // Displays an error alert if the app fails during its initialization.
    app.events.on('app:sync:public:error', function(error) {
        alertUser('public_sync_failure', 'Unable to load the application.');
    });
    
    /**
     * This is caused by attempt to login with invalid creds. 
     */
    app.error.handleNeedLoginError = function(error) {
        backToLogin(true);
        alertUser("needs_login_error" , "LBL_PORTAL_INVALID_CREDS_TITLE", "LBL_PORTAL_INVALID_CREDS");
    };

    /**
     * This is caused by expired or invalid token. 
     */
    app.error.handleInvalidGrantError = function(error) {
        backToLogin(true);
        alertUser("invalid_grant_error", "LBL_PORTAL_INVALID_GRANT_TITLE", "LBL_PORTAL_INVALID_GRANT");
    };

    /**
     * Client authentication handler. 
     */
    app.error.handleInvalidClientError = function(error) {
        backToLogin(true);
        alertUser("invalid_client_error","LBL_PORTAL_AUTH_FAILED_TITLE","LBL_PORTAL_AUTH_FAILED");
    };
    
    /**
     * Invalid request handler. 
     */
    app.error.handleInvalidRequestError = function(error) {
        backToLogin(true);
        alertUser("invalid_request_error", "LBL_PORTAL_INVALID_REQUEST_TITLE", "LBL_PORTAL_INVALID_REQUEST");
    };

    /**
     * Non-OAuth bad request error
     */
     app.error.handleUnspecified400Error = function(error) {
        app.controller.loadView({
            layout: 'error',
            errorType: '400',
            module: 'Error',
            create: true
        });
    };

    /**
     * 0 Timeout error handler. If server doesn't respond within timeout.
     */
    app.error.handleTimeoutError = function(error) {
        backToLogin(true);
        alertUser("timeout_error", "LBL_PORTAL_REQUEST_TIMEOUT_TITLE", "LBL_PORTAL_REQUEST_TIMEOUT");
    };

    /**
     * 401 Unauthorized error handler. 
     */
    app.error.handleUnauthorizedError = function(error) {
        backToLogin(true);
        alertUser("unauthorized_request_error", "LBL_PORTAL_UNAUTHORIZED_TITLE", "LBL_PORTAL_UNAUTHORIZED");
    };

    /**
     * 403 Forbidden error handler. 
     */
    app.error.handleForbiddenError = function(error) {
        app.alert.dismissAll();
        // If portal is not configured, return to login screen if necessary
        if(error.code == "portal_not_configured"){
            backToLogin(true);
        }
        alertUser("forbidden_request_error", "LBL_PORTAL_RESOURCE_UNAVAILABLE_TITLE", error.message ? error.message : "LBL_PORTAL_RESOURCE_UNAVAILABLE");
    };
    
    /**
     * 404 Not Found handler. 
     */
    app.error.handleNotFoundError = function(error) {
        app.controller.loadView({
            layout: "error",
            errorType: "404",
            module: "Error",
            create: true
        });    
    };

    /**
     * 405 Method not allowed handler.
     */
    app.error.handleMethodNotAllowedError = function(error) {
        backToLogin(true);
        alertUser("not_allowed_error", "LBL_PORTAL_METHOD_NOT_ALLOWED_TITLE", "LBL_PORTAL_METHOD_NOT_ALLOWED");
    };

    /**
     * 412 Header precondition failure error.
     */
    app.error.handleHeaderPreconditionFailed = function(error) {
        app.sync();
    };

    /**
     * 424 Method failure error.
     */
    app.error.handleMethodFailureError = function(error) {
        backToLogin(true);
        // TODO: For finer grained control we could sniff the {error: <code>} in the response text (JSON) for one of:
        // missing_parameter, invalid_parameter, request_failure
        alertUser("precondtion_failure_error", "LBL_PORTAL_PRECONDITION_MISSING_TITLE", "LBL_PORTAL_PRECONDITION_MISSING");
    };
       
    /**
     * 500 Internal server error handler. 
     */
    app.error.handleServerError = function(error) {
        app.controller.loadView({
            layout: "error",
            errorType: error.status || "500",
            module: "Error",
            error: error, 
            create: true
        });
    };

})(SUGAR.App);

