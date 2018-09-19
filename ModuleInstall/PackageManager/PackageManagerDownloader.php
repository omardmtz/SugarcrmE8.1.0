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

class PackageManagerDownloader
{
    const PACKAGE_MANAGER_DOWNLOAD_SERVER = 'https://depot.sugarcrm.com/depot/';

    const PACKAGE_MANAGER_DOWNLOAD_PAGE = 'download.php';

	/**
	 * Using curl we will download the file from the depot server
	 *
     * @param string $session_id The session_id this file is queued for
     * @param string $file_name The file_name to download
     * @param string $save_dir (optional) If specified it will direct where to save the file once downloaded
     * @param string $download_sever (optional) If specified it will direct the url for the download
	 *
     * @return string The full path of the saved file
	 */
    public static function download($session_id, $file_name, $save_dir = '', $download_server = '')
    {
		if(empty($save_dir)){
			$save_dir = "upload://";
		}
		if(empty($download_server)){
            $download_server = self::PACKAGE_MANAGER_DOWNLOAD_SERVER;
		}
        $download_server .= self::PACKAGE_MANAGER_DOWNLOAD_PAGE;
		$ch = curl_init($download_server . '?filename='. $file_name);
		$fp = sugar_fopen($save_dir . $file_name, 'w');
		curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID='.$session_id. ';');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		return $save_dir . $file_name;
	}
}
