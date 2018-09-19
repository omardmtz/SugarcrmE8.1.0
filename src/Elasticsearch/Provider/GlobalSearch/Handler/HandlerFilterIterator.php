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

/**
 *
 * Handler filter iterator
 *
 */
class HandlerFilterIterator extends \FilterIterator
{
    /**
     * @var string
     */
    protected $interface;

    /**
     * Ctor
     * @param HandlerCollection $collection
     * @param string $interface
     */
    public function __construct(\Iterator $collection, $interface)
    {
        $this->setInterface($interface);
        parent::__construct($collection);
    }

    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        return in_array($this->interface, class_implements($this->current()));
    }

    /**
     * Set interface to filter by
     * @param string $interface
     */
    protected function setInterface($interface)
    {
        $this->interface = sprintf(
            'Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\%sHandlerInterface',
            $interface
        );

        // ensure interface is valid
        if (!interface_exists($this->interface)) {
            throw new \LogicException("Handler interface '{$this->interface}' does not exist");
        }
    }
}
