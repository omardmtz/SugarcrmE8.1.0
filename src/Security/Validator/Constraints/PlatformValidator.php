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
 * Platform validator
 *
 * This constraint validates if a given platform name is allowed or not:
 *  1. Base validation against length and allowed characters
 *  2. Validate against a list of allowed platforms
 *
 */
class PlatformValidator extends ConstraintValidator
{
    /**
     * Allowed platforms (keyed)
     * @var array
     */
    protected $platforms = array();

    /**
     * Ctor
     * @param array $platforms Allowed platforms
     */
    public function __construct(array $platforms = null)
    {
        $this->platforms = $platforms ?: array_flip(\MetaDataManager::getPlatformList());
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Platform) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Platform');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        // base validation - length
        if (strlen($value) > 127) {
            $this->context->buildViolation($constraint->message)
                ->setInvalidValue($value)
                ->setCode(Platform::ERROR_INVALID_PLATFORM_FORMAT)
                ->setParameters(array(
                    '%platform%' => $value,
                    '%reason%' => 'maximum length of 127 characters exceeded',
                ))
                ->addViolation();
        }

        // base validation - allowed characters
        if (!preg_match('/^[a-z0-9\-_]*$/i', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setInvalidValue($value)
                ->setCode(Platform::ERROR_INVALID_PLATFORM_FORMAT)
                ->setParameters(array(
                    '%platform%' => $value,
                    '%reason%' => 'invalid characters (a-z, 0-9, dash and underscore allowed)',
                ))
                ->addViolation();
        }

        // known platform list
        if (!isset($this->platforms[$value])) {
            $this->context->buildViolation($constraint->message)
                ->setInvalidValue($value)
                ->setCode(Platform::ERROR_INVALID_PLATFORM)
                ->setParameters(array(
                    '%platform%' => $value,
                    '%reason%' => 'unknown platform',
                ))
                ->addViolation();
        }
    }
}
