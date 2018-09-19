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

r48064 - 2009-06-04 11:20:27 -0700 (Thu, 04 Jun 2009) - rob - Bug 30503: Moved out the list code for the TeamSets into the sugarfield, added a special smarty function to call it, changed the SugarFeed dashlet to call that function
*/

function smarty_function_sugar_teamset_list($params, &$smarty)
{
    if (!isset($params['row']) && !isset($params['col'])) {
		$smarty->trigger_error("sugar_phone: missing parameters, cannot continue");
		return '';
    }

    $sfh = new SugarFieldHandler();

    return($sfh->displaySmarty($params['row'], $params['vardef'], 'ListView', array('col'=>$params['col'])));
}
