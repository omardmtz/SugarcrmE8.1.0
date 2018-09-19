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
 * MetaParser.php
 *
 * This is a utility base file to parse HTML
 * @author Collin Lee
 * @api
 */
class MetaParser {


function __construct() {

}

/**
 * hasMultiplePanels
 * This is a utility function to determine if a given set of panels as defined in a metadata file contain mutiple panels
 *
 * @param Array $panels Array of panels as defined in a metadata file
 * @return bool Returns true if there are multiple panels defined; false otherwise
 */
public static function hasMultiplePanels($panels)
{
   if(!isset($panels) || empty($panels) || !is_array($panels)) {
   	  return false;
   }

   if(is_array($panels) && (count($panels) == 0 || count($panels) == 1)) {
   	  return false;
   }

   foreach($panels as $panel) {
   	  if(!empty($panel) && !is_array($panel)) {
   	  	 return false;
   	  } else {
   	  	 foreach($panel as $row) {
   	  	    if(!empty($row) && !is_array($row)) {
   	  	       return false;
   	  	    } //if
   	  	 } //foreach
   	  } //if-else
   } //foreach

   return true;
}


/**
 * parseDelimiters
 * This is a utility function that helps to insert Smarty delimiters into a block of code
 *
 * @param string $javascript String contents of javascript
 * @return string Formatted javascript String with Smarty tags applied
 */
public static function parseDelimiters($javascript)
{
    $newJavascript = '';
    $scriptLength = strlen($javascript);
    $count = 0;
    $inSmartyVariable = false;

    while($count < $scriptLength) {

          if($inSmartyVariable) {
             $start = $count;
             $numOfChars = 1;
             while(isset($javascript[$count]) && $javascript[$count] != '}') {
                   $count++;
                   $numOfChars++;
             }

             $newJavascript .= substr($javascript, $start, $numOfChars);
             $inSmartyVariable = false;

          } else {

              $char = $javascript[$count];
              $nextChar = ($count + 1 >= $scriptLength) ? '' : $javascript[$count + 1];

              if($char == "{" && $nextChar == "$") {
                 $inSmartyVariable = true;
                 $newJavascript .= $javascript[$count];
              } else if($char == "{") {
                 $newJavascript .=  " {ldelim} ";
              } else if($char == "}") {
                 $newJavascript .= " {rdelim} ";
              } else {
                 $newJavascript .= $javascript[$count];
              }
          }
          $count++;
    } //while

    return $newJavascript;
}

}
