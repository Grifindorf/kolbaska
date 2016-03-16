<?php

define('_JEXEC', 1);

ini_set('display_errors', 'On');

define('JPATH_BASE', dirname(__FILE__) . '/../../../../../../' );

define('DS', DIRECTORY_SEPARATOR);

error_reporting(E_ALL);

require_once('/var/www/kolbaska/'.DS.'includes'.DS.'defines.php');

require_once('/var/www/kolbaska/'.'includes'.DS.'framework.php');

$db = JFactory::getDbo();

unlink('/var/www/kolbaska/ya.xml');

$yaxml = fopen("/var/www/kolbaska/ya.xml", "w");
$txt = "<?xml version='1.0' encoding='UTF-8'?>\r\n";
$txt .= "<!DOCTYPE yml_catalog SYSTEM 'shops.dtd'>\r\n";
$txt .= "<yml_catalog date='".date('Y-m-d H:m')."'>\r\n";
$txt .= "<shop>\r\n";
$txt .= "<name>Kolbaska.com.ua</name>\r\n";
$txt .= "<company>Kolbaska.com.ua</company>\r\n";
$txt .= "<url>http://kolbaska.com.ua</url>\r\n";
$txt .= "<currencies>\r\n";
$txt .= "<currency id='UAH' rate='1'/>\r\n";
$txt .= "</currencies>\r\n";
$txt .= "<categories>\r\n";
fwrite($yaxml, $txt);

$category_ids = array();


$query = "SELECT * FROM #__jshopping_categories WHERE category_publish = 1 AND category_parent_id = 0 order by ordering ASC";
$db->setQuery($query);
$results = $db->loadObjectList();
foreach ($results as $res) {
    $category_ids[]=$res->category_id;
    $txt = "<category id='".$res->category_id."'>".$res->{'name_ru-RU'}."</category>\r\n";
    fwrite($yaxml, $txt);

    $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res->category_id." and category_publish = 1 order by ordering ASC";
    $db->setQuery($query);
    $results1 = $db->loadObjectList();
    foreach($results1 as $res1){
        $category_ids[]=$res1->category_id;
        $txt = "<category id='".$res1->category_id."' parentId='".$res1->category_parent_id."'>".$res1->{'name_ru-RU'}."</category>\r\n";
        fwrite($yaxml, $txt);

        $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res1->category_id." and category_publish = 1 order by ordering ASC";
        $db->setQuery($query);
        $vr_categoryes_level3[$res1->category_id] = $results2 = $db->loadObjectList();
        foreach($results2 as $res2){
            $category_ids[]=$res2->category_id;
            $txt = "<category id='".$res2->category_id."' parentId='".$res2->category_parent_id."'>".$res2->{'name_ru-RU'}."</category>\r\n";
            fwrite($yaxml, $txt);
        }
    }
}

$txt = "</categories>\r\n";
$txt .= "<offers>\r\n";
fwrite($yaxml, $txt);

$query = "SELECT jp.*,jpc.category_id,jm.`name_ru-RU` as manufacturer_name FROM #__jshopping_products as jp
          INNER JOIN #__jshopping_products_to_categories as jpc ON jp.product_id=jpc.product_id
          INNER JOIN #__jshopping_manufacturers as jm ON jp.product_manufacturer_id=jm.manufacturer_id
          WHERE jp.product_original_price > 0 AND jp.product_publish = 1 AND jpc.category_id IN (".implode(',',$category_ids).") ";
$db->setQuery($query);
$products = $results = $db->loadObjectList();

foreach ($results as $res) {
    if(count(explode('-',$res->product_ean))==1){
        $txt = "<offer id='".$res->product_ean."' type='vendor.model' available='true'>\r\n";
        $txt .= "<url>http://kolbaska.com.ua/product/".$res->{'alias_ru-RU'}."</url>\r\n";
        $txt .= "<price>".round($res->product_price)."</price>\r\n";
        $txt .= "<currencyId>UAH</currencyId>\r\n";
        $txt .= "<categoryId>".$res->category_id."</categoryId>\r\n";
        $txt .= "<picture>http://kolbaska.com.ua/components/com_jshopping/files/img_products/".$res->image."</picture>\r\n";
        $txt .= "<vendor>".htmlspecialchars(strip_tags($res->manufacturer_name))."</vendor>\r\n";
        $txt .= "<model>".htmlspecialchars(strip_tags($res->{'name_ru-RU'}))."</model>\r\n";
        $txt .= "<description>".htmlspecialchars(strip_tags($res->{'short_description_ru-RU'}))."</description>\r\n";
        $txt .= "</offer>\r\n";
        fwrite($yaxml, $txt);
    }
}

$txt = "</offers>\r\n";
$txt .= "</shop>\r\n";
$txt .= "</yml_catalog>\r\n";
fwrite($yaxml, $txt);