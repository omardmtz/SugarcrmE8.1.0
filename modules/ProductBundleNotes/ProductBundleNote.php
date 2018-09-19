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

class ProductBundleNote extends SugarBean
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
    public $description;

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

    public $table_name = "product_bundle_notes";
    public $rel_quotes = "product_bundle_quote";
    public $rel_products = "product_bundle_product";
    public $rel_notes = "product_bundle_note";

    public $module_dir = "ProductBundleNotes";
    public $object_name = "ProductBundleNote";

    public $new_schema = true;

    public $column_fields = Array(
        "id",
        "description",
        "date_entered",
        "date_modified",
        "modified_user_id",
        "created_by"
    );

    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = Array();

    // This is the list of fields that are copied over from product template.


    // This is the list of fields that are in the lists.
    public $list_fields = array('id');
    // This is the list of fields that are required
    public $required_fields = array();

    //deletes related products might want to change this in the future if we allow for sharing of products

    public function __construct()
    {
        parent::__construct();

        $this->disable_row_level_security = true;
    }

    /**
     * @deprecated
     * @param string $bundle_id
     * @param string $product_id
     * @param string $note_id
     * @param integer $note_index
     */
    public function set_product_bundle_product_notes_relationship($bundle_id, $product_id, $note_id = '', $note_index)
    {
        if (empty($note_id)) {
            $note_id = $this->id;
        }

        $query = "INSERT INTO $this->rel_notes SET id='" . create_guid(
            ) . "', bundle_id='" . $bundle_id . "', product_id='" . $product_id . "', note_id='" . $note_id . "', note_index='" . $note_index . "'";

        $this->db->query($query, true, "Error setting note to product to product bundle relationship: " . "<BR>$query");
        $GLOBALS['log']->debug(
            "Setting note to product to product bundle relationship for bundle_id: $bundle_id, product_id: $product_id, and note_id: $note_id"
        );
    }

    /**
     * @deprecated
     * @param string $bundle_id
     */
    public function clear_product_bundle_product_notes_relationship($bundle_id)
    {
        $query = "DELETE FROM $this->rel_notes WHERE (bundle_id='$bundle_id') AND deleted=0";

        $this->db->query($query, true, "Error clearing note to product to product bundle relationship");
    }

    public function fill_in_additional_list_fields()
    {
        $this->fill_in_additional_detail_fields();
    }

    public function fill_in_additional_detail_fields()
    {
        // empty
    }

    public function get_list_view_data()
    {
        // empty
    }

    /**
     *    builds a generic search based on the query string using or
     *    do not include any $this-> because this is called on without having the class instantiated
     */
    public function build_generic_where_clause($the_query_string)
    {
        $where_clauses = Array();
        $the_query_string = addslashes($the_query_string);
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
}
