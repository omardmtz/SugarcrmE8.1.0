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
 * Class for parsing title from RSS feed, and keep default encoding (UTF-8)
 * Created: Sep 12, 2011
 */
class DashletRssFeedTitle {
	public $defaultEncoding = "UTF-8";
	public $readBytes = 8192;
	public $url;
	public $cut = 70;
	public $contents = "";
	public $title = "";
	public $endWith = "...";
	public $xmlEncoding = false;
	public $fileOpen = false;

	public function __construct($url) {
		$this->url = $url;
	}

	public function generateTitle() {
		if ($this->readFeed()) {
			$this->getTitle();
			if (!empty($this->title)) {
				$this->convertEncoding();
				$this->cutLength();
			}
		}
		return $this->title;
	}

	/**
	 * @todo use curl with waiting timeout instead of fopen
	 */
	public function readFeed() {
		if ($this->url) {
		    if (!in_array(strtolower(parse_url($this->url, PHP_URL_SCHEME)), array("http", "https"), true)) {
		        return false;
		    }
			$fileOpen = @fopen($this->url, 'r');
			if ($fileOpen) {
				$this->fileOpen = true;
				$this->contents = fread($fileOpen, $this->readBytes);
				fclose($fileOpen);
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 */
	public function getTitle() {
		$matches = array ();
		preg_match("/<title>.*?<\/title>/i", $this->contents, $matches);
		if (isset($matches[0])) {
			$this->title = str_replace(array('<![CDATA[', '<title>', '</title>', ']]>'), '', $matches[0]);
		}
	}

	public function cutLength() {
		if (mb_strlen(trim($this->title), $this->defaultEncoding) > $this->cut) {
			$this->title = mb_substr($this->title, 0, $this->cut, $this->defaultEncoding) . $this->endWith;
		}
	}

	private function _identifyXmlEncoding() {
		$matches = array ();
		preg_match('/encoding\=*\".*?\"/', $this->contents, $matches);
		if (isset($matches[0])) {
			$this->xmlEncoding = trim(str_replace('encoding="', '"', $matches[0]), '"');
		}
	}

	public function convertEncoding() {
		$this->_identifyXmlEncoding();
		if ($this->xmlEncoding && $this->xmlEncoding != $this->defaultEncoding) {
			$this->title = iconv($this->xmlEncoding, $this->defaultEncoding, $this->title);
		}
	}
}