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
$viewdefs['Notes']['EditView'] = array(
    'templateMeta' => array('form' => array('enctype'=> 'multipart/form-data',
                                            ),
							'maxColumns' => '2',
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30')
                                            ),
'javascript' => '{sugar_getscript file="include/javascript/dashlets.js"}
<script>
function deleteAttachmentCallBack(text)
	{literal} { {/literal}
	if(text == \'true\') {literal} { {/literal}
		document.getElementById(\'new_attachment\').style.display = \'\';
		ajaxStatus.hideStatus();
		document.getElementById(\'old_attachment\').innerHTML = \'\';
	{literal} } {/literal} else {literal} { {/literal}
		document.getElementById(\'new_attachment\').style.display = \'none\';
		ajaxStatus.flashStatus(SUGAR.language.get(\'Notes\', \'ERR_REMOVING_ATTACHMENT\'), 2000);
	{literal} } {/literal}
{literal} } {/literal}
</script>
<script>toggle_portal_flag(); function toggle_portal_flag()  {literal} { {/literal} {$TOGGLE_JS} {literal} } {/literal} </script>',
),
	'panels' =>array (
  		'lbl_note_information' => array (
  					array ('contact_name','parent_name'),
	    			array (
                        array('name'=>'name', 'displayParams'=>array('size'=>60)),''
                    ),

					array ( 
						'filename',

					    array('name'=>'portal_flag',
					          'displayParams'=>array('required'=>false),
			   				  'label' => 'LBL_PORTAL_FLAG',
                		      'hideIf' => 'empty($PORTAL_ENABLED)',
					    ),
	    			),
	    			array (
                        array('name' => 'description', 'label' => 'LBL_NOTE_STATUS'),
                    ),

  		),


	  'LBL_PANEL_ASSIGNMENT' => array(
	    array(
		    array ('name' => 'assigned_user_name','label' => 'LBL_ASSIGNED_TO'),
		    array('name'=>'team_name'),
	    ),
	  ),
	)
);
?>