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
 * Interface of Executer for runners.
 *
 * Interface RunnableInterface
 * @package Sugarcrm\Sugarcrm\Util\Runner
 */
interface RunnableInterface
{
    /**
     * Return traversable list of bean for running.
     *
     * @return \Traversable
     */
    public function getBeans();

    /**
     * Do action by one bean.
     *
     * @param \SugarBean $bean
     */
    public function execute(\SugarBean $bean);
}
