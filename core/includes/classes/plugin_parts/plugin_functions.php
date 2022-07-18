<?php
add_option('mirakl_last_updated','');
function woocommerce_mirakl_products(){
    $mirakl_products = [];
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_mirakl_sku', // the key 'specialkey'
                'compare' => '!=', // has a value that is equal to
                'value' => '' // hello world
            )
        )
    );

    $loop = get_posts( $args );
    foreach($loop as $products){
        $product = wc_get_product( $products->ID );
        array_push($mirakl_products, $product);
    }
    return $mirakl_products;
}



function mirakl_api(){
    $apiUrl = get_option( 'mirakl_plugin_settings' )['mirakl_site_url'];
    $apiKey = get_option( 'mirakl_plugin_settings' )['mirakl_api_key'];

    $apiClient = new Mirakl\MMP\Shop\Client\ShopApiClient($apiUrl, $apiKey, '2092');
    $arr = [];

    $startDate = '';
    if(!empty(get_option('mirakl_last_updated'))){
        $startDate =  get_option('mirakl_last_updated');
    }else{
        $startDate = '2022-05-16-10:00';
    }
    $endDate = date('Y-m-d-H:i');

    $GetOR = new Mirakl\MMP\Shop\Request\Order\Get\GetOrdersRequest(); //initiate request
    $GetOR->setOrderStates(['CLOSED','RECEIVED','SHIPPED','SHIPPING']);
    $GetOR->setStartDate( $startDate);
    $GetOR->setEndDate($endDate);
    $GetOR->setMax(100);
    $GetOR->setPaginate(false);
    // $GetOR->setOffset(100);
    $MiraklColl = $apiClient->getOrders($GetOR);
    if ($MiraklColl->getTotalCount() > 0) {
        $orders_arr = $MiraklColl->toArray();
        $x = -1;
        foreach($orders_arr as $item){

            $x += 1;

            $country = $item['customer']['billing_address']['country'];
           // $country_code = $item['customer']['billing_address']['country_iso_code'];
            $country_code = $item['shipping']['zone']['code'];
            $city = $item['customer']['billing_address']['city'];
            $firstname = $item['customer']['billing_address']['firstname'];
            $lastname = $item['customer']['billing_address']['lastname'];
            $adress = $item['customer']['billing_address']['street_1'];
            $adress2 = $item['customer']['billing_address']['street_2'];
            $zip = $item['customer']['billing_address']['zip_code'];
            $email = $item['customer_notification_email'];
            $date = $item['acceptance_decision_date'];

            $arr[$x]['country'] = $country;
            $arr[$x]['country_code'] = $country_code;
            $arr[$x]['city'] = $city;
            $arr[$x]['firstname'] = $firstname;
            $arr[$x]['lastname'] = $lastname;
            $arr[$x]['adress'] = $adress;
            $arr[$x]['adress2'] = $adress2;
            $arr[$x]['zip'] = $zip;
            $arr[$x]['email'] = $email;
            $arr[$x]['date'] = $date;


            $country_shipping = $item['customer']['shipping_address']['country'];
            $firstname_shipping = $item['customer']['shipping_address']['firstname'];
            $lastname_shipping = $item['customer']['shipping_address']['lastname'];
            $adress_shipping = $item['customer']['shipping_address']['street_1'];
            $adress2_shipping = $item['customer']['shipping_address']['street_2'];
            $zip_shipping = $item['customer']['shipping_address']['zip_code'];

            foreach ($item['order_lines'] as $order){
                $arr[$x]['product']['sku'] = $order['offer']['product']['sku'];//product should be an array
                $arr[$x]['product']['quantity'] = $order['quantity'];
            }
        }
    }
    else echo "No orders returned";
    return $arr;
}


function create_mirakl_order($adress,$product_id ='',$quantity='',$date =''){
    global $woocommerce;



    // Now we create the order
    $order = wc_create_order();

    // The add_product() function below is located in /plugins/woocommerce/includes/abstracts/abstract_wc_order.php
    $order->add_product( wc_get_product( $product_id ), $quantity ); // This is an existing SIMPLE product
    $order->set_address( $adress, 'billing' );
    $order->set_date_created( $date );
    //
    $order->calculate_totals();
    $order->update_status('completed', 'Order imported from shop-apotheke.com', TRUE);
    update_post_meta($order->get_id(), 'order_source', 'shop-apotheke.com');
}

function synchonize_mirakl_orders(){
    $mirakl_products = woocommerce_mirakl_products();
    $mirakl_products_array = mirakl_api();
    $x = -1;

    foreach($mirakl_products_array as $mirakl){
        $x += 1;

        foreach($mirakl_products as $item){
            global $product;
            if(get_post_meta($item->get_id(), '_mirakl_sku', true) == $mirakl['product']['sku']){
                $quantity = $mirakl_products_array[$x]['product']['quantity'];
                $date = $mirakl_products_array[$x]['date'];
                $adress = [];
                $adress['first_name'] = $mirakl_products_array[$x]['firstname'];
                $adress['last_name']  = $mirakl_products_array[$x]['lastname'];
                $adress['email']      = $mirakl_products_array[$x]['email'];
                $adress['address_1']  = $mirakl_products_array[$x]['adress'];
                $adress['address_2']  = $mirakl_products_array[$x]['adress2'];
                $adress['city']       = $mirakl_products_array[$x]['city'];
                $adress['postcode']   = $mirakl_products_array[$x]['zip'];
                $adress['country']    = $mirakl_products_array[$x]['country_code'];
                create_mirakl_order($adress, $item->get_id(), $quantity, $date);
                update_option( 'mirakl_last_updated', date('Y-m-d-H:i') );
            }
        }

    }
}
