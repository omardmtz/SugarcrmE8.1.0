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

namespace Sugarcrm\Sugarcrm\Dbal\Query;

use Doctrine\DBAL\Query\QueryBuilder as BaseQueryBuilder;

/**
 * {@inheritDoc}
 */
class QueryBuilder extends BaseQueryBuilder
{
    /**
     * Imports sub-query parameters into itself and returns string representation of the sub-query
     *
     * @param BaseQueryBuilder $subBuilder Sub-query builder
     * @return string
     */
    public function importSubQuery(BaseQueryBuilder $subBuilder)
    {
        $params = $subBuilder->getParameters();
        foreach ($params as $key => $value) {
            $this->createPositionalParameter(
                $value,
                $subBuilder->getParameterType($key)
            );
        }

        return $subBuilder->getSQL();
    }
}
