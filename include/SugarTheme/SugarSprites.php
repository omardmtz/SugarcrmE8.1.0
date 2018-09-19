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

// Singleton to load sprites metadata from SugarTheme

class SugarSprites {

	private static $instance;
	public $sprites = array();
	public $dirs = array();

	private function __construct() {
		// load default sprites
		$this->dirs['default'] = true;
		$this->loadMetaHelper('default','sprites');
		// load repeatable sprites
		//$this->dirs['Repeatable'] = true;
		//$this->loadMetaHelper('Repeatable','sprites');
	}

	public static function getInstance() {
		if(!self::$instance)
			self::$instance = new self();
		return self::$instance;
    }

	public function loadSpriteMeta($dir) {
		if(! isset($this->dirs[$dir])) {
			$this->loadMetaHelper($dir, 'sprites');
			$this->dirs[$dir] = true;
		}
	}

	private function loadMetaHelper($dir, $file) {
		if(file_exists("cache/sprites/{$dir}/{$file}.meta.php")) {
			$sprites = array();
			$GLOBALS['log']->debug("Sprites: Loading sprites metadata for $dir");
			include("cache/sprites/{$dir}/{$file}.meta.php");
			foreach($sprites as $id => $meta) {
				$this->sprites[$id] = $meta;
			}
		}
	}
}

?>
