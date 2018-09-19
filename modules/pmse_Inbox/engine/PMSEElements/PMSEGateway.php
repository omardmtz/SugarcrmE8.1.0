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

class PMSEGateway extends PMSEShape
{
    protected $evaluator;

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getEvaluator()
    {
        return $this->evaluator;
    }

    /**
     *
     * @param type $expressionEvaluator
     * @codeCoverageIgnore
     */
    public function setEvaluator($expressionEvaluator)
    {
        $this->evaluator = $expressionEvaluator;
    }

    /**
     * Class Constructor
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        parent::__construct();
        $this->evaluator = ProcessManager\Factory::getPMSEObject('PMSEEvaluator');
    }

}
