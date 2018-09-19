<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
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

require_once('service/core/REST/SugarRest.php');

/**
 * This class is a serialize implementation of REST protocol
 * @api
 */
class SugarRestRSS extends SugarRest
{
	/**
	 * It will serialize the input object and echo's it
	 *
	 * @param array $input - assoc array of input values: key = param name, value = param type
	 * @return String - echos serialize string of $input
	 */
	public function generateResponse($input)
	{
		if(!isset($input['entry_list'])) {
		    $this->fault($app_strings['ERR_RSS_INVALID_RESPONSE']);
		}
		ob_clean();
		$this->generateResponseHeader(count($input['entry_list']));
		$this->generateItems($input);
		$this->generateResponseFooter();
	} // fn

	protected function generateResponseHeader($count)
	{
	    global $app_strings, $sugar_version, $sugar_flavor;

		$date = TimeDate::httpTime();

		echo <<<EORSS
<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
<channel>
    <title>{$app_strings['LBL_RSS_FEED']} &raquo; {$app_strings['LBL_BROWSER_TITLE']}</title>
    <link>{$GLOBALS['sugar_config']['site_url']}</link>
    <description>{$count} {$app_strings['LBL_RSS_RECORDS_FOUND']}</description>
    <pubDate>{$date}</pubDate>
    <generator>SugarCRM $sugar_version $sugar_flavor</generator>

EORSS;
	}

	protected function generateItems($input)
	{
        global $app_strings;

	    if(!empty($input['entry_list'])){
            foreach($input['entry_list'] as $item){
                $this->generateItem($item);
            }
        }
    }

    protected function generateItem($item)
    {
        $name = !empty($item['name_value_list']['name']['value'])?htmlentities( $item['name_value_list']['name']['value']): '';
        $url = $GLOBALS['sugar_config']['site_url']  . htmlentities('/index.php?module=' . $item['module_name']. '&action=DetailView&record=' . $item['id']);
        $date = TimeDate::httpTime(TimeDate::getInstance()->fromDb($item['name_value_list']['date_modified']['value'])->getTimestamp());
        $description = '';
        $displayFieldNames = true;
        if(count($item['name_value_list']) == 2 &&isset($item['name_value_list']['name']))$displayFieldNames = false;
        foreach($item['name_value_list'] as $k=>$v){
            if ( $k == 'name' || $k == 'date_modified') {
                continue;
            }
            if($displayFieldNames) $description .= '<b>' .htmlentities( $k) . ':<b>&nbsp;';
            $description .= htmlentities( $v['value']) . "<br>";
        }

        echo <<<EORSS
    <item>
        <title>$name</title>
        <link>$url</link>
        <description><![CDATA[$description]]></description>
        <pubDate>$date GMT</pubDate>
        <guid>{$item['id']}</guid>
    </item>

EORSS;
    }

    protected function generateResponseFooter()
    {
		echo <<<EORSS
</channel>
</rss>
EORSS;
	}

	/**
	 * Returns a fault since we cannot accept RSS as an input type
	 *
	 * @see SugarRest::serve()
	 */
	public function serve()
	{
	    global $app_strings;

	    $this->fault($app_strings['ERR_RSS_INVALID_INPUT']);
	}

	/**
	 * @see SugarRest::fault()
	 */
	public function fault($faultObject)
	{
		ob_clean();
		$this->generateResponseHeader();
		echo '<item><name>';
		if(is_object($errorObject)){
			$error = $errorObject->number . ': ' . $errorObject->name . '<br>' . $errorObject->description;
			$GLOBALS['log']->error($error);
		}else{
			$GLOBALS['log']->error(var_export($errorObject, true));
			$error = var_export($errorObject, true);
		} // else
		echo $error;
		echo '</name></item>';
		$this->generateResponseFooter();
	}
}