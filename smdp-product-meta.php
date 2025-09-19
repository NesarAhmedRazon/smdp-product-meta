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


if (!defined('SMDP_PRODUCT_META_DOMAIN')) {
    define('SMDP_PRODUCT_META_DOMAIN', 'smdp-product-meta');
}

if (!defined('SMDP_PRODUCT_META_DIR')) {
    define('SMDP_PRODUCT_META_DIR', plugin_dir_path(__FILE__));
}

if (!defined('SMDP_PRODUCT_META_URL')) {
    define('SMDP_PRODUCT_META_URL', plugin_dir_url(__FILE__));
}

if (!defined('SMDP_PRODUCT_META_FILE')) {
    define('SMDP_PRODUCT_META_FILE', __FILE__);
}

add_action('woocommerce_init', 'smdpsmd_wooReady');
function smdpsmd_wooReady()
{
    require_once SMDP_PRODUCT_META_DIR . 'sourcing-data.php';
    require_once SMDP_PRODUCT_META_DIR . 'product-moq.php';
}