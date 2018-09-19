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
/**
 * Remove "go pro" dashlet for CE->PRO
 */
class SugarUpgradeRemoveGopro extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if(!($this->from_flavor == 'ce' && $this->toFlavor('pro'))) return;

		$result = $this->db->query("SELECT id, contents, assigned_user_id FROM user_preferences WHERE deleted = 0 AND category = 'Home'");
		if(empty($result)) {
		    return $this->fail("Unable to upgrade dashlets");
		}
		while ($row = $this->db->fetchByAssoc($result)) {
			$content = unserialize(base64_decode($row['contents']));
			$assigned_user_id = $row['assigned_user_id'];
			$record_id = $row['id'];

			$current_user = BeanFactory::getBean('Users', $row['assigned_user_id']);

			if(!empty($content['dashlets']) && !empty($content['pages'])){
				$originalDashlets = $content['dashlets'];
				foreach($originalDashlets as $key => $ds){
				    if(!empty($ds['options']['url']) && stristr($ds['options']['url'],'http://www.sugarcrm.com/crm/product/gopro')){
						unset($originalDashlets[$key]);
					}
				}
				$current_user->setPreference('dashlets', $originalDashlets, 0, 'Home');
			}
		}
    }
}
