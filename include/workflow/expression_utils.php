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

// $Id: expression_utils.php 51719 2009-10-22 17:18:00Z mitani $

function get_expression($express_type, $first, $second){
	
	if($express_type=="+"){
		return express_add($first, $second);
	}	
	if($express_type=="-"){
		return express_subtract($first, $second);
	}		
	if($express_type=="*"){
		return express_multiple($first, $second);
	}		
	if($express_type=="/"){
		return express_divide($first, $second);
	}			
//end function get_expression
}

function express_add($first, $second){
	return $first + $second;
}	

function express_subtract($first, $second){
	return $first - $second;
}

function express_multiple($first, $second){
	return $first * $second;
}

function express_divide($first, $second){
	return $first / $second;
}



?>
