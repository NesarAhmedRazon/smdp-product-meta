<?php
defined('ABSPATH') or die('No script kiddies please!');

add_action('woocommerce_product_options_general_product_data', 'add_moq_field');

function add_moq_field()
{
    global $post;
    $moq = get_post_meta($post->ID, '_smdp_moq', true) ? get_post_meta($post->ID, '_smdp_moq', true) : 1;

    $html = '';
    $html .= '<div class="options_group smdp_moq_field">';
    $html .= '<p class="form-field">';
    $html .= '<label for="smdp_moq">' . __('Set a MoQ', SMDP_PM_TEXTDOMAIN) . '</label>';
    $html .= '<input class="input_field" type="text" pattern="[0-9]"  name="smdp_moq" id="smdp_moq" value="' . esc_attr($moq) . '" placeholder="' . __('Set a moq', SMDP_PM_TEXTDOMAIN) . '" />';
    // $html .= '<span class="description">' . __('Set the minimum order quantity for this product.', SMDP_PM_TEXTDOMAIN) . '</span>';
    $html .= '</p>';
    $html .= '</div>';
    echo $html;
}

// Save the custom field value to the product meta
add_action('woocommerce_process_product_meta', 'save_moq_field');

function save_moq_field($post_id)
{
    $moq = 1;
    if (isset($_POST['smdp_moq']) && !empty($_POST['smdp_moq'])) {
        $moq = $_POST['smdp_moq'];
        update_post_meta($post_id, '_smdp_moq', $moq);
    }
}


// Display the custom field value on the single product page
function smdpicker_display_moq()
{

    global $post;
    $moq = get_post_meta($post->ID, '_smdp_moq', true);

    if (!empty($moq) && intval($moq) > 1) {
        echo '<p style="color: #d35400; font-weight: bold;">Minimum Order Quantity: ' . esc_html($moq) . '</p>';
    }
}
add_action('woocommerce_single_product_summary', 'smdpicker_display_moq', 25);

// Enforce the minimum order quantity
function smdpicker_enforce_moq($passed, $product_id, $quantity)
{
    $moq = get_post_meta($product_id, '_smdp_moq', true);

    if (!empty($moq) && $quantity < $moq) {
        wc_add_notice(sprintf('The minimum order quantity for this product is %d.', $moq), 'error');
        return false;
    }

    return $passed;
}
add_filter('woocommerce_add_to_cart_validation', 'smdpicker_enforce_moq', 10, 3);
