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
function build_argument_string($arguments=array()) {
   if(!is_array($arguments)) {
   	  return '';
   }

   $argument_string = '';
   $count = 0;
   foreach($arguments as $arg) {
   	   if($count != 0)
   	   {
   	   	  //If current directory or parent directory is specified, substitute with full path
   	   	  if($arg == '.')
   	   	  {
   	   	  	 $arg = getcwd();
   	   	  } else if ($arg == '..') {
   	   	  	 $dir = getcwd();
			 $arg = substr($dir, 0, strrpos($dir, DIRECTORY_SEPARATOR));
   	   	  }
          $argument_string .= ' ' . escapeshellarg($arg);
   	   }
   	   $count++;
   }

   return $argument_string;
}

//Bug 52872. Dies if the request does not come from CLI.
$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) != 'cli') {
    die("This is command-line only script");
}
//End of #52872

if(defined('PHP_BINDIR')) {
    $php_path = PHP_BINDIR."/";
} else {
    $php_path = '';
}

$php_file = $argv[0];
$p_info = pathinfo($php_file);
$php_dir = (isset($p_info['dirname']) && $p_info['dirname'] != '.') ?  $p_info['dirname'] . DIRECTORY_SEPARATOR : '';

//Make sure that the php executable really exists; if not, just default back assuming the executable is set
if(!file_exists($php_path . 'php')) {
    $php_path = '';
}

for($step=1;$step<=3;$step++) {
    $step_cmd = $php_path."php -f {$php_dir}silentUpgrade_step{$step}.php " . build_argument_string($argv);
    passthru($step_cmd, $output);
    if($output != 0) {
	    echo "***************         Step {$step} failed         ***************: $output\n";
	    exit(1);
    } else {
        echo "***************         Step {$step} OK\n";
    }
}

echo "***************         SUCCESS!\n";
exit(0);