<?php

/**
 * Plugin Name:       TF GPG Frontend Pub Claims
 * Description:       This plugin contains functions for the Claims section of the Good Pub Guide Frontend site.
 * Version:           1.0.0
 * Author:            Thinking Fox
 * Author URI:        https://thinkingfox.com/
 */

// Shortcodes for claimed pubs dashboard area
include( plugin_dir_path( __FILE__ ) . '/shortcodes/dashboard/claimed-pubs.php');
include( plugin_dir_path( __FILE__ ) . '/shortcodes/dashboard/pub_dashboard.php');
include( plugin_dir_path( __FILE__ ) . '/shortcodes/dashboard/form-submit-message.php');
include( plugin_dir_path( __FILE__ ) . '/shortcodes/dashboard/get_url_parameter.php');

// Shortcodes for claimed pubs frontend area
include( plugin_dir_path( __FILE__ ) . '/shortcodes/frontend/claim_image_gallery.php');
include( plugin_dir_path( __FILE__ ) . '/shortcodes/frontend/image_field.php');
include( plugin_dir_path( __FILE__ ) . '/shortcodes/frontend/text_field.php');
include( plugin_dir_path( __FILE__ ) . '/shortcodes/frontend/checkbox_field.php');

// Shortcodes for claimed pubs admin area
include( plugin_dir_path( __FILE__ ) . '/shortcodes/admin/cpt_claim.php');
include( plugin_dir_path( __FILE__ ) . '/shortcodes/admin/acf_claim.php');