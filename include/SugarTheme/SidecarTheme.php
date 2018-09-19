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

/*********************************************************************************
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/



/**
 * Class that provides tools for working with a theme.
 * @api
 */
class SidecarTheme
{
    /**
     * @var string Client Name
     */
    private $myClient;

    /**
     * @var string Theme Name
     */
    private $myTheme;

    /**
     * @var array A set of useful paths
     */
    private $paths;

    /**
     * @var array Less variables of the theme
     */
    private $variables = array();

    /**
     * @var array Less files to compile
     */
    public $lessFilesToCompile = array('sugar');


    /**
     * @var lessc dependency
     */
    protected $compiler;

    /**
     * Constructor
     *
     * @param string $client Client Name
     * @param string $themeName Theme Name
     */
    public function __construct($client = 'base', $themeName = '')
    {
        // Get user theme if the themeName isn't defined
        if (empty($themeName)) {
            $themeName = $this->getUserTheme();
        }

        $this->myClient = $client;
        $this->myTheme = $themeName;
        $this->paths = $this->makePaths($client, $themeName);
        $this->compiler = new lessc;

        // Check for a customer less file. If exists, it will be compiled like other files
        if (file_exists('custom/themes/custom.less')) {
            array_push($this->lessFilesToCompile, 'custom');
        }
    }

    /**
     * Retrieve CSS files from cache for this theme
     * Compile missing files if the theme has metadata definition
     * Compile default theme otherwise
     *
     * @return array Locations of CSS files for this theme
     */
    public function getCSSURL()
    {
        $filesInCache = $this->retrieveCssFilesInCache();

        // Remove the custom css file if the less file does not exist anymore
        if (isset($filesInCache['custom']) && !in_array('custom', $this->lessFilesToCompile)) {
            unlink($this->getCssFileLocation('custom', $filesInCache['custom']));
            unset($filesInCache['custom']);
        }

        //If we found css files in cache we can return css urls
        if (count($filesInCache) === count($this->lessFilesToCompile)) {
            return $this->returnFileLocations($filesInCache);
        }

        //Since we did not find css files in cache we have to compile the theme.
        //First check if the theme has metadata, otherwise compile the default theme instead
        if ($this->myTheme !== 'default' && !$this->isDefined()) {
            $clientDefaultTheme = new SidecarTheme($this->myClient, 'default');
            return $clientDefaultTheme->getCSSURL();
        }

        //Arrived here we are going to compile missing css files
        $missingFiles = array_diff($this->lessFilesToCompile, array_keys($filesInCache));
        foreach ($missingFiles as $lessFile) {
            $filesInCache[$lessFile] = $this->compileFile($lessFile);
        }

        return $this->returnFileLocations($filesInCache);
    }

    /**
     * Compile all the css files and save the file hashes
     *
     * @param bool $min True to minify the CSS
     *
     * @return array Generated file hashes
     */
    public function compileTheme($min = true)
    {
        $files = array();
        foreach ($this->lessFilesToCompile as $lessFile) {
            $files[$lessFile] = $this->compileFile($lessFile, $min);
        }

        //Cache the hash in sugar_cache so we don't have to hit the filesystem for etag comparisons
        sugar_cache_put($this->paths['hashKey'], $files);
        return $files;
    }

    /**
     * Compile all the css files but don't save
     *
     * @param bool $min True to minify the CSS, false otherwise
     *
     * @return string Plaintext CSS
     * @throws SugarApiExceptionError
     */
    public function previewCss($min = true)
    {
        try {
            $css = array();
            foreach ($this->lessFilesToCompile as $lessFile) {
                $css[$lessFile] = $this->compileFile($lessFile, $min, false);
            }
            return implode('', array_values($css));
        } catch (Exception $e) {
            throw new SugarApiExceptionError('lessc fatal error:<br />' . $e->getMessage());
        }
    }

    /**
     * Compile a less file and save the output css file
     *
     * @param string $lessFile Name of Less file to compile
     * @param bool $min True to minify the CSS
     * @param bool $writeFile True to write the file on the file system
     *
     * @return mixed Plaintext CSS if writeFile is false, a hash otherwise
     * @throws SugarApiExceptionError
     */
    public function compileFile($lessFile, $min = true, $writeFile = true)
    {
        try {
            //In case we are building customer css file we need to create a temporary compiler.less
            if ($lessFile === 'custom') {
                $compilerFile = $this->writeCustomCompiler($lessFile);
            } else {
                $compilerFile = $this->getLessFileLocation($lessFile);
            }

            //Load and set variables
            $this->loadVariables();
            if (!isset($this->variables['baseUrl'])) {
                //Relative path from /cache/themes/clients/PLATFORM/THEMENAME/FILE.css
                //              to   /styleguide/assets/
                $this->setVariable('baseUrl', '"../../../../styleguide/assets"');
            }
            $this->compiler->setVariables($this->loadVariables());

            if ($min) {
                $this->compiler->setFormatter('compressed');
            }
            $css = $this->compiler->compileFile($compilerFile);

            //Delete the temporary compiler.less file
            if ($lessFile === 'custom') {
                unlink($compilerFile);
            }

            //If preview return css;
            if (!$writeFile) {
                return $css;
            }
            //Otherwise write file and return hash
            $hash = md5($css);
            // Write CSS file on the file system
            sugar_mkdir($this->paths['cache'], null, true);
            sugar_file_put_contents($this->getCssFileLocation($lessFile, $hash), $css);
            return $hash;
        } catch (Exception $e) {
            throw new SugarApiExceptionError('lessc fatal error:<br />' . $e->getMessage());
        }
    }

