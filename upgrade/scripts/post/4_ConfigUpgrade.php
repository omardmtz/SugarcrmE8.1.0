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
/**
 * Update config entries for CE->PRO
 */
class SugarUpgradeConfigUpgrade extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        // only do it when going from ce to non-ce
        if(!($this->from_flavor == 'ce' && $this->to_flavor != 'ce')) return;

        if(isset($this->upgrader->config['sugarbeet']))
        {
            unset($this->upgrader->config['sugarbeet']);
        }

        if(isset($this->upgrader->config['disable_team_access_check']))
        {
            unset($this->upgrader->config['disable_team_access_check']);
        }

        $defaults = array(
            'merge_duplicates' => array(
                'merge_relate_fetch_concurrency' => 2,
                'merge_relate_fetch_timeout' => 90000,
                'merge_relate_fetch_limit' => 20,
                'merge_relate_update_concurrency' => 4,
                'merge_relate_update_timeout' => 90000,
                'merge_relate_max_attempt' => 3,
            ),
            'passwordsetting' => array(
                'minpwdlength' => '',
                'maxpwdlength' => '',
                'oneupper' => '',
                'onelower' => '',
                'onenumber' => '',
                'onespecial' => '',
                'SystemGeneratedPasswordON' => '',
                'generatepasswordtmpl' => '',
                'lostpasswordtmpl' => '',
                'customregex' => '',
                'regexcomment' => '',
                'forgotpasswordON' => false,
                'linkexpiration' => '1',
                'linkexpirationtime' => '30',
                'linkexpirationtype' => '1',
                'userexpiration' => '0',
                'userexpirationtime' => '',
                'userexpirationtype' => '1',
                'userexpirationlogin' => '',
                'systexpiration' => '0',
                'systexpirationtime' => '',
                'systexpirationtype' => '0',
                'systexpirationlogin' => '',
                'lockoutexpiration' => '0',
                'lockoutexpirationtime' => '',
                'lockoutexpirationtype' => '1',
                'lockoutexpirationlogin' => '',
            ),
        );

        foreach ($defaults as $key => $values) {
            if (isset($this->upgrader->config[$key]) && is_array($this->upgrader->config[$key]) && is_array($values)) {
                $this->upgrader->config[$key] = array_merge(
                    $values,
                    $this->upgrader->config[$key]
                );
            } else {
                $this->upgrader->config[$key] = $values;
            }
        }
    }
}
