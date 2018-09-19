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

 // $Id: progress_bar_utils.php 53116 2009-12-10 01:24:37Z mitani $
function progress_bar_flush()
{
	if(ob_get_level()) {
	    @ob_flush();
	} else {
        @flush();
	}
}

function display_flow_bar($name,$delay, $size=200)
{
	$chunk = $size/5;
	echo "<div id='{$name}_flow_bar'><table  class='list view' cellpading=0 cellspacing=0><tr><td id='{$name}_flow_bar0' width='{$chunk}px' bgcolor='#cccccc' align='center'>&nbsp;</td><td id='{$name}_flow_bar1' width='{$chunk}px' bgcolor='#ffffff' align='center'>&nbsp;</td><td id='{$name}_flow_bar2' width='{$chunk}px' bgcolor='#ffffff' align='center'>&nbsp;</td><td id='{$name}_flow_bar3' width='{$chunk}px' bgcolor='#ffffff' align='center'>&nbsp;</td><td id='{$name}_flow_bar4' width='{$chunk}px' bgcolor='#ffffff' align='center'>&nbsp;</td></tr></table></div><br>";

	echo str_repeat(' ',256);

	progress_bar_flush();
	start_flow_bar($name, $delay);
}

function start_flow_bar($name, $delay)
{
	$delay *= 1000;
	$timer_id = $name . '_id';
	echo "<script>
		function update_flow_bar(name, count){
			var last = (count - 1) % 5;
			var cur = count % 5;
			var next = cur + 1;
			eval(\"document.getElementById('\" + name+\"_flow_bar\" + last+\"').style.backgroundColor='#ffffff';\");
			eval(\"document.getElementById('\" + name+\"_flow_bar\" + cur+\"').style.backgroundColor='#cccccc';\");
			$timer_id = setTimeout(\"update_flow_bar('$name', \" + next + \")\", $delay);
		}
		 var $timer_id = setTimeout(\"update_flow_bar('$name', 1)\", $delay);

	</script>
";
	echo str_repeat(' ',256);

	progress_bar_flush();
}

function destroy_flow_bar($name)
{
	$timer_id = $name . '_id';
	echo "<script>clearTimeout($timer_id);document.getElementById('{$name}_flow_bar').innerHTML = '';</script>";
	echo str_repeat(' ',256);

	progress_bar_flush();
}

function display_progress_bar($name,$current, $total)
{
	$percent = $current/$total * 100;
	$remain = 100 - $percent;
	$status = floor($percent);
	//scale to a larger size
	$percent *= 2;
	$remain *= 2;
	if($remain == 0){
		$remain = 1;
	}
	if($percent == 0){
		$percent = 1;
	}
	echo "<div id='{$name}_progress_bar' style='width: 50%;'><table class='list view' cellpading=0 cellspacing=0><tr><td id='{$name}_complete_bar' width='{$percent}px' bgcolor='#cccccc' align='center'>$status% </td><td id='{$name}_remain_bar' width={$remain}px' bgcolor='#ffffff'>&nbsp;</td></tr></table></div><br>";
	if($status == 0){
		echo "<script>document.getElementById('{$name}_complete_bar').style.backgroundColor='#ffffff';</script>";
	}
	echo str_repeat(' ',256);

	progress_bar_flush();
}

function update_progress_bar($name,$current, $total)
{
	$percent = $current/$total * 100;
	$remain = 100 - $percent;
	$status = floor($percent);
	//scale to a larger size
	$percent *= 2;
	$remain *= 2;
	if($remain == 0){
		$remain = 1;
	}
	if($status == 100){
		echo "<script>document.getElementById('{$name}_remain_bar').style.backgroundColor='#cccccc';</script>";
	}
	if($status == 0){
		echo "<script>document.getElementById('{$name}_remain_bar').style.backgroundColor='#ffffff';</script>";
		echo "<script>document.getElementById('{$name}_complete_bar').style.backgroundColor='#ffffff';</script>";
	}
	if($status > 0){
		echo "<script>document.getElementById('{$name}_complete_bar').style.backgroundColor='#cccccc';</script>";
	}


	if($percent == 0){
		$percent = 1;
	}

	echo "<script>
		document.getElementById('{$name}_complete_bar').width='{$percent}px';
		document.getElementById('{$name}_complete_bar').innerHTML = '$status%';
		document.getElementById('{$name}_remain_bar').width='{$remain}px';
		</script>";
	progress_bar_flush();
}
