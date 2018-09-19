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

use Sugarcrm\Sugarcrm\Bean\Visibility\Strategy;
use Sugarcrm\Sugarcrm\Bean\Visibility\Strategy\AllowAll;
use Sugarcrm\Sugarcrm\Bean\Visibility\Strategy\DenyAll;
use Sugarcrm\Sugarcrm\Bean\Visibility\Strategy\TeamSecurity\Denormalized;
use Sugarcrm\Sugarcrm\DependencyInjection\Container;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State;

/**
 * Team security visibility
 */
class TeamSecurity extends NormalizedTeamSecurity
{
    /**
     * @var bool
     */
    private $preferDenormalized;

    /**
     * @var User
     */
    private $user;

    /**
     * @var bool
     */
    private $table;

    /**
     * @var Strategy
     */
    private $strategy;

    /**
     * @var DBManager
     */
    private $db;

    public function __construct(SugarBean $bean, $params = null)
    {
        global $current_user;

        parent::__construct($bean, $params);

        $this->user = $current_user;
        $this->db = DBManagerFactory::getInstance();
    }

    public function setOptions($options)
    {
        parent::setOptions($options);

        $this->preferDenormalized = !empty($this->options['use_denorm']);
        $this->table = isset($this->options['table_alias'])
            ? $this->options['table_alias'] : $this->bean->getTableName();
        $this->strategy = null;

        return $this;
    }

    public function addVisibilityFrom(&$query)
    {
        $query = $this->getStrategy()->applyToFrom($this->db, $query, $this->table);

        return $query;
    }

    public function addVisibilityWhere(&$query)
    {
        $query = $this->getStrategy()->applyToWhere($this->db, $query, $this->table);

        return $query;
    }

    public function addVisibilityQuery(SugarQuery $query)
    {
        $this->getStrategy()->applyToQuery($query, $this->table);
    }

    private function getStrategy()
    {
        if ($this->strategy) {
            return $this->strategy;
        }

        return $this->strategy = $this->detectStrategy();
    }

    private function detectStrategy()
    {
        if (!$this->user) {
            return new DenyAll();
        }

        if (!$this->isTeamSecurityApplicable()) {
            return new AllowAll();
        }

        if (!empty($this->options['use_denorm'])) {
            $state = Container::getInstance()->get(State::class);

            if ($state->isAvailable()) {
                return new Denormalized($state->getActiveTable(), $this->user);
            }
        }

        return (new NormalizedTeamSecurity($this->bean))->setOptions($this->options);
    }
}
