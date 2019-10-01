<?php

/*****************
 * EXPORT TO CSV *
 * ------------- *
 *****************/

/**
 * For import a CSV, you could use post, see:
 * - handling_POST_request.php
 * - register_post_callback_without_ajax.php
 *
 * @link https://tommcfarlin.com/importing-csv-files-into-wordpress-visual-cues-part-1/
 */

 ////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

	private function define_public_hooks() {

        // ...

		$this->loader->add_action( 'admin_post_csv_export', $plugin_public, 'csv_export' );
		$this->loader->add_action( 'admin_post_nopriv_csv_export', $plugin_public, 'csv_export' );

        // ...

    }

/////////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

    /**
     * Convert array to CSV.
     * Array first "row" should be the "header".
     */
    public function array_to_csv( array &$array ) {

        if (count($array) == 0) {
            return null;
        }

        ob_start();

        $df = fopen("php://output", 'w');

        foreach ( $array as $row ) {
            fputcsv( $df, $row );
        }

        fclose($df);

        return ob_get_clean();

    }

    /**
     * Set csv file header without caching.
     */
    public function set_csv_header( $extra_file_name = 'csv_export' ) {

        $sitename = sanitize_key( get_bloginfo( 'name' ) );

        if ( ! empty( $sitename ) ) {
            $sitename .= '.';
        }

        $filename = $sitename . $extra_file_name . '.' . date( 'Y-m-d-H-i-s' ) . '.csv';
        $now = gmdate( "D, d M Y H:i:s" );

        // disable caching
        header( "Expires: Tue, 01 Jan 2000 00:00:00 GMT" );
        header( "Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate" );
        header( "Last-Modified: ". $now . " GMT" );
        header( "Content-Description: File Transfer" );
        header( "Pragma: no-cache" );

        // force download
        header( "Content-Type: application/force-download" );
        header( "Content-Type: application/octet-stream" );
        header( "Content-Type: application/download" );

        header( "Content-Disposition: attachment; filename=" . $filename );
        header( "Content-Transfer-Encoding: binary" );
        header( "Content-Type: text/csv; charset=" . get_option( 'blog_charset' ), true );

    }


    /**
     * Process content of CSV file
     *
     * @since 0.1
     */
    public function csv_export() {

        $this->set_csv_header( 'custom_csv_data_file_name' );

        /**
         * PROCESS YOUR VALUES EXAMPLE
         */
        // Add header to csv
        $header_values = array(
            array(
                'header_column_1',
                'header_column_2',
                'header_column_3',
            )
        );

        // Get only IDs from all test post type sort by title.
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'tests',
            'fields'           => 'ids',
            'orderby'          => 'title',
	        'order'            => 'ASC',
        );
        $tests = get_posts( $args );

        // Loop IDs and get valuses what we want.
        $tests_values = array();
        foreach ( $tests as $test ) {
            $test_meta = get_post_meta( $mitglieder_id );
            $tests_values[] = array(
                $test_meta['color_1'][0],
                $test_meta['color_2'][0],
                $test_meta['color_3'][0],
            );
        }

        // Merge them together.
        $fields = array_merge( $header_values, $tests_values );
        /**
         * END PROCESS YOUR VALUES EXAMPLE
         */

        echo $this->array_to_csv( $fields );

        exit;
    }

/////////////////////////////////////////////////////
// ADD TO SOMEWHERE

/*
 * You have to add a link to trigger export.
 *
 * You could use PLUGIN-NAME/ADMIN/PARTIALS/PLUGIN-NAME-ADMIN-DISPLAY.PHP
 * and add:
 * <a href="<?php echo admin_url( 'admin-post.php?action=csv_export' ); ?>" class="button button-primary" target="_blank">Export CSV</a>
 *
 * OR you can add to EXOPITE SIMPLE OPTIONS FRAMEWORK ADMIN MENU
 *    array(
 *        'id'          => 'export_csv',
 *        'type'        => 'button',
 *        'title'       => 'Export CSV',
 *        'options'     => array(
 *            'href'      => admin_url( 'admin-post.php?action=csv_export' ),
 *            'target'    => '_blank',
 *            'value'     => 'Export CSV',
 *            'btn-class' => 'exopite-sof-btn',
 *        ),
 *    ),
 */
