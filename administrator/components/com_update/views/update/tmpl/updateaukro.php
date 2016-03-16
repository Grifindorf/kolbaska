<?php

define('_JEXEC', 1);

ini_set('display_errors', 'On');

define('JPATH_BASE', '/home/www/dexline.com.ua');
define( 'DS', DIRECTORY_SEPARATOR );
require_once( JPATH_BASE .DS.'configuration.php');
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'factory.php' );
require( JPATH_LIBRARIES. '/import.php');
jimport( 'joomla.environment.uri' );
jimport( 'joomla.user.user');
jimport('joomla.application.component.helper');

$db = JFactory::getDbo();

unlink('/home/www/dexline.com.ua/aukro.xml');

$yaxml = fopen("/home/www/dexline.com.ua/aukro.xml", "w");
$txt = "<?xml version='1.0' encoding='UTF-8'?>\r\n";
$txt .= "<!DOCTYPE yml_catalog SYSTEM 'shops.dtd'>\r\n";
$txt .= "<yml_catalog date='".date('Y-m-d H:m')."'>\r\n";
$txt .= "<shop>\r\n";
$txt .= "<name>DEXline</name>\r\n";
$txt .= "<company>DEXline</company>\r\n";
$txt .= "<url>http://dexline.com.ua</url>\r\n";
$txt .= "<currencies>\r\n";
$txt .= "<currency id='UAH' rate='1'/>\r\n";
$txt .= "</currencies>\r\n";
$txt .= "<categories>\r\n";
fwrite($yaxml, $txt);

$query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = 0 and category_publish = 1 AND category_id NOT IN (1,2,3,5) order by ordering ASC";
$db->setQuery($query);
$results = $db->loadObjectList();
foreach ($results as $res) {
    $txt = "<category id='".$res->category_id."'>".$res->{'name_ru-RU'}."</category>\r\n";
    fwrite($yaxml, $txt);

    $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res->category_id." and category_publish = 1 order by ordering ASC";
    $db->setQuery($query);
    $results1 = $db->loadObjectList();
    foreach($results1 as $res1){
        $txt = "<category id='".$res1->category_id."' parentId='".$res1->category_parent_id."'>".$res1->{'name_ru-RU'}."</category>\r\n";
        fwrite($yaxml, $txt);

        $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res1->category_id." and category_publish = 1 order by ordering ASC";
        $db->setQuery($query);
        $vr_categoryes_level3[$res1->category_id] = $results2 = $db->loadObjectList();
        foreach($results2 as $res2){
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
          WHERE jp.status=1 AND jpc.category_id NOT IN (1,2,3,5) AND jm.manufacturer_id=220";
$db->setQuery($query);
$products = $results = $db->loadObjectList();

jimport('joomla.application.component.model');
require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php');
require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');

JTable::addIncludePath('/administrator/components/com_jshopping'.DS.'tables', 'jshop');
$productjsh = JTable::getInstance('product', 'jshop');
foreach ($results as $res) {
    $productjsh = JTable::getInstance('product', 'jshop');
    $productjsh->load($res->product_id);
    $productjsh->extra_field = $productjsh->getExtraFields();
    $vrproduct = '';
    if (is_array($productjsh->extra_field )){
        $vrcountextra = 0;
        foreach($productjsh->extra_field  as $extra_field){
            if ($extra_field['value']) {
                $vrproduct .= $extra_field['name'].": ".$extra_field['value']."\r\n";
                $vrcountextra++;
            }
        }
    }

    $txt = "<offer id='".$res->product_id."' type='vendor.model' available='true'>\r\n";
    $txt .= "<url>http://dexline.com.ua/product/".$res->{'alias_ru-RU'}."</url>\r\n";
    $txt .= "<price>".round($res->product_price)."</price>\r\n";
    $txt .= "<currencyId>UAH</currencyId>\r\n";
    $txt .= "<categoryId>".$res->category_id."</categoryId>\r\n";
    $txt .= "<picture>http://dexline.com.ua/components/com_jshopping/files/img_products/".$res->image."</picture>\r\n";
    $txt .= "<vendor>".htmlspecialchars(strip_tags($res->manufacturer_name))."</vendor>\r\n";
    $txt .= "<model>".htmlspecialchars(strip_tags($res->{'name_ru-RU'}))."</model>\r\n";
    $txt .= "<description>".htmlspecialchars(strip_tags($res->{'short_description_ru-RU'})).htmlspecialchars(strip_tags($vrproduct))."</description>\r\n";
    $txt .= "</offer>\r\n";
    fwrite($yaxml, $txt);
}

$txt = "</offers>\r\n";
$txt .= "</shop>\r\n";
$txt .= "</yml_catalog>\r\n";
fwrite($yaxml, $txt);

mail('v.repij@gmail.com','Обновление прайса aukro',count($products));
mail('price@dexline.com.ua','Обновление прайса aukro',count($products));