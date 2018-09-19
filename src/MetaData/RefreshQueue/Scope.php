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

namespace Sugarcrm\Sugarcrm\MetaData\RefreshQueue;

/**
 * Task scope.
 */
class Scope
{
    /**
     * Scope parameters
     *
     * @var array
     */
    protected $params = array();

    /**
     * Constructor.
     *
     * @param array $params Scope parameters
     */
    public function __construct(array $params)
    {
        // ignore empty parameters
        $this->params = array_filter($params);
    }

    /**
     * Returns scope parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Checks if the given scope is equal to another
     *
     * @param Scope $other Other scope
     * @return bool
     */
    public function equals(self $other)
    {
        return $this->params == $other->params;
    }

    /**
     * Checks if the given scope completely includes another
     *
     * @param Scope $other Other scope
     * @return bool
     */
    public function includes(self $other)
    {
        if (empty($this->params)) {
            return true;
        }

        if (empty($other->params)) {
            return false;
        }

        $diff = $this->params;
        foreach ($other->params as $param => $value) {
            if (!isset($this->params[$param]) || $this->params[$param] == $other->params[$param]) {
                unset($diff[$param]);
            }
        }

        return empty($diff);
    }

    /**
     * Add/Update the params of this scope
     *
     * @param array $params
     */
    public function mergeParams(array $params)
    {
        $this->params = array_merge($this->params, $params);
    }
}
