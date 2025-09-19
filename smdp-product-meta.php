<?php

/**
 * Plugin Name: SMDP: Product Meta
 * Plugin URI: https://github.com/NesarAhmedRazon/simple-sourcig-data
 * Description: A plugin to add custom Meta Data for Wc Product.
 * Version: 0.0.1
 * Author: Nesar Ahmed
 * Author URI: https://nesarahmed.dev/
 * License: GPLv2 or later
 * Text Domain: smdp-product-meta
 * Domain Path: /languages/
 */


// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


if (!defined('SMDP_SOURCING_META_DATA_DOMAIN')) {
    define('SMDP_SOURCING_META_DATA_DOMAIN', 'smdp-product-meta');
}

if (!defined('SMDP_SOURCING_META_DATA_DIR')) {
    define('SMDP_SOURCING_META_DATA_DIR', plugin_dir_path(__FILE__));
}

if (!defined('SMDP_SOURCING_META_DATA_URL')) {
    define('SMDP_SOURCING_META_DATA_URL', plugin_dir_url(__FILE__));
}

if (!defined('SMDP_SOURCING_META_DATA_FILE')) {
    define('SMDP_SOURCING_META_DATA_FILE', __FILE__);
}

add_action('woocommerce_init', 'smdpsmd_wooReady');
function smdpsmd_wooReady()
{
    require_once SMDP_SOURCING_META_DATA_DIR . 'sourcing-data.php';
}