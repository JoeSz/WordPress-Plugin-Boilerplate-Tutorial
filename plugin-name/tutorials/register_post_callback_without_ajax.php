<?php

/**************************
 * REGISTER AJAX CALLBACK *
 * ---------------------- *
 **************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_admin_hooks() {

    // ...

    /*
     * Handle POST
     */
    $this->loader->add_action('admin_post_post_first', $plugin_admin, 'post_first');
    $this->loader->add_action('admin_post_nopriv_post_first', $plugin_admin, 'post_first');

}

///////////////////
// ADD TO YOUR FORM
// EG: admin/partials/plugin-name-admin-display.php

/**
 * @link https://codex.wordpress.org/Creating_Options_Pages
 * @link https://www.smashingmagazine.com/2016/04/three-approaches-to-adding-configurable-fields-to-your-plugin/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;

// Generate a custom nonce value.
$_nonce = wp_create_nonce( 'my_post_form_nonce' );
?>
    <!--
        enctype="multipart/form-data" for files
        action:
        - you can add your target to process, eg: options.php (if it is an option page)
        - leave it empty if you want to porcess form in this files
        - set to esc_url( admin_url('admin-post.php') ) to process with a admin_post_/admin_post_nopriv_ hook
    -->
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>?action=post_first" method="post" enctype="multipart/form-data">
        <!-- action for admin_post_/admin_post_nopriv_ hook -->
        <input type="hidden" name="action" value="post_first">
        <input type="hidden" name="_nonce" value="<?php echo $_nonce; ?>" />
        <?php
            /**
             * The setting fields will know which settings your options page will handle.
             * After the opening form tag, add this function
             */
            settings_fields( $this->plugin_name );
            do_settings_sections( $this->plugin_name );
        ?>
        <!--
            your form fields if any,
            you could use add_settings_section hook
            @link https://codex.wordpress.org/Function_Reference/add_settings_section
        -->
        <?php
            submit_button( esc_attr__( 'Submit', $this->plugin_name ), 'primary', 'submit-name', TRUE );
        ?>
    </form>
<?php

// ... some php code ...

///////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php


public function post_first() {

    /*
     * Code to handle POST request...
     *
     * See tutorial file:
     * handling_POST_request.php
     */

}
