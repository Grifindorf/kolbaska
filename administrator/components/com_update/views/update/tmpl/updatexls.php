<?php

define('_JEXEC', 1);

ini_set('display_errors', 'On');

define('JPATH_BASE', dirname(__FILE__) . '/../../../../../../' );

define('DS', DIRECTORY_SEPARATOR);

require_once('/var/www/kolbaska/includes/defines.php');
require_once('/var/www/kolbaska/includes/framework.php');
require_once('/var/www/kolbaska/administrator/components/com_update/PHPExcel/PHPExcel.php');
require_once('/var/www/kolbaska/administrator/components/com_update/PHPExcel/PHPExcel/Writer/Excel5.php');

?>

<form method="POST" enctype="multipart/form-data" action="/administrator/components/com_update/views/update/tmpl/updatexls.php">
    <input type="file" name="file"/>
    <input type="hidden" name="import" value="1"/>
    <input type="submit" value="Загрузить прайс"/>
</form>

<?php
if($_POST['import']==1 && $_FILES['file']["tmp_name"]){

    require_once JPATH_SITE.'/components/com_jshopping/lib/factory.php';
    require_once JPATH_SITE.'/components/com_jshopping/lib/functions.php';

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


    for ($row = 2; $row <= $highestRow; $row++){
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
        $rowData = $rowData[0];

        var_dump($rowData);
        die;

        if($category==1){
            $categoryIn = 2;
        }

        if($rowData[0]!='' && $rowData[1]=='' && $rowData[2]==''){
            $category = 2;
            $categoryIn = 1;
            $category_name = $rowData[0];
        }

        if($rowData[0]=='' && $rowData[1]!='' && $rowData[2]==''){
            $category = 2;
            $categoryIn = 1;
            $category_name = $rowData[1];
        }

        if($categoryIn==1){
            $query = "SELECT `category_id` FROM `#__jshopping_categories` WHERE `name_ru-RU` LIKE '%".$category_name."%' LIMIT 1";
            $db->setQuery($query);
            $category_id = $db->loadResult();
        }

        if($rowData[0]=='' && $rowData[1]=='' && $rowData[2]!=''){
            set_time_limit(10);
            if($category_id>0){
                $query = "SELECT * FROM `#__jshopping_products` WHERE `product_ean` = '".$rowData[2]."' LIMIT 1";
                $db->setQuery($query);
                $product = $db->loadAssoc();

                if($product['product_id'] > 0){
                    $products_change++;

                    $query_set = "";

                    if($rowData[15]=='Да'){
                        $unlimited = 1;
                        $count = 999;
                    }else{
                        $unlimited = 0;
                        $count = 0;
                    }

                    $query_set .= " `product_quantity` = ".$count.", `unlimited` = ".$unlimited.",";

                    if($product['name_ru-RU']==''){
                        $query_set .= " `name_ru-RU` = ".$db->quote($rowData[14]).", ";
                    }
                    if($product['alias_ru-RU']==''){
                        //$alias = JApplication::stringURLSafe($rowData[14]);
                        $alias = $rowData[2];
                        $query_set .= " `alias_ru-RU` = ".$db->quote($alias).", ";
                    }
                    if($product['description_ru-RU']==''){
                        $query_set .= " `description_ru-RU` = ".$db->quote($rowData[3]).", ";
                    }

                    if($_POST["percent"]>0){
                        $product_price = round($rowData[6]*$_POST["percent"],2);
                    }else{
                        $product_price = round($rowData[6]*1.5,2);
                    }

                    $query_set .= " `product_price` = ".$product_price.", ";

                    if($rowData[10]!=''){
                        $img1 = explode('/',$rowData[10]);
                        $img1 = $img1[count($img1)-1];
                        $query = "SELECT `image_id` FROM `#__jshopping_products_images` WHERE `image_name` = '".$img1."' AND `product_id` = ".$product['product_id']." ";
                        $db->setQuery($query);
                        $image_id = $db->loadResult();
                        if ($image_id>0){

                        }else{
                            $query_set .= " `image` = '".$img1."', ";
                            file_put_contents($jshopConfig->image_product_path."/".$img1, fopen($rowData[10], 'r'));

                            $name_image = $img1;
                            $name_thumb = 'thumb_'.$name_image;
                            $name_full = 'full_'.$name_image;
                            @chmod($jshopConfig->image_product_path."/".$name_image, 0777);
                            $path_image = $jshopConfig->image_product_path."/".$name_image;
                            $path_thumb = $jshopConfig->image_product_path."/".$name_thumb;
                            $path_full =  $jshopConfig->image_product_path."/".$name_full;
                            rename($path_image, $path_full);
                            if ($jshopConfig->image_product_original_width || $jshopConfig->image_product_original_height){
                                if (!ImageLib::resizeImageMagic($path_full, $jshopConfig->image_product_original_width, $jshopConfig->image_product_original_height, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_full, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                    JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                    $error = 1;
                                }
                            }
                            $error = 0;
                            $product_width_image = $jshopConfig->image_product_width;
                            $product_height_image = $jshopConfig->image_product_height;
                            if ($product_width_image || $product_height_image){
                                if (!ImageLib::resizeImageMagic($path_full, $product_width_image, $product_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                    JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                    $error = 1;
                                }
                                @chmod($path_thumb, 0777);
                            }
                            $product_full_width_image = $jshopConfig->image_product_full_width;
                            $product_full_height_image = $jshopConfig->image_product_full_height;
                            if ($product_full_width_image || $product_full_height_image){
                                if (!ImageLib::resizeImageMagic($path_full, $product_full_width_image, $product_full_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_image, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                    JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                    $error = 1;
                                }
                                @chmod($path_image, 0777);
                            }

                            $query = "INSERT INTO `#__jshopping_products_images` (`product_id`,`image_name`,`ordering`) VALUES (".$product['product_id'].",'".$img1."',1)";
                            $db->setQuery($query);
                            $db->execute();
                        }
                    }
                    if($rowData[10]!='' && $product['image']==''){
                        $img1 = explode('/',$rowData[10]);
                        $img1 = $img1[count($img1)-1];
                        $query_set .= " `image` = '".$img1."', ";
                    }
                    /*if($rowData[11]!=''){
                        $img1 = explode('/',$rowData[11]);
                        $img1 = $img1[count($img1)-1];
                        file_put_contents($jshopConfig->image_product_path."/".$img1, fopen($rowData[11], 'r'));

                        $name_image = $img1;
                        $name_thumb = 'thumb_'.$name_image;
                        $name_full = 'full_'.$name_image;
                        @chmod($jshopConfig->image_product_path."/".$name_image, 0777);
                        $path_image = $jshopConfig->image_product_path."/".$name_image;
                        $path_thumb = $jshopConfig->image_product_path."/".$name_thumb;
                        $path_full =  $jshopConfig->image_product_path."/".$name_full;
                        rename($path_image, $path_full);
                        if ($jshopConfig->image_product_original_width || $jshopConfig->image_product_original_height){
                            if (!ImageLib::resizeImageMagic($path_full, $jshopConfig->image_product_original_width, $jshopConfig->image_product_original_height, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_full, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                        }
                        $error = 0;
                        $product_width_image = $jshopConfig->image_product_width;
                        $product_height_image = $jshopConfig->image_product_height;
                        if ($product_width_image || $product_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_width_image, $product_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_thumb, 0777);
                        }
                        $product_full_width_image = $jshopConfig->image_product_full_width;
                        $product_full_height_image = $jshopConfig->image_product_full_height;
                        if ($product_full_width_image || $product_full_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_full_width_image, $product_full_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_image, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_image, 0777);
                        }

                        $query = "INSERT INTO `#__jshopping_products_images` (`product_id`,`image_name`,`ordering`) VALUES (".$product['product_id'].",'".$img1."',2)";
                        $db->setQuery($query);
                        $db->execute();
                    }*/
                    /*if($rowData[12]!=''){
                        $img1 = explode('/',$rowData[12]);
                        $img1 = $img1[count($img1)-1];
                        file_put_contents($jshopConfig->image_product_path."/".$img1, fopen($rowData[12], 'r'));

                        $name_image = $img1;
                        $name_thumb = 'thumb_'.$name_image;
                        $name_full = 'full_'.$name_image;
                        @chmod($jshopConfig->image_product_path."/".$name_image, 0777);
                        $path_image = $jshopConfig->image_product_path."/".$name_image;
                        $path_thumb = $jshopConfig->image_product_path."/".$name_thumb;
                        $path_full =  $jshopConfig->image_product_path."/".$name_full;
                        rename($path_image, $path_full);
                        if ($jshopConfig->image_product_original_width || $jshopConfig->image_product_original_height){
                            if (!ImageLib::resizeImageMagic($path_full, $jshopConfig->image_product_original_width, $jshopConfig->image_product_original_height, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_full, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                        }
                        $error = 0;
                        $product_width_image = $jshopConfig->image_product_width;
                        $product_height_image = $jshopConfig->image_product_height;
                        if ($product_width_image || $product_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_width_image, $product_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_thumb, 0777);
                        }
                        $product_full_width_image = $jshopConfig->image_product_full_width;
                        $product_full_height_image = $jshopConfig->image_product_full_height;
                        if ($product_full_width_image || $product_full_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_full_width_image, $product_full_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_image, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_image, 0777);
                        }

                        $query = "INSERT INTO `#__jshopping_products_images` (`product_id`,`image_name`,`ordering`) VALUES (".$product['product_id'].",'".$img1."',3)";
                        $db->setQuery($query);
                        $db->execute();
                    }*/
                    /*if($rowData[13]!=''){
                        $img1 = explode('/',$rowData[13]);
                        $img1 = $img1[count($img1)-1];
                        file_put_contents($jshopConfig->image_product_path."/".$img1, fopen($rowData[13], 'r'));

                        $name_image = $img1;
                        $name_thumb = 'thumb_'.$name_image;
                        $name_full = 'full_'.$name_image;
                        @chmod($jshopConfig->image_product_path."/".$name_image, 0777);
                        $path_image = $jshopConfig->image_product_path."/".$name_image;
                        $path_thumb = $jshopConfig->image_product_path."/".$name_thumb;
                        $path_full =  $jshopConfig->image_product_path."/".$name_full;
                        rename($path_image, $path_full);
                        if ($jshopConfig->image_product_original_width || $jshopConfig->image_product_original_height){
                            if (!ImageLib::resizeImageMagic($path_full, $jshopConfig->image_product_original_width, $jshopConfig->image_product_original_height, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_full, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                        }
                        $error = 0;
                        $product_width_image = $jshopConfig->image_product_width;
                        $product_height_image = $jshopConfig->image_product_height;
                        if ($product_width_image || $product_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_width_image, $product_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_thumb, 0777);
                        }
                        $product_full_width_image = $jshopConfig->image_product_full_width;
                        $product_full_height_image = $jshopConfig->image_product_full_height;
                        if ($product_full_width_image || $product_full_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_full_width_image, $product_full_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_image, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_image, 0777);
                        }

                        $query = "INSERT INTO `#__jshopping_products_images` (`product_id`,`image_name`,`ordering`) VALUES (".$product['product_id'].",'".$img1."',4)";
                        $db->setQuery($query);
                        $db->execute();
                    }*/

                    $query = "UPDATE `#__jshopping_products_to_categories` SET `category_id` = ".$category_id." WHERE `product_id` = ".$product['product_id']." ";
                    $db->setQuery($query);
                    $db->execute();

                    $query = "UPDATE `#__jshopping_products` SET ".$query_set." `product_publish` = 1 WHERE `product_id` = ".$product['product_id']." ";
                    $db->setQuery($query);
                    $db->execute();

                }else{

                    $products_add++;

                    if($rowData[15]=='Да'){
                        $unlimited = 1;
                        $count = 999;
                    }else{
                        $unlimited = 0;
                        $count = 0;
                    }

                    $query_image = '';

                    if($rowData[10]!=''){
                        $img1 = explode('/',$rowData[10]);
                        $img1 = $img1[count($img1)-1];
                        $query_image = $img1;
                        file_put_contents($jshopConfig->image_product_path."/".$img1, fopen($rowData[10], 'r'));
                        $name_image = $img1;
                        $name_thumb = 'thumb_'.$name_image;
                        $name_full = 'full_'.$name_image;
                        @chmod($jshopConfig->image_product_path."/".$name_image, 0777);
                        $path_image = $jshopConfig->image_product_path."/".$name_image;
                        $path_thumb = $jshopConfig->image_product_path."/".$name_thumb;
                        $path_full =  $jshopConfig->image_product_path."/".$name_full;
                        rename($path_image, $path_full);
                        if ($jshopConfig->image_product_original_width || $jshopConfig->image_product_original_height){
                            if (!ImageLib::resizeImageMagic($path_full, $jshopConfig->image_product_original_width, $jshopConfig->image_product_original_height, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_full, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                        }
                        $error = 0;
                        $product_width_image = $jshopConfig->image_product_width;
                        $product_height_image = $jshopConfig->image_product_height;
                        if ($product_width_image || $product_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_width_image, $product_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_thumb, 0777);
                        }
                        $product_full_width_image = $jshopConfig->image_product_full_width;
                        $product_full_height_image = $jshopConfig->image_product_full_height;
                        if ($product_full_width_image || $product_full_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_full_width_image, $product_full_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_image, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_image, 0777);
                        }
                    }

                    if($_POST["percent"]>0){
                        $product_price = round($rowData[6]*$_POST["percent"],2);
                    }else{
                        $product_price = round($rowData[6]*1.5,2);
                    }
                    //$alias = JApplication::stringURLSafe($rowData[14]);
                    $alias = $rowData[2];
                    $query = "INSERT INTO `#__jshopping_products` (`product_ean`,`product_publish`,`product_quantity`,`unlimited`,`image`,`product_price`,`name_ru-RU`,`alias_ru-RU`,`description_ru-RU`) VALUES ('".$rowData[2]."',1,'".$count."','".$unlimited."','".$query_image."','".$product_price."',".$db->quote($rowData[14]).",".$db->quote($alias).",".$db->quote($rowData[3]).") ";
                    $db->setQuery($query);
                    $db->execute();
                    $last_id = $db->insertid();
                    $query = "INSERT INTO `#__jshopping_products_to_categories` (`category_id`,`product_id`) VALUES ('".$category_id."','".$last_id."')";
                    $db->setQuery($query);
                    $db->execute();

                    if($rowData[10]!=''){
                        $img1 = explode('/',$rowData[10]);
                        $img1 = $img1[count($img1)-1];
                        $query = "INSERT INTO `#__jshopping_products_images` (`product_id`,`image_name`,`ordering`) VALUES (".$last_id.",'".$img1."',1)";
                        $db->setQuery($query);
                        $db->execute();
                    }
                    if($rowData[11]!=''){
                        $img1 = explode('/',$rowData[10]);
                        $img1 = $img1[count($img1)-1];
                        file_put_contents($jshopConfig->image_product_path."/".$img1, fopen($rowData[10], 'r'));

                        $name_image = $img1;
                        $name_thumb = 'thumb_'.$name_image;
                        $name_full = 'full_'.$name_image;
                        @chmod($jshopConfig->image_product_path."/".$name_image, 0777);
                        $path_image = $jshopConfig->image_product_path."/".$name_image;
                        $path_thumb = $jshopConfig->image_product_path."/".$name_thumb;
                        $path_full =  $jshopConfig->image_product_path."/".$name_full;
                        rename($path_image, $path_full);
                        if ($jshopConfig->image_product_original_width || $jshopConfig->image_product_original_height){
                            if (!ImageLib::resizeImageMagic($path_full, $jshopConfig->image_product_original_width, $jshopConfig->image_product_original_height, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_full, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                        }
                        $error = 0;
                        $product_width_image = $jshopConfig->image_product_width;
                        $product_height_image = $jshopConfig->image_product_height;
                        if ($product_width_image || $product_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_width_image, $product_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_thumb, 0777);
                        }
                        $product_full_width_image = $jshopConfig->image_product_full_width;
                        $product_full_height_image = $jshopConfig->image_product_full_height;
                        if ($product_full_width_image || $product_full_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_full_width_image, $product_full_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_image, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_image, 0777);
                        }

                        $query = "INSERT INTO `#__jshopping_products_images` (`product_id`,`image_name`,`ordering`) VALUES (".$last_id.",'".$img1."',2)";
                        $db->setQuery($query);
                        $db->execute();
                    }
                    if($rowData[12]!=''){
                        $img1 = explode('/',$rowData[10]);
                        $img1 = $img1[count($img1)-1];
                        file_put_contents($jshopConfig->image_product_path."/".$img1, fopen($rowData[10], 'r'));

                        $name_image = $img1;
                        $name_thumb = 'thumb_'.$name_image;
                        $name_full = 'full_'.$name_image;
                        @chmod($jshopConfig->image_product_path."/".$name_image, 0777);
                        $path_image = $jshopConfig->image_product_path."/".$name_image;
                        $path_thumb = $jshopConfig->image_product_path."/".$name_thumb;
                        $path_full =  $jshopConfig->image_product_path."/".$name_full;
                        rename($path_image, $path_full);
                        if ($jshopConfig->image_product_original_width || $jshopConfig->image_product_original_height){
                            if (!ImageLib::resizeImageMagic($path_full, $jshopConfig->image_product_original_width, $jshopConfig->image_product_original_height, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_full, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                        }
                        $error = 0;
                        $product_width_image = $jshopConfig->image_product_width;
                        $product_height_image = $jshopConfig->image_product_height;
                        if ($product_width_image || $product_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_width_image, $product_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_thumb, 0777);
                        }
                        $product_full_width_image = $jshopConfig->image_product_full_width;
                        $product_full_height_image = $jshopConfig->image_product_full_height;
                        if ($product_full_width_image || $product_full_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_full_width_image, $product_full_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_image, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_image, 0777);
                        }

                        $query = "INSERT INTO `#__jshopping_products_images` (`product_id`,`image_name`,`ordering`) VALUES (".$last_id.",'".$img1."',3)";
                        $db->setQuery($query);
                        $db->execute();
                    }
                    if($rowData[13]!=''){
                        $img1 = explode('/',$rowData[10]);
                        $img1 = $img1[count($img1)-1];
                        file_put_contents($jshopConfig->image_product_path."/".$img1, fopen($rowData[10], 'r'));

                        $name_image = $img1;
                        $name_thumb = 'thumb_'.$name_image;
                        $name_full = 'full_'.$name_image;
                        @chmod($jshopConfig->image_product_path."/".$name_image, 0777);
                        $path_image = $jshopConfig->image_product_path."/".$name_image;
                        $path_thumb = $jshopConfig->image_product_path."/".$name_thumb;
                        $path_full =  $jshopConfig->image_product_path."/".$name_full;
                        rename($path_image, $path_full);
                        if ($jshopConfig->image_product_original_width || $jshopConfig->image_product_original_height){
                            if (!ImageLib::resizeImageMagic($path_full, $jshopConfig->image_product_original_width, $jshopConfig->image_product_original_height, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_full, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                        }
                        $error = 0;
                        $product_width_image = $jshopConfig->image_product_width;
                        $product_height_image = $jshopConfig->image_product_height;
                        if ($product_width_image || $product_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_width_image, $product_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_thumb, 0777);
                        }
                        $product_full_width_image = $jshopConfig->image_product_full_width;
                        $product_full_height_image = $jshopConfig->image_product_full_height;
                        if ($product_full_width_image || $product_full_height_image){
                            if (!ImageLib::resizeImageMagic($path_full, $product_full_width_image, $product_full_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_image, $jshopConfig->image_quality, $jshopConfig->image_fill_color, $jshopConfig->image_interlace)){
                                JError::raiseWarning("",_JSHOP_ERROR_CREATE_THUMBAIL);
                                $error = 1;
                            }
                            @chmod($path_image, 0777);
                        }

                        $query = "INSERT INTO `#__jshopping_products_images` (`product_id`,`image_name`,`ordering`) VALUES (".$last_id.",'".$img1."',4)";
                        $db->setQuery($query);
                        $db->execute();
                    }

                }

            } else {
                $products_whithout_category++;
            }
        }

    }

}

?>

<!--
<a target="_blank" href="/administrator/components/com_update/views/update/tmpl/kolbaskaprice.php">Пересчет цен</a>
<br/>
<a target="_blank" href="/administrator/components/com_update/views/update/tmpl/updatesitemap.php">Sitemap</a>
<br/>
<a target="_blank" href="/administrator/components/com_update/views/update/tmpl/createpricetmrubak.php">Прайс Рибак</a>
-->