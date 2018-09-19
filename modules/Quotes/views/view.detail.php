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


class QuotesViewDetail extends ViewDetail
{
    /**
     * @see SugarView::display()
     */
    public function display()
    {
        global $beanFiles;
        require_once($beanFiles['Quote']);
        require_once($beanFiles['TaxRate']);
        require_once($beanFiles['Shipper']);

        if ($this->bean->fetched_row['date_quote_expected_closed'] == '1970-01-01' ||
            $this->bean->fetched_row['date_quote_expected_closed'] == '0001-01-01') {
            $this->bean->date_quote_expected_closed = '';
        }

        $this->bean->load_relationship('product_bundles');
        $product_bundle_list = $this->bean->product_bundles->getBeans();
        if (is_array($product_bundle_list)) {
            usort($product_bundle_list, array('ProductBundle', 'compareProductBundlesByIndex'));
        }

        $this->ss->assign('ordered_bundle_list', $product_bundle_list);
        $currency = BeanFactory::getBean('Currencies', $this->bean->currency_id);
        $this->ss->assign('CURRENCY_SYMBOL', $currency->symbol);
        $this->ss->assign('CURRENCY', $currency->iso4217);
        $this->ss->assign('CURRENCY_ID', $currency->id);

        if (!(strpos($_SERVER['HTTP_USER_AGENT'], 'Mozilla/5') === false)) {
            $this->ss->assign('PDFMETHOD', 'POST');
        } else {
            $this->ss->assign('PDFMETHOD', 'GET');
        }

        // if there is an opportunity on this quote, then disable the convert to Opportunity
        $disable_convert = '';
        if (!empty($this->bean->opportunity_id)) {
            $disable_convert = 'disabled="disabled"';
        }
        $this->ss->assign('DISABLE_CONVERT', $disable_convert);

        global $app_list_strings, $current_user;
        $this->ss->assign('APP_LIST_STRINGS', $app_list_strings);
        $this->ss->assign('gridline', $current_user->getPreference('gridline') == 'on' ? '1' : '0');

        parent::display();
    }
}
