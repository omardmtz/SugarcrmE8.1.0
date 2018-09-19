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



abstract class SugarForecasting_AbstractForecastArgs
{
    /**
     * @var array Rest Arguments
     */
    protected $args;

    /**
     * Class Constructor
     * @param array $args       Service Arguments
     */
    public function __construct($args)
    {
        $this->setArgs($args);
    }

    /**
     * Set the arguments
     *
     * @param array $args
     * @return SugarForecasting_AbstractForecast
     */
    public function setArgs($args)
    {
        $this->args = $args;

        return $this;
    }

    /**
     * Return the arguments array
     *
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Get a specific Arg Value, If it doesn't exist return Empty
     *
     * @param $key
     * @return string
     */
    public function getArg($key)
    {
        return isset($this->args[$key]) ? $this->args[$key] : "";
    }

    /**
     * Set an Arg to track
     *
     * @param string $key
     * @param mixed $value
     * @return SugarForecasting_AbstractForecast
     */
    public function setArg($key, $value)
    {
        $this->args[$key] = $value;

        return $this;
    }

}