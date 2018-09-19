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
 * Removes Forecast field from the Opportunity Record and List Views
 */
class SugarUpgradeOpportunityRemoveForecastField extends UpgradeScript
{
    /**
     * When to run the upgrade task
     *
     * @var int
     */
    public $order = 7010;

    /**
     * Type of Upgrade Task
     *
     * @var int
     */
    public $type = self::UPGRADE_CUSTOM;

    /**
     * Upgrade Task to Run
     */
    public function run()
    {
        if ($this->toFlavor('ent')) {
            $settings = Opportunity::getSettings();
            if ($settings['opps_view_by'] !== 'RevenueLineItems') {
                $this->log('Not using Revenue Line Items; Skipping Upgrade Script');
                return;
            }

            $fields = array(
                'commit_stage'
            );

            $this->log('Processing Opportunity RecordView');
            $recordViewDefsParser = ParserFactory::getParser(MB_RECORDVIEW, 'Opportunities', null, null, 'base');
            if ($this->removeFields($recordViewDefsParser, $fields)) {
                $recordViewDefsParser->handleSave(false);
            }

            $this->log('Processing Opportunity ListView');
            $recordViewDefsParser = ParserFactory::getParser(MB_LISTVIEW, 'Opportunities', null, null, 'base');
            if ($this->removeFields($recordViewDefsParser, $fields)) {
                $recordViewDefsParser->handleSave(false);
            }
        }
    }

    /**
     * Utility method to to make it easier to determine if we should save or not.
     *
     * @param AbstractMetaDataParser $parser
     * @param array $fields
     * @return bool
     */
    protected function removeFields($parser, $fields)
    {
        $shouldSave = false;
        foreach ($fields as $field) {
            if ($parser->removeField($field)) {
                $shouldSave = true;
            }
        }

        return $shouldSave;
    }
}
