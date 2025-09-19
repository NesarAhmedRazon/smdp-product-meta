<?php 
defined('ABSPATH') or die('No script kiddies please!');

add_action('woocommerce_product_options_general_product_data', 'add_moq_field');

function add_moq_field() {
    global $post;
    $moq = get_post_meta($post->ID, '_smdp_moq', true) ? get_post_meta($post->ID, '_smdp_moq', true) : 1;
   
    echo '<div class="smdpicker_documents">'; 
    echo '<p class="smdpicker_documents_title">' . __('Set MoQ', 'smd-picker-extension') . '</p>';
    echo '<div class="smdpicker_documents_fields">';
        echo '<div class="smdpicker_documents_field">';
            echo '<div class="smdpicker_documents_field_container">';
                echo '<input class="input_field" type="number" name="smdp_moq" id="smdp_moq" value="' . esc_attr($moq) . '" placeholder="' . __('Set x a moq', 'smd-picker-extension') . '" />';
            echo '</div>';
        echo '</div>';
    echo '</div>';
    echo '</div>';
    
}

// Save the custom field value to the product meta
add_action('woocommerce_process_product_meta', 'save_moq_field');

function save_moq_field($post_id) {
   $moq = 1;
    if (isset($_POST['smdp_moq']) && !empty($_POST['smdp_moq'])) { 
        $moq = $_POST['smdp_moq'];
        update_post_meta($post_id, '_smdp_moq', $moq); 
    }
}


// Display the custom field value on the single product page
function smdpicker_display_moq() {
    
        global $post;
        $moq = get_post_meta($post->ID, '_smdp_moq', true);
        
        if (!empty($moq) && intval($moq) > 1) {
            echo '<p style="color: #d35400; font-weight: bold;">Minimum Order Quantity: ' . esc_html($moq) . '</p>';
        }
    

    
}
add_action('woocommerce_single_product_summary', 'smdpicker_display_moq', 25);

// Enforce the minimum order quantity
function smdpicker_enforce_moq($passed, $product_id, $quantity) {
    $moq = get_post_meta($product_id, '_smdp_moq', true);
    
    if (!empty($moq) && $quantity < $moq) {
        wc_add_notice(sprintf('The minimum order quantity for this product is %d.', $moq), 'error');
        return false;
    }
    
    return $passed;
}
add_filter('woocommerce_add_to_cart_validation', 'smdpicker_enforce_moq', 10, 3);
