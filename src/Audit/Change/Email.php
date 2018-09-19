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

namespace Sugarcrm\Sugarcrm\Audit\Change;

use Sugarcrm\Sugarcrm\Audit\Change;

/**
 * Represents an email field
 */
class Email implements Change
{
    /**
     * @var string
     */
    private $before;
    private $after;

    /**
     * Constructor
     *
     * @param string $id The ID of the email to be erased
     */
    public function __construct(?string $before, ?string $after)
    {
        $this->before = $before;
        $this->after = $after;
    }

    /**
     * @inheritdoc
     */
    public static function getAuditFieldChanges(array $change)
    {
        $emailChanges = [];
        $before_addresses = [];
        $after_addresses = [];

        if (is_array($change['before'])) {
            $before_addresses = array_column($change['before'], 'email_address_id');
        }
        if (is_array($change['after'])) {
            $after_addresses = array_column($change['after'], 'email_address_id');
        }

        //Check for removed addresses
        foreach (array_diff($before_addresses, $after_addresses) as $id) {
            $emailChanges[] = new self($id, null);
        }
        //Check for added addresses
        foreach (array_diff($after_addresses, $before_addresses) as $id) {
            $emailChanges[] = new self(null, $id);
        }

        return $emailChanges;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->getChangeArray();
    }

    public function getChangeArray()
    {
        return [
            'field_name' => 'email',
            'data_type' => 'email',
            'before' => $this->before,
            'after' => $this->after,
        ];
    }
}
