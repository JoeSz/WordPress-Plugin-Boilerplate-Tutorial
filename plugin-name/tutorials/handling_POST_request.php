<?php

/**
 * May override the default upload path.
 * /wp-content/uploads/{folder}/
 *
 * @param   array   $dir
 * @return  array
 */
function change_upload_dir( $dir ) {
    return array(
        'path'   => $dir['basedir'] . '/{folder}',
        'url'    => $dir['baseurl'] . '/{folder}',
        'subdir' => '/{folder}',
    ) + $dir;
}

function handling_POST_request() {

    // Code to handle POST request...

    /*
     * May check nonce
     */
    $nonce = $_POST['_nonce'];

    if ( ! isset( $nonce ) || ! wp_verify_nonce($nonce, 'post_first' ) ) {
        // Nonce not match
        // Diplay some error
        wp_redirect( home_url() . '/desired_url' );
        exit;
    }

    /*
     * May prehandle file upload
     */

    // Set an array containing a list of acceptable formats
    $allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');

    // Check file types
    foreach( $_FILES as $file ) {

        // Get the type of the uploaded file. This is returned as "type/extension"
        $arr_file_type = wp_check_filetype( basename( $file['name'] ) );
        $uploaded_file_type = $arr_file_type['type'];

        if( ! in_array( $uploaded_file_type, $allowed_file_types ) ) {
            // Diplay some error
            wp_redirect( home_url() . '/desired_url' );
            exit;
        }

    }

    /*
     * May insert Post and meta
     *
     * @link https://wordpress.stackexchange.com/questions/106973/wp-insert-post-or-similar-for-custom-post-type
     */
    $post_id = wp_insert_post(array (
       'post_type' => 'post',
       'post_title' => sanitize_title( $_POST['title'] ),
       'post_status' => 'publish',
       'comment_status' => 'closed',   // if you prefer
       'ping_status' => 'closed',      // if you prefer
    ));

    if ( $post_id ) {

        // insert some post meta
        add_post_meta( $post_id, 'custom_filed_name', esc_html( $_POST['custom_filed_name'] ) );

    } else {
        // Con not insert post
        // Diplay some error
        wp_redirect( home_url() . '/desired_url' );
        exit;
    }

    /*
     * May handle the file upload after post inserted
     */

    /*
     * May register path override.
     *
     * @link https://wordpress.stackexchange.com/questions/141088/wp-handle-upload-how-to-upload-to-a-custom-subdirectory-within-uploads
     * @link https://premium.wpmudev.org/blog/upload-file-functions/?omtr=b&utm_expid=3606929-101.TxEXoCfwS1KxJG1JVvj_5Q.1&utm_referrer=https%3A%2F%2Fwww.google.de%2F
     * @link https://wordpress.stackexchange.com/questions/4307/how-can-i-add-an-image-upload-field-directly-to-a-custom-write-panel
     */
    add_filter( 'upload_dir', array( $this, 'change_upload_dir' ) );

    if( ! isset( $_FILES ) || empty( $_FILES ) ) {
        // There is nothing in $_FILES array.
        // Diplay some error
        wp_redirect( home_url() . '/desired_url' );
        exit;
    }

    // Check if wp_handle_upload WordPress function is exist, file is included
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }

    $upload_overrides = array( 'test_form' => false );

    foreach( $_FILES as $file ) {

        $uploadedfile = array(
            'name'     => $file['name'],
            'type'     => $file['type'],
            'tmp_name' => $file['tmp_name'],
            'error'    => $file['error'],
            'size'     => $file['size'],
        );

        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

        if ( $movefile && ! isset( $movefile['error'] ) ) {

            /*
             * May add uploaded file to meta
             */
            add_post_meta( $post_id, 'meta_name', $movefile['url'] );


            /*
             * May add uploaded file to media library
             *
             * @link https://wordpress.stackexchange.com/questions/60802/how-to-register-images-uploaded-via-ftp-in-media-library?rq=1
             */
            $attachment = array(
                'guid'           => $movefile['url'],
                'post_mime_type' => $file['type'],

                // keep original name for title, eg.: test-pdf not test-1.pdf, etc...
                'post_title'     => sanitize_title( $file['name'] ),

                'post_status'    => 'inherit',
                'post_date'      => date('Y-m-d H:i:s')
            );

            $attachment_id = wp_insert_attachment( $attachment, $movefile['file'] );
            $attachment_data = wp_generate_attachment_metadata( $attachment_id, $movefile['file'] );
            wp_update_attachment_metadata( $attachment_id, $attachment_data );

        } else {
            // File can not be uploaded
            // Diplay some error
        }

    }

    /*
     * May deregister path override, if registered before
     */
    remove_filter( 'upload_dir', array( $this, 'change_upload_dir' ) );


}
