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

require_once('include/SugarSmarty/plugins/function.sugar_replace_vars.php');

class SugarFieldLink extends SugarFieldBase {

    /**
     * {@inheritDoc}
     */
    public function apiFormatField(
        array &$data,
        SugarBean $bean,
        array $args,
        $fieldName,
        $properties,
        array $fieldList = null,
        ServiceBase $service = null
    ) {
        $this->ensureApiFormatFieldArguments($fieldList, $service);

        // this is only for generated links
    	if(isset($bean->field_defs[$fieldName]['gen']) && isTruthy($bean->field_defs[$fieldName]['gen'])) {
            $subject = $bean->field_defs[$fieldName]['default'];
            if (!empty($subject)) {
                $data[$fieldName] = replace_sugar_vars($subject, $bean->toArray(), true);
            } else {
                $data[$fieldName] = "";
            }
	    } else {
            parent::apiFormatField($data, $bean, $args, $fieldName, $properties, $fieldList, $service);
        }
    }
}
