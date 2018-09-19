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

namespace Sugarcrm\Sugarcrm\Security\InputValidation\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 *
 *
 *
 */
class ViolationException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @var ConstraintViolationListInterface
     */
    protected $violations;

    /**
     * Ctor
     * @param string $message
     * @param ConstraintViolationListInterface $violations
     */
    public function __construct($message, ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
        parent::__construct($message);
    }

    /**
     * Get violation object
     * @return ConstraintViolationListInterface
     */
    public function getViolations()
    {
        return $this->violations;
    }
}
