<?php

/**********************************************
 * REGISTER AND USE CUSTOM TABLES IN DATEBASE *
 * ------------------------------------------ *
 **********************************************/

/**
 * More info:
 * @link https://codex.wordpress.org/Class_Reference/wpdb
 * @link https://premium.wpmudev.org/blog/creating-database-tables-for-plugins/
 */

//////////////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name-activator.php
// To create the datebase on plugin actiovation
public static function activate() {

    // ...

    self::create_db();

    // ...
}

public static function create_db() {

    global $wpdb;
    $table_name = $wpdb->prefix . "custom_table_name";
    $plugin_name_db_version = get_option( 'plugin-name_db_version', '1.0' );

    if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ||
        version_compare( $version, '1.0' ) < 0 ) {

        $charset_collate = $wpdb->get_charset_collate();

        $sql[] = "CREATE TABLE " . $wpdb->prefix . "database_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            db_field_tinytext tinytext,
            db_field_datetime datetime DEFAULT '0000-00-00 00:00:00',
            db_field_varchar varchar(128) DEFAULT '',
            db_field_mediumint mediumint,
            db_field_text text,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        /**
         * It seems IF NOT EXISTS isn't needed if you're using dbDelta - if the table already exists it'll
         * compare the schema and update it instead of overwriting the whole table.
         *
         * @link https://code.tutsplus.com/tutorials/custom-database-tables-maintaining-the-database--wp-28455
         */
        dbDelta( $sql );

        add_option( 'plugin-name_db_version', $plugin_name_db_version );

    }

}

///////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php
// - OR -
// ADD TO FILE -> public/class-plugin-name-public.php
// - ETC... -
// To access the datebase

public function get_rows_from_db_as_associative_array() {
    global $wpdb;

    $sql = "SELECT * FROM `" . $wpdb->prefix . "custom_table_name` WHERE db_field_text = 'SOME TEXT' ORDER BY db_field_mediumint ASC";

    return $wpdb->get_results( $sql, ARRAY_A );
}

public function insert_row_to_db() {
    global $wpdb;

    $table = $wpdb->prefix . 'custom_table_name';
    $data = array(
        'db_field_tinytext'     => 'SOME TEXT',
        'db_field_datetime'     => date( 'Y-m-d H:i:s' ),
        'db_field_varchar'      => 'SOME OTHER TEXT',
        'db_field_mediumint'    => 123,
        'db_field_text'         => 'LONGER TEXT',
    );
    $format = array( '%s','%s', '%s', '%d', '%s' );

    $wpdb->insert( $table, $data, $format );

    return $wpdb->insert_id;
}

public function update_row_in_db() {
    global $wpdb;

    $table = $wpdb->prefix . 'custom_table_name';
    $data = array(
        'db_field_tinytext'     => 'SOME TEXT',
        'db_field_datetime'     => date( 'Y-m-d H:i:s' ),
        'db_field_varchar'      => 'SOME OTHER TEXT',
        'db_field_mediumint'    => 123,
        'db_field_text'         => 'LONGER TEXT',
    );
    $format = array( '%s','%s', '%s', '%d', '%s' );
    $where = array(
        'ID' => 1
    );
    $where_format = array(
        '%d'
    );

    return $wpdb->update( $table, $data, $where, $format, $where_format );

}

public function delete_row_from_db() {
    global $wpdb;

    $table = $wpdb->prefix . 'custom_table_name';

    $where = array(
        'ID' => 1
    );
    $where_format = array(
        '%d'
    );

    return $wpdb->delete( $table, $where, $where_format );

}

public function use_prepare_db_query() {
    global $wpdb;

    $sql = "UPDATE $wpdb->posts SET post_parent = %d WHERE ID = %d AND post_status = %s";

    return $wpdb->query( $wpdb->prepare( $sql, 7, 15, 'static' ) );

}
