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

if ( !empty($_GET['expression']) ) {
	$expr = $_GET['expression'];
	try {
		echo Parser::evaluate($expr)->evaluate();
	} catch (Exception $e) {
		echo $e;
	}
	die();
}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<title>SUGAR Arithmetic Engine (JS)</title>


<!-- yui js -->
<script type="text/javascript" src="http://yui.yahooapis.com/2.5.2/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.5.2/build/container/container_core-min.js"></script>

<!-- custom js -->
<script language="javascript">
YAHOO.util.Event.onDOMReady( function() {
	var container = new YAHOO.widget.Overlay("container", {fixedcenter:true, visible:true, width:"400px"});
	container.render();
});

function checkEnter(e) {
	var characterCode; // literal character code will be stored in this variable

	characterCode = e.keyCode;

	if ( characterCode == 13 ) {
		evalExpression();
		document.getElementById('expression').select();
		return false;
	} else {
		return true;
	}
}

var i = 0;
var expression = "";
function evalExpression()
{
	var parser = new SUGAR.ExpressionParser();
	var output = document.getElementById('results');
	expression = document.getElementById('expression').value;

	if ( expression == 'clear' ) {
		output.innerHTML = "";
		return;
	}

	var sUrl = "index.php?expr=" + escape(expression);
	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, { success: populate } );
}


function populate(o)
{
	var output = document.getElementById('results');

	var out = "";
	out += '<div class="expression">' + expression + ':</div>';
	out += '<div class="result">' + o.responseText() + "</div>";
	out += "<div class='clearer'></div>";

	var x = (i++%2);

	output.innerHTML = "<div class='casing" + x + "'>" + out + "</div>" + output.innerHTML;
}

</script>


<style type="text/css">

BODY {
	background: #BFBFBF;
	font-family: Trebuchet MS;
}

.expression {
	font-size:  10pt;
	float: left;
}

.casing0 {
	padding: 5px;
	background: #E4E4E4;
}

.casing1 {
	padding: 5px;
	background: #f4f4f4;
}

.result {
	font-family: Verdana;
	font-size: 10pt;
	font-weight: bold;
	float: right;
}

.clearer {
	clear: both;
}

#results {
	margin: 0 auto;
	width: 350px;
	height: 350px;
	border: 1px solid #cccccc;
	overflow:auto;
	padding: 10px;
	background: #ffffff;
}


#container {
	width: 400px;
	margin: 0 auto;
	height: 400px;
	background: #ffffff;
	background: #EFEFEF;
	border: 1px solid #000;
	padding: 30px;
}


#input {
	text-align: center;
	height: 50px;
	margin: 0 auto;
}

#input INPUT[type=text] {
	font-family: Courier New;
}

</style>

</head>

<body>

<div id="container">

	<div id="results">
	</div>

	<div id="input">
		<input type="text" id="expression" style="width: 288px" onkeypress="checkEnter(event)">
		<input type="button" value="Evaluate" onclick="evalExpression()">
	</div>

</div>

</body>

</html>
