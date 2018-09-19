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

namespace Sugarcrm\Sugarcrm\DataPrivacy\Erasure;

use Countable;
use InvalidArgumentException;
use SugarBean;
use Sugarcrm\Sugarcrm\DataPrivacy\Erasure\Field\Email;
use Sugarcrm\Sugarcrm\DataPrivacy\Erasure\Field\Scalar;

/**
 * List of fields marked for erasure
 */
class FieldList implements Field, Countable
{
    /**
     * @var Field[]
     */
    private $fields;

    /**
     * Constructor
     *
     * @param Field[] $fields
     */
    public function __construct(Field ...$fields)
    {
        $this->fields = $fields;
    }

    /**
     * Creates list from an array representation
     *
     * @param array $array
     * @return self
     */
    public static function fromArray(array $array) : self
    {
        return new self(
            ...array_map(function ($element) : Field {
                if (is_string($element)) {
                    $element = ['field_name' => $element];
                }

                if (!isset($element['field_name'])) {
                    throw new InvalidArgumentException(
                        'The array representation of a field must contain the "field_name" element'
                    );
                }

                if ($element['field_name'] === 'email') {
                    return self::createEmailField($element);
                }

                return self::createScalarField($element);
            }, $array)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function erase(SugarBean $bean) : void
    {
        foreach ($this->fields as $field) {
            $field->erase($bean);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return array_map(function (Field $field) {
            return $field->jsonSerialize();
        }, $this->fields);
    }

    /**
     * Returns a new list which contain fields both of this and the other lists
     *
     * @param self $other
     * @return self
     */
    public function with(self $other) : self
    {
        $fields = $this->fields;

        foreach ($other->fields as $field) {
            if (array_search($field, $fields) === false) {
                $fields[] = $field;
            }
        }

        return new self(...$fields);
    }

    /**
     * Returns a new list which contain fields this list but not the other one's
     *
     * @param self $other
     * @return self
     */
    public function without(self $other) : self
    {
        $fields = $this->fields;

        foreach ($other->fields as $field) {
            $index = array_search($field, $fields);

            if ($index !== false) {
                unset($fields[$index]);
            }
        }

        return new self(...$fields);
    }

    /**
     * Creates a scalar field from array representation
     *
     * @param array $array
     * @return Scalar
     */
    private static function createScalarField(array $array) : Scalar
    {
        return new Scalar($array['field_name']);
    }

    /**
     * Creates an email field from array representation
     *
     * @param array $array
     * @return Email
     */
    private static function createEmailField(array $array) : Email
    {
        if (!isset($array['id'])) {
            $array['id'] = '';
//            throw new InvalidArgumentException(
//                'The array representation of an email field must contain the "id" element'
//            );
        }

        return new Email($array['id']);
    }

    /**
     * {@inheritDoc}
     */
    public function count() : int
    {
        return count($this->fields);
    }
}
