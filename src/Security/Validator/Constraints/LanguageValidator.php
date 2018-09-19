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
 * Language validator
 *
 */
class LanguageValidator extends ConstraintValidator
{
    /**
     * Supported languages as defined in $sugar_config['languages']
     * @var array
     */
    protected $languages = array();

    /**
     * Ctor
     */
    public function __construct(array $languages = null)
    {
        $this->languages = $languages ?: \SugarConfig::getInstance()->get('languages');
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Language) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Language');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!isset($this->languages[$value])) {
            $this->context->buildViolation($constraint->message)
                ->setInvalidValue($value)
                ->setCode(Language::ERROR_LANGUAGE_NOT_FOUND)
                ->setParameter('%msg%', 'language not found')
                ->addViolation();
            return;
        }
    }
}
