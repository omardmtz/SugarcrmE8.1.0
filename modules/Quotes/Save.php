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

require_once('include/formbase.php');
require_once('modules/Quotes/config.php');
Activity::disable();
/* @var $focus Quote */
$focus = BeanFactory::newBean('Quotes');
$focus = populateFromPost('', $focus);

if (!$focus->ACLAccess('Save')) {
    ACLController::displayNoAccess(true);
    sugar_cleanup(true);
}

//we have to commit the teams here in order to obtain the team_set_id for use with products and product bundles.
if (empty($focus->teams)) {
    $focus->load_relationship('teams');
}
$focus->in_save = true;
$focus->teams->save();
//bug: 35297 - set the teams to have not been saved, so workflow can update if necessary
$focus->teams->setSaved(false);

if (!empty($_POST['assigned_user_id']) &&
    ($focus->assigned_user_id != $_POST['assigned_user_id']) &&
    ($_POST['assigned_user_id'] != $current_user->id)
) {
    $check_notify = true;
} else {
    $check_notify = false;
}
//bug55337 - Inline edit to different stage, cause total amount to display 0
if (!isset($_REQUEST['from_dcmenu'])) {
    $focus->tax = 0;
    $focus->total = 0;
    $focus->subtotal = 0;
    $focus->deal_tot = 0;
    $focus->new_sub = 0;
    $focus->shipping = 0;
    $focus->tax_usdollar = 0;
    $focus->total_usdollar = 0;
    $focus->subtotal_usdollar = 0;
    $focus->shipping_usdollar = 0;
}

if (empty($_REQUEST['calc_grand_total'])) {
    $focus->calc_grand_total = 0;
} else {
    $focus->calc_grand_total = 1;
}
if (empty($_REQUEST['show_line_nums'])) {
    $focus->show_line_nums = 0;
} else {
    $focus->show_line_nums = 1;
}

if (empty($focus->id)) {
    // bug 14323, add this to create products firsts, and create the quotes at last,
    // so the workflow can manipulate the products.
    $focus->id = create_guid();
    $focus->new_with_id = true;
}

//remove the relate id element if this is a duplicate
if (isset($_REQUEST['duplicateSave']) && isset($_REQUEST['relate_id'])) {
    //this is a 'create duplicate' quote, keeping the relate_id in focus will assign the quote product bundles
    //to the original quote, not the new duplicate one, so we will unset the element
    unset($_REQUEST['relate_id']);
}

// since the ProductBundles and Products get the BaseRate from the Quote
// make sure it's set correctly before we do any of that processing.
// This will ensure that SugarLogic doesn't do weird conversion.
SugarCurrency::verifyCurrencyBaseRateSet($focus, !$focus->new_with_id);

global $beanFiles;
require_once($beanFiles['Product']);
$GLOBALS['log']->debug("Saving associated products");

$i = 0;

if (isset($_POST['product_count'])) {
    $product_count = $_POST['product_count'];
}
//totals keys is a list of tables for the products bundles
if (isset($_REQUEST['total'])) {
    $total_keys = array_keys($_REQUEST['total']);
} else {
    $total_keys = array();
}

//unset relate fields for product bundles
$tmpRelate_id = isset($_REQUEST['relate_id']) ? $_REQUEST['relate_id'] : '';
$tmpRelate_to = isset($_REQUEST['relate_to']) ? $_REQUEST['relate_to'] : '';
unset($_REQUEST['relate_id']);
unset($_REQUEST['relate_to']);

$product_bundels = array();
for ($k = 0; $k < sizeof($total_keys); $k++) {
    $pb = BeanFactory::newBean('ProductBundles');

    if (substr_count($total_keys[$k], 'group_') == 0) {
        $pb->retrieve($total_keys[$k]);
    }

    $pb->team_id = $focus->team_id;
    $pb->team_set_id = $focus->team_set_id;
    $pb->shipping = (string)unformat_number($_REQUEST['shipping'][$total_keys[$k]]);
    $pb->currency_id = $focus->currency_id;
    $pb->base_rate = $focus->base_rate;
    $pb->taxrate_id = $focus->taxrate_id;
    $pb->bundle_stage = $_REQUEST['bundle_stage'][$total_keys[$k]];
    $pb->name = $_REQUEST['bundle_name'][$total_keys[$k]];

    $pb->load_relationship('quotes');
    $pb->quotes->getBeans();
    $pb->quotes->__set("beans", array($focus->id => $focus));

    $product_bundels[$total_keys[$k]] = $pb->save();
    if (substr_count($total_keys[$k], 'group_') > 0) {
        $lastIndex = getLastProductBundleIndex($focus->id);
        $pb->set_productbundle_quote_relationship($focus->id, $pb->id, $lastIndex + 1);
    }

    //clear the old relationships out
    $pb->clear_productbundle_product_relationship($product_bundels[$total_keys[$k]]);
    $pb->clear_product_bundle_note_relationship($product_bundels[$total_keys[$k]]);
}

$deletedGroups = array();
if (isset($_POST['delete_table'])) {
    foreach ($_POST['delete_table'] as $todelete) {
        $pb = BeanFactory::getBean('ProductBundles', $todelete);
        if ($todelete !== '1') {
            $pb->mark_deleted($todelete);
            $deletedGroups[$todelete] = $todelete;
        }
    }
}
//Fix bug 25509
$focus->process_save_dates = true;

