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
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Exception\HandlerCollectionException;

/**
 *
 * Handler collection iterator
 *
 */
class HandlerCollection implements \IteratorAggregate
{
    /**
     * @var GlobalSearch
     */
    protected $provider;

    /**
     * @var HandlerInterface[]
     */
    protected $handlers;

    /**
     * Ctor
     * @param GlobalSearch $provider
     */
    public function __construct(GlobalSearch $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->handlers);
    }

    /**
     * Add handler. Every handler is identified by its name. When adding
     * a handler which already exists with the same name, the previous
     * one is replaced by the one which is passed in.
     *
     * @param HandlerInterface $handler
     */
    public function addHandler(HandlerInterface $handler)
    {
        $handler->setProvider($this->provider);
        $this->handlers[$handler->getName()] = $handler;
    }

    /**
     * Check if given handler exists
     * @param string $name
     */
    public function hasHandler($name)
    {
        return isset($this->handlers[$name]);
    }

    /**
     * Remove handler from collection
     * @param string $name
     * @throws HandlerCollectionException
     */
    public function removeHandler($name)
    {
        if (!$this->hasHandler($name)) {
            throw new HandlerCollectionException("Cannot remove non-existing handler $name");
        }
        unset($this->handlers[$name]);
    }

    /**
     * Get handler by name
     * @param string $name
     * @throws HandlerCollectionException
     * @return HandlerInterface
     */
    public function getHandler($name)
    {
        if (!$this->hasHandler($name)) {
            throw new HandlerCollectionException("Handler $name does not exist");
        }
        return $this->handlers[$name];
    }
}
