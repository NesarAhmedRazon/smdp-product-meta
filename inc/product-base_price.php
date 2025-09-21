<?php
defined('ABSPATH') or die('No script kiddies please!');

add_action('woocommerce_product_options_general_product_data', 'add_originalPrice_field');

function add_originalPrice_field()
{
    global $post;
    $op = get_post_meta($post->ID, '_original_price', true);

    $html = '';
    $html .= '<div class="options_group smdp_op_field">';
    $html .= '<p class="form-field">';
    $html .= '<label for="smdp_op">' . __('Initial Price', SMDP_PM_TEXTDOMAIN) . '</label>';
    $html .= '<input class="input_field" type="text" name="original_price" id="original_price" value="' . esc_attr($op) . '" placeholder="' . __('0.00', SMDP_PM_TEXTDOMAIN) . '" />';
    // $html .= '<span class="description">' . __('Set the minimum order quantity for this product.', SMDP_PM_TEXTDOMAIN) . '</span>';
    $html .= '</p>';
    $html .= '</div>';
    echo $html;
}

// Save the custom field value to the product meta
add_action('woocommerce_process_product_meta', 'save_originalPrice_field');

function save_originalPrice_field($post_id)
{

    if (isset($_POST['original_price']) && !empty($_POST['original_price'])) {
        update_post_meta($post_id, '_original_price', sanitize_text_field($_POST['original_price']));
    }
}
