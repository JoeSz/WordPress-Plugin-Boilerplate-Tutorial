<?php


/*******************************************************
 * REGISTER METABOX FOR A CUSTOM POST TYPE (customers) *
 * --------------------------------------------------- *
 *******************************************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_admin_hooks() {

    // ...

    /**
     * Add metabox and register custom fields
     *
     * @link https://code.tutsplus.com/articles/rock-solid-wordpress-30-themes-using-custom-post-types--net-12093
     */
    $this->loader->add_action( 'admin_init', $plugin_admin, 'rerender_meta_options' );
    $this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_options' );

}

///////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php


// Save custom fields
public function save_meta_options() {

    global $post;
    update_post_meta($post->ID, "customer_id", $_POST["customer_id"]);
    update_post_meta($post->ID, "customer_address", $_POST["customer_address"]);

}

/* Create a meta box for our custom fields */
public function rerender_meta_options() {

    add_meta_box("customer-meta", "Customer Details", array($this, "dispaly_meta_options"), "customers", "normal", "low");

}

// Display meta box and custom fields
public function dispaly_meta_options() {

    global $post;
    $custom = get_post_custom($post->ID);

    $customer_id = $custom["customer_id"][0];
    ?>
    <label><?php _e( 'Customer ID:', $this->plugin_name ); ?></label><input name="customer_id" value="<?php echo $customer_id; ?>" /><br>
    <?php

    $customer_address = $custom["customer_address"][0];
    ?>
    <label><?php _e( 'Customer Address:', $this->plugin_name ); ?></label><textarea name="customer_address"><?php echo $customer_address; ?></textarea>
    <?php

}
