<?php declare(strict_types=1);
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

namespace Sugarcrm\Sugarcrm\DataPrivacy\Erasure\Field;

use SugarBean;
use Sugarcrm\Sugarcrm\DataPrivacy\Erasure\Field;

/**
 * Represents a scalar field stored on the bean itself
 */
final class Scalar implements Field
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name The field name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function erase(SugarBean $bean) : void
    {
        $bean->{$this->name} = null;
    }
}
