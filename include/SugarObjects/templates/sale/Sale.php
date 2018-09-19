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

class Sale extends Basic
{

    public function __construct()
    {
        parent::__construct();
        // initialize currency
        $currency = SugarCurrency::getBaseCurrency();
        $this->currency_id = $currency->id;
        $this->base_rate = $currency->conversion_rate;
    }

    public function create_new_list_query(
        $order_by,
        $where,
        $filter = array(),
        $params = array(),
        $show_deleted = 0,
        $join_type = '',
        $return_array = false,
        $parentbean = null,
        $singleSelect = false,
        $ifListForExport = false
    ) {
        //Ensure that amount is always on list view queries if amount_usdollar is as well.
        if (!empty($filter) && isset($filter['amount_usdollar']) && !isset($filter['amount'])) {
            $filter['amount'] = true;
        }
        return parent::create_new_list_query(
            $order_by,
            $where,
            $filter,
            $params,
            $show_deleted,
            $join_type,
            $return_array,
            $parentbean,
            $singleSelect,
            $ifListForExport
        );
    }

    public function fill_in_additional_list_fields()
    {
        parent::fill_in_additional_list_fields();
        if (empty($this->amount_usdollar) && !empty($this->amount)) {
            $this->amount_usdollar = SugarCurrency::convertWithRate($this->amount, $this->base_rate);
        }
    }

    public function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        if (empty($this->amount_usdollar) && !empty($this->amount)) {
            $this->amount_usdollar = SugarCurrency::convertWithRate($this->amount, $this->base_rate);
        }
    }
}
