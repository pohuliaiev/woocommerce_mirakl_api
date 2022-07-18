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

    $apiClient = new Mirakl\MMP\Shop\Client\ShopApiClient($apiUrl, $apiKey, '2913');
    $arr = [];

    $startDate = '';
    if(!empty(get_option('mirakl_last_updated'))){
        $startDate =  get_option('mirakl_last_updated');
    }else{
        $startDate = '2022-05-16-10:00';
    }
    $endDate = date('Y-m-d-H:i');

    $GetOR = new Mirakl\MMP\Shop\Request\Order\Get\GetOrdersRequest(); //initiate request
    //$GetOR->setOrderStates(['CLOSED','RECEIVED','SHIPPED','SHIPPING']);
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

            /*
            foreach ($item['order_lines'] as $order){
                $arr[$x]['product']['sku'] = $order['offer']['product']['sku'];//product should be an array
                $arr[$x]['product']['quantity'] = $order['quantity'];
            }
            */
        }

        $y = -1;
        foreach($MiraklColl as $item){
            $n = -1;
            $y += 1;
            foreach($item->getOrderLines() as $order){
                $n += 1;
                $arr[$y]['products'][$n]['sku'] = $order->getOffer()->getProduct()->getSku();
                $arr[$y]['products'][$n]['quantity'] = $order->getQuantity();
                $price = $order->getOffer()->getPrice();
                $priceComma = str_replace('.', ',', $price);
                $arr[$y]['products'][$n]['price'] = $priceComma;
            }
        }
    }
    else echo "No orders returned";
    return $arr;
}

function synchonize_mirakl_orders(){
    $mirakl_products = woocommerce_mirakl_products();
    $mirakl_products_array = mirakl_api();
    $x = -1;

    foreach($mirakl_products_array as $mirakl){
        $x += 1;
        $order = wc_create_order();
        $adress = [];
        $adress['first_name'] = $mirakl['firstname'];
        $adress['last_name']  = $mirakl['lastname'];
        $adress['email']      = $mirakl['email'];
        $adress['address_1']  = $mirakl['adress'];
        $adress['address_2']  = $mirakl['adress2'];
        $adress['city']       = $mirakl['city'];
        $adress['postcode']   = $mirakl['zip'];
        $adress['country']    = $mirakl['country_code'];
        $order->set_address( $adress, 'billing' );
        $order->set_date_created( $date );
        $date = $mirakl['date'];
        $mirakl_cart = $mirakl['products'];
        foreach ($mirakl_cart as $inner_product){

            foreach($mirakl_products as $item){
                global $product;
                if(get_post_meta($item->get_id(), '_mirakl_sku', true) == $inner_product['sku']){
                    $quantity = $inner_product['quantity'];
                    $price = $inner_product['price'];
                    $mirakl_product = wc_get_product( $item->get_id() );
                    $mirakl_product->set_price( $price );
                    $order->add_product( $mirakl_product, $quantity );
                }
            }

        }
        $order->calculate_totals();
        $order->update_status('completed', 'Order imported from shop-apotheke.com', TRUE);
        update_option( 'mirakl_last_updated', date('Y-m-d-H:i') );
    }
}
