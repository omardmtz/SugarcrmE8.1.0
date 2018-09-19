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
require_once("include/Sugarpdf/sugarpdf_config.php");
if ( !headers_sent() ) {
    ini_set('zlib.output_compression', 'Off');
}
if (PDF_CLASS == "EZPDF" && file_exists('modules/Reports/templates/templates_ezpdf.php')) {
    require_once('modules/Reports/templates/templates_ezpdf.php');
}else{
    require_once('modules/Reports/templates/templates_tcpdf.php');
}
