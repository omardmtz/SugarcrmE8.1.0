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

/*

Modification information for LGPL compliance

r56990 - 2010-06-16 13:05:36 -0700 (Wed, 16 Jun 2010) - kjing - snapshot "Mango" svn branch to a new one for GitHub sync

r56989 - 2010-06-16 13:01:33 -0700 (Wed, 16 Jun 2010) - kjing - defunt "Mango" svn dev branch before github cutover

r55980 - 2010-04-19 13:31:28 -0700 (Mon, 19 Apr 2010) - kjing - create Mango (6.1) based on windex

r51719 - 2009-10-22 10:18:00 -0700 (Thu, 22 Oct 2009) - mitani - Converted to Build 3  tags and updated the build system 

r51634 - 2009-10-19 13:32:22 -0700 (Mon, 19 Oct 2009) - mitani - Windex is the branch for Sugar Sales 1.0 development

r50375 - 2009-08-24 18:07:43 -0700 (Mon, 24 Aug 2009) - dwong - branch kobe2 from tokyo r50372

r42807 - 2008-12-29 11:16:59 -0800 (Mon, 29 Dec 2008) - dwong - Branch from trunk/sugarcrm r42806 to branches/tokyo/sugarcrm

r34090 - 2008-04-14 11:15:00 -0700 (Mon, 14 Apr 2008) - dwheeler - 20936: Link field now uses the vardef's default value when creating generated links instead of the database value.

r34040 - 2008-04-11 11:48:13 -0700 (Fri, 11 Apr 2008) - dwheeler - Fixed Typo that prevented enum fields from being replaced properly.

r33968 - 2008-04-10 11:30:04 -0700 (Thu, 10 Apr 2008) - dwheeler - bug 20936: Added smarty function to find and replace dynamic strings for custom dynamic fields, now handles translating strings for enum variables. 


*/



/**
 * This function will replace fields taken from the fields variable
 * and insert them into the passed string replacing [variableName] 
 * tokens where found.
 *
 * @param unknown_type $params
 * @param unknown_type $smarty
 * @return unknown
 */
function smarty_function_sugar_replace_vars($params, &$smarty)
{
	if(empty($params['subject']))  {
	    $smarty->trigger_error("sugarvar: missing 'subject' parameter");
	    return;
	} 
	$fields = empty($params['fields']) ? $smarty->get_template_vars('fields') : $params['fields'];
    $subject = replace_sugar_vars($params['subject'], $fields, !empty($params['use_curly']));
	if (!empty($params['assign']))
	{
		$smarty->assign($params['assign'], $subject);
		return '';
	}
	
	return $subject;
}