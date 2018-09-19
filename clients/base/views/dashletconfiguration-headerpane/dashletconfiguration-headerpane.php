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
$viewdefs["base"]["view"]["dashletconfiguration-headerpane"] = array(
    "buttons" => array(
        array(
            "name"      => "cancel_button",
            "type"      => "button",
            "label"     => "LBL_CANCEL_BUTTON_LABEL",
            "css_class" => "btn-invisible btn-link",
        ),
        array(
            "name"      => "save_button",
            "type"      => "button",
            "label"     => "LBL_SAVE_BUTTON_LABEL",
            "css_class" => "btn-primary",
        ),
    ),
    "panels" => array(
        array(
            "name" => "header",
            "fields" => array(
                array(
                    "type" => "base",
                    "name" => "label",
                    "placeholder" => "LBL_NAME",
                    "required" => true,
                ),
            )
        )
    )
);
