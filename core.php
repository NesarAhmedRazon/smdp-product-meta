<?php 

defined('ABSPATH') or die('No script kiddies please!');

function smdpicker_add_sourcing_status_metabox() {
    add_meta_box(
        'smdpicker_sourcing_status',
        'Sourcing Status',
        'smdpicker_sourcing_status_callback',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'smdpicker_add_sourcing_status_metabox');

function smdpicker_sourcing_status_callback($post) {
    $status = get_post_meta($post->ID, '_smdpicker_sourcing_status', true);
    $options = [
        
        'listed' => 'Listed',
        'ordered' => 'Ordered',
        'customer_ordered' => 'Customer Ordered',
    ];
    ?>
    <label for="smdpicker_sourcing_status">Select Status:</label>
    <select name="smdpicker_sourcing_status" id="smdpicker_sourcing_status">
        <option value="">-</option>
        <?php foreach ($options as $key => $label) : ?>
            <option value="<?php echo esc_attr($key); ?>" <?php selected($status, $key); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}


function smdpicker_save_sourcing_status($post_id) {
    if (isset($_POST['smdpicker_sourcing_status'])) {
        update_post_meta($post_id, '_smdpicker_sourcing_status', sanitize_text_field($_POST['smdpicker_sourcing_status']));
    }
}
add_action('save_post_product', 'smdpicker_save_sourcing_status');



add_action( 'woocommerce_single_product_summary', 'display_sourcing_status_on_product_page',5 );

function display_sourcing_status_on_product_page() {
    if ( is_singular( 'product' ) && current_user_can( 'manage_options' ) ) {
        $s_status = get_post_meta( get_the_ID(), '_smdpicker_sourcing_status', true );
        
        if ( $s_status && $s_status !== '-' ) {
            echo '<span class="posted_in" style="color:red;">'.esc_html( $s_status ).'</span>';
            
        }
    }
}