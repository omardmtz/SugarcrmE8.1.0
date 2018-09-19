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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\Filter;

use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use TeamSet;
use User;

/**
 *
 * Team set filter
 *
 */
class TeamSetFilter implements FilterInterface
{
    use FilterTrait;

    /**
     * @var string
     */
    protected $defaultField = 'team_set_id.set';

    /**
     * {@inheritdoc}
     */
    public function buildFilter(array $options = array())
    {
        $teamSetIds = $this->getTeamSetIds($options['user']);
        $field = !empty($options['field']) ? $options['field'] : $this->defaultField;
        $field = $options['module'] . Mapping::PREFIX_SEP . $field;
        return new \Elastica\Query\Terms($field, $teamSetIds);
    }

    /**
     * Get team set ids for given user
     * @param User $user
     * @return array
     */
    protected function getTeamSetIds(User $user)
    {
        return TeamSet::getTeamSetIdsForUser($user->id);
    }
}
