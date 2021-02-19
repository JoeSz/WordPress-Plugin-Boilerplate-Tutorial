<?php

/****************************************************************
 * ADD/REMOVE/REORDER/SORT CUSTOM POST TYPE LIST COLUMNS (test) *
 * ------------------------------------------------------------ *
 ****************************************************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_admin_hooks() {

    // ...

    /**
     * Modify columns in tests list in admin area.
     *
     * The hooks to create custom columns and their associated data for a custom post type
     * are manage_{$post_type}_posts_columns and
     * manage_{$post_type}_{$post_type_type}_custom_column or manage_{$post_type_hierarchical}_custom_column respectively,
     * where {$post_type} is the name of the custom post type and {$post_type_hierarchical} is post or page.
     *
     * @link https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
     * @link https://wordpress.stackexchange.com/questions/253640/adding-custom-columns-to-custom-post-types/253644#253644
     */
    $this->loader->add_filter( 'manage_test_posts_columns', $this->admin, 'manage_test_posts_columns' );
    $this->loader->add_action( 'manage_posts_custom_column', $this->admin, 'manage_posts_custom_column', 10, 2 );

    $this->loader->add_action( 'admin_head', $this->admin, 'add_style_to_admin_head' );

    /**
     * Filters the list table sortable columns for a specific screen.
     * manage_{$this->screen->id}_sortable_columns
     *
     * @link https://developer.wordpress.org/reference/hooks/manage_this-screen-id_sortable_columns/
     * @link https://www.smashingmagazine.com/2017/12/customizing-admin-columns-wordpress/
     */
    $this->loader->add_filter( 'manage_edit-test_sortable_columns', $this->admin, 'manage_sortable_columns' );
    $this->loader->add_action( 'pre_get_posts', $this->admin, 'manage_posts_orderby' );

}

///////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php


/**
 * Modify columns in tests list in admin area.
 */
public function manage_test_posts_columns( $columns ) {

    // Remove unnecessary columns
    unset(
        $columns['author'],
        $columns['comments']
    );

    // Rename title and add ID and Address
    $columns['thumbnail'] = '';
    $columns['text_1'] = esc_attr__( 'Text', 'plugin-name' );
    $columns['color_2'] = esc_attr__( 'Color', 'plugin-name' );
    $columns['date_2'] = esc_attr__( 'Date', 'plugin-name' );


    /**
     * Rearrange column order
     *
     * Now define a new order. you need to look up the column
     * names in the HTML of the admin interface HTML of the table header.
     *
     *     "cb" is the "select all" checkbox.
     *     "title" is the title column.
     *     "date" is the date column.
     *     "icl_translations" comes from a plugin (eg.: WPML).
     *
     * change the order of the names to change the order of the columns.
     *
     * @link http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
     */
    $custom_order = array('cb', 'thumbnail', 'title', 'text_1', 'color_2', 'date_2', 'icl_translations', 'date');

    // -- OR --
    // https://crunchify.com/how-to-move-wordpress-admin-column-before-or-after-any-other-column-manage-post-columns-hook/

    /**
     * return a new column array to wordpress.
     * order is the exactly like you set in $customOrder.
     */
    foreach ( $custom_order as $column_name )
        $rearranged[$column_name] = $columns[$column_name];

    return $rearranged;

}

// Populate new columns in customers list in admin area
public function manage_posts_custom_column( $column, $post_id ) {

    // For array, not simple options
    // global $post;
    // $custom = get_post_custom();
    // $meta = maybe_unserialize( $custom[$this->plugin_name][0] );

    // Populate column form meta
    switch ( $column  {

        case "thumbnail":
            echo '<a href="' . get_edit_post_link() . '">';
            echo get_the_post_thumbnail( $post_id, array( 60, 60 ) );
            echo '</a>';
            break;
        case "text_1":
            // no break;
        case "color_2":
            // no break;
        case "date_2":
            echo get_post_meta( $post_id, $column, true );
            break;
        // case "some_column":
        //     // For array, not simple options
        //     echo $meta["some_column"];
        //     break;

    }

}

public function add_style_to_admin_head() {
    global $post_type;
    if ( 'test' == $post_type ) {
        ?>
            <style type="text/css">
                .column-thumbnail {
                    width: 80px !important;
                }
                .column-title {
                    width: 30% !important;
                }
            </style>
        <?php
    }
}

/**
 * To sort, Exopite Simple Options Framework need 'options' => 'simple'.
 * Simple options is stored az induvidual meta key, value pair, otherwise it is stored in an array.
 *
 *
 * Meta key value paars need to sort as induvidual.
 *
 * I implemented this option because it is possible to search in serialized (array) post meta:
 * @link https://wordpress.stackexchange.com/questions/16709/meta-query-with-meta-values-as-serialize-arrays
 * @link https://stackoverflow.com/questions/15056407/wordpress-search-serialized-meta-data-with-custom-query
 * @link https://www.simonbattersby.com/blog/2013/03/querying-wordpress-serialized-custom-post-data/
 *
 * but there is no way to sort them with wp_query or SQL.
 * @link https://wordpress.stackexchange.com/questions/87265/order-by-meta-value-serialized-array/87268#87268
 * "Not in any reliable way. You can certainly ORDER BY that value but the sorting will use the whole serialized string,
 * which will give * you technically accurate results but not the results you want. You can't extract part of the string
 * for sorting within the query itself. Even if you wrote raw SQL, which would give you access to database functions like
 * SUBSTRING, I can't think of a dependable way to do it. You'd need a MySQL function that would unserialize the value--
 * you'd have to write it yourself.
 * Basically, if you need to sort on a meta_value you can't store it serialized. Sorry."
 *
 * It is possible to get all required posts and store them in an array and then sort them as an array,
 * but what if you want multiple keys/value pair to be sorted?
 *
 * UPDATE
 * it is maybe possible:
 * @link http://www.russellengland.com/2012/07/how-to-unserialize-data-using-mysql.html
 * but it is waaay more complicated and less documented as meta query sort and search.
 * It should be not an excuse to use it, but it is not as reliable as it should be.
 *
 * @link https://wpquestions.com/Order_by_meta_key_where_value_is_serialized/7908
 * "...meta info serialized is not a good idea. But you really are going to lose the ability to query your
 * data in any efficient manner when serializing entries into the WP database.
 *
 * The overall performance saving and gain you think you are achieving by serialization is not going to be noticeable to
 * any major extent. You might obtain a slightly smaller database size but the cost of SQL transactions is going to be
 * heavy if you ever query those fields and try to compare them in any useful, meaningful manner.
 *
 * Instead, save serialization for data that you do not intend to query in that nature, but instead would only access in
 * a passive fashion by the direct WP API call get_post_meta() - from that function you can unpack a serialized entry
 * to access its array properties too."
 */
public function manage_sortable_columns( $columns ) {

    $columns['text_1'] = 'text_1';
    $columns['color_2'] = 'color_2';
    $columns['date_2'] = 'date_2';

    return $columns;

}

public function manage_posts_orderby( $query ) {

    if( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    /**
     * meta_types:
     * Possible values are 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'.
     * Default value is 'CHAR'.
     *
     * @link https://codex.wordpress.org/Class_Reference/WP_Meta_Query
     */
    $columns = array(
        'text_1'  => 'char',
        'color_2' => 'char',
        'date_2'  => 'date',
    );

    foreach ( $columns as $key => $type ) {

        if ( $key === $query->get( 'orderby') ) {
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_key', $key );
            $query->set( 'meta_type', $type );
            break;
        }

    }

}
