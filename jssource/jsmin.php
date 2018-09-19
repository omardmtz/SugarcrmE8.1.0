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

class SugarMin {

    /**
     * jsParser will take javascript source code and minify it.
     *
     * Note: There is a lot of redundant code since both passes
     * operate similarly but with slight differences. It will probably
     * be a good idea to refactor the code at a later point when it is stable.
     *
     * JSParser will perform 3 passes on the code. Pass 1 takes care of single
     * line and mult-line comments. Pass 2 performs some sanitation on each of the lines
     * and pass 3 works on stripping out unnecessary spaces.
     *
     * @param string $js
     * @param string $currentOptions
     * @return void
     */
    private function __construct($text, $compression) {
        $this->text = trim($text)."\n";
        $this->compression = $compression;
    }

    /**
     * Entry point function to minify javascript.
     *
     * @param string $js Javascript source code as a string.
     * @param string $compression Compression option. {light, deep}.
     * @return string $output Output javascript code as a string.
     */
    static public function minify($js, $compression = 'light') {
        try {
            $me = new SugarMin($js, $compression);
            $output = $me->jsParser();

            return $output;
        } catch (Exception $e) {
            // Exception handling is left up to the implementer.
            throw $e;
        }
    }

    protected function jsParser() {
        if (!shouldResourcesBeMinified()) {
            return $this->text;
        }

        //If the JSMIn extension is loaded, use that as it can be as much as 1000x faster than JShrink
        if (self::isJSMinExtAvailable()) {
            return @jsmin($this->text);
        }

        if(!empty($GLOBALS['sugar_config']['uglify'])){
            $descriptorspec = array(
               0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
               1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
            );
            $process = proc_open($GLOBALS['sugar_config']['uglify'], $descriptorspec, $pipes);
            if (is_resource($process)) {
                fwrite($pipes[0], $this->text);
                fclose($pipes[0]);
                $out = stream_get_contents($pipes[1]);
                fclose($pipes[1]);
                proc_close($process);
                return $out;
             }
        }

        return JShrink\Minifier::minify($this->text);
	}

    /**
     * @return bool true if native jsmin extension that works exists on the system.
     */
    protected static function isJSMinExtAvailable()
    {
        // at present latest jsmin extension 2.0.0 and 2.0.1 are incompatible
        return extension_loaded('jsmin') && version_compare(phpversion('jsmin'), '2.0.0', '<');
    }

    /**
     * @return bool true if a more native js minifier exists on the system.
     */
    public static function isMinifyFast() {
        return self::isJSMinExtAvailable()  || !empty($GLOBALS['sugar_config']['uglify']);
    }
}
