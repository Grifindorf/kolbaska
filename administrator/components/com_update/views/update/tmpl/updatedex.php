<?php

define('_JEXEC', 1);

ini_set('display_errors', 'On');

define('JPATH_BASE', dirname(__FILE__) . '/../../../../../../' );

define('DS', DIRECTORY_SEPARATOR);


require_once('/home/www/dexline.com.ua/'.DS.'includes'.DS.'defines.php');

require_once('/home/www/dexline.com.ua/'.'includes'.DS.'framework.php');

$db = JFactory::getDbo();

unlink('/home/www/dexline.com.ua/dex.xml');

$yaxml = fopen("/home/www/dexline.com.ua/dex.xml", "w");
$txt = "<?xml version='1.0' encoding='windows-1251'?>\r\n";
$txt .= "<!DOCTYPE yml_catalog SYSTEM 'shops.dtd'>\r\n";
$txt .= "<yml_catalog date='".date('Y-m-d H:m')."'>\r\n";
$txt .= "<shop>\r\n";
$txt .= "<name>DEXline</name>\r\n";
$txt .= "<company>DEXline</company>\r\n";
$txt .= "<url>http://dexline.com.ua</url>\r\n";
$txt .= "<currencies>\r\n";
$txt .= "<currency id='UAH' rate='1'/>\r\n";
$txt .= "</currencies>\r\n";
$txt .= "<offers>\r\n";
fwrite($yaxml, $txt);

$query = "SELECT jp.*,jpc.category_id,jm.`name_ru-RU` as manufacturer_name FROM #__jshopping_products as jp
          INNER JOIN #__jshopping_products_to_categories as jpc ON jp.product_id=jpc.product_id
          INNER JOIN #__jshopping_manufacturers as jm ON jp.product_manufacturer_id=jm.manufacturer_id
          WHERE jp.status=1 AND jpc.category_id NOT IN (1,2,3,5) AND jm.manufacturer_id=220";
$db->setQuery($query);
$products = $results = $db->loadObjectList();

foreach ($results as $res) {
    $txt = "<offer id='".$res->product_ean."' type='vendor.model' available='true'>\r\n";
    $txt .= "<url>http://dexline.com.ua/product/".$res->{'alias_ru-RU'}."</url>\r\n";
    $txt .= "<price>".round($res->product_price)."</price>\r\n";
    $txt .= "<currencyId>UAH</currencyId>\r\n";
    $txt .= "<categoryId>".$res->category_id."</categoryId>\r\n";
    $txt .= "<picture>http://dexline.com.ua/components/com_jshopping/files/img_products/".$res->image."</picture>\r\n";
    $txt .= "<vendor>".iconv("UTF-8","windows-1251",htmlspecialchars(strip_tags($res->manufacturer_name)))."</vendor>\r\n";
    $txt .= "<model>".iconv("UTF-8","windows-1251",htmlspecialchars(strip_tags($res->{'name_ru-RU'})))."</model>\r\n";
    $txt .= "<description>".iconv("UTF-8","windows-1251",htmlspecialchars(strip_tags($res->{'short_description_ru-RU'})))."</description>\r\n";
    $txt .= "</offer>\r\n";
    fwrite($yaxml, $txt);
}

$txt = "</offers>\r\n";
$txt .= "</shop>\r\n";
$txt .= "</yml_catalog>\r\n";
fwrite($yaxml, $txt);

mail('v.repij@gmail.com','Обновление прайса декс',count($products));
mail('price@dexline.com.ua','Обновление прайса декс',count($products));