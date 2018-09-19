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
 * Fixes files reported by healthcheck as `removedSidecarAPI_app.date` and `removedSidecarAPI_Bean_fixable`
 */
class SugarUpgradeFixSidecarDeprecatedAPI extends UpgradeScript
{
    public $order = 7100;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * HealthCheck reports we are going to fix
     * @var array
     */
    private $HCReports = array(
        'removedSidecarAPI_app_date',
        'removedSidecarAPI_Bean_fixable',
    );

    /**
     * Replace patterns for deprecated API
     * @var array
     */
    private $patterns = array(
        '/\.(s|g)etDefaultAttribute(\W)/',
        '/([Aa])pp\.date\.compareDates\s*\(/',
        '/([Aa])pp\.date\.isDate(After|Before|Between)\s*\((.+?),(.+?)\)/',
        '/([Aa])pp\.date\.isDateOn\s*\((.+?),(.+?)\)/',
    );

    /**
     * Corresponding replacements for deprecated API
     * @var array
     */
    private $replacements = array(
        '.$1etDefault$2',
        '$1pp.date.compare(',
        '$1pp.date($3).is$2($4)',
        '$1pp.date($2).isSame($3)',
    );

    public function run()
    {
        if (empty($this->state['healthcheck'])) {
            return;
        }

        foreach ($this->state['healthcheck'] as $meta) {
            if (empty($meta['report']) || !in_array($meta['report'], $this->HCReports)) {
                continue;
            }

            foreach ($meta['params'][0] as $file) {
                $this->fixDeprecatedAPI($file);
            }
        }
    }

    /**
     * @param string $file
     */
    private function fixDeprecatedAPI($file)
    {
        $fileContents = sugar_file_get_contents($file);
        $fileContentsReplaced = preg_replace($this->patterns, $this->replacements, $fileContents);
        if ($fileContentsReplaced && ($fileContentsReplaced != $fileContents)) {
            if (!file_exists("$file.bak")) {
                copy($file, "$file.bak");
            }
            sugar_file_put_contents_atomic($file, $fileContentsReplaced);
            $this->log(
                sprintf(
                    'Deprecated code categorized as %s has been replaced in file %s. Original contents of the file has'
                    . ' been backed up in %s.',
                    implode(', ', $this->HCReports),
                    $file,
                    $file . '.bak'
                )
            );
        }
    }
}
