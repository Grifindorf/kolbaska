<?php

define('_JEXEC', 1);

ini_set('display_errors', 'On');

define('JPATH_BASE', dirname(__FILE__) . '/../../../../../../' );

define('DS', DIRECTORY_SEPARATOR);


require_once('/home/www/dexline.com.ua/'.DS.'includes'.DS.'defines.php');

require_once('/home/www/dexline.com.ua/'.'includes'.DS.'framework.php');

$db = JFactory::getDbo();


unlink('/home/www/dexline.com.ua/hotli.xml');

$yaxml = fopen("/home/www/dexline.com.ua/hotli.xml", "w");
$txt = "<?xml version='1.0' encoding='UTF-8' ?>\r\n";
$txt .= "<price>\r\n";
$txt .= "<firmName>DEXline</firmName>\r\n";
$txt .= "<firmId>10595</firmId>\r\n";
$txt .= "<categories>\r\n";
fwrite($yaxml, $txt);

$query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = 0 and category_publish = 1 AND category_id NOT IN (1,2,3,5) order by ordering ASC";
$db->setQuery($query);
$results = $db->loadObjectList();
foreach ($results as $res) {
    $txt = "<category>";
    $txt .= "<id>".$res->category_id."</id>";
    $txt .= "<name>".$res->{'name_ru-RU'}."</name>";
    $txt .= "</category>\r\n";
    fwrite($yaxml, $txt);

    $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res->category_id." and category_publish = 1 order by ordering ASC";
    $db->setQuery($query);
    $results1 = $db->loadObjectList();
    foreach($results1 as $res1){
        $txt = "<category>";
        $txt .= "<id>".$res1->category_id."</id>";
        $txt .= "<parentId>".$res1->category_parent_id."</parentId>";
        $txt .= "<name>".$res1->{'name_ru-RU'}."</name>";
        $txt .= "</category>\r\n";
        fwrite($yaxml, $txt);

        $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res1->category_id." and category_publish = 1 order by ordering ASC";
        $db->setQuery($query);
        $vr_categoryes_level3[$res1->category_id] = $results2 = $db->loadObjectList();
        foreach($results2 as $res2){
            $txt = "<category>";
            $txt .= "<id>".$res2->category_id."</id>";
            $txt .= "<parentId>".$res2->category_parent_id."</parentId>";
            $txt .= "<name>".$res2->{'name_ru-RU'}."</name>";
            $txt .= "</category>\r\n";
            fwrite($yaxml, $txt);
        }
    }
}

$txt = "</categories>\r\n";
$txt .= "<items>\r\n";
fwrite($yaxml, $txt);

$query = "SELECT jp.*,jpc.category_id,jm.`name_ru-RU` as manufacturer_name FROM #__jshopping_products as jp
          INNER JOIN #__jshopping_products_to_categories as jpc ON jp.product_id=jpc.product_id
          INNER JOIN #__jshopping_manufacturers as jm ON jp.product_manufacturer_id=jm.manufacturer_id
          WHERE jp.status=1 AND jpc.category_id NOT IN (1,2,3,5)";
$db->setQuery($query);
$products = $results = $db->loadObjectList();

foreach ($results as $res) {
    $txt = "<item>\r\n";
    $txt .= "<id>".$res->product_id."</id>\r\n";
    $txt .= "<categoryId>".$res->category_id."</categoryId>\r\n";
    $txt .= "<vendor>".htmlspecialchars(strip_tags($res->manufacturer_name))."</vendor>\r\n";
    $txt .= "<name>".htmlspecialchars(strip_tags($res->{'name_ru-RU'}))."</name>\r\n";
    $txt .= "<code>".$res->product_ean."</code>\r\n";
    $txt .= "<description>".htmlspecialchars(strip_tags($res->{'short_description_ru-RU'}))."</description>\r\n";
    $txt .= "<url>http://dexline.com.ua/product/".$res->{'alias_ru-RU'}."</url>\r\n";
    $txt .= "<image>http://dexline.com.ua/components/com_jshopping/files/img_products/".$res->image."</image>\r\n";
    $txt .= "<priceRUAH>".round($res->product_price)."</priceRUAH>\r\n";
    $txt .= "<stock>На складе</stock>\r\n";
    $txt .= "</item>\r\n";
    fwrite($yaxml, $txt);
}

$txt = "</items>\r\n";
$txt .= "</price>\r\n";
fwrite($yaxml, $txt);

mail('v.repij@gmail.com','Обновление прайса hotline',count($products));
mail('price@dexline.com.ua','Обновление прайса hotline',count($products));




unlink('/home/www/dexline.com.ua/hotliaction.xml');

