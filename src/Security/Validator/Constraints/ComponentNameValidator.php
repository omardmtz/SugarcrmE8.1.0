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

namespace Sugarcrm\Sugarcrm\Security\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 *
 * Component name validator
 *
 */
class ComponentNameValidator extends ConstraintValidator
{
    /**
     * List of reseverd SQL keywords
     * @var array
     */
    protected $sqlKeywords = array();

    /**
     * Ctor
     */
    public function __construct(array $sqlKeywords = null)
    {
        if ($sqlKeywords !== null) {
            $this->sqlKeywords = $sqlKeywords;
        } elseif ($db = \DBManagerFactory::getInstance()) {
            $this->sqlKeywords = $db->getReservedWords();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ComponentName) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ComponentName');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        // check for invalid characters
        if (!preg_match('/^[a-z][a-z0-9_\-]*$/i', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter(
                    '%msg%',
                    'must start with a letter and may only consist of letters, numbers, hyphens and underscores.'
                )
                ->setInvalidValue($value)
                ->setCode(ComponentName::ERROR_INVALID_COMPONENT_NAME)
                ->addViolation();
            return;
        }

        // check for reserved SQL keyword
        if (!$constraint->allowReservedSqlKeywords && isset($this->sqlKeywords[strtoupper($value)])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%msg%', 'reserved SQL keyword not allowed')
                ->setInvalidValue($value)
                ->setCode(ComponentName::ERROR_RESERVED_KEYWORD)
                ->addViolation();
            return;
        }
    }
}
