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
 * Before 7.1.5, SidecarThemes were defined in a variables.less file.
 * By 7.1.5, SidecarThemes are defined in a variables.php file
 */
class SugarUpgradeConvertPortalTheme extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_CUSTOM;
    public $version = "7.1.5";

    public function run()
    {
        foreach(glob('custom/themes/clients/*/*/variables.less') as $customTheme) {
            $path = pathinfo($customTheme, PATHINFO_DIRNAME);
            $variables = $this->parseFile($path . '/variables.less');

            // Convert to new defs
            $lessdefs = array(
                'colors' => $variables['hex']
            );

            // Write new defs
            $write = "<?php\n" .
                '// created: ' . date('Y-m-d H:i:s') . "\n" .
                '$lessdefs = ' .
                var_export_helper($lessdefs) . ';';
            sugar_file_put_contents($path . '/variables.php', $write);

            // Delete old defs
            $this->upgrader->fileToDelete($path . '/variables.less', $this);
        }
    }
    
    /**
     * Does a preg_match_all on a less file to retrieve a type of less variables
     *
     * @param string $pattern Pattern to search
     * @param string $input Input string
     *
     * @return array Variables found
     */
    private function parseLessVars($pattern, $input)
    {
        $output = array();
        preg_match_all($pattern, $input, $match, PREG_PATTERN_ORDER);
        foreach ($match[1] as $key => $lessVar) {
            $output[$lessVar] = $match[3][$key];
        }
        return $output;
    }

    /**
     * Parse a less file to retrieve all types of less variables
     * - 'mixins' defs         @varName:      mixinName;
     * - 'hex' colors          @varName:      #aaaaaa;
     * - 'rgba' colors         @varName:      rgba(0,0,0,0);
     * - 'rel' related colors  @varName:      @relatedVar;
     * - 'bg'  backgrounds     @varNamePath:  "./path/to/img.jpg";
     *
     * @param string $file The file to parse
     *
     * @return array Variables found by type
     */
    private function parseFile($file)
    {
        $contents = file_get_contents($file);
        $output = array();
        $output['mixins'] = $this->parseLessVars("/@([^:|@]+):(\s+)([^\#|@|\(|\"]*?);/", $contents);
        $output['hex'] = $this->parseLessVars("/@([^:|@]+):(\s+)(\#.*?);/", $contents);
        $output['rgba'] = $this->parseLessVars("/@([^:|@]+):(\s+)(rgba\(.*?\));/", $contents);
        $output['rel'] = $this->parseLessVars("/@([^:|@]+):(\s+)(@.*?);/", $contents);
        $output['bg'] = $this->parseLessVars("/@([^:|@]+Path):(\s+)\"(.*?)\";/", $contents);
        return $output;
    }
}
