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

require_once('include/export_utils.php');

function template_handle_export(&$reporter)
{
    ini_set('zlib.output_compression', 'Off');
$reporter->plain_text_output = true;
//disable paging so we get all results in one pass
$reporter->enable_paging = false;
$reporter->run_query();
$reporter->_load_currency();
$header_arr = array();
$header_row = $reporter->get_header_row();
$content = '';
        foreach ($header_row as $cell)
        {
                array_push($header_arr, $cell);
        }
        $header = implode("\"".getDelimiter() ."\"",array_values($header_arr));
        $header = "\"" .$header;
        $header .= "\"\r\n";
        $content .= $header;

        while (( $row = $reporter->get_next_row('result', 'display_columns', false, true) ) != 0 )
        {
                $new_arr = array();

                for($i=0;$i < count($row['cells']);$i++)
                {
                        array_push($new_arr, preg_replace("/\"/","\"\"", from_html($row['cells'][$i])));
                }

                $line = implode("\"".getDelimiter() ."\"",$new_arr);
                $line = "\"" .$line;
                $line .= "\"\r\n";

                $content .= $line;
        }
        global $locale, $sugar_config;

        $transContent = $GLOBALS['locale']->translateCharset(
            $content,
            'UTF-8',
            $GLOBALS['locale']->getExportCharset(),
            false,
            true
        );

ob_clean();
header("Pragma: cache");
header("Content-type: application/octet-stream; charset=".$locale->getExportCharset());
header("Content-Disposition: attachment; filename={$_REQUEST['module']}.csv");
header("Content-transfer-encoding: binary");
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . TimeDate::httpTime() );
header( "Cache-Control: post-check=0, pre-check=0", false );
header("Content-Length: ".mb_strlen($transContent, '8bit'));
    if (!empty($sugar_config['export_excel_compatible'])) {
        print $transContent;
    } else {
        $user_agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if ($locale->getExportCharset() == 'UTF-8' &&
            ! preg_match('/macintosh|mac os x|mac_powerpc/i', $user_agent)) {
            $BOM = "\xEF\xBB\xBF";
        } else {
            $BOM = ''; // Mac Excel does not support utf-8
        }
        print $BOM . $transContent;
    }
}
