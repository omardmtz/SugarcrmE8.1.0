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

namespace Sugarcrm\Sugarcrm\Security;

use DomainException;
use JsonSerializable;
use Psr\Log\LoggerInterface;
use SplStack;
use Sugarcrm\Sugarcrm\Security\Context\SubjectAttributes;

/**
 * Security context which stores current security related information for audit purposes
 */
class Context implements JsonSerializable
{
    /**
     * @var SplStack
     */
    private $stack;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     * @codeCoverageIgnore
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->stack = new SplStack();
        $this->logger = $logger;
    }

    /**
     * Activates the given subject
     *
     * All data modifications will be attributed to this subject until it's deactivated or superseded.
     *
     * @param Subject $subject
     */
    public function activateSubject(Subject $subject)
    {
        $this->stack->push(
            new SubjectAttributes($subject)
        );

        $this->logger->info('Subject activated: ' . json_encode($subject));
    }

    /**
     * Deactivates the given subject
     *
     * @param Subject $subject
     * @throws DomainException
     */
    public function deactivateSubject(Subject $subject)
    {
        $activeSubject = $this->top()->getSubject();

        if ($subject !== $activeSubject) {
            throw new DomainException('The given security subject is not active');
        }

        $this->stack->pop();

        $this->logger->info('Subject deactivated: ' . json_encode($subject));
    }

    /**
     * Checks whether there is active security subject
     *
     * @return bool
     */
    public function hasActiveSubject()
    {
        return !$this->stack->isEmpty();
    }

    /**
     * Associates the given attribute with active security subject
     *
     * @param string $name
     * @param mixed $value
     * @throws DomainException
     */
    public function setAttribute($name, $value)
    {
        $top = $this->top();

        $attributes = $top->getAttributes();
        $attributes[$name] = $value;
        $top->setAttributes($attributes);
    }

    /**
     * Removes association between the given attribute and active security subject
     *
     * @param $name
     * @throws DomainException
     */
    public function unsetAttribute($name)
    {
        $top = $this->top();

        $attributes = $top->getAttributes();
        unset($attributes[$name]);
        $top->setAttributes($attributes);
    }

    /**
     * @return SubjectAttributes
     * @throws DomainException
     */
    private function top()
    {
        if (!$this->hasActiveSubject()) {
            throw new DomainException('There is no active security subject');
        }

        return $this->stack->top();
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        if (!$this->hasActiveSubject()) {
            return null;
        }

        return $this->top()->jsonSerialize();
    }
}
