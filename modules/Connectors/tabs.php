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

class ConnectorWidgetTabs extends SugarWidgetTabs
{
 var $class;

    public function __construct(&$tabs,$current_key,$jscallback, $class='tablist')
    {
        parent::__construct($tabs, $current_key, $jscallback);
        $this->class = $class;
    }

 function display()
 {
	global $image_path;
	$IMAGE_PATH = $image_path;
	ob_start();
?>
<script>
var keys = [ <?php 
$tabs_count = count($this->tabs);
for($i=0; $i < $tabs_count;$i++) 
{
 $tab = $this->tabs[$i];
 echo "\"".$tab['key']."\""; 
 if ($tabs_count > ($i + 1))
 {
   echo ",";
 }
}
?>]; 
tabPreviousKey = '';

function selectTabCSS(key)
{


  for( var i=0; i<keys.length;i++)
  {
   var liclass = '';
   var linkclass = '';

 if ( key == keys[i])
 {
   var liclass = 'selected';
   var linkclass = 'selected';
 }
  	document.getElementById('tab_li_'+keys[i]).className = liclass;

  	//document.getElementById('tab_link_'+keys[i]).className = linkclass;
  }
    <?php echo $this->jscallback;?>(key, tabPreviousKey);
    tabPreviousKey = key;
}
</script>

<div>
<div class="yui-navset yui-navset-top">
<ul class="yui-nav">
<?php 
	foreach ($this->tabs as $tab)
	{
		$TITLE = $tab['title'];
		$LI_ID = "";

	  if ( ! empty($tab['hidden']) && $tab['hidden'] == true)
		{
			  $LI_ID = "";

		} else if ( $this->current_key == $tab['key'])
		{
			  $LI_ID = "class=\"selected\"";
		}

		$LINK = "<li $LI_ID id=\"tab_li_".$tab['link']."\"><a id=\"tab_link_".$tab['link']."\" href=\"javascript:selectTabCSS('{$tab['link']}');\"><em>$TITLE</em></a></li>";

?>
<?php echo $LINK; ?>	
<?php
	}
?>
</ul>
</div>
</div>

<?php 
	$ob_contents = ob_get_contents();
        ob_end_clean();
        return $ob_contents;
	}
}
