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
