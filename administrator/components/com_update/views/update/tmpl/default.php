<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

/*
 <?php echo JHtml::tooltipText('COM_SEARCH_SEARCH_IN_PHRASE'); ?>
<?php echo JRoute::_('index.php?option=com_search&filter_results=0');?>
<?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
 */

$app = JFactory::getApplication();
$input = $app->input;
$user		= JFactory::getUser();
$userId		= $user->get('id');

?>

<a target="_blank" href="/administrator/components/com_update/views/update/tmpl/kolbaskaprice.php">Пересчет цен</a>
<br/>
<a target="_blank" href="/administrator/components/com_update/views/update/tmpl/updatesitemap.php">Sitemap</a>
<br/>
<a target="_blank" href="/administrator/components/com_update/views/update/tmpl/createpricetmrubak.php">Прайс Рибак</a>
<br/>
<a target="_blank" href="/administrator/components/com_update/views/update/tmpl/createpricesxls.php">Обновить прайс-листы XLS</a>
<br/>
<a target="_blank" href="/administrator/components/com_update/views/update/tmpl/updatexml.php">Обновить ya.xml</a>
<br/>
<a href="/administrator/index.php?option=com_update&updatexls=1">Обновить цены с прайса xls</a>

<?php if($_GET['updatexls']==1){ ?>

<form method="POST" enctype="multipart/form-data" action="/administrator/index.php?option=com_update&updatexls=1">
    <input type="file" name="file"/>
    <input type="submit" value="Загрузить прайс"/>
</form>

<?php

if($_FILES['file']["tmp_name"]){

    require_once('/var/www/kolbaska/administrator/components/com_update/PHPExcel/PHPExcel.php');
    require_once('/var/www/kolbaska/administrator/components/com_update/PHPExcel/PHPExcel/Writer/Excel5.php');

    require_once JPATH_SITE.'/components/com_jshopping/lib/factory.php';
    require_once JPATH_SITE.'/components/com_jshopping/lib/functions.php';


    $db = JFactory::getDbo();

    $inputFileName = $_FILES['file']["tmp_name"];
    $jshopConfig = JSFactory::getConfig();

    try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
    } catch(Exception $e) {
        die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
    }

    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

    /*$columnTitles = $sheet->rangeToArray('A1:' . $highestColumn . '1',NULL,TRUE,FALSE);
    $columnTitles = $columnTitles[0];*/

    $categoryIn = 0;
    $category = 1; //not category
    $category_name = '';

    $products_whithout_category = 0;
    $products_add = 0;
    $products_change = 0;


    for ($row = 1; $row <= $highestRow; $row++){
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
        $rowData = $rowData[0];

        $query = "SELECT product_id FROM `#__jshopping_products` WHERE `product_ean` = ".$rowData[0]." ";
        $db->setQuery($query);
        $product = $db->loadResult();
        
        if($product>0){
            $query = "UPDATE `#__jshopping_products` SET `product_old_price` = '".$rowData[1]."' WHERE `product_id` = ".$product." ";
            $db->setQuery($query);
            $db->execute();
        }

    }

}

?>

<?php } ?>