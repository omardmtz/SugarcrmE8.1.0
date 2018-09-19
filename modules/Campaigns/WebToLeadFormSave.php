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
require_once('include/formbase.php');

global $mod_strings;
global $app_strings;


//-----------begin replacing text input tags that have been marked with text area tags
//get array of text areas strings to process
$bodyHTML = html_entity_decode($_REQUEST['body_html'],ENT_QUOTES);
//Bug53791
$bodyHTML = str_replace(chr(160), " ", $bodyHTML);

while (strpos($bodyHTML, "ta_replace") !== false){

	//define the marker edges of the sub string to process (opening and closing tag brackets)
	$marker = strpos($bodyHTML, "ta_replace");
	$start_border = strpos($bodyHTML, "input", $marker) - 1;// to account for opening '<' char;
	$end_border = strpos($bodyHTML, '>', $start_border); //get the closing tag after marker ">";

	//extract the input tag string
	$working_str = substr($bodyHTML, $marker-3, $end_border-($marker-3) );

	//replace input markup with text areas markups
	$new_str = str_replace('input','textarea',$working_str);
	$new_str = str_replace("type='text'", ' ', $new_str);
	$new_str = $new_str . '> </textarea';

	//replace the marker with generic term
	$new_str = str_replace('ta_replace', 'sugarslot', $new_str);

	//merge the processed string back into bodyhtml string
	$bodyHTML = str_replace($working_str , $new_str, $bodyHTML);
}
//<<<----------end replacing marked text inputs with text area tags

$guid = create_guid();
$form_file = "upload://$guid";

$SugarTiny =  new SugarTinyMCE();
$html = $SugarTiny->cleanEncodedMCEHtml($bodyHTML);

//Check to ensure we have <html> tags in the form. Without them, IE8 will attempt to display the page as XML.
if (stripos($html, "<html") === false) {
    $langHeader = get_language_header();
    $html = "<html {$langHeader}><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"></head><body>" . $html . "</body></html>";
}
file_put_contents($form_file, $html);

$xtpl=new XTemplate ('modules/Campaigns/WebToLeadDownloadForm.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$webformlink = "<b>$mod_strings[LBL_DOWNLOAD_TEXT_WEB_TO_LEAD_FORM]</b><br/>";
$webformlink .= "<a href=\"index.php?entryPoint=download&id={$guid}&isTempFile=1&tempName=WebToLeadForm.html&type=temp\">$mod_strings[LBL_DOWNLOAD_WEB_TO_LEAD_FORM]</a>";
$xtpl->assign("LINK_TO_WEB_FORM",$webformlink);
$xtpl->assign("RAW_SOURCE", htmlspecialchars($html)); 
$xtpl->parse("main.copy_source");
$xtpl->parse("main");
$xtpl->out("main");
