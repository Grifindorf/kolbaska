<?php

define('_JEXEC', 1);

ini_set('display_errors', 'On');

define('JPATH_BASE', dirname(__FILE__) . '/../../../../../../' );

define('DS', DIRECTORY_SEPARATOR);


require_once('/var/www/kolbaska/includes/defines.php');
require_once('/var/www/kolbaska/includes/framework.php');

$db = JFactory::getDbo();

unlink('/var/www/kolbaska/sitemap.xml');

$yaxml = fopen("/var/www/kolbaska/sitemap.xml", "w");
$txt = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
$txt .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\r\n";
fwrite($yaxml, $txt);

$query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = 0 and category_publish = 1 order by ordering ASC";
$db->setQuery($query);
$results = $db->loadObjectList();
foreach ($results as $res) {
    $txt = "<url>\r\n";
    $txt .= "<loc>http://kolbaska.com.ua/category/".$res->{'alias_ru-RU'}."</loc>\r\n";
    $txt .= "<changefreq>daily</changefreq>\r\n";
    $txt .= "<priority>0.8</priority>\r\n";
    $txt .= "</url>\r\n";
    fwrite($yaxml, $txt);

    $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res->category_id." and category_publish = 1 order by ordering ASC";
    $db->setQuery($query);
    $results1 = $db->loadObjectList();
    foreach($results1 as $res1){
        $txt = "<url>\r\n";
        $txt .= "<loc>http://kolbaska.com.ua/category/".$res1->{'alias_ru-RU'}."</loc>\r\n";
        $txt .= "<changefreq>daily</changefreq>\r\n";
        $txt .= "<priority>0.8</priority>\r\n";
        $txt .= "</url>\r\n";
        fwrite($yaxml, $txt);

        $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res1->category_id." and category_publish = 1 order by ordering ASC";
        $db->setQuery($query);
        $vr_categoryes_level3[$res1->category_id] = $results2 = $db->loadObjectList();
        foreach($results2 as $res2){
            $txt = "<url>\r\n";
            $txt .= "<loc>http://kolbaska.com.ua/category/".$res2->{'alias_ru-RU'}."</loc>\r\n";
            $txt .= "<changefreq>daily</changefreq>\r\n";
            $txt .= "<priority>0.8</priority>\r\n";
            $txt .= "</url>\r\n";
            fwrite($yaxml, $txt);
        }
    }
}

$query = "SELECT jp.*,jpc.category_id FROM #__jshopping_products as jp
          INNER JOIN #__jshopping_products_to_categories as jpc ON jp.product_id=jpc.product_id
          WHERE jp.product_original_price > 0 AND jp.product_publish = 1 ";
$db->setQuery($query);
$products = $results = $db->loadObjectList();

foreach ($results as $res) {
    $txt = "<url>\r\n";
    $txt .= "<loc>http://kolbaska.com.ua/product/".$res->{'alias_ru-RU'}."</loc>\r\n";
    $txt .= "<changefreq>daily</changefreq>\r\n";
    $txt .= "<priority>0.8</priority>\r\n";
    $txt .= "</url>\r\n";
    fwrite($yaxml, $txt);
}


$query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = 0 and category_publish = 1 order by ordering ASC";
$db->setQuery($query);
$results = $db->loadObjectList();
foreach ($results as $res) {
    $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res->category_id." and category_publish = 1 order by ordering ASC";
    $db->setQuery($query);
    $results1 = $db->loadObjectList();
    foreach($results1 as $res1){
        $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res1->category_id." and category_publish = 1 order by ordering ASC";
        $db->setQuery($query);
        $vr_categoryes_level3[$res1->category_id] = $results2 = $db->loadObjectList();
        foreach($results2 as $res2){
            $query = "SELECT DISTINCT jm.`alias_ru-RU` FROM #__jshopping_manufacturers as jm
          INNER JOIN #__jshopping_products as jp ON jp.product_manufacturer_id=jm.manufacturer_id
          INNER JOIN #__jshopping_products_to_categories as jpc ON jp.product_id=jpc.product_id
          WHERE jp.product_original_price > 0 AND jpc.category_id = ".$res2->category_id."";
            $db->setQuery($query);
            $products_manufa = $db->loadObjectList();
            foreach ($products_manufa as $man) {
                $txt = "<url>\r\n";
                $txt .= "<loc>http://kolbaska.com.ua/category/".$res2->{'alias_ru-RU'}."/".$man->{'alias_ru-RU'}."</loc>\r\n";
                $txt .= "<changefreq>daily</changefreq>\r\n";
                $txt .= "<priority>0.8</priority>\r\n";
                $txt .= "</url>\r\n";
                fwrite($yaxml, $txt);
            }
        }
    }
}

$txt = "</urlset>\r\n";
fwrite($yaxml, $txt);

$message = 'Обновление сайтмеп';