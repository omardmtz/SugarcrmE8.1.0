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

namespace Sugarcrm\Sugarcrm\Util\Runner;

/**
 * Dot runner for execution action on list of beans.
 *
 * Class Doc
 * @package Sugarcrm\Sugarcrm\Util\Runner
 */
class Dot
{
    /**
     * @var RunnableInterface
     */
    protected $runnable;

    /**
     * Dot runner constructor.
     *
     * @param RunnableInterface $runnable
     */
    public function __construct(RunnableInterface $runnable)
    {
        $this->runnable = $runnable;
    }

    /**
     * Iteration by beans and echo dot for each execution.
     */
    public function run()
    {
        set_time_limit(0);
        foreach ($this->runnable->getBeans() as $bean) {
            $this->runnable->execute($bean);
            echo '. ';
            flush();
        }
    }
}
