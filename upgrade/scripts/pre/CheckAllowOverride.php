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
 * Check that AllowOverride is properly configured
 */
class SugarUpgradeCheckAllowOverride extends UpgradeScript
{
    public $order = 200;
    public $version = '7.2.0';
    public $type = self::UPGRADE_CORE;

    public function run()
    {
        if(version_compare($this->from_version, '7.0', '>=')) {
            // no need to run this on 7, if AllowOverride doesn't work 7 wouldn't work too
            return;
        }

        if(!empty($_SERVER["SERVER_SOFTWARE"]) && strpos($_SERVER["SERVER_SOFTWARE"],'Microsoft-IIS') !== false) {
            // can't do it for IIS
            return;
        }

        //if running upgrade from cli we need to check server software from URL
        if (php_sapi_name() == 'cli') {
            file_put_contents('server_test.php', '<?php print $_SERVER["SERVER_SOFTWARE"];');
            $serverSoftware = @file_get_contents($this->upgrader->config['site_url'] . '/server_test.php');
            @unlink('server_test.php');

            if ($serverSoftware && stripos($serverSoftware, 'Microsoft-IIS') !== false) {
                return;
            }
        }

        $this->log("Testing .htaccess redirects");
        if(file_exists(".htaccess")) {
            $old_htaccess = file_get_contents(".htaccess");
        }
        $basePath = parse_url($this->upgrader->config['site_url'], PHP_URL_PATH);
        if(empty($basePath)) $basePath = '/';
        $htaccess_test = <<<EOT

# Upgrader test addition
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase {$basePath}
    RewriteRule ^itest.test$ install_test.test [N,QSA]
</IfModule>
EOT;
        if(!empty($old_htaccess)) {
            $htaccess_test = $old_htaccess.$htaccess_test;
        }
        file_put_contents(".htaccess", $htaccess_test);
        file_put_contents("install_test.test", "SUCCESS");
        $res = file_get_contents($this->upgrader->config['site_url']."/itest.test");
        unlink("install_test.test");
        if(!empty($old_htaccess)) {
            file_put_contents(".htaccess", $old_htaccess);
        } else {
            unlink(".htaccess");
        }
        if($res != "SUCCESS") {
            $this->error("Could not verify .htaccess is working: $res");
        }
    }
}
