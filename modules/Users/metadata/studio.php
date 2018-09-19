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

 // $Id: studio.php 16622 2006-09-05 23:03:50Z awu $

$GLOBALS['studioDefs']['Users'] = array(
	'LBL_DETAILVIEW'=>array(
        'template'=>'DetailView',
        'meta_file'=>'modules/Users/detailviewdefs.php',
        'type'=>'Detailview',
    ),
	'LBL_EDITVIEW'=>array(
        'template'=>'EditView',
        'meta_file'=>'modules/Users/editviewdefs.php',
        'type'=>'EditView',
    ),
	'LBL_LISTVIEW'=>array(
        'template'=>'listview',
        'meta_file'=>'modules/Users/listviewdefs.php',
        'type'=>'ListView',
    ),
	'LBL_SEARCHFORM'=>array(
        'template'=>'xtpl',
        'template_file'=>'modules/Users/SearchForm.html',
        'php_file'=>'modules/Users/ListView.php',
        'type'=>'SearchForm',
    ),
);
