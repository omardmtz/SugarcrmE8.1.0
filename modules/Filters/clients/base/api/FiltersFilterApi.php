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

class FiltersFilterApi extends FilterApi {


    //Override the parent definition to allow responses to be cached for a short period client side
    public function registerApiRest()
    {
        $parentDef = parent::registerApiRest();
        if (!empty($parentDef['filterModuleAll'])) {
            $def = array_merge($parentDef['filterModuleAll'], array(
                'path' => array('Filters'),
                //Should be the only change from the parent method
                'cacheEtag' => true,
            ));

            return array('retrieve' => $def);
        }

        return array();
    }

}
