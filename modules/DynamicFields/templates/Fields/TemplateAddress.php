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

class TemplateAddress extends TemplateField
{
    function save ($df)
    {
        // Bug 58560 - Set the group name since addresses are part of a group
        $this->group = $df->getDBName($this->name);

        $parser = new ParserLabel ( $df->getModuleName() , $df->getPackageName() ) ;

        // Clean up the labels so they more accurately reflect the actual field
        if (!empty($this->label_value)) {
            $labelValue = $this->label_value;
        } else {
            $labelValue = empty($_REQUEST['labelValue']) ? '' : $_REQUEST['labelValue'];
        }

        // Create the main label to be used as the group label
        $this->group_label = 'LBL_' . strtoupper($this->name);

        // If there is a label to use, space it here for use below
        if (!empty($labelValue)) {
            $labelValue .= ' ';
        }
        // To prevent mutilple calls to the metadata api cache rebuilder, queue
        MetaDataManager::enableCacheRefreshQueue();

        // Save the main group label to the language extension now
        $parser->handleSave(array('label_' . $this->group_label => $this->group_label), $GLOBALS['current_language']);

        // To keep consistency with OOTB address groups, add Street to the fields
        foreach ( array ( 'Street', 'City' , 'State' , 'PostalCode' , 'Country' ) as $addressFieldName )
        {
            $systemLabel = $this->group_label . '_' . strtoupper($addressFieldName);
            // Use the entered label value as a prefix instead of the field name
            $parser->handleSave ( array( "label_" . $systemLabel => $labelValue . $addressFieldName ) , $GLOBALS [ 'current_language' ] ) ;

            if ($addressFieldName === 'Street') {
                $addressField = new TemplateAddressStreet();
                $addressField->len = 150;
            } else {
                $addressField = new TemplateField();
                $addressField->len = ($addressFieldName === 'PostalCode') ? 20 : 100;
            }
            $addressField->name = $this->name . '_' . strtolower ( $addressFieldName ) ;
            $addressField->label = $addressField->vname = $systemLabel ;
            // Bug 58560 - Add the group to this field so it gets written to the custom vardefs
            $addressField->group = $this->group;
            $addressField->group_label = $this->group_label;

            // Maintain unified search setting for 'Street'
            $addressField->supports_unified_search = $addressField == 'Street';

            // audit and pii should be in sync whith this address
            $addressField->audited = $this->audited;
            $addressField->pii = $this->pii;

            $addressField->save ( $df ) ;
        }

        // Handle the metadata api update now
        MetaDataManager::runCacheRefreshQueue();
    }
}

?>
