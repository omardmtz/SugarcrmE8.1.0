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
use Sugarcrm\Sugarcrm\Security\Validator\ConstraintReturnValueInterface;
use Sugarcrm\Sugarcrm\Security\Validator\ConstraintReturnValueTrait;

/**
 *
 * @see FileValidator
 *
 */
class File extends Constraint implements ConstraintReturnValueInterface
{
    use ConstraintReturnValueTrait;

    const ERROR_NULL_BYTES = 1;
    const ERROR_FILE_NOT_FOUND = 2;
    const ERROR_OUTSIDE_BASEDIR = 3;
    const ERROR_DIR_TRAVERSAL = 4;

    protected static $errorNames = array(
        self::ERROR_NULL_BYTES => 'ERROR_NULL_BYTES',
        self::ERROR_FILE_NOT_FOUND => 'ERROR_FILE_NOT_FOUND',
        self::ERROR_OUTSIDE_BASEDIR => 'ERROR_OUTSIDE_BASEDIR',
        self::ERROR_DIR_TRAVERSAL => 'ERROR_DIR_TRAVERSAL',
    );

    public $message = 'File name violation: %msg%';
    public $baseDirs = array();

    /**
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        if (!isset($options['baseDirs'])) {
            $options['baseDirs'][] = realpath(SUGAR_BASE_DIR);

            // add additional base directory when shadow is enabled
            if (defined('SHADOW_INSTANCE_DIR')) {
                $options['baseDirs'][] = SHADOW_INSTANCE_DIR;
            }
        }

        parent::__construct($options);
    }
}
