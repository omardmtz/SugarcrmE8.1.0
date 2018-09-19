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

define('MB_BASEMETADATALOCATION', 'base');
define('MB_CUSTOMMETADATALOCATION', 'custom');
define('MB_WORKINGMETADATALOCATION', 'working');
define('MB_HISTORYMETADATALOCATION', 'history');
define('MB_GRIDLAYOUTMETADATA', 'gridLayoutMetaData');
define('MB_LISTLAYOUTMETADATA', 'listLayoutMetaData');
define('MB_LISTVIEW', 'listview');
define('MB_SIDECARLISTVIEW', 'list');
define('MB_SIDECARQUOTEDATAGROUPLIST', 'quote-data-group-list');
define('MB_SIDECARPOPUPVIEW', 'selection-list');
define('MB_SIDECARDUPECHECKVIEW', 'dupecheck-list');
define('MB_SEARCHVIEW', 'searchview');
define('MB_BASICSEARCH', 'basic_search' );
define('MB_ADVANCEDSEARCH', 'advanced_search' );
define('MB_DASHLET', 'dashlet');
define('MB_DASHLETSEARCH', 'dashletsearch');
define('MB_EDITVIEW', 'editview');
define('MB_DETAILVIEW', 'detailview');
define('MB_QUICKCREATE', 'quickcreate');
define('MB_CONVERT', 'convert');
define('MB_POPUPLIST', 'popuplist');
define('MB_POPUPSEARCH', 'popupsearch');
define('MB_LABEL', 'label');
define('MB_ONETOONE', 'one-to-one');
define('MB_ONETOMANY', 'one-to-many');
define('MB_MANYTOONE', 'many-to-one');
define('MB_MANYTOMANY', 'many-to-many');
define('MB_MAXDBIDENTIFIERLENGTH', 30); // maximum length of any identifier in our supported databases
define('MB_EXPORTPREPEND', 'project_');
define('MB_VISIBILITY', 'visibility');
define('MB_WIRELESSEDITVIEW', 'wirelesseditview');
define('MB_WIRELESSDETAILVIEW', 'wirelessdetailview');
define('MB_WIRELESSLISTVIEW', 'wirelesslistview');
define('MB_WIRELESSBASICSEARCH', 'wireless_basic_search' );
define('MB_WIRELESSADVANCEDSEARCH', 'wireless_advanced_search' );
define('MB_WIRELESS', 'mobile');
define('MB_PORTALEDITVIEW','portaleditview');
define('MB_PORTALDETAILVIEW','portaldetailview');
define('MB_PORTALRECORDVIEW', 'portalrecordview');
define('MB_PORTALLISTVIEW', 'portallistview');
define('MB_PORTALSEARCHVIEW', 'portalsearchview');
define('MB_PORTAL', 'portal');
define('MB_RECORDVIEW', 'recordview');
define('MB_FILTERVIEW', 'filter');
define('MB_BWCFILTERVIEW', 'searchfields');
define('MB_DROPDOWN', 'dropdown');
define('MB_PACKAGE_PATH', 'custom/modulebuilder/packages');
define('MB_PACKAGE_BUILD', 'custom/modulebuilder/builds');

class MBConstants
{
    public static $EMPTY = array ( 'name' => '(empty)' , 'label' => '(empty)' ) ;
    public static $FILLER = array ( 'name' => '(filler)' , 'label' => 'LBL_FILLER' ) ; // would prefer to have label => translate('LBL_FILLER') but can't be done in a static, and don't want to require instantiating a new object to get these constants
}
