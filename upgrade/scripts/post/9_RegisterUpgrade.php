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
 * Register upgrade with the system
 */
class SugarUpgradeRegisterUpgrade extends UpgradeScript
{
    public $order = 9900;

    public function __construct($upgrader)
    {
        $this->type = self::UPGRADE_CUSTOM | self::UPGRADE_DB;
        parent::__construct($upgrader);
    }

    public function run()
    {
        // if error was encountered, script should have died before now
        $new_upgrade = new UpgradeHistory();
        $new_upgrade->filename = $this->context['zip'];
        if (!empty($this->context['zip_as_dir'])) {
            $new_upgrade->md5sum = trim(file_get_contents($this->context['zip'] . DIRECTORY_SEPARATOR . 'md5sum'));
        } else {
            if (file_exists($this->context['zip'])) {
                $new_upgrade->md5sum = md5_file($this->context['zip']);
            } else {
                // if file is not there, just md5 the filename
                $new_upgrade->md5sum = md5($this->context['zip']);
            }
        }
        $dup = $this->db->getOne("SELECT id FROM upgrade_history WHERE md5sum='{$new_upgrade->md5sum}'");
        if ($dup) {
            $this->error("Duplicate install for package, md5: {$new_upgrade->md5sum}");
            // Not failing it - by now there's no point, we're at the end anyway
            return;
        }
        $new_upgrade->name = pathinfo($this->context['zip'], PATHINFO_FILENAME);
        $new_upgrade->description = $this->manifest['description'];
        $new_upgrade->type = 'patch';
        $new_upgrade->version = $this->to_version;
        $new_upgrade->status = "installed";
        $new_upgrade->manifest = base64_encode(serialize($this->manifest));
        $new_upgrade->save();
    }
}
