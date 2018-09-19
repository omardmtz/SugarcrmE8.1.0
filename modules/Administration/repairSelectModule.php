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
		
		global $mod_strings;
		global $current_language;
		$smarty = new Sugar_Smarty();
			$temp_bean_list = $beanList;
			asort($temp_bean_list);
			$values= array_values($temp_bean_list);
			$output= array_keys($temp_bean_list);  
			$output_local = array();
			if($current_language != 'en_us') {
				foreach($output as $temp_out) {
					$output_local[] = translate($temp_out);
				}
			} else {
				$output_local = $output;
			}
			$values=array_merge(array($mod_strings['LBL_ALL_MODULES']), $values);
			$output= array_merge(array($mod_strings['LBL_ALL_MODULES']),$output_local);
			$checkbox_values=array(
									 'clearTpls',
									 'clearJsFiles',
									 'clearVardefs', 
									 'clearJsLangFiles',
									 'clearDashlets',
									 'clearThemeCache',
									 'rebuildAuditTables',
									 'rebuildExtensions',
									 'clearLangFiles',
                                     'clearSearchCache',
			                         'clearPDFFontCache',
									 );
			$checkbox_output = array(   $mod_strings['LBL_QR_CBOX_CLEARTPL'], 
                                        $mod_strings['LBL_QR_CBOX_CLEARJS'],
                                        $mod_strings['LBL_QR_CBOX_CLEARVARDEFS'],
                                        $mod_strings['LBL_QR_CBOX_CLEARJSLANG'],
                                        $mod_strings['LBL_QR_CBOX_CLEARDASHLET'],
                                        $mod_strings['LBL_QR_CBOX_CLEARTHEMECACHE'],
                                        $mod_strings['LBL_QR_CBOX_REBUILDAUDIT'],
                                        $mod_strings['LBL_QR_CBOX_REBUILDEXT'],
                                        $mod_strings['LBL_QR_CBOX_CLEARLANG'],
                                        $mod_strings['LBL_QR_CBOX_CLEARSEARCH'],
                                        $mod_strings['LBL_QR_CBOX_CLEARPDFFONT'],
									 );
			$smarty->assign('checkbox_values', $checkbox_values);
			$smarty->assign('values', $values);
			$smarty->assign('output', $output);
			$smarty->assign('MOD', $mod_strings);
			$smarty->assign('checkbox_output', $checkbox_output);
			$smarty->assign('checkbox_values', $checkbox_values);
			$smarty->display("modules/Administration/templates/QuickRepairAndRebuild.tpl");