    /**
     * Determines if the theme exists
     *
     * @return bool True if able to retrieve theme definition file in the file system.
     */
    public function isDefined()
    {
        // We compile expected theme by if we found variables.php in the file system (in /custom/themes or /themes)
        $customThemeVars = $this->paths['custom'] . 'variables.php';
        $baseThemeVars = $this->paths['base'] . 'variables.php';
        return file_exists($customThemeVars) || file_exists($baseThemeVars);
    }

    /**
     * Parse the variables.php metadata files of the theme
     *
     * @return string Array of categorized variables
     */
    public function getThemeVariables()
    {
        include 'styleguide/themes/clients/base/default/variables.php';
        $variables = $lessdefs;

        // Crazy override from :
        // - the base/default custom theme
        $variables = $this->loadLessDefs($variables, 'custom/themes/clients/base/default/variables.php');

        // - the client/default base theme
        // - the client/default custom theme
        if ($this->myClient !== 'base') {
            $variables = $this->loadLessDefs($variables, 'styleguide/themes/clients/' . $this->myClient . '/default/variables.php', false);
            $variables = $this->loadLessDefs($variables, 'custom/themes/clients/' . $this->myClient . '/default/variables.php');
        }

        // - the client/themeName base theme
        // - the client/themeName custom theme
        if ($this->myTheme !== 'default') {
            $variables = $this->loadLessDefs($variables, 'styleguide/themes/clients/' . $this->myClient . '/' . $this->myTheme . '/variables.php');
            $variables = $this->loadLessDefs($variables, 'custom/themes/clients/' . $this->myClient . '/' . $this->myTheme . '/variables.php');
        }
        return $variables;
    }

    /**
     * Write the theme metadata file. This method actually does:
     * - Read contents of base/default theme metadata file
     * - Override variables (if $resetBaseDefaultTheme is false)
     * - Save the file as the theme metadata file.
     *
     * @param bool $reset True if you want to reset the theme to the base default theme
     */
    public function saveThemeVariables($reset = false)
    {
        // take the contents from /themes/clients/base/default/variables.php
        $baseDefaultTheme = new SidecarTheme('base', 'default');
        $baseDefaultThemePaths = $baseDefaultTheme->getPaths();

        include $baseDefaultThemePaths['base'] . 'variables.php';

        if (is_dir($this->paths['cache'])) {
            rmdir_recursive($this->paths['cache']);
        }

        if ($reset) {
            //In case of reset we just need to delete the theme files.
            if (is_dir($this->paths['custom'])) {
                rmdir_recursive($this->paths['custom']);
            }
        } else {
            //override the base variables with variables passed in arguments
            foreach ($this->variables as $lessVar => $lessValue) {
                foreach($lessdefs as $type => $varset) {
                    if (isset($lessdefs[$type][$lessVar])) {
                        $lessdefs[$type][$lessVar] = $lessValue;
                    }
                }
            }
            // save the theme variables in /themes/clients/$client/$themeName/variables.php
            sugar_mkdir($this->paths['custom'], null, true);
            $write = "<?php\n" .
                '// created: ' . date('Y-m-d H:i:s') . "\n" .
                '$lessdefs = ' .
                var_export_helper($lessdefs) . ';';
            sugar_file_put_contents($this->paths['custom'] . 'variables.php', $write);
        }
    }

    /**
     * Load the less variables of the theme. By default it prevents from parsing the metadata files twice.
     *
     * @param bool $force True if you want to force reloading the theme variables
     *
     * @return array Variables
     */
    public function loadVariables($force = false)
    {
        if (!empty($this->variables) && !$force) {
            return $this->variables;
        }
        foreach ($this->getThemeVariables() as $variables) {
            foreach ($variables as $lessVar => $lessValue) {
                $this->setVariable($lessVar, $lessValue);
            }
        }
        return $this->variables;
    }

    /**
     * Writes a temporary less file sugar importing fixed variables and customer less file.
     * Like any other css files, custom less variables will be applied to the customer css file.
     *
     * @return string File location
     */
    public function writeCustomCompiler()
    {
        $compilerFile = 'tmp_less_custom_compiler.less';
        $fp = fopen($compilerFile, 'w');

        //Import fixed variables
        fwrite($fp, '@import "styleguide/less/fixed_variables.less";');
        fwrite($fp, "\n");

        //Import potential customer less file
        fwrite($fp, '@import "custom/themes/custom.less";');
        fwrite($fp, "\n");

        //Return the array of temporary less files
        return $compilerFile;
    }

