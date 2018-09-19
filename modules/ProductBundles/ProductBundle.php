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

// Product is used to store customer information.
class ProductBundle extends SugarBean
{
    // Stored fields
    public $id;
    public $deleted;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $created_by;
    public $created_by_name;
    public $modified_by_name;
    public $name;
    public $currency_id;
    public $base_rate;
    public $description;
    public $tax;
    public $shipping;
    public $subtotal;
    public $deal_tot;
    public $deal_tot_usdollar;
    public $new_sub;
    public $new_sub_usdollar;
    public $total;
    public $tax_usdollar;
    public $shipping_usdollar;
    public $subtotal_usdollar;
    public $total_usdollar;
    public $bundle_stage;
    public $is_template;
    public $is_editable;

    // These are for related fields
    public $type_name;
    public $type_id;
    public $quote_id;
    public $quote_name;
    public $manufacturer_name;
    public $manufacturer_id;
    public $category_name;
    public $category_id;
    public $account_name;
    public $account_id;
    public $contact_name;
    public $contact_id;
    public $related_product_id;

    public $table_name = "product_bundles";
    public $rel_quotes = "product_bundle_quote";
    public $rel_products = "product_bundle_product";
    public $rel_notes = "product_bundle_note";
    public $module_dir = 'ProductBundles';
    public $object_name = "ProductBundle";

    public $new_schema = true;

    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = Array();

    //deletes related products might want to change this in the future if we allow for sharing of products
    public function mark_deleted($id)
    {

        // for now only delete comments, not the products
        //$lineItems = $this->getLineItems();

        $this->load_relationship('product_bundle_notes');
        $lineItems = $this->product_bundle_notes->getBeans();

        /* @var $item SugarBean */
        foreach ($lineItems as $item) {
            $item->mark_deleted($item->id);
        }
        return parent::mark_deleted($id);
    }

    public function __construct()
    {
        parent::__construct();
        $this->team_id = 1; // make the item globally accessible
    }

    /**
     * @deprecated
     */
    public function get_index($quote_id)
    {
        $values = array('quote_id' => $quote_id, 'bundle_id' => $this->id);
        return $this->retrieve_relationships($this->rel_quotes, $values, 'bundle_index');
    }

    /**
     * @deprecated
     */
    public function get_bundle_product_indexes()
    {
        $values = array('bundle_id' => $this->id);
        return $this->retrieve_relationships($this->rel_products, $values, 'product_index');
    }

    /**
     * @deprecated
     */
    public function get_bundle_note_indexes()
    {
        $values = array('bundle_id' => $this->id);
        return $this->retrieve_relationships($this->rel_notes, $values, 'note_index');
    }

    /**
     * Returns a list of the associated products
     * @deprecated
     * @see getProducts
     */
    public function get_products()
    {
        return $this->getProducts();
    }

    /**
     * Get all the products listed in the order that they belong
     *
     * @return array
     */
    public function getProducts()
    {
        $this->load_relationship('products');
        $beans = $this->products->getBeans();

        usort($beans, array(__CLASS__, 'compareProductOrNoteIndexAsc'));

        return $beans;
    }


    /**
     * @deprecated
     * @see getQuotes()
     */
    public function get_quotes()
    {
        return $this->getQuotes();
    }

    /**
     * Return any associated quotes to this ProductBundle
     * @return array
     */
    public function getQuotes()
    {
        $this->load_relationship('quotes');

        return $this->quotes->getBeans();
    }

    /**
     * @deprecated
     * @see getNotes()
     * @return array
     */
    public function get_notes()
    {
        return $this->getNotes();
    }

    /**
     * Returns a list of notes that has been sorted in the display order for this specific product bundle
     *
     * @return array
     */
    public function getNotes()
    {
        $this->load_relationship('product_bundle_notes');
        $beans = $this->product_bundle_notes->getBeans();

        usort($beans, array(__CLASS__, 'compareProductOrNoteIndexAsc'));

        return $beans;
    }

    /**
     * @deprecated
     * @see getLineItems()
     * @return array
     */
    public function get_product_bundle_line_items()
    {
        return $this->getLineItems();
    }

    /**
     * Get all the line items for a ProductBundle in the order they are set.
     *
     * @return array
     */
    public function getLineItems()
    {
        $this->load_relationship('products');
        $this->load_relationship('product_bundle_notes');

        $bundle_list = array_merge(
            $this->products->getBeans(),
            $this->product_bundle_notes->getBeans()
        );

        usort($bundle_list, array(__CLASS__, 'compareProductOrNoteIndexAsc'));

        return $bundle_list;
    }

    /**
     * @deprecated
     * @param string $bundle_id
     * @return bool
     */
    public function clear_productbundle_product_relationship($bundle_id)
    {
        $query = "delete from $this->rel_products where (bundle_id='$bundle_id') and deleted=0";
        $this->db->query($query, true, "Error clearing product bundle to product relationship: ");
        return true;
    }

