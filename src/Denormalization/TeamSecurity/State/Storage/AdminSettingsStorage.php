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

namespace Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State\Storage;

use Administration;
use BeanFactory;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State\Storage;

/**
 * Implementation of the state storage which uses admin settings as a persistance layer
 */
final class AdminSettingsStorage implements Storage
{
    /**#@+
     * @var string
     */
    const CATEGORY = 'team_security';
    const NAME = 'denormalization_state';
    /**#@-*/

    /**
     * @var Administration
     */
    private $admin;

    /**
     * @var array|null
     */
    private $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->admin = BeanFactory::newBean('Administration');
    }

    /**
     * {@inheritDoc}
     */
    public function get($var)
    {
        if ($this->data === null) {
            $this->data = $this->load();
        }

        if (isset($this->data[$var])) {
            return $this->data[$var];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function update($var, $value)
    {
        if ($this->data === null) {
            $this->data = $this->load();
        }

        $this->data[$var] = $value;
        $this->admin->saveSetting(self::CATEGORY, self::NAME, $this->data);
    }

    private function load()
    {
        $this->admin->retrieveSettings(self::CATEGORY);

        $key = self::CATEGORY . '_' . self::NAME;

        if (!isset($this->admin->settings[$key])
            || !is_array($this->admin->settings[$key])) {
            return [];
        }

        return $this->admin->settings[$key];
    }
}
