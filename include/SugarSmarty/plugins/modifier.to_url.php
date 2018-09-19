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

r38999 - 2008-08-19 19:58:56 -0700 (Tue, 19 Aug 2008) - Ajay Gupta - added missing header.

r36643 - 2008-06-11 14:28:43 -0700 (Wed, 11 Jun 2008) - dwheeler - bug 20270: Added a new smarty modifyer, to_url, which adds http:// to the begining of a string if it is unable to find another protocol. The protocol found is not checked (unknown protocols can be used, such as svn+ssh:// or ftp://)
*/
/**
 * Smarty to_url modifier plugin
 *
 * Type:     modifier<br>
 * Name:     to_url<br>
 * Purpose:  adds http:// to the begining of a string if it contains no protocol
 * @author   SugarCRM
 * @param string The link to modify
 * @return string The converted link
 */
function smarty_modifier_to_url($string)
{
    if (preg_match('/^[^:\/]*:\/\/.*/', $string)) {
    	return $string;
    } else {
    	return 'http://' . $string;
    }
}

?>