    /**
     * @deprecated
     * @param string $product_id
     * @return bool
     */
    public function clear_product_productbundle_relationship($product_id)
    {
        $query = "delete from $this->rel_products where (product_id='$product_id') and deleted=0";
        $this->db->query($query, true, "Error clearing product to product bundle relationship: ");
        return true;
    }

    /**
     * @deprecated
     * @param string $product_id
     * @return bool
     */
    public function retrieve_productbundle_from_product($product_id)
    {
        $query = "SELECT bundle_id FROM $this->rel_products where (product_id='$product_id') and deleted=0";
        $result = $this->db->query($query, true, "Error retrieving product bundle for product $product_id ");
        if ($row = $this->db->fetchByAssoc($result)) {
            $this->retrieve($row['bundle_id']);
            return true;
        }
        return false;
    }

    /**
     * @deprecated
     * @param string $product_id
     * @return bool
     */
    public function in_productbundle_from_product($product_id)
    {
        $query = "SELECT bundle_id FROM $this->rel_products where (product_id='$product_id') and deleted=0";
        $result = $this->db->query($query, true, "Error retrieving product bundle for product $product_id ");
        if ($row = $this->db->fetchByAssoc($result)) {
            return true;
        }
        return false;
    }

    /**
     * @deprecated
     * @param string $product_id
     * @param string $product_index
     * @param string $bundle_id
     * @return bool
     */
    public function set_productbundle_product_relationship($product_id, $product_index, $bundle_id = '')
    {
        if (empty($bundle_id)) {
            $bundle_id = $this->id;
        }
        $query = sprintf(
            'INSERT INTO %s (id, product_index, product_id, bundle_id, date_modified) VALUES (%s, %s, %s, %s, %s)',
            $this->rel_products,
            $this->db->quoted(create_guid()),
            $this->db->quoted($product_index),
            $this->db->quoted($product_id),
            $this->db->quoted($bundle_id),
            db_convert("'" . TimeDate::getInstance()->nowDb() . "'", 'datetime')
        );
        $this->db->query($query, true, "Error setting product to product bundle relationship: " . "<BR>$query");
        $GLOBALS['log']->debug("Setting product to product bundle relationship for $product_id and $bundle_id");
        return true;
    }

    /**
     * @deprecated
     * @param string $note_index
     * @param string $note_id
     * @param string $bundle_id
     * @return bool
     */
    public function set_product_bundle_note_relationship($note_index, $note_id, $bundle_id = '')
    {
        if (empty($bundle_id)) {
            $bundle_id = $this->id;
        }

        $query = "INSERT INTO $this->rel_notes (id,bundle_id,note_id,note_index, date_modified) VALUES ('" . create_guid(
        ) . "'," . $this->db->quoted($bundle_id) . "," . $this->db->quoted($note_id) . "," .
            $this->db->quoted($note_index) . ", " . db_convert(
                "'" . TimeDate::getInstance()->nowDb() . "'",
                'datetime'
            ) . ")";

        $this->db->query($query, true, "Error setting note to product to product bundle relationship: " . "<BR>$query");
        $GLOBALS['log']->debug(
            "Setting note to product to product bundle relationship for bundle_id: $bundle_id, note_index: $note_index, and note_id: $note_id"
        );
        return true;
    }

    /**
     * @deprecated
     * @param string $bundle_id
     * @return bool
     */
    public function clear_product_bundle_note_relationship($bundle_id = '')
    {
        $query = "DELETE FROM $this->rel_notes WHERE (bundle_id=".$this->db->quoted($bundle_id).") AND deleted=0";

        $this->db->query($query, true, "Error clearing note to product to product bundle relationship");
        return true;
    }

    /**
     * @deprecated
     * @param string $bundle_id
     * @return bool
     */
    public function clear_productbundle_quote_relationship($bundle_id)
    {
        $query = "delete from $this->rel_quotes where (bundle_id=".$this->db->quoted($bundle_id).") and deleted=0";
        $this->db->query($query, true, "Error clearing product bundle to quote relationship: ");
        return true;
    }

    /**
     * @deprecated
     * @param string $quote_id
     * @return bool
     */
    public function clear_quote_productbundle_relationship($quote_id)
    {
        $query = "delete from $this->rel_quotes where (quote_id='$quote_id') and deleted=0";
        $this->db->query($query, true, "Error clearing quote to product bundle relationship: ");
        return true;
    }

