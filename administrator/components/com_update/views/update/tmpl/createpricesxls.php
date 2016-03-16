<?php

define('_JEXEC', 1);

error_reporting(E_ALL);

ini_set('display_errors', 'On');

define('JPATH_BASE', dirname(__FILE__) . '/../../../../../../' );

define('DS', DIRECTORY_SEPARATOR);

echo $_SERVER['DOCUMENT_ROOT'];

//require_once('/var/www/kolbaska/includes/defines.php');
//require_once('/var/www/kolbaska/includes/framework.php');
//require_once('/var/www/kolbaska/administrator/components/com_update/PHPExcel/PHPExcel.php');
//require_once('/var/www/kolbaska/administrator/components/com_update/PHPExcel/PHPExcel/Writer/Excel5.php');


require_once('/../../../../../../includes/defines.php');
require_once('/../../../../../../includes/framework.php');
require_once('/../../../../../../administrator/components/com_update/PHPExcel/PHPExcel.php');
require_once('/../../../../../../administrator/components/com_update/PHPExcel/PHPExcel/Writer/Excel5.php');

$db = JFactory::getDbo();

$query = "SELECT * FROM `#__jshopping_unit`";
$db->setQuery($query);
$units = $db->loadObjectList();
$un = array();
foreach($units as $unit){
    $un[$unit->id]=$unit->{'name_ru-RU'};
}


$query = "SELECT * FROM `#__jshopping_categories` WHERE `category_publish` = 1 AND `category_parent_id` = 0";
$db->setQuery($query);
$categories = $db->loadObjectList();