/* @var $pb ProductBundle */
$pb = BeanFactory::newBean('ProductBundles');
for ($i = 0; $i < $product_count; $i++) {

    if ((isset($_POST['delete'][$i]) && $_POST['delete'][$i] != '1')) {
        $productId = $_POST['delete'][$i];
        $product = BeanFactory::getBean('Products', $productId);
        $GLOBALS['log']->debug("deleting product id $productId");
        $product->mark_deleted($productId);
    } else {
        // delete a comment row
        if (isset($_POST['comment_delete'][$i]) &&
            $_POST['comment_delete'][$i] != '1' &&
            !isset($_REQUEST['duplicateSave'])
        ) {
            $productBundleNoteId = $_POST['comment_delete'][$i];
            $product_bundle_note = BeanFactory::getBean('ProductBundleNotes', $productBundleNoteId);
            $GLOBALS['log']->debug("Deleting Product Bundle Note Id: $productBundleNoteId");
            $product_bundle_note->mark_deleted($productBundleNoteId);
        } else {
            // insert/update a product into products table
            if (!empty($_POST['product_name'][$i]) && !empty($_POST['parent_group'][$i])) {
                $product = BeanFactory::newBean('Products');
                $the_product_template_id = '-1';
                if (!empty($_POST['product_id'][$i])) {
                    $product->retrieve($_POST['product_id'][$i]);
                    $the_product_template_id = $product->product_template_id;
                }
                $GLOBALS['log']->debug("product id is $product->id");
                $GLOBALS['log']->debug("product template id is $product->product_template_id");

                foreach ($product->column_fields as $field) {
                    if ($field == 'name') {
                        $j = 'product_name';
                    } elseif ($field == 'description') {
                        $j = 'product_description';
                    } else {
                        $j = $field;
                    }
                    if (isset($_POST[$j]) && is_array($_POST[$j]) && isset($_POST[$j][$i])) {
                        $value = $_POST[$j][$i];
                        if (isset($product->field_defs[$field]['type'])) {
                            // figure out the type that we need
                            $def = $product->field_defs[$field];
                            // get the correct type in the following order
                            //  custom_type -> dbType -> type
                            // from the vardefs
                            $type = !empty($def['custom_type']) ? $def['custom_type'] :
                                !empty($def['dbType']) ? $def['dbType'] : $def['type'];
                            $sugarField = SugarFieldHandler::getSugarField($type);
                            $sugarField->save($product, array($field => $value), $field, $product->field_defs[$field]);
                        } else {
                            $product->$field = $value;
                        }
                    }
                }

                $product->currency_id = $focus->currency_id;
                $product->base_rate = $focus->base_rate;

                $product->team_id = $focus->team_id;
                $product->team_set_id = $focus->team_set_id;

                $product->assigned_user_id = $focus->assigned_user_id;
                $product->assigned_user_name = $focus->assigned_user_name;

                $product->quote_id = $focus->id;
                $product->account_id = $focus->billing_account_id;
                $product->contact_id = $focus->billing_contact_id;
                //SM: removed as per Bug 15305 $product->status=$focus->quote_type;
                // if ($focus->quote_stage == 'Closed Accepted') $product->status='Orders';
                $product->ignoreQuoteSave = true;
                $product->save();
                $pb->set_productbundle_product_relationship(
                    $product->id,
                    $_POST['parent_group_position'][$i],
                    $product_bundels[$_REQUEST['parent_group'][$i]]
                );
            } else {
                // insert comment row
                if (!empty($_POST['comment_index'][$i]) && !empty($_POST['parent_group'][$i])) {
                    $product_bundle_note = BeanFactory::newBean('ProductBundleNotes');
                    if (!empty($_POST['comment_id'][$i]) && !isset($_REQUEST['duplicateSave'])) {
                        $product_bundle_note->id = $_POST['comment_id'][$i];
                    }
                    $product_bundle_note->description = $_POST['comment_description'][$i];
                    $product_bundle_note->save();
                    $pb->set_product_bundle_note_relationship(
                        $_POST['parent_group_position'][$i],
                        $product_bundle_note->id,
                        $product_bundels[$_REQUEST['parent_group'][$i]]
                    );
                }
            }
        }
    }
}

if (isset($GLOBALS['check_notify'])) {
    $check_notify = $GLOBALS['check_notify'];
} else {
    $check_notify = false;
}

// we need to resave all the product bundles, so sugarlogic works.
foreach ($product_bundels as $bundle_key) {
    $pb = BeanFactory::getBean('ProductBundles', $bundle_key);
    // if the products link is already load, we need to usnet it so we get the fresh values from the db.
    if (isset($pb->products)) {
        unset($pb->products);
    }
    $pb->save();
}

//reset relate fields
$_REQUEST['relate_id'] = $tmpRelate_id;
$_REQUEST['relate_to'] = $tmpRelate_to;

$focus->save($check_notify);

$return_id = $focus->id;

$GLOBALS['log']->debug("Saved record with id of " . $return_id);
$return_module = 'Quotes';
if (!empty($_REQUEST['return_module'])) {
    $return_module = $_REQUEST['return_module'];
}
handleRedirect($return_id, $return_module);

function getLastProductBundleIndex($focusId)
{
    $tableName = $GLOBALS['dictionary']['product_bundle_quote']['table'];
    $focusId = $GLOBALS['db']->quoted($focusId);
    $query = "SELECT MAX(bundle_index) as last_index FROM $tableName WHERE quote_id = $focusId AND deleted = 0";
    $result = $GLOBALS['db']->query($query);
    $row = $GLOBALS['db']->fetchByAssoc($result);
    return ($row['last_index'] != null) ? $row['last_index'] : -1;
}
