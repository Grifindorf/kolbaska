<?php

define('_JEXEC', 1);

error_reporting(0);

ini_set('display_errors', 'On');

define('JPATH_BASE', dirname(__FILE__) . '/../../../../../../' );

define('DS', DIRECTORY_SEPARATOR);

require_once('/var/www/kolbaska/includes/defines.php');
require_once('/var/www/kolbaska/includes/framework.php');

$db = JFactory::getDbo();

require_once('/var/www/kolbaska/administrator/components/com_update/PHPExcel/PHPExcel.php');
require_once('/var/www/kolbaska/administrator/components/com_update/PHPExcel/PHPExcel/Writer/Excel5.php');

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
$list->setTitle("Прайс ТМ 'Рибак'");

$list->mergeCells("A1:F1");
$list->mergeCells("A2:F2");
$list->mergeCells("A3:F3");

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setWorksheet($list);
$objDrawing->setName("kolbaska.com.ua");
$objDrawing->setPath("/var/www/kolbaska/images/kolbaska.com.ua.png");
$objDrawing->setCoordinates("A1");
$objDrawing->setOffsetX(1);
$objDrawing->setOffsetY(5);
$objDrawing->setWidth(200);
$list->getRowDimension(1)->setRowHeight(80);
$list->setCellValue("A2", "Интернет-магазин kolbaska.com.ua");
$list->setCellValue("A3", "тел.: 044 578-21-33 ");


$list->setCellValue("A5", " №");
$list->setCellValue("B5", "Фото");
$list->setCellValue("C5", "Код товара");
$list->setCellValue("D5", "Наименование");
$list->setCellValue("E5", "Оптовая цена, грн/кг");
$list->setCellValue("F5", "Розничная цена, грн/кг");
$list->getRowDimension(5)->setRowHeight(40);
$list->getStyle("A5")->getAlignment()->setWrapText(true);
$list->getStyle("B5")->getAlignment()->setWrapText(true);
$list->getStyle("C5")->getAlignment()->setWrapText(true);
$list->getStyle("D5")->getAlignment()->setWrapText(true);
$list->getStyle("E5")->getAlignment()->setWrapText(true);
$list->getStyle("F5")->getAlignment()->setWrapText(true);


$list->getStyle("D1")->applyFromArray($headerStyle);
/*for($i = "A"; $i <= "D"; $i++){
    $list->getStyle($i."7")->applyFromArray($borderedStyle);
}*/

$list->getColumnDimension("A")->setWidth("4");
$list->getColumnDimension("B")->setWidth("26");
//$list->getColumnDimension("B")->setWidth("1");
$list->getColumnDimension("C")->setWidth("15");
$list->getColumnDimension("D")->setWidth("15");
$list->getColumnDimension("E")->setWidth("15");
$list->getColumnDimension("F")->setWidth("15");

$query = "SELECT `category_id`,`name_ru-RU` as `name` FROM `#__jshopping_categories` WHERE `category_id` IN (SELECT `category_id` FROM `#__jshopping_products_to_categories` WHERE `product_id` IN (SELECT `product_id` FROM `#__jshopping_products` WHERE `product_provider_id` = 1 )) ";
$db->setQuery($query);
$categories = $db->loadObjectlist();

$i = 0;
$y = 0;
foreach($categories as $category){
    $i++;
    $nowRow = (5 + $i);
    $list->mergeCells("B".$nowRow.":C".$nowRow);
    $list->getRowDimension($nowRow)->setRowHeight(30);
    $list->setCellValue("B".$nowRow, htmlspecialchars_decode($category->name));
    $list->getStyle("B".$nowRow)->getAlignment()->setWrapText(true);

    $query = "SELECT `product_ean`, `name_ru-RU` as `name`, `image`, `product_price`, `product_old_price` FROM `#__jshopping_products` WHERE `product_id` IN (SELECT `product_id` FROM `#__jshopping_products_to_categories` WHERE `category_id` = ".$category->category_id.") AND `product_provider_id` = 1 AND `product_publish` = 1 ";
    $db->setQuery($query);
    $products = $db->loadObjectlist();
    foreach($products as $product){
        $y++;
        $i++;
        $nowRow = (5 + $i);
        $list->setCellValue("A".$nowRow, $y);
        if($product->image != '' && file_exists("/var/www/kolbaska/components/com_jshopping/files/img_products/".$product->image)){
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setWorksheet($list);
            $objDrawing->setName($product->name);
            $objDrawing->setPath("/var/www/kolbaska/components/com_jshopping/files/img_products/".$product->image);
            $objDrawing->setCoordinates("B".$nowRow);
            $objDrawing->setOffsetX(1);
            $objDrawing->setOffsetY(5);
            $objDrawing->setWidth(130);
        }
        $list->getRowDimension($nowRow)->setRowHeight(70);
        $list->setCellValue("C".$nowRow, $product->product_ean);
        $list->setCellValue("D".$nowRow, htmlspecialchars_decode($product->name));
        $list->getStyle("D".$nowRow)->getAlignment()->setWrapText(true);
        $list->setCellValue("E".$nowRow, $product->product_price);
        $list->setCellValue("F".$nowRow, $product->product_old_price);
        $list->getStyle("A".$nowRow.":F".$nowRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setWrapText(TRUE);
    }
}


    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=exportgoods.xls");
    $objWriter = new PHPExcel_Writer_Excel5($excel);
    $objWriter->save('php://output');

?>
