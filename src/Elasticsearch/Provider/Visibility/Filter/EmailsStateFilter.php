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

class EmailsStateFilter implements FilterInterface
{
    use FilterTrait;

    /**
     * Builds a filter to find emails matching the provided "state" option. See {@link Email} for possible states.
     *
     * {@inheritdoc}
     */
    public function buildFilter(array $options = [])
    {
        $field = 'Emails' . Mapping::PREFIX_SEP . 'state.emails_state';

        $filter = new \Elastica\Query\Term();
        $filter->setTerm($field, $options['state']);

        return $filter;
    }
}
