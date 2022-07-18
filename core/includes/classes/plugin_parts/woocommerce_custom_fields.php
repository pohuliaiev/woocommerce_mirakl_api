<?php
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');
// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields()
{
    global $woocommerce, $post;
    echo '<div class="product_custom_field">';
    // Custom Product Text Field
    woocommerce_wp_text_input(
        array(
            'id' => '_mirakl_sku',
            'placeholder' => 'Mirakl SKU',
            'label' => __('Mirakl SKU', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );
    echo '</div>';
}

function woocommerce_product_custom_fields_save($post_id)
{
    // Custom Product Text Field
    $woocommerce_custom_product_text_field = $_POST['_mirakl_sku'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, '_mirakl_sku', esc_attr($woocommerce_custom_product_text_field));
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
function my_custom_checkout_field_display_admin_order_meta( $order ){
    $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
    echo '<p><strong>'.__('Source').':</strong> ' . get_post_meta( $order_id, 'order_source', true ) . '</p>';
}