foreach($categories as $category){
    $headerStyle = array(
        'font' => array(
            'bold' => false,
            'size' => 16,
            'color' => array('rgb' => '484848'),
        ),
    );

    $borderedStyle = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => 'FF000000'),
            ),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        ),
    );

    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0);
    $list = $excel->getActiveSheet();
    $list->setTitle(substr($category->{'name_ru-RU'},0,31));

    $list->mergeCells("A1:F1");
    $list->mergeCells("A2:F2");
    $list->mergeCells("A3:F3");

    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setWorksheet($list);
    $objDrawing->setName("kolbaska.com.ua");
    $objDrawing->setPath($_SERVER['DOCUMENT_ROOT'].'/images/kolbaska.com.ua.png'); // /var/www/kolbaska/
    $objDrawing->setCoordinates("A1");
    $objDrawing->setOffsetX(1);
    $objDrawing->setOffsetY(5);
    $objDrawing->setWidth(200);
    $list->getRowDimension(1)->setRowHeight(80);
    $list->setCellValue("A2", "Интернет-магазин kolbaska.com.ua");
    $list->setCellValue("A3", "тел.: 044 578-21-33 ");


    $list->setCellValue("A5", " №");
    $list->setCellValue("B5", "Код товара");
    $list->setCellValue("C5", "Наименование");
    $list->setCellValue("D5", "Оптовая цена");
    $list->setCellValue("E5", "Розничная цена");
    $list->getRowDimension(5)->setRowHeight(40);
    $list->getStyle("A5")->getAlignment()->setWrapText(true);
    $list->getStyle("B5")->getAlignment()->setWrapText(true);
    $list->getStyle("C5")->getAlignment()->setWrapText(true);
    $list->getStyle("D5")->getAlignment()->setWrapText(true);
    $list->getStyle("E5")->getAlignment()->setWrapText(true);


    $list->getStyle("D1")->applyFromArray($headerStyle);
    /*for($i = "A"; $i <= "D"; $i++){
        $list->getStyle($i."7")->applyFromArray($borderedStyle);
    }*/

    $list->getColumnDimension("A")->setWidth("4");
    $list->getColumnDimension("B")->setWidth("15");
    $list->getColumnDimension("C")->setWidth("30");
    $list->getColumnDimension("D")->setWidth("20");
    $list->getColumnDimension("E")->setWidth("25");

    $query = "SELECT `category_id`,`name_ru-RU` as `name`,`alias_ru-RU` as `alias` FROM `#__jshopping_categories` WHERE `category_publish` = 1 AND `category_parent_id` = ".$category->category_id;
    $db->setQuery($query);
    $c_categories = $db->loadObjectList();

    $i = 0;
    $y = 0;
    if(count($c_categories)>0){
        foreach($c_categories as $c_category){

            $i++;
            $nowRow = (5 + $i);

            $query = "SELECT `product_ean`, `name_ru-RU` as `name`, `image`, `product_price`, `product_old_price`, `basic_price_unit_id` FROM `#__jshopping_products` WHERE `product_id` IN (SELECT `product_id` FROM `#__jshopping_products_to_categories` WHERE `category_id` = ".$c_category->category_id." OR `category_id` IN (SELECT `category_id` FROM `#__jshopping_categories` WHERE `category_parent_id` = ".$c_category->category_id." AND `category_publish` = 1) ) AND `product_original_price` > 0 AND `product_publish` = 1 ";
            $db->setQuery($query);
            $products = $db->loadObjectlist();

            if(count($products)>0){
                $list->mergeCells("B".$nowRow.":E".$nowRow);
                $list->getRowDimension($nowRow)->setRowHeight(30);
                $list->setCellValue("B".$nowRow, htmlspecialchars_decode($c_category->name));
                $list->getStyle("B".$nowRow)->getAlignment()->setWrapText(true);
                $list->getStyle("B".$nowRow)->applyFromArray($headerStyle);

                foreach($products as $product){
                    $y++;
                    $i++;
                    $nowRow = (5 + $i);
                    $list->setCellValue("A".$nowRow, $y);
                    $list->setCellValue("B".$nowRow, $product->product_ean);
                    $list->setCellValue("C".$nowRow, htmlspecialchars_decode($product->name));
                    $list->getStyle("C".$nowRow)->getAlignment()->setWrapText(true);
                    $list->setCellValue("D".$nowRow, $product->product_price." грн/".$un[$product->basic_price_unit_id]);
                    $list->setCellValue("E".$nowRow, $product->product_old_price." грн/".$un[$product->basic_price_unit_id]);
                    $list->getStyle("A".$nowRow.":e".$nowRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setWrapText(TRUE);
                }
            }

        }
    }else{
        $i++;
        $nowRow = (5 + $i);
		$parentCategoryID = $category->category_id;
        $query = "SELECT `product_ean`, `name_ru-RU` as `name`, `image`, `product_price`, `product_old_price`, `basic_price_unit_id` FROM `#__jshopping_products` WHERE `product_id` IN (SELECT `product_id` FROM `#__jshopping_products_to_categories` WHERE `category_id` = ".$category->category_id." OR `category_id` IN (SELECT `category_id` FROM `#__jshopping_categories` WHERE `category_parent_id` = ".$parentCategoryID." AND `category_publish` = 1) ) AND `product_original_price` > 0 AND `product_publish` = 1 ";
        $db->setQuery($query);
        $products = $db->loadObjectlist();

        if(count($products)>0){
            $list->mergeCells("B".$nowRow.":E".$nowRow);
            $list->getRowDimension($nowRow)->setRowHeight(30);
            $list->setCellValue("B".$nowRow, htmlspecialchars_decode($category->{'name_ru-RU'}));
            $list->getStyle("B".$nowRow)->getAlignment()->setWrapText(true);
            $list->getStyle("B".$nowRow)->applyFromArray($headerStyle);

            foreach($products as $product){
                $y++;
                $i++;
                $nowRow = (5 + $i);
                $list->setCellValue("A".$nowRow, $y);
                $list->setCellValue("B".$nowRow, $product->product_ean);
                $list->setCellValue("C".$nowRow, htmlspecialchars_decode($product->name));
                $list->getStyle("C".$nowRow)->getAlignment()->setWrapText(true);
                $list->setCellValue("D".$nowRow, $product->product_price." грн/".$un[$product->basic_price_unit_id]);
                $list->setCellValue("E".$nowRow, $product->product_old_price." грн/".$un[$product->basic_price_unit_id]);
                $list->getStyle("A".$nowRow.":e".$nowRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setWrapText(TRUE);
            }
        }
    }


    $objWriter = new PHPExcel_Writer_Excel5($excel);
    $objWriter->save('/var/www/kolbaska/'.str_replace(' ','_',$category->{'alias_ru-RU'}).'.xls');

}