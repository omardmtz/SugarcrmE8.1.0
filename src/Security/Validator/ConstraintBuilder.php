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

namespace Sugarcrm\Sugarcrm\Security\Validator;

use Sugarcrm\Sugarcrm\Security\Validator\Exception\ConstraintBuilderException;
use Symfony\Component\Validator\Constraint;

/**
 *
 * Constraint Builder
 *
 * This constraint builder hides the details of instantiating the different
 * asserts/constraints with the optional configuration parameters. The goal
 * is to be able to use a declarative form of constraint chains which can
 * be interpretted by this ConstraintBuilder inside the business logic.
 *
 * Use `ConstraintBuilder::build($definition)` to transform your definition
 * into the actual constraint objects which are valid to be passed into the
 * validator context. See `InputValidation\Request::getValidInput` how
 * ConstraintBuilder is being used.
 *
 */
class ConstraintBuilder
{
    /**
     * Available namespace to load constraints in order of priority
     * @var array
     */
    protected $namespaces = array(
        'Sugarcrm\Sugarcrm\custom\Security\Validator\Constraints',
        'Sugarcrm\Sugarcrm\Security\Validator\Constraints',
        'Symfony\Component\Validator\Constraints',
    );

    /**
     * Set namespaces to search for constraints
     * @param array $namespaces
     */
    public function setNamespaces(array $namespaces)
    {
        $this->namespaces = $namespaces;
    }

    /**
     * Get namespaces
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Build constraints based on given definition
     * @param array|string $constraints Constraint definition
     * @return Constraint[]
     */
    public function build($constraints)
    {
        // empty definitions are possible
        if (empty($constraints)) {
            return array();
        }

        if (!is_array($constraints)) {
            $constraints = array($constraints);
        }

        $result = array();
        foreach ($constraints as $assert => $options) {

            if (is_numeric($assert)) {
                $assert = $options;
                $options = array();
            }

            $result[] = $this->buildConstraint($assert, $options);
        }

        return $result;
    }

    /**
     * Build constraint with options
     * @param string $assert
     * @param array $options
     * @throws ConstraintBuilderException
     * @return Constraint
     */
    protected function buildConstraint($assert, $options = array())
    {
        if (!$this->isAssert($assert)) {
            if (is_string($assert)) {
                throw new ConstraintBuilderException(sprintf(
                    'Invalid constraint "%s", should start with "Assert\"',
                    $assert
                ));
            } else {
                throw new ConstraintBuilderException(sprintf(
                    'Invalid constraint, expecting string, got %s',
                    gettype($assert)
                ));
            }
        }

        $class = $this->getAssertClass($assert);

        if (!is_array($options)) {
            throw new ConstraintBuilderException(sprintf(
                'Assert options expected to be an array, %s given',
                gettype($options))
            );
        }

        return new $class($this->parseOptions($options));
    }

    /**
     * Constraint options parsers
     * @param array $options
     * @return array
     */
    protected function parseOptions(array $options)
    {
        $result = array();
        foreach ($options as $key => $value) {

            if ($this->isAssert($key)) {
                $result[] = $this->buildConstraint($key, $value);
                continue;
            }

            if ($this->isAssert($value)) {
                $result[$key] = $this->buildConstraint($value);
                continue;
            }

            if (is_array($value)) {
                $result[$key] = $this->parseOptions($value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Test if given string is an Assert definition
     * @param string $assert
     * @return boolean
     */
    protected function isAssert($assert)
    {
        return is_string($assert) && strpos($assert, 'Assert\\') === 0;
    }

    /**
     * Figure out classname for given assert definition
     * @param string $assert
     * @throws ConstraintBuilderException
     * @return string
     */
    protected function getAssertClass($assert)
    {
        foreach ($this->namespaces as $prefix) {
            $class = $prefix . '\\' . substr($assert, strlen('Assert\\'));
            if (class_exists($class)) {
                return $class;
            }
        }

        throw new ConstraintBuilderException(sprintf(
            'Cannot find class for assert "%s"',
            $assert
        ));
    }
}
