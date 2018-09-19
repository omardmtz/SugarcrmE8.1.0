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

namespace Sugarcrm\Sugarcrm\Audit;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Sugarcrm\Sugarcrm\Audit\Change\Email;
use Sugarcrm\Sugarcrm\Audit\Change\Scalar;

/**
 * List of fields marked for erasure
 */
class FieldChangeList implements JsonSerializable, IteratorAggregate, \Countable
{
    /**
     * @var Change[]
     */
    private $changes;

    /**
     * Constructor
     *
     * @param Change[] $changes
     */
    public function __construct(Change ...$changes)
    {
        $this->changes = $changes;
    }

    /**
     * Creates list from an array representation
     *
     * @param array $changes
     *
     * @return self
     */
    public static function fromChanges(array $changes) : self
    {
        //Todo: Refactor once we want to add support for custom Field Change classes.
        $auditFields = [];
        foreach ($changes as $fieldName => $change) {
            $class = Scalar::class;
            if ($change['data_type'] == 'email') {
                $class = Email::class;
            }
            $auditFieldChanges = $class::getAuditFieldChanges($change);
            if (!empty($auditFieldChanges)) {
                array_push($auditFields, ...$auditFieldChanges);
            }
        }

        return new self(...$auditFields);
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return array_map(
            function (Change $change) {
                return $change->jsonSerialize();
            },
            $this->changes
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->changes);
    }

    /**
     * @return array
     */
    public function getChangesList()
    {
        return array_map(
            function (Change $change) {
                return $change->getChangeArray();
            },
            $this->changes
        );
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->changes);
    }
}
