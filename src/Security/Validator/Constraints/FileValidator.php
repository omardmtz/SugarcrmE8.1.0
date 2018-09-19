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
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 *
 * File validator
 *
 * Validate given file path against the following criteria:
 *  - File should exist
 *  - File path within allowed base directory (*)
 *  - Does not contain null characters
 *  - Does not contain directory traversal (..)
 *
 * This validator returns the normalized (realpath) value of the given file
 * path.
 *
 * (*) By default the SUGAR_BASE_DIR (and SHADOW_INSTANCE_DIR if applicable)
 * are set as base directories for validation. This can be overriden if needed
 * on the File constraint.
 *
 * This constraint is primarily used by the following utility methods:
 * @see \Sugarcrm\Sugarcrm\Util\Files\FileLoader::validateFilePath
 * @see \Sugarcrm\Sugarcrm\Util\Files\FileLoader::varFromInclude
 * @see \Sugarcrm\Sugarcrm\Util\Files\FileLoader::varsFromInclude
 *
 */
class FileValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof File) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\File');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (empty($constraint->baseDirs) || !is_array($constraint->baseDirs)) {
            throw new ConstraintDefinitionException('No basedirs defined');
        }

        $value = (string) $value;

        // check for null bytes
        if (strpos($value, chr(0)) !== false) {
            $this->context->buildViolation($constraint->message)
                ->setInvalidValue($value)
                ->setCode(File::ERROR_NULL_BYTES)
                ->setParameter('%msg%', 'null bytes detected')
                ->addViolation();
            return;
        }

        // check for directory traversal attempt
        if (strpos($value, '..') !== false) {
            $this->context->buildViolation($constraint->message)
                ->setInvalidValue($value)
                ->setCode(File::ERROR_DIR_TRAVERSAL)
                ->setParameter('%msg%', 'directory traversal detected')
                ->addViolation();
            return;
        }

        // normalize using realpath, implies a fileExists check
        $normalized = realpath($value);

        if ($normalized === false) {
            $this->context->buildViolation($constraint->message)
                ->setInvalidValue($value)
                ->setCode(File::ERROR_FILE_NOT_FOUND)
                ->setParameter('%msg%', 'file not found')
                ->addViolation();
            return;
        }

        // normalized format needs to start with baseDir value
        $baseDirCompliant = false;
        foreach ($constraint->baseDirs as $baseDir) {
            if (strpos($normalized, $baseDir) === 0) {
                $baseDirCompliant = true;
                break;
            }
        }

        if (!$baseDirCompliant) {
            $this->context->buildViolation($constraint->message)
                ->setInvalidValue($normalized)
                ->setCode(File::ERROR_OUTSIDE_BASEDIR)
                ->setParameter('%msg%', 'file outside basedir')
                ->addViolation();
            return;
        }

        $constraint->setFormattedReturnValue($normalized);
    }
}