$yaxml = fopen("/home/www/dexline.com.ua/hotliaction.xml", "w");
$txt = "<?xml version='1.0' encoding='cp1251' ?>\r\n";
$txt .= "<sales>\r\n";
fwrite($yaxml, $txt);

$query = "SELECT * FROM `j2x5i_shares` WHERE enabled = 1 and banner = 0 and date_end >= ".$db->quote(date('Y-m-d'))." and date_start <= ".$db->quote(date('Y-m-d'))." ";
$db->setQuery($query);
$resultsactions = $db->loadObjectList();
foreach ($resultsactions as $res) {

    $txt = "<sale>\r\n";
    $txt .= "<title><![CDATA[".iconv("UTF-8", "cp1251",$res->name)."]]></title>\r\n";
    $txt .= "<description><![CDATA[".iconv("UTF-8", "cp1251",htmlspecialchars(strip_tags($res->text)))."]]></description>\r\n";
    $txt .= "<url><![CDATA[http://dexline.com.ua/action/".$res->url."]]></url>\r\n";
    $txt .= "<image><![CDATA[".(file_exists($res->image) ? 'http://dexline.com.ua/'.$res->image : 'http://dexline.com.ua/images/actions/'.$res->image )."]]></image>\r\n";
    $txt .= "<date_start><![CDATA[".$res->date_start."]]></date_start>\r\n";
    $txt .= "<date_end><![CDATA[".$res->date_end."]]></date_end>\r\n";

        if ( $res->type == 1 || $res->type == 7 || $res->type == 6 ) {
            $txt .= "<type><![CDATA[".(iconv("UTF-8", "cp1251", 'Со скидками'))."]]></type>\r\n";
        }
        if ( $res->type == 4 ) {
            $txt .= "<type><![CDATA[".(iconv("UTF-8", "cp1251", 'С подарками'))."]]></type>\r\n";
        }

        if ($res->image) $txt .= "<show_image><![CDATA[true]]></show_image>\r\n";

    $txt .= "<products>\r\n";

    if ($res->type==4) {
        if ($res->typesearch==1) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                        INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                        WHERE jcp.category_id = ".$resR->category_id." AND jsp.status=1" ;
                    $db->setQuery($query);
                    $mainproducts = $db->LoadObjectList();
                    foreach ($mainproducts as $products) {
                        foreach ( json_decode($resR->bonusproducts) as $bonid=>$bonprod ) {
                            $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$bonid." ";
                            $db->setQuery($query);
                            $product = $db->LoadAssoc();
                            if ($product) {
                                $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$products->{'alias_ru-RU'}."]]></product>\r\n";
                                $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$product['alias_ru-RU']."]]></product>\r\n";
                                $vrcount++;
                            }
                        }
                    }
                }
            }
        }
        if ($res->typesearch==2) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    foreach (json_decode($resR->category_id) as $category_id) {
                        $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                        INNER JOIN `#__jshopping_manufacturers` jm ON jm.manufacturer_id=jsp.product_manufacturer_id
                        INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                        WHERE jm.manufacturer_id = ".$resR->brend_id." AND jcp.category_id = $category_id AND jsp.status=1" ;
                        $db->setQuery($query);
                        $mainproducts = $db->LoadObjectList();
                        foreach ($mainproducts as $products) {
                            foreach ( json_decode($resR->bonusproducts)->$category_id as $bonid=>$bonprod ) {
                                $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$bonid." ";
                                $db->setQuery($query);
                                $product = $db->LoadAssoc();
                                if ($product) {
                                    $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$products->{'alias_ru-RU'}."]]></product>\r\n";
                                    $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$product['alias_ru-RU']."]]></product>\r\n";
                                    $vrcount++;
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($res->typesearch==3) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                        INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                        WHERE jsp.product_id = ".$resR->product_id." AND jsp.status=1";
                    $db->setQuery($query);
                    $mainproduct = $db->LoadAssoc();
                    if ($mainproduct) {
                        foreach ( json_decode($resR->bonusproducts) as $bonid=>$bonprod ) {
                            $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$bonid." ";
                            $db->setQuery($query);
                            $product = $db->LoadAssoc();
                            if ($product) {
                                $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$mainproduct['alias_ru-RU']."]]></product>\r\n";
                                $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$product['alias_ru-RU']."]]></product>\r\n";
                                $vrcount++;
                            }
                        }
                    }
                }
            }
        }
    }
    if ($res->type==6) {
        if ($res->typesearch==1) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                            WHERE jcp.category_id = ".$resR->category_id." AND jsp.status=1" ;
                    $db->setQuery($query);
                    $mainproducts = $db->LoadObjectList();
                    foreach ($mainproducts as $products) {
                        $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$products->{'alias_ru-RU'}."]]></product>\r\n";
                        $vrcount++;
                    }
                }
            }
        }
        if ($res->typesearch==2) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    foreach (json_decode($resR->category_id) as $category_id) {
                        $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                            INNER JOIN `#__jshopping_manufacturers` jm ON jm.manufacturer_id=jsp.product_manufacturer_id
                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                            WHERE jm.manufacturer_id = ".$resR->brend_id." AND jcp.category_id = $category_id AND jsp.status=1" ;
                        $db->setQuery($query);
                        $mainproducts = $db->LoadObjectList();
                        foreach ($mainproducts as $products) {
                            $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$products->{'alias_ru-RU'}."]]></product>\r\n";
                            $vrcount++;
                        }
                    }
                }
            }
        }
        if ($res->typesearch==3) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                            WHERE jsp.product_id = ".$resR->product_id." AND jsp.status=1";
                    $db->setQuery($query);
                    $mainproduct = $db->LoadAssoc();
                    if ($mainproduct) {
                        $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$mainproduct['alias_ru-RU']."]]></product>\r\n";
                        $vrcount++;
                    }
                }
            }
        }
    }
    if ($res->type==7) {
        if ($res->typesearch==1) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                            WHERE jcp.category_id = ".$resR->category_id." AND jsp.status=1" ;
                    $db->setQuery($query);
                    $mainproducts = $db->LoadObjectList();
                    foreach ($mainproducts as $products) {
                        $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$products->{'alias_ru-RU'}."]]></product>\r\n";
                        $vrcount++;
                    }
                }
            }
        }
        if ($res->typesearch==2) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    foreach (json_decode($resR->category_id) as $category_id) {
                        $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                            INNER JOIN `#__jshopping_manufacturers` jm ON jm.manufacturer_id=jsp.product_manufacturer_id
                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                            WHERE jm.manufacturer_id = ".$resR->brend_id." AND jcp.category_id = $category_id AND jsp.status=1" ;
                        $db->setQuery($query);
                        $mainproducts = $db->LoadObjectList();
                        foreach ($mainproducts as $products) {
                            $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$products->{'alias_ru-RU'}."]]></product>\r\n";
                            $vrcount++;
                        }
                    }
                }
            }
        }
        if ($res->typesearch==3) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                            WHERE jsp.product_id = ".$resR->product_id." AND jsp.status=1";
                    $db->setQuery($query);
                    $mainproduct = $db->LoadAssoc();
                    if ($mainproduct) {
                        $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$mainproduct['alias_ru-RU']."]]></product>\r\n";
                        $vrcount++;
                    }
                }
            }
        }
    }
    if ($res->type==1) {
        if ($res->typesearch==1) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                            WHERE jcp.category_id = ".$resR->category_id." AND jsp.status=1" ;
                    $db->setQuery($query);
                    $mainproducts = $db->LoadObjectList();
                    foreach ($mainproducts as $products) {
                        $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$products->{'alias_ru-RU'}."]]></product>\r\n";
                        $vrcount++;
                    }
                }
            }
        }
        if ($res->typesearch==2) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    foreach (json_decode($resR->category_id) as $category_id) {
                        $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                            INNER JOIN `#__jshopping_manufacturers` jm ON jm.manufacturer_id=jsp.product_manufacturer_id
                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                            WHERE jm.manufacturer_id = ".$resR->brend_id." AND jcp.category_id = $category_id AND jsp.status=1" ;
                        $db->setQuery($query);
                        $mainproducts = $db->LoadObjectList();
                        foreach ($mainproducts as $products) {
                            $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$products->{'alias_ru-RU'}."]]></product>\r\n";
                            $vrcount++;
                        }
                    }
                }
            }
        }
        if ($res->typesearch==3) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$res->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                $vrcount=0;
                foreach ($result as $resR) {
                    $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                            WHERE jsp.product_id = ".$resR->product_id." AND jsp.status=1";
                    $db->setQuery($query);
                    $mainproduct = $db->LoadAssoc();
                    if ($mainproduct) {
                        $txt .= "<product><![CDATA[http://dexline.com.ua/product/".$mainproduct['alias_ru-RU']."]]></product>\r\n";
                        $vrcount++;
                    }
                }
            }
        }
    }

    $txt .= "</products>\r\n";

    $txt .= "</sale>\r\n";

    fwrite($yaxml, $txt);
}

$txt = "</sales>\r\n";
fwrite($yaxml, $txt);

mail('v.repij@gmail.com','Обновление акций hotline',count($resultsactions));
mail('price@dexline.com.ua','Обновление акций hotline',count($resultsactions));