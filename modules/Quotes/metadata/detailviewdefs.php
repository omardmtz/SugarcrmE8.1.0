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

$viewdefs['Quotes']['DetailView'] = array(
    'templateMeta' => array(
        'form' => array(
            'closeFormBeforeCustomButtons' => true,
            'buttons' => array(
                'EDIT',
                'SHARE',
                'DUPLICATE',
                'DELETE',
                array(
                    'customCode' => '<form action="index.php" method="POST" name="Quote2Opp" id="form">
                    <input type="hidden" name="module" value="Quotes">
                    <input type="hidden" name="record" value="{$fields.id.value}">
                    <input type="hidden" name="user_id" value="{$current_user->id}">
                    <input type="hidden" name="team_id" value="{$fields.team_id.value}">
                    <input type="hidden" name="user_name" value="{$current_user->user_name}">
                    <input type="hidden" name="action" value="QuoteToOpportunity">
                    <input type="hidden" name="opportunity_subject" value="{$fields.name.value}">
                    <input type="hidden" name="opportunity_name" value="{$fields.name.value}">
                    <input type="hidden" name="opportunity_id" value="{$fields.billing_account_id.value}">
                    <input type="hidden" name="amount" value="{$fields.total.value}">
                    <input type="hidden" name="valid_until" value="{$fields.date_quote_expected_closed.value}">
                    <input type="hidden" name="currency_id" value="{$fields.currency_id.value}">
                    <input id="create_opp_from_quote_button" title="{$APP.LBL_QUOTE_TO_OPPORTUNITY_TITLE}"
                        class="button" type="submit" name="opp_to_quote_button"
                        value="{$APP.LBL_QUOTE_TO_OPPORTUNITY_LABEL}" {$DISABLE_CONVERT}></form>'
                ),
            ),
            'footerTpl' => 'modules/Quotes/tpls/DetailViewFooter.tpl'
        ),
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30')
        ),
    ),
    'panels' => array(
        'lbl_quote_information' => array(
            array(
                array(
                    'name' => 'name',
                    'label' => 'LBL_QUOTE_NAME',
                ),
                array(
                    'name' => 'opportunity_name',
                ),
            ),
            array(
                'quote_num',
                'quote_stage',
            ),
            array(
                'purchase_order_num',
                array(
                    'name' => 'date_quote_expected_closed',
                    'label' => 'LBL_DATE_QUOTE_EXPECTED_CLOSED',
                ),
            ),
            array(
                'payment_terms',
                'original_po_date',
            ),
            array(
                'billing_account_name',
                'shipping_account_name',
            ),
            array(
                'billing_contact_name',
                'shipping_contact_name'
            ),
            array(
                array(
                    'name' => 'billing_address_street',
                    'label' => 'LBL_BILL_TO',
                    'type' => 'address',
                    'displayParams' => array('key' => 'billing'),
                ),
                array(
                    'name' => 'shipping_address_street',
                    'label' => 'LBL_SHIP_TO',
                    'type' => 'address',
                    'displayParams' => array('key' => 'shipping'),
                ),
            ),
            array(
                'description',
            ),
        ),
        'LBL_PANEL_ASSIGNMENT' => array(
            array(
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_ASSIGNED_TO',
                ),
                array(
                    'name' => 'date_modified',
                    'label' => 'LBL_DATE_MODIFIED',
                    'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                ),
            ),
            array(

                'team_name',
                array(
                    'name' => 'date_entered',
                    'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                ),
            ),
        ),
    )
);
