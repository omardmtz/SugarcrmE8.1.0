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
class SugarUpgradeResaveQuote extends UpgradeScript
{
    public $order = 9000;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.9.0.0', '<')) {
            $this->resaveQuotes();
        }
    }

    /**
     * Resave all quotes and product bundles in the system to have sugarlogic update new fields for 7.9
     * @throws SugarQueryException
     */
    public function resaveQuotes()
    {
        //load all product bundles and resave for sugarlogic to do magic.
        $sq = $this->getSugarQuery();
        $sq->select(array('id'));
        $sq->from($this->getBean('ProductBundles'));
        $pbRows = $sq->execute();
        foreach ($pbRows as $pbRow) {
            $bundle = $this->getBean('ProductBundles', $pbRow['id']);
            $bundle->save();
        }

        // iterate over all the quotes, add taxrate_value, and resave for sugarlogic magic
        $sq = $this->getSugarQuery();
        $sq->select(array('id'));
        $sq->from($this->getBean('Quotes'));
        $rows = $sq->execute();
        foreach ($rows as $row) {
            //set tax rate and save quote.
            $bean = $this->getBean('Quotes', $row['id']);
            if (!empty($bean->taxrate_id)) {
                $taxrate = $this->getBean('TaxRates', $bean->taxrate_id);
                $bean->taxrate_value = $taxrate->value;
            }

            $bean->save();
        }
    }

    /**
     * helper function for test mocks
     * @return SugarQuery
     */
    public function getSugarQuery()
    {
        return new SugarQuery();
    }

    /**
     * helper function for test mocks
     * @param $name
     * @param $id
     * @return null|SugarBean
     */
    public function getBean($name, $id = null)
    {
        return BeanFactory::getBean($name, $id);
    }
}
