<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function smdp_repeatable_meta_box_enqueue()
{
    wp_enqueue_script('smdp-repeatable-meta-box', SMDP_PRODUCT_META_URL . 'assets/js/wc-repeatable-meta.js', ['jquery'], time(), true);
    wp_enqueue_style('smdp-repeatable-meta-box',  SMDP_PRODUCT_META_URL . 'assets/style/wc-repeatable-meta.css', time());
}
add_action('admin_enqueue_scripts', 'smdp_repeatable_meta_box_enqueue');




// change sequence of tabs
add_filter('woocommerce_product_tabs', 'modify_additional_information_tab', 98);
function modify_additional_information_tab($tabs)
{
    $tab_changed = (bool)get_post_meta(get_the_ID(), '_tab_changed', true);
    // if ( is_singular( 'product' ) && current_user_can( 'manage_options' ) ) {


    // }

    if (isset($tabs['additional_information'])) {
        $tabs['additional_information']['title'] = __('<strong>স্পেসিফিকেশন</strong>', SMDP_PM_TEXTDOMAIN); // Change the title here.
        $tabs['additional_information']['priority'] = 10;
    }
    if (isset($tabs['description'])) {
        $tabs['description']['title'] = __('<strong>বিবরণ</strong>', SMDP_PM_TEXTDOMAIN);
        $tabs['description']['priority'] = $tab_changed ? 9 : 20;
    }
    return $tabs;
}


// add custom tab to product page 
add_filter('woocommerce_product_tabs', 'add_custom_product_tab', 98);
function add_custom_product_tab($tabs)
{
    // check for a product meta with key 'product_datasheet'
    $product_datasheet = get_post_meta(get_the_ID(), '_extra_documents', true);
    //var_dump($product_datasheet);
    // if the product meta exists, add a new tab
    if (!empty($product_datasheet)) {
        $tabs['datasheet'] = array(
            'title'    => __('<strong>অন্যান্য ডকুমেন্ট</strong>', SMDP_PM_TEXTDOMAIN),
            'priority' => 31,
            'callback' => 'custom_product_tab_content'
        );
    }
    return $tabs;
}


function custom_product_tab_content()
{ // 
    $documents = get_post_meta(get_the_ID(), '_extra_documents', true);
    if (!empty($documents)) {
        echo '<h3>' . __('Reference', SMDP_PM_TEXTDOMAIN) . '</h3>';
        foreach ($documents as $document) {
            echo '<a href="' . esc_url($document['url']) . '" target="_blank" title="' . esc_html($document['label']) . '">' . esc_html($document['label']) . '</a>';
            if (next($documents)) {
                echo ', ';
            }
        }
    }
}



add_action('woocommerce_product_options_general_product_data', 'add_custom_field_to_product_data');

function add_custom_field_to_product_data()
{
    global $post;
    $documents = get_post_meta($post->ID, '_extra_documents', true);

    $tab_changed = (bool)get_post_meta($post->ID, '_tab_changed', true) ? ' checked' : '';


    echo '<div class="smdpicker_meta_wrapper">';

    echo '<p class="smdpicker_meta_title">' . __('Description in First Tab', SMDP_PM_TEXTDOMAIN) . '</p>';
    echo '<div class="smdpicker_meta_fields">';
    echo '<input' . $tab_changed . ' class="smdpicker_meta_field_input" type="checkbox" id="tab_changed" name="tab_changed" value="true"><label class="smdpicker_meta_field_label" for="Yes">Yes</label>';
    echo '</div>';
    echo '</div>';
    // Custom fields for product data panel 
    echo '<div class="smdpicker_documents smdpicker_meta_wrapper" id="repeatable-fieldset-one" data-index="1">';
    echo '<p class="smdpicker_documents_title">' . __('Extra Documents', SMDP_PM_TEXTDOMAIN) . '</p>';
    echo '<div class="smdpicker_documents_fields">';
    if (!empty($documents)) {
        foreach ($documents as $index => $document) {
            echo '<div class="smdpicker_documents_field repeatable">';
            echo '<div class="smdpicker_documents_field_container">';
            echo '<input class="input_field" type="text" name="repeatable_name[]" id="repeatable_name_' . $index . '" value="' . esc_attr($document['label']) . '" placeholder="' . __('Document Name', SMDP_PM_TEXTDOMAIN) . '" />';
            echo '<input class="input_field" type="url" name="repeatable_url[]" id="repeatable_url_' . $index . '" value="' . esc_url($document['url']) . '" placeholder="' . __('URL', SMDP_PM_TEXTDOMAIN) . '" />';
            echo '</div>';
            echo '<button type="button" class="notice-dismiss remove-document dashicons dashicons-no-alt"></button>';
            echo '</div>'; // Row ends here
        }
    } else {
        echo '<div class="smdpicker_documents_field repeatable">';
        echo '<div class="smdpicker_documents_field_container">';
        echo '<input class="input_field" type="text" name="repeatable_name[]" id="repeatable_name_0" placeholder="' . __('Document Name', SMDP_PM_TEXTDOMAIN) . '" />';
        echo '<input class="input_field" type="url" name="repeatable_url[]" id="repeatable_url_0" placeholder="' . __('URL', SMDP_PM_TEXTDOMAIN) . '" />';
        echo '</div>';
        echo '<button type="button" class="notice-dismiss remove-document dashicons dashicons-no-alt"></button>';
        echo '</div>'; // Row ends here
    }

    echo '<button type="button" class="button add-documents">' . __('Add Document', SMDP_PM_TEXTDOMAIN) . '</button>';
    echo '</div>';

    echo '</div>';
}


// Save the custom field value to the product meta
add_action('woocommerce_process_product_meta', 'save_custom_field_value');
function save_custom_field_value($post_id)
{
    $documents = [];
    if (isset($_POST['repeatable_name']) && isset($_POST['repeatable_url'])) {
        $names = $_POST['repeatable_name'];
        $urls = $_POST['repeatable_url'];
        foreach ($names as $index => $name) {
            if (!empty($name) && !empty($urls[$index])) {
                $documents[] = [
                    'label' => sanitize_text_field($name),
                    'url' => esc_url_raw($urls[$index]),
                ];
            }
        }
        update_post_meta($post_id, '_extra_documents', $documents);
    }
    if (isset($_POST['tab_changed'])) {
        update_post_meta($post_id, '_tab_changed', true);
    } else {
        update_post_meta($post_id, '_tab_changed', false);
    }
}