    /**
     * Loads less variables from a file and override $variables
     *
     * @param array $variables Array that contains variables
     * @param string $file Extract variables from this file
     * @return array mixed Merged array
     */
    private function loadLessDefs($variables, $file, $intersect_merge = true) {
        if (file_exists($file)) {
            include $file;
            if ($intersect_merge) {
                foreach($lessdefs as $type => $varset) {
                    foreach($varset as $key => $value) {
                        if (isset($variables[$type][$key])) {
                            $variables[$type][$key] = $value;
                        }
                    }
                }
            } else {
                $variables = array_merge_recursive($variables, $lessdefs);
            }
        }
        return $variables;
    }

    /**
     * Setter for private variables
     *
     * @param string $variable Variable name
     * @param string $value Variable value
     *
     * @throws InvalidArgumentException
     */
    public function setVariable($variable, $value)
    {
        if (empty($value) || !is_string($value)) {
            throw new \InvalidArgumentException('Invalid Less variable: ' . $variable);
        }
        $this->variables[$variable] = $value;
    }

    /**
     * Setter for private variables
     *
     * @param array $variables Array of variables
     */
    public function setVariables(array $variables)
    {
        foreach ($variables as $var => $value) {
            $this->setVariable($var, $value);
        }
    }

    /**
     * Getter for paths
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Get the user preferred theme
     *
     * @return string themeName
     */
    private function getUserTheme()
    {
        // THIS IS A FIX FOR 7.1.5
        // We no longer have multiple themes support.

        // We removed the feature for the user to choose his preferred theme.
        // In the future, we'll add this ability back. Meanwhile, there will be
        // only one theme possible per platform, known as the `default` theme.

        return 'default';
    }

    /**
     * Builds the paths attribute
     * 'base' : the path of the base theme
     * 'custom' : the path of the customized theme
     * 'cache' : the path of the cached theme
     * 'clients' : the clients path of less files to compile
     * 'hashKey' the theme hash key
     *
     * @param string $client Client Name
     * @param string $themeName Theme Name
     *
     * @return array Paths related to this client and theme
     */
    private function makePaths($client, $themeName)
    {
        return array(
            'base' => 'styleguide/themes/clients/' . $client . '/' . $themeName . '/',
            'custom' => 'custom/themes/clients/' . $client . '/' . $themeName . '/',
            'cache' => sugar_cached('themes/clients/' . $client . '/' . $themeName . '/'),
            'clients' => 'styleguide/less/clients/',
            'hashKey' => 'theme:' . $client . ':' . $themeName,
        );
    }

    /**
     * Retrieve CSS files in cache. This method actually does:
     * - Get file hashes from the cache.
     * - If file hashes are found verify that the file exists.
     * - If file hashes are not found try to retrieve some css files from the file system
     *
     * @return array Css files found in cache
     */
    private function retrieveCssFilesInCache()
    {
        $filesInCache = array();

        //First check if the file hashes are cached so we don't have to load the metadata manually to calculate it
        $hashKey = $this->paths['hashKey'];
        $hashArray = sugar_cache_retrieve($hashKey);

        if (is_array($hashArray) && count($hashArray) === count($this->lessFilesToCompile)) {
            foreach ($hashArray as $lessFile => $hash) {
                $file = $this->getCssFileLocation($lessFile, $hash);
                if (file_exists($file)) {
                    $filesInCache[$lessFile] = $hash;
                }
            }
        } else {
            /**
             * Checks the filesystem for a generated css file
             * This is useful on systems without a php memory cache
             * or if the memory cache is filled
             */
            $files = glob($this->paths['cache'] . '*.css', GLOB_NOSORT);
            foreach ($files as $file) {
                $nameParts = explode('_', pathinfo($file, PATHINFO_FILENAME));
                $filesInCache[$nameParts[0]] = $nameParts[1];
            }
        }
        return $filesInCache;
    }

    /**
     * Get the location of a compiler less file
     *
     * @param string $lessFile
     *
     * @return string of less files to compile
     */
    private function getLessFileLocation($lessFile)
    {
        $file = $this->paths['clients'] . $this->myClient . '/' . $lessFile . '.less';
        $baseFile = $this->paths['clients'] . 'base' . '/' . $lessFile . '.less';
        return file_exists($file) ? $file : $baseFile;
    }

    /**
     * Get the location of a css file
     *
     * @param string $compilerName
     * @param string $hash
     *
     * @return string file location
     */
    private function getCssFileLocation($compilerName, $hash)
    {
        return $this->paths['cache'] . $compilerName . '_' . $hash . ".css";
    }

    /**
     * Save file hashes and format response
     *
     * @param array $filesArray File hashes
     *
     * @return array Array of css file locations
     */
    private function returnFileLocations(array $filesArray)
    {
        $urls = array();
        sugar_cache_put($this->paths['hashKey'], $filesArray);
        if (!empty($filesArray)) {
            foreach ($this->lessFilesToCompile as $lessFile) {
                $urls[$lessFile] = $this->getCssFileLocation($lessFile, $filesArray[$lessFile]);
            }
        }
        return $urls;
    }
}
