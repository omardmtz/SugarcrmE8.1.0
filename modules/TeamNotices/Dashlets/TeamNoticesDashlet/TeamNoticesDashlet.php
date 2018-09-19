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


class TeamNoticesDashlet extends Dashlet
{
    public $isRefreshable = true;
    public $hasScript     = true;

    public function __construct($id)
    {
        parent::__construct($id);
        $this->title = translate('LBL_MODULE_NAME', 'TeamNotices');
    }

    public function displayScript()
    {
    }

    public function display($text = '')
    {
        $data = array();


        $ss = new Sugar_Smarty();


        $focus = BeanFactory::newBean('TeamNotices');

        $today = db_convert("'".TimeDate::getInstance()->nowDbDate()."'", 'date');
        $query = $focus->create_new_list_query("date_start",$focus->table_name.".date_start <= $today and ".$focus->table_name.".date_end >= $today and ".$focus->table_name.'.status=\'Visible\'');

        if ( $result = $focus->db->query($query) )
            while ( $row = $focus->db->fetchByAssoc($result) )
                $data[] = $row;

        $ss->assign("data", $data);

        return parent::display($text)
            . $ss->fetch('modules/TeamNotices/Dashlets/TeamNoticesDashlet/TeamNoticesDashlet.tpl');
    }
}
