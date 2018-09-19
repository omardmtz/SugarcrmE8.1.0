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

namespace Sugarcrm\Sugarcrm\Security\Context;

use JsonSerializable;
use Sugarcrm\Sugarcrm\Security\Subject;

/**
 * Maintains association between security subject and attributes
 *
 * @internal
 */
class SubjectAttributes implements JsonSerializable
{
    /**
     * @var Subject
     */
    private $subject;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * Constructor
     *
     * @param Subject $subject
     */
    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
    }

    /**
     * Returns security subject
     *
     * @return Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Returns associated attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets attributes associated with security subject
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        $data = ['subject' => $this->subject->jsonSerialize()];

        if (count($this->attributes)) {
            $data['attributes'] = $this->attributes;
        }

        return $data;
    }
}
