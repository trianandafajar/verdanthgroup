<?php
/**
 * Plugin Name: Hide Admin Bar (frontend)
 * Description: Hides the WordPress admin bar on the public site for all users.
 */
add_filter( 'show_admin_bar', '__return_false' );