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

function get_body (&$ss , $vardef)
{

    $modules = array ( ) ;

    $relatableModules = array_keys ( DeployedRelationships::findRelatableModules () ) ;

    foreach ( $relatableModules as $module )
    {
        $modules [ $module ] = translate ( 'LBL_MODULE_NAME', $module ) ;
    }

    $modules = SugarACL::filterModuleList($modules);
    unset ( $modules [ "" ] ) ;
    unset ( $modules [ 'Activities' ] ) ; // cannot relate to Activities as only Activities' submodules have records; use a Flex Relate instead!

    // tyoung bug 18631 - reduce potential confusion when creating a relate custom field for Products - actually points to the Product Catalog, so label it that way in the drop down list
    if (isset ( $modules [ 'ProductTemplates' ] ) && $modules [ 'ProductTemplates' ] == 'Product')
    {
        $modules [ 'ProductTemplates' ] = translate ( 'LBL_MODULE_NAME', 'ProductTemplates' ) ;
    }

    // C.L. - Merge from studio_rel_user branch
    $modules['Users'] = translate('LBL_MODULE_NAME', 'Users');
    asort($modules);

    $ss->assign ( 'modules', $modules ) ;

    return $ss->fetch ( 'modules/DynamicFields/templates/Fields/Forms/relate.tpl' ) ;
}
?>
