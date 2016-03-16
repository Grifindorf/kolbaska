<?php

define('_JEXEC', 1);

ini_set('display_errors', 'On');

define('JPATH_BASE', dirname(__FILE__) . '/../../../../../../' );

define('DS', DIRECTORY_SEPARATOR);


require_once('/home/www/dexline.com.ua/'.DS.'includes'.DS.'defines.php');

require_once('/home/www/dexline.com.ua/'.'includes'.DS.'framework.php');

$db = JFactory::getDbo();


$xml_file = simplexml_load_string(file_get_contents('http://ws.dexline.com.ua:8888/price/PriceListComplectXML'));
$count_xml = count($xml_file);

$query = "DELETE FROM `#__kitsdb_kit`";
$db->setQuery($query);
$results = $db->execute();
$query = "DELETE FROM `#__kitsdb`";
$db->setQuery($query);
$results = $db->execute();


foreach($xml_file as $komplekt){
    $query = "INSERT INTO `j2x5i_kitsdb`(`id`, `typesearch`) VALUES ({$komplekt->complekt_id},3)";
    $db->setQuery($query);
    $results = $db->execute();
    foreach($komplekt->complekt_det as $complekt_det){
        foreach($complekt_det->goods as $complekt_goods){
            if($complekt_goods->main == 'true'){
                $query = "SELECT product_id FROM #__jshopping_products WHERE product_ean = '{$complekt_goods->goods_id}'";
                $db->setQuery($query);
                $product_id = $db->loadResult();
                $main_product = $product_id;
            }
        }
        foreach($complekt_det->goods as $complekt_goods){
            if($complekt_goods->main != 'true') {
                $query = "SELECT product_id FROM #__jshopping_products WHERE product_ean = '{$complekt_goods->goods_id}'";
                $db->setQuery($query);
                $product_id = $db->loadResult();
                $price = (string)$complekt_goods->price;
                $price = str_replace(',','.',$price);
                $array = array();
                $array[$product_id]=$price;
                $type_insert = json_encode($array);
                $query = "INSERT INTO `#__kitsdb_kit` (`typesearch`, `product_id`, `kit_id`, `bonusproducts`) VALUES (3,{$main_product},{$komplekt->complekt_id},".$db->quote($type_insert).")";
                $db->setQuery($query);
                $results = $db->execute();
            }
        }
    }

}

mail('v.repij@gmail.com','Обновление комплектов',$count_xml);
mail('price@dexline.com.ua','Обновление комплектов',$count_xml);