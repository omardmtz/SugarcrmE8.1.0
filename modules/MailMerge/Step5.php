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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

if(!empty($_REQUEST['mtime']))
{
    $request = InputValidation::getService();
    $mTime = $request->getValidInputRequest('mtime', array('Assert\Type' => array('type' => 'numeric')));
	$file = $_SESSION['MAILMERGE_TEMP_FILE_'.$mTime];
	$rtfFile = 'sugartokendoc'.$mTime.'.doc';
	unlink($file);
	if(file_exists($rtfFile)){
		unlink($rtfFile);
	}
}

header("Location: index.php?module=MailMerge");
