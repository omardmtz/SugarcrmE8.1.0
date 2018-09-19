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


class CurrentUserMobileApi extends CurrentUserApi {

    /**
     * Get the user hash
     *
     * @param User $user
     *
     * @return string
     */
    protected function getUserHash(User $user)
    {
        $hash = parent::getUserHash($user);
        //Mix in the mobile tabs as User::getUserMDHash only takes the base tabs into account
        $tabs = MetaDataManager::getManager('mobile')->getTabList();

        return md5($hash . serialize($tabs));
    }
}
