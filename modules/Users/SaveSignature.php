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
global $current_user;

$us = new UserSignature();
if(isset($_REQUEST['record']) && !empty($_REQUEST['record'])) {
	$us->retrieve($_REQUEST['record']);
} else {
	$us->id = create_guid();
	$us->new_with_id = true;
}

$name = htmlspecialchars_decode($_REQUEST['name'], ENT_QUOTES);
$us->name = SugarCleaner::cleanHtml($name, false);
$us->signature = strip_tags(br2nl(from_html($_REQUEST['description'])));
$us->signature_html = $_REQUEST['description'];
if(empty($us->user_id) && isset($_REQUEST['the_user_id'])){
	$us->user_id = $_REQUEST['the_user_id'];
}
else{
	$us->user_id = $current_user->id;
}
$us->save();

$js = '
<script type="text/javascript">
function refreshTemplates() {
	window.opener.refresh_signature_list("'.$us->id.'","'.$us->name.'");
	window.close();
}

refreshTemplates();
window.close();
</script>';

echo $js;
