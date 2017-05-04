<?php

/****************************************************************
 * ADD/REMOVE/REORDER CUSTOM POST TYPE LIST COLUMNS (customers) *
 * ------------------------------------------------------------ *
 ****************************************************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_admin_hooks() {

    // ...

    /*
     * Change list columns in customers list
     *
     * manage_<custom post type>_<type>_columns (posts, pages <- type of {custom} post type)
     */
    $this->loader->add_filter( 'manage_customers_posts_columns', $plugin_admin, 'customers_list_edit_columns' );

    // manage_<type>_custom_column, type: pages or posts
    $this->loader->add_action( 'manage_pages_custom_column', $plugin_admin, 'customers_list_custom_columns', 10, 2 );

}

///////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php

// Modify columns in customers list in admin area
public function customers_list_edit_columns( $columns ) {

    // Remove unnecessary columns
    unset(
        $columns['author'],
        $columns['comments']
    );

    // Rename title and add ID and Address
    $columns['title'] = __( 'Customer Name', $this->plugin_name );
    $columns['customer_id'] = __( 'ID', $this->plugin_name );
    $columns['customer_address'] = __( 'Address', $this->plugin_name );

    /*
     * Rearrange column order
     *
     * Now define a new order. you need to look up the column
     * names in the HTML of the admin interface HTML of the table header.
     *
     *     "cb" is the "select all" checkbox.
     *     "title" is the title column.
     *     "date" is the date column.
     *     "icl_translations" comes from a plugin (in this case, WPML).
     *
     * change the order of the names to change the order of the columns.
     *
     * @link http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
     */
    $customOrder = array('cb', 'title', 'customer_id', 'customer_address', 'icl_translations', 'date');

    /*
     * return a new column array to wordpress.
     * order is the exactly like you set in $customOrder.
     */
    foreach ($customOrder as $column_name)
        $rearranged[$column_name] = $columns[$column_name];

    return $rearranged;

}

// Populate new columns in customers list in admin area
public function customers_list_custom_columns( $column ) {

    global $post;
    $custom = get_post_custom();

    // Populate column form meta
    switch ($column) {
        case "customer_id":
            echo $custom["customer_id"][0];
            break;
        case "customer_address":
            echo $custom["customer_address"][0];
            break;

    }

}

