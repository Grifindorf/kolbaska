<?php

define('_JEXEC', 1);

ini_set('display_errors', 'On');

define('JPATH_BASE', dirname(__FILE__) . '/../../../../../../' );

define('DS', DIRECTORY_SEPARATOR);

require_once('/var/www/kolbaska/includes/defines.php');
require_once('/var/www/kolbaska/includes/framework.php');

$db = JFactory::getDbo();

$query = "SELECT * FROM `#__jshopping_providers` ";
$db->setQuery($query);
$providers = $db->loadObjectlist();

/*$query = "SELECT * FROM `#__jshopping_providers` WHERE `manufacturer_id` IN (11,12)";
$db->setQuery($query);
$providers = $db->loadObjectlist();*/

foreach($providers as $provider){

    $currency = 1;
    if($provider->currency != 1){
        $query = "SELECT `value` FROM `#__currency` WHERE `id` = '{$provider->currency}' ";
        $db->setQuery($query);
        $currency = $db->loadResult();
    }

    $query = "SELECT `product_id`,`product_original_price` FROM `#__jshopping_products` WHERE `product_provider_id` = '{$provider->manufacturer_id}' ";
    $db->setQuery($query);
    $products = $db->loadObjectList();
    foreach($products as $product){
        $product_original_price = $product->product_original_price*$currency;
        $product_opt = round($product_original_price + (($product_original_price/100)*$provider->opt_percent),2);
        $product_rozn = round($product_original_price + (($product_original_price/100)*$provider->rozn_percent),2);

        $query = "UPDATE `#__jshopping_products` SET `product_price` = '{$product_opt}', `product_old_price` = '{$product_rozn}' WHERE `product_id` = '{$product->product_id}' ";
        $db->setQuery($query);
        $db->execute();
    }


}

?>