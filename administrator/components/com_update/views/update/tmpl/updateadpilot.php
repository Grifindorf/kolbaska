<?php

define('_JEXEC', 1);

ini_set('display_errors', 'On');

define('JPATH_BASE', dirname(__FILE__) . '/../../../../../../' );

define('DS', DIRECTORY_SEPARATOR);


require_once('/home/www/dexline.com.ua/'.DS.'includes'.DS.'defines.php');

require_once('/home/www/dexline.com.ua/'.'includes'.DS.'framework.php');

$db = JFactory::getDbo();

unlink('/home/www/dexline.com.ua/creativ.xml');

$yaxml = fopen("/home/www/dexline.com.ua/creativ.xml", "w");
$txt = "<?xml version='1.0' encoding='UTF-8'?>\r\n";
$txt .= "<offers>\r\n";
fwrite($yaxml, $txt);

$query = "SELECT jp.*,jpc.category_id,jm.`name_ru-RU` as manufacturer_name, jc.`name_ru-RU` as category_name FROM #__jshopping_products as jp
          INNER JOIN #__jshopping_products_to_categories as jpc ON jp.product_id=jpc.product_id
          INNER JOIN #__jshopping_categories as jc ON jpc.category_id=jc.category_id
          INNER JOIN #__jshopping_manufacturers as jm ON jp.product_manufacturer_id=jm.manufacturer_id
          WHERE jp.status=1 AND jpc.category_id NOT IN (1,2,3,5,1019,1017,1036)";
$db->setQuery($query);
$products = $results = $db->loadObjectList();

foreach ($results as $res) {
    $txt = "<offer>\r\n";
    $txt .= "<id>".$res->product_id."</id>\r\n";
    $txt .= "<url>http://dexline.com.ua/product/".$res->{'alias_ru-RU'}."</url>\r\n";
    $txt .= "<price>".round($res->product_price)."</price>\r\n";
    $txt .= "<currencyId>UAH</currencyId>\r\n";
    $txt .= "<categoryId>".$res->category_id."</categoryId>\r\n";
    $txt .= "<categoryName>".$res->category_name."</categoryName>\r\n";
    $txt .= "<picture>http://dexline.com.ua/components/com_jshopping/files/img_products/".$res->image."</picture>\r\n";
    $txt .= "<vendor>".htmlspecialchars(strip_tags($res->manufacturer_name))."</vendor>\r\n";
    $txt .= "<model>".htmlspecialchars(strip_tags($res->{'name_ru-RU'}))."</model>\r\n";
    $txt .= "<description>".htmlspecialchars(strip_tags($res->{'short_description_ru-RU'}))."</description>\r\n";
    $txt .= "</offer>\r\n";
    fwrite($yaxml, $txt);
}

$txt = "</offers>\r\n";
fwrite($yaxml, $txt);

mail('v.repij@gmail.com','Обновление creativ',count($products));
mail('price@dexline.com.ua','Обновление creativ',count($products));