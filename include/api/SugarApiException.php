<?php
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

class SugarApiException extends SugarException
{
    /**
     * The HTTP response code to send to the consumer in case of an exception
     *
     * @var integer
     */
    public $httpCode = 400;

    /**
     * The label for the description of this exception. Used in help documentation.
     * Maps to the $messageLabel value with '_DESC' appended to it.
     *
     * @var string
     */
    public $descriptionLabel;

    /**
     * @param string $messageLabel optional Label for error message.  Used to load the appropriate translated message.
     * @param array $msgArgs optional set of arguments to substitute into error message string
     * @param string|null $moduleName Provide module name if $messageLabel is a module string, leave empty if
     *  $messageLabel is in app strings.
     * @param int $httpCode
     * @param string $errorLabel
     */
    public function __construct($messageLabel = null, $msgArgs = null, $moduleName = null, $httpCode = 0, $errorLabel = null)
    {

        if ($httpCode != 0) {
            $this->httpCode = $httpCode;
        }
        parent::__construct($messageLabel, $msgArgs, $moduleName, $errorLabel);
        if (!empty($this->messageLabel)) {
            $this->descriptionLabel = $this->messageLabel . '_DESC';
        }
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }
}
/**
 * General error, no specific cause known.
 */
class SugarApiExceptionError extends SugarApiException
{
    public $httpCode = 500;
    public $errorLabel = 'fatal_error';
    public $messageLabel = 'EXCEPTION_FATAL_ERROR';
}

/**
 * Incorrect API version
 */
class SugarApiExceptionIncorrectVersion extends SugarApiException
{
    public $httpCode = 301;
    public $errorLabel = 'incorrect_version';
    public $messageLabel = 'EXCEPTION_INCORRECT_VERSION';
}

/**
 * Token not supplied or token supplied is invalid.
 * The client should display the username and password screen
 */
class SugarApiExceptionNeedLogin extends SugarApiException
{
    public $httpCode = 401;
    public $errorLabel = 'need_login';
    public $messageLabel = 'EXCEPTION_NEED_LOGIN';
}

/**
 * The user's session is invalid
 * The client should get a new token and retry.
 */
class SugarApiExceptionInvalidGrant extends SugarApiException
{
    public $httpCode = 401;
    public $errorLabel = 'invalid_grant';
    public $messageLabel = 'EXCEPTION_INVALID_TOKEN';
}

/**
 * This action is not allowed for this user.
 */
class SugarApiExceptionNotAuthorized extends SugarApiException
{
    public $httpCode = 403;
    public $errorLabel = 'not_authorized';
    public $messageLabel = 'EXCEPTION_NOT_AUTHORIZED';
}
/**
 * This user is not active.
 */
class SugarApiExceptionPortalUserInactive extends SugarApiException
{
    public $httpCode = 403;
    public $errorLabel = 'inactive_portal_user';
    public $messageLabel = 'EXCEPTION_INACTIVE_PORTAL_USER';
}
/**
 * Portal is not activated by configuration.
 */
class SugarApiExceptionPortalNotConfigured extends SugarApiException
{
    public $httpCode = 403;
    public $errorLabel = 'portal_not_configured';
    public $messageLabel = 'EXCEPTION_PORTAL_NOT_CONFIGURED';
}
/**
 * URL does not resolve into a valid REST API method.
 */
class SugarApiExceptionNoMethod extends SugarApiException
{
    public $httpCode = 404;
    public $errorLabel = 'no_method';
    public $messageLabel = 'EXCEPTION_NO_METHOD';
}
/**
 * Resource specified by the URL does not exist.
 */
class SugarApiExceptionNotFound extends SugarApiException
{
    public $httpCode = 404;
    public $errorLabel = 'not_found';
    public $messageLabel = 'EXCEPTION_NOT_FOUND';
}
/**
 * Thrown when the client attempts to edit the data on the server that was already edited by
 * different client.
 */
class SugarApiExceptionEditConflict extends SugarApiException
{
    public $httpCode = 409;
    public $errorLabel = 'edit_conflict';
    public $messageLabel = 'EXCEPTION_EDIT_CONFLICT';
}

class SugarApiExceptionInvalidHash extends SugarApiException
{
    public $httpCode = 412;
    public $errorLabel = 'metadata_out_of_date';
    public $messageLabel = 'EXCEPTION_METADATA_OUT_OF_DATE';
}

class SugarApiExceptionRequestTooLarge extends SugarApiException
{
    public $httpCode = 413;
    public $errorLabel = 'request_too_large';
    public $messageLabel = 'EXCEPTION_REQUEST_TOO_LARGE';
}
/**
 * One of the required parameters for the request is missing.
 */
class SugarApiExceptionMissingParameter extends SugarApiException
{
    public $httpCode = 422;
    public $errorLabel = 'missing_parameter';
    public $messageLabel = 'EXCEPTION_MISSING_PARAMTER';
}
/**
 * One of the required parameters for the request is incorrect.
 */
class SugarApiExceptionInvalidParameter extends SugarApiException
{
    public $httpCode = 422;
    public $errorLabel = 'invalid_parameter';
    public $messageLabel = 'EXCEPTION_INVALID_PARAMETER';
}
/**
 * The API method is unable to process parameters due to some of them being wrong.
 */
class SugarApiExceptionRequestMethodFailure extends SugarApiException
{
    public $httpCode = 424;
    public $errorLabel = 'request_failure';
    public $messageLabel = 'EXCEPTION_REQUEST_FAILURE';
}

/**
 * The client is out of date for this version
 */
class SugarApiExceptionClientOutdated extends SugarApiException
{
    public $httpCode = 433;
    public $errorLabel = 'client_outdated';
    public $messageLabel = 'EXCEPTION_CLIENT_OUTDATED';
}

/**
 * When used as a proxy, this means that our API made a call and got a response
 * it couldn't handle
 */
class SugarApiExceptionConnectorResponse extends SugarApiException
{
    public $httpCode = 502;
    public $errorLabel = 'bad_gateway';
    public $messageLabel = 'EXCEPTION_CONNECTOR_RESPONSE';
}

/**
 * We're in the maintenance mode
 */
class SugarApiExceptionMaintenance extends SugarApiException
{
    public $httpCode = 503;
    public $errorLabel = 'maintenance';
    public $messageLabel = 'EXCEPTION_MAINTENANCE';
}

/**
 * The server is busy or overloaded. Generally should be temporary.
 */
class SugarApiExceptionServiceUnavailable extends SugarApiException
{
    public $httpCode = 503;
    public $errorLabel = 'service_unavailable';
    public $messageLabel = 'EXCEPTION_SERVICE_UNAVAILABLE';
}

/**
 * SearchEngine is unavailable
 */
class SugarApiExceptionSearchUnavailable extends SugarApiExceptionServiceUnavailable
{
    public $errorLabel = 'search_unavailable';
    public $messageLabel = 'EXCEPTION_SEARCH_UNAVAILABLE';
}

/**
 * SearchEngine runtime error
 */
class SugarApiExceptionSearchRuntime extends SugarApiExceptionError
{
    public $errorLabel = 'search_runtime';
    public $messageLabel = 'EXCEPTION_SEARCH_RUNTIME';
}

/**
 * Locked field edit attempt exception
 */
class SugarApiExceptionFieldEditDisabled extends SugarApiExceptionNotAuthorized
{
    public $errorLabel = 'field_locked';
    public $messageLabel = 'EXCEPTION_FIELD_LOCKED_FOR_EDIT';
}
