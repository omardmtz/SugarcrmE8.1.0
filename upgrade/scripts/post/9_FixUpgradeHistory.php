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
 * Fix upgrade_history.manifest:
 *
 * The format of this column used to be a base64 encoded serialized php array.
 * Since the new CLI upgrader the content has been json encoded. This script
 * fixes the formatting and re-encodes in the old way if json is detected.
 */
class SugarUpgradeFixUpgradeHistory extends UpgradeScript
{
    public $order = 9901;
    public $type = self::UPGRADE_DB;

    /**
     *
     * Execute upgrade tasks
     * @see UpgradeScript::run()
     */
    public function run()
    {
        $q = $this->db->query("SELECT id, manifest FROM upgrade_history");
        while ($row = $this->db->fetchByAssoc($q, false)) {
            if ($this->isJson($row['manifest'])) {
                $update = sprintf(
                    "UPDATE upgrade_history SET manifest = %s WHERE id = %s",
                    $this->db->quoted($this->reEncode($row['manifest'])),
                    $this->db->quoted($row['id'])
                );
                $this->db->query($update);
            }
        }
    }

    /**
     *
     * Check if passed in string is json encoded
     * @param string $string
     * @return boolean
     */
    protected function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     *
     * Re-encode given string using base64/serialize
     * @param string $string
     * @return string
     */
    protected function reEncode($string)
    {
        return base64_encode(serialize(json_decode($string)));
    }
}
