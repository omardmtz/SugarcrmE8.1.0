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

namespace Sugarcrm\Sugarcrm\Security\Teams;

use function array_merge;
use function array_values;
use Team;
use TeamSet as PersistentTeamSet;

final class TeamSet
{
    private $teams = [];

    public function __construct(Team ...$teams)
    {
        foreach ($teams as $team) {
            $this->teams[$team->id] = $team;
        }
    }

    /**
     * @param Team $team
     * @return self
     */
    public function withTeam(Team $team)
    {
        if (isset($this->teams[$team->id])) {
            return $this;
        }

        $teams = array_merge($this->teams, [$team]);

        return new self(...array_values($teams));
    }

    /**
     * @param Team $team
     * @return self
     */
    public function withoutTeam(Team $team)
    {
        if (!isset($this->teams[$team->id])) {
            return $this;
        }

        $teams = $this->teams;
        unset($teams[$team->id]);

        return new self(...array_values($teams));
    }

    public function persist()
    {
        if (!count($this->teams)) {
            throw new \DomainException('Empty team set cannot be persisted');
        }

        return (new PersistentTeamSet())->addTeams(array_keys($this->teams));
    }
}
