<?php

define('_JEXEC', 1);

ini_set('display_errors', 'On');

define('JPATH_BASE', dirname(__FILE__) . '/../../../../../../' );

define('DS', DIRECTORY_SEPARATOR);


require_once('/home/www/dexline.com.ua/'.DS.'includes'.DS.'defines.php');

require_once('/home/www/dexline.com.ua/'.'includes'.DS.'framework.php');

$db = JFactory::getDbo();

$xml_file = simplexml_load_string(file_get_contents('http://ws.dexline.com.ua:8888/price/PriceListXML'));
$count_xml = count($xml_file);

if ( $count_xml > 10000 ) {

    $query = "TRUNCATE TABLE `#__temporary_products`";
    $db->setQuery($query);
    $db->execute();
    $array_error = array();
    foreach ($xml_file as $good) {
        if(substr($good->size,0,1)==','){
            $good->size = '0'.$good->size;
            $good->size = str_replace(',','.',$good->size);
        }else{
            $good->size = str_replace(',','.',$good->size);
        }
        if(substr($good->weight,0,1)==','){
            $good->weight = '0'.$good->weight;
            $good->weight = str_replace(',','.',$good->weight);
        }else{
            $good->weight = str_replace(',','.',$good->weight);
        }
        $query = "INSERT INTO `#__temporary_products`(`goods_id`, `goods`, `brand`, `price`, `rasprod`, `weight`, `size`, `termin`, `status`) VALUES (" . $db->quote($good->goods_id) . "," . $db->quote($good->goods) . "," . $db->quote($good->brand) . "," . $db->quote(str_replace(',', '.', $good->price)) . "," . $good->rasprod . "," . $good->weight . "," . $good->size . "," . $good->termin . "," . $good->status . ")";
        $db->setQuery($query);
        if ($db->execute()) {
        } else {
            $array_error[] = $good->goods_id;
        }
    }

    $query = "UPDATE `#__jshopping_products` SET `status` = 0, `unlimited` = 0, `product_quantity`=0 ";
    $db->setQuery($query);
    if ($db->execute()) {
    }

    $query = "UPDATE `#__jshopping_products` jsp inner join `#__temporary_products` tp on jsp.product_ean=tp.goods_id SET jsp.`product_quantity` = tp.`status`, jsp.`unlimited` = tp.`status`, jsp.`product_price` = tp.`price`, jsp.`termin` = tp.`price`, jsp.`status` = tp.`status`, jsp.`rasprod` = tp.`rasprod`, jsp.`weight` = tp.`weight`, jsp.`size` = tp.`size`, jsp.`lastupdate` = ".$db->quote(date('Y-m-d H:i:s'))." ";
    $db->setQuery($query);
    if ($db->execute()) {
    } else {
    }

    $query = "SELECT DISTINCT `product_ean` FROM `#__jshopping_products` WHERE `product_ean` IN (SELECT goods_id FROM `#__temporary_products`)";
    $db->setQuery($query);
    $result_ids = $db->LoadColumn();

    $query = "SELECT goods_id FROM `#__temporary_products`";
    $db->setQuery($query);
    $result_tmpids = $db->LoadColumn();

    $query = "TRUNCATE TABLE `#__products_to_import_vr`";
    $db->setQuery($query);
    $db->execute();
    foreach (array_diff($result_tmpids, $result_ids) as $ids) {
        $query = "INSERT INTO `#__products_to_import_vr` (`id`) VALUES (" . $db->quote($ids) . ")";
        $db->setQuery($query);
        $db->execute();
    }

    $query = "INSERT INTO `#__jshopping_products` (`product_ean`, `product_quantity`, `unlimited`, `product_date_added`, `product_publish`, `currency_id`, `product_price`, `product_manufacturer_id`, `name_ru-RU`, `alias_ru-RU`, `termin`, `status`, `rasprod`, `weight`, `size`, `lastupdate`)
    SELECT tp.`goods_id`, tp.`status`, tp.`status`, CURDATE(), 1, 2, tp.`price`, 1, tp.`goods`, tp.`goods_id`, tp.`termin`, tp.`status`, tp.`rasprod`, tp.`weight`, tp.`size`, CURDATE()
    FROM `#__temporary_products` as tp WHERE tp.goods_id IN ( SELECT id FROM `#__products_to_import_vr` ) ";
    $db->setQuery($query);
    if ($db->execute()) {
    }

    $query = "SELECT product_id FROM `#__jshopping_products` WHERE product_ean IN ( SELECT id FROM `#__products_to_import_vr` )";
    $db->setQuery($query);
    $result_tmpids = $db->LoadColumn();

    foreach ($result_tmpids as $catid) {
        $query = "INSERT INTO `#__jshopping_products_to_categories` (`product_id`,`category_id`) VALUES (" . $db->quote($catid) . ",3)";
        $db->setQuery($query);
        $db->execute();
    }

    $query = "UPDATE `#__vrupdate` SET `date_last_update` = " . $db->quote(date('Y-m-d H:m:s')) . ", `count_products_in_price` = " . $count_xml . " WHERE id = 1";
    $db->setQuery($query);
    if ($db->execute()) {
    }

    $query = "SELECT * FROM `#__jshopping_categories`
          WHERE category_parent_id = 0 ";
    $db->setQuery($query);
    $result = $db->LoadObjectList();
    foreach ($result as $res) {
        $query = "SELECT * FROM `#__jshopping_categories`
          WHERE category_parent_id = ".$res->category_id." ";
        $db->setQuery($query);
        $cats1 = $db->LoadObjectList();
        foreach ($cats1 as $cat1) {
            $query = "SELECT * FROM `#__jshopping_categories`
                WHERE category_parent_id = ".$cat1->category_id." ";
            $db->setQuery($query);
            $cats2 = $db->LoadObjectList();
            foreach ($cats2 as $cat2) {

                $query = "SELECT * FROM `#__jshopping_products` as jsp
                    INNER JOIN `#__jshopping_products_to_categories` as jspc USING (product_id)
                    WHERE jsp.status = 1 AND jspc.category_id = ".$cat2->category_id." ";
                $db->setQuery($query);
                $cats3P = $db->LoadObjectList();

                if ($cat2->category_publish==1) {
                    if (count($cats3P)==0) {
                        $query = "SELECT `name_ru-RU` FROM `#__jshopping_categories`
                            WHERE category_id = ".$cat2->category_id." ";
                        $db->setQuery($query);
                        $array_category_disabled[] = $db->LoadResult();
                        $query = "UPDATE `#__jshopping_categories` SET `category_publish` = 0 WHERE category_id = ".$cat2->category_id." ";
                        $db->setQuery($query);
                        $db->execute();
                    } else {
                        $query = "UPDATE `#__jshopping_categories` SET `category_publish` = 1 WHERE category_id = ".$cat2->category_id." ";
                        $db->setQuery($query);
                        $db->execute();
                    }
                } else {
                    if (count($cats3P)==0) {

                    } else {
                        $query = "SELECT `name_ru-RU` FROM `#__jshopping_categories`
                            WHERE category_id = ".$cat2->category_id." ";
                        $db->setQuery($query);
                        $array_category_enabled[] = $db->LoadResult();
                        $query = "UPDATE `#__jshopping_categories` SET `category_publish` = 1 WHERE category_id = ".$cat2->category_id." ";
                        $db->setQuery($query);
                        $db->execute();
                    }
                }


                /*foreach ($cats3 as $cat3) {
                    $query = "SELECT * FROM `#__jshopping_products` as jsp
                    INNER JOIN `#__jshopping_products_to_categories` as jspc USING (product_id)
                    WHERE jsp.status = 1 AND jspc.category_id = ".$cat3->category_id." ";
                    $db->setQuery($query);
                    $cats3P = $db->LoadObjectList();
                    if ($cat3->category_id==531) {
var_dump($cats3P);
                        die;
                    }
                    if (count($cats3P)==0) {
                        $query = "SELECT `name_ru-RU` FROM `#__jshopping_categories`
                            WHERE category_id = ".$cat3->category_id." ";
                        $db->setQuery($query);
                        $array_category_disabled[] = $db->LoadResult();
                        $query = "UPDATE `#__jshopping_categories` SET `category_publish` = 0 WHERE category_id = ".$cat3->category_id." ";
                        $db->setQuery($query);
                        $db->execute();
                    }
                }*/
            }
        }
    }

}

$message = "Количество товаров в прайсе: ".$count_xml."\r\n";
$message .= "Количество добавленных товаров: ".count(array_diff($result_tmpids, $result_ids))."\r\n";
$message .= "Количество обновленных товаров: ".($count_xml-count(array_diff($result_tmpids, $result_ids)))."\r\n";
$message .= "Количество ошибок: 0 \r\n";
foreach ($array_category_enabled as $category) {
    $message .= "В категории '".$category."' нет товаров. Снята с публикации. \r\n";
}
foreach ($array_category_disabled as $category) {
    $message .= "В категории '".$category."' появились товары. Опубликована. \r\n";
}
mail('v.repij@gmail.com','Обновление товаров',$message);
mail('price@dexline.com.ua','Обновление товаров',$message);