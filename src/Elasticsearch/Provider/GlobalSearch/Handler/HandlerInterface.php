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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler;

use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\GlobalSearch;

/**
 *
 * Handler marker interface
 *
 */
interface HandlerInterface
{
    /**
     * Set global search provider
     * @param GlobalSearch $provider
     */
    public function setProvider(GlobalSearch $provider);

    /**
     * Return the handler name
     * @return string
     */
    public function getName();
}
