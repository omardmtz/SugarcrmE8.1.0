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
class ViewFunctiondetail extends SugarView
{
    public function __construct()
    {
		$this->options['show_footer'] = false;
		$this->options['show_header'] = false;
        parent::__construct();
 	}

 	function display(){
 		global $app_strings, $current_user, $mod_strings, $theme, $beanList, $beanFiles;
 		if (!is_file($cachefile = sugar_cached('Expressions/functionmap.php'))) {
        	$GLOBALS['updateSilent'] = true;
            include ('include/Expressions/updatecache.php');
        }
 		include $cachefile;
 		$desc = "";
		$function = $this->request->getValidInputRequest('function', 'Assert\SugarLogic\FunctionName');
 		if (!empty($function) && !empty($FUNCTION_MAP[$function])){
 			$func_def =  $FUNCTION_MAP[$function];
			require_once($func_def['src']);
			$class = new ReflectionClass($func_def['class']);
			$doc = $class->getDocComment();
			if (!empty($doc)) {
				//Remove the javadoc style comment *'s
				$desc = preg_replace("/((\/\*+)|(\*+\/)|(\n\s*\*)[^\/])/", "", $doc);
			} else if (isset($mod_strings['func_descriptions'][$function]))
			{
				$desc = $mod_strings['func_descriptions'][$function];
			}
			else
			{
				$seed = $func_def['class'];
				$count = call_user_func(array($seed, "getParamCount"));
				$type = call_user_func(array($seed, "getParameterTypes"));
				$desc = $function . "(";
				if ($count == -1)
				{
					$desc .=  $type . ", ...";
				} else {
					for ($i = 0; $i < $count; $i++) {
						if ($i != 0) $desc .= ", ";
						if (is_array($type))
							$desc .=  $type[$i] . ($i+1);
						else
							$desc .=  $type . ($i+1);
					}
				}
				$desc .= ")";
			}
		}
		else {
			$desc = "function not found";
		}
		echo JSON::encode(array(
			"func" => empty($function) ? "" : $function,
			"desc" => $desc,
		));
 	}
}

