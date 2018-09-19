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
 * Removes fields from the Opportunity Record View and SubPanel that are hidden in Enterprise
 */
class SugarUpgradeOpportunityRemoveHiddenEntFields extends UpgradeScript
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
        if (version_compare($this->from_version, '7.0.0', '<') && $this->toFlavor('ent')) {

            $settings = Opportunity::getSettings();
            if ($settings['opps_view_by'] !== 'RevenueLineItems') {
                $this->log('Not using Revenue Line Items; Skipping Upgrade Script');
                return;
            }

            $fields = array(
                'sales_stage',
                'probability',
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

            $modules =  array(
                'Accounts',
                'Contacts',
                'Campaigns',
                'Documents',
            );

            global $modInvisList;
            if (array_search('Project', $modInvisList)) {
                $modules[] = 'Project';
            }

            foreach ($modules as $module) {
                $this->log('Processing Opportunity SubPanel for ' . $module . ' module');
                if (isModuleBWC($module)) {
                    $pf = new SubpanelMetaDataParser('opportunities', $module);
                } else {
                    $pf = ParserFactory::getParser(MB_LISTVIEW, $module, null, 'opportunities');
                }
                if ($this->removeFields($pf, $fields)) {
                    $pf->handleSave(false);
                }
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
