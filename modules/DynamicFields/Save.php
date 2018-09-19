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


use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

$request = InputValidation::getService();
$module = $request->getValidInputRequest('module_name', 'Assert\Mvc\ModuleName');
$custom_fields = new DynamicField($module);
if(!empty($module)){
    $mod = BeanFactory::newBean($module);
	$custom_fields->setup($mod);
}else{
	echo "\n".$mod_strings['ERR_NO_MODULE_INCLUDED'];
}

$fieldLabel = $request->getValidInputRequest('field_label');
$fieldType = $request->getValidInputRequest('field_type');
$fieldCount = $request->getValidInputRequest('field_count');
$fileType = $request->getValidInputRequest('file_type');

$name = $fieldLabel;
$options = '';
if($fieldType == 'enum'){
	$options = $request->getValidInputRequest('options');
}
$default_value = '';

$custom_fields->addField($name,$name, $fieldType,'255','optional', $default_value, $options, '', '' );
$html = $custom_fields->getFieldHTML($name, $fileType);

set_register_value('dyn_layout', 'field_counter', $fieldCount);
$label = $custom_fields->getFieldLabelHTML($name, $fieldType);
require_once('modules/DynamicLayout/AddField.php');
$af = new AddField();
$af->add_field($name, $html,$label, 'window.opener.');
echo $af->get_script('window.opener.');
echo "\n<script>window.close();</script>";

?>
