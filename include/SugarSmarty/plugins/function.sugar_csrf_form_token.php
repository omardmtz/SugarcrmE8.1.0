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

use Sugarcrm\Sugarcrm\Security\Csrf\CsrfAuthenticator;

/**
 * Generate CSRF form token.
 *
 * Accepted $params:
 *
 *  - raw   If true, only return the bare token instead of returning the
 *          default hidden input html field.
 *
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_sugar_csrf_form_token($params, &$smarty)
{
    $csrf = CsrfAuthenticator::getInstance();

    if (!empty($params['raw'])) {
        return $csrf->getFormToken();
    }

    return sprintf(
        '<input type="hidden" name="%s" value="%s" />',
        $csrf::FORM_TOKEN_FIELD,
        $csrf->getFormToken()
    );
}

