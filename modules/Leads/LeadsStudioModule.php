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
/*********************************************************************************
 * $Id: view.sugarpdf.php 
 * Description: This file is used to override the default Meta-data EditView behavior
 * to provide customization specific to the Quotes module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class LeadsStudioModule extends StudioModule
{
     function __construct ($module)
    {
         parent::__construct ($module);
    }

    function getLayouts()
    {
        $layouts = parent::getLayouts();

        $layouts = array_merge(array( translate("LBL_CONVERTLEAD", "Leads") => array (
                'name' => translate("LBL_CONVERTLEAD", "Leads") ,
                'action' => "module=Leads&action=Editconvert&to_pdf=1" ,
                'imageTitle' => 'icon_ConvertLead' ,
                'help' => 'layoutsBtn' ,
                'size' => '48')), $layouts);

        return $layouts ;
    }
}
