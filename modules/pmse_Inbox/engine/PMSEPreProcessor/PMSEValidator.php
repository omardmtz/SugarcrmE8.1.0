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


use Sugarcrm\Sugarcrm\ProcessManager;

class PMSEValidator
{
    /**
     * The type of validator being run
     * @var string
     */
    protected $type;

    /**
     * The list of validators
     * @var array
     */
    protected $validators = [
        'direct' => [
            'terminate' => PMSEValidationLevel::NoValidation,
            'concurrency' => PMSEValidationLevel::Simple,
            'element' => PMSEValidationLevel::NoValidation,
            'expression' => PMSEValidationLevel::NoValidation,
        ],
        'hook' => [
            'terminate' => PMSEValidationLevel::Simple,
            'concurrency' => PMSEValidationLevel::NoValidation,
            'element' => PMSEValidationLevel::Simple,
            'expression' => PMSEValidationLevel::Simple,
        ],
        'engine' => [
            'terminate' => PMSEValidationLevel::NoValidation,
            'concurrency' => PMSEValidationLevel::NoValidation,
            'element' => PMSEValidationLevel::NoValidation,
            'expression' => PMSEValidationLevel::NoValidation,
        ],
        'queue' => [
            'terminate' => PMSEValidationLevel::NoValidation,
            'concurrency' => PMSEValidationLevel::Simple,
            'element' => PMSEValidationLevel::NoValidation,
            'expression' => PMSEValidationLevel::NoValidation,
        ],
    ];

    /**
     * The PMSELogger object
     * @var PMSELogger
     */
    protected $logger;

    /**
     * List of known validator classes to be used in the retrieveValidator method
     * @var array
     */
    protected $validatorClasses = [
        'terminate' => 'PMSETerminateValidator',
        'concurrency' => 'PMSEConcurrencyValidator',
        'record' => 'PMSERecordValidator',
        'element' => 'PMSEElementValidator',
        'expression' => 'PMSEExpressionValidator'
    ];

    /**
     * List of instatiated validator objects
     * @var array
     */
    protected $validatorObjects = [];

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getLogger()
    {
        if (empty($this->logger)) {
            $this->logger = PMSELogger::getInstance();
        }

        return $this->logger;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     *
     * @param type $validators
     */
    public function setValidators($validators)
    {
        $this->validators = $validators;
    }

    /**
     *
     * @param PMSELogger $logger
     * @codeCoverageIgnore
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @param type $type
     * @codeCoverageIgnore
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     *
     * @param type $name
     * @param type $level
     * @return boolean|PMSEBaseValidator
     * @codeCoverageIgnore
     */
    public function retrieveValidator($name, $level)
    {
        // Default the base return value
        $validator = false;

        // If we have a validator class defined for this name
        if (isset($this->validatorClasses[$name])) {
            if ($validator = ProcessManager\Factory::getPMSEObject($this->validatorClasses[$name])) {
                $validator->setLevel($level);
            }

            $this->validatorObjects[$name] = $validator;
        }

        return $validator;
    }

    /**
     *
     * @return \PMSERequest
     * @codeCoverageIgnore
     */
    public function generateNewRequest()
    {
        return ProcessManager\Factory::getPMSEObject('PMSERequest');
    }

    /**
     *
     * @param PMSERequest $request
     * @return type
     */
    public function validateRequest(PMSERequest $request)
    {
        // Get our type
        $type = $request->getType();

        // A default request is always valid, if fails to validate in any validator
        // the status is set to invalid and no further validation is required
        if (!isset($this->validators[$type])) {
            return false;
        }

        // Loop over our validators and check state
        foreach ($this->validators[$type] as $validatorName => $validatorLevel) {
            // If we need validation for this type...
            if ($validatorLevel != PMSEValidationLevel::NoValidation) {
                // Get our validator
                $validator = $this->retrieveValidator($validatorName, $validatorLevel);

                // Run the validator
                $request = $validator->validateRequest($request);

                // If we are not valid, return the request now so we stop validating
                if (!$request->isValid()) {
                    return $request;
                }
            }
        }

        $request->setStatus('PROCESSED');
        return $request;
    }

    /**
     * Clears any internal caches created during the validation loop
     */
    public function clearValidatorCaches()
    {
        foreach ($this->validatorObjects as $validator) {
            $validator->clearCache();
        }
    }

}
