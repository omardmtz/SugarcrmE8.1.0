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
require_once('soap/SoapHelperFunctions.php');

$module = $_POST['mailmerge_module'];
$document_id = $_POST['document_id'];
$selObjs = urldecode($_POST['selected_objects_def']);

$item_ids = array();
parse_str($selObjs,$item_ids);

$seed = BeanFactory::newBean($module);
$fields =  get_field_list($seed);

$document = BeanFactory::getBean('Documents', $document_id);

$items = array();
foreach($item_ids as $key=>$value)
{
	$seed->retrieve($key);
	$items[] = $seed;
}

if (ini_get('max_execution_time') > 0 && ini_get('max_execution_time') < 600) {
    ini_set('max_execution_time', 600);
}
ini_set('error_reporting', 'E_ALL');
$dataDir = create_cache_directory("MergedDocuments/");
$fileName = UploadFile::realpath("upload://$document->document_revision_id");
$outfile = pathinfo($document->filename, PATHINFO_FILENAME);

$mm = new MailMerge(null, null, $dataDir);
$mm->SetDataList($items);
$mm->SetFieldList($fields);
$mm->Template(array($fileName, $outfile));
$file = $mm->Execute();
$mm->CleanUp();

header("Location: index.php?module=MailMerge&action=Step4&file=".urlencode($file));
