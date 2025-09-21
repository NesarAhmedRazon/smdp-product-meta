<?php 
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}   

add_filter('woodmart_product_label_output', 'custom_woodmart_modify_sold_out_label', 10, 1 );

function custom_woodmart_modify_sold_out_label($output)
{
    // Loop through the label output and replace 'Sold out' with 'TBA'
    foreach ($output as $key => $label) {
        // Check if the label contains 'Sold out'
        if (strpos($label, 'Sold out') !== false) {
            // Replace 'Sold out' with 'TBA'
            $output[$key] = str_replace('Sold out', 'স্টক আসবে', $label);
        }
    }

    return $output;
}