    /**
     * @deprecated
     * @param string $quote_id
     * @param string $bundle_id
     * @param string $bundle_index
     * @return bool
     */
    public function set_productbundle_quote_relationship($quote_id, $bundle_id, $bundle_index = '0')
    {
        if (empty($bundle_id)) {
            $bundle_id = $this->id;
        }
        $query = "insert into $this->rel_quotes (id,quote_id,bundle_id,bundle_index, date_modified) values (" . $this->db->quoted(
                create_guid()
            ) . ", " . $this->db->quoted($quote_id) . ", " . $this->db->quoted($bundle_id) . ", " . $this->db->quoted(
                $bundle_index
            ) . ", " . $this->db->convert($this->db->quoted(TimeDate::getInstance()->nowDb()), 'datetime') . ")";
        $this->db->query($query, true, "Error setting quote to product bundle relationship: " . "<BR>$query");
        $GLOBALS['log']->debug("Setting quote to product bundle relationship for $quote_id and $bundle_id");
        return true;
    }

    public function fill_in_additional_list_fields()
    {
        $this->fill_in_additional_detail_fields();
    }

    public function fill_in_additional_detail_fields()
    {
        $currency = BeanFactory::getBean('Currencies', $this->currency_id);
        if ($currency->id != $this->currency_id || $currency->deleted == 1) {
            $this->tax = $this->tax_usdollar;
            $this->shipping = $this->shipping_usdollar;
            $this->subtotal = $this->subtotal_usdollar;
            $this->total = $this->total_usdollar;
            $this->currency_id = $currency->id;
        }

    }


    /**
     * Returns a list of the associated opportunities
     */
    public function get_list_view_data()
    {
        global $current_language, $app_strings, $app_list_strings;
//		global $support_expired, $support_coming_due, $support_coming_due_color, $support_expired_color;
        $product_mod_strings = return_module_language($current_language, "Products");
        include('modules/Products/config.php');

        global $current_user;

        $currency = BeanFactory::newBean('Currencies');
        if ($current_user->getPreference('currency')) {
            $currency->retrieve($current_user->getPreference('currency'));
            $symbol = $currency->symbol;
        } else {
            $currency->retrieve('-99');
            $symbol = $currency->symbol;
        }


        return Array(
            'ID' => $this->id,
            'NAME' => (($this->name == "") ? "<em>blank</em>" : $this->name),
            'SHIPPING' => $symbol . '&nbsp;' . number_format(
                    round($currency->convertFromDollar($this->shipping_usdollar), 2),
                    2,
                    '.',
                    ''
                ),
            'TAX' => $symbol . '&nbsp;' . number_format(
                    round($currency->convertFromDollar($this->tax_usdollar), 2),
                    2,
                    '.',
                    ''
                ),
            'TOTAL' => $symbol . '&nbsp;' . number_format(
                    round($currency->convertFromDollar($this->total_usdollar), 2),
                    2,
                    '.',
                    ''
                ),
            'SUBTOTAL' => $symbol . '&nbsp;' . number_format(
                    round($currency->convertFromDollar($this->subtotal_usdollar), 2),
                    2,
                    '.',
                    ''
                ),
        );
    }

    /**
     * builds a generic search based on the query string using or
     * do not include any $this-> because this is called on without having the class instantiated
     */
    public function build_generic_where_clause($the_query_string)
    {
        $where_clauses = Array();
        $the_query_string = $GLOBALS['db']->quote($the_query_string);
        array_push($where_clauses, "name like '$the_query_string%'");
        $the_where = "";
        foreach ($where_clauses as $clause) {
            if ($the_where != "") {
                $the_where .= " or ";
            }
            $the_where .= $clause;
        }


        return $the_where;
    }

    /**
     * Compare Product and(or) ProductBundleNote objects by {record}_index field
     *
     * @param object $obj1
     * @param object $obj2
     * @return int
     */
    protected static function compareProductOrNoteIndexAsc($obj1, $obj2)
    {
        $firstValue = $obj1->position;
        $secondValue = $obj2->position;

        if ($firstValue == $secondValue) {
            return 0;
        }
        return ($firstValue < $secondValue) ? -1 : 1;
    }

    /**
     * Compare Product Bundles by bundle index
     *
     * @param object $pb1
     * @param object $pb2
     * @return int
     */
    public static function compareProductBundlesByIndex($pb1, $pb2)
    {
        if ($pb1->position == $pb2->position) {
            return 0;
        }
        return ($pb1->position < $pb2->position) ? -1 : 1;
    }

    /**
     * Bean specific logic for when SugarFieldCurrency_id::save() is called to make sure we can update the base_rate
     *
     * @return bool
     */
    public function updateCurrencyBaseRate()
    {
        // load the bundle -> quotes relationship
        $this->load_relationship('quotes');

        // get the beans
        $beans = $this->quotes->getBeans();
        $quote = array_pop($beans);

        if (empty($quote)) {
            return true;
        }

        // if the quote is not closed, we should update the base rate
        return !$quote->isClosed();

    }

    public function get_summary_text()
    {
        return $this->name;
    }
}
