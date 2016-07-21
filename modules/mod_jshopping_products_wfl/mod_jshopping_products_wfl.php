<?php
# $Id: mod_jshopping_products_wfl.php 1.0.10 2013-10-14
# package mod_jshopping_products_wfl
# file mod_jshopping_products_wfl.php
# author Aleksey M. Abrosimov wflab
# url http://wflab.ru
# copyright (C) 2013 Web Face Laboratory All rights reserved
# license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
    defined('_JEXEC') or die('Restricted access');
    if (!file_exists(JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS.'jshopping.php')){
        JError::raiseError(500,"Please install component \"joomshopping\"");
    }
    require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS."lib".DS."factory.php");
    require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS."lib".DS."jtableauto.php");
    require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS.'tables'.DS.'config.php');
    require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS."lib".DS."functions.php");
    require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS."lib".DS."multilangfield.php");
    $lang = &JFactory::getLanguage();
    if(file_exists(JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS . 'lang'. DS . $lang->getTag() . '.php'))
        require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS . 'lang'. DS . $lang->getTag() . '.php');
    else
        require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS . 'lang'.DS.'en-GB.php');

    JTable::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS.'tables');

    $document = &JFactory::getDocument();
    $jshopConfig = &JSFactory::getConfig();    
    $product_source = $params->get('products_source','random');
    $product_count = $params->get('count_products',0);
    $ribbon_orientation = $params->get('ribbon_orientation','hor');
    $block_width = $params->get('block_width',150);
    $block_height= $params->get('block_height',150);

    $ribbon_behavior = $params->get('ribbon_behavior','static');
    $effect_speed  = $params->get('effect_speed',500);
    $effect_block = $params->get('effect_block','single');

    $moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
    $additional_params = $params->get('additional_params');
    if(!is_array($additional_params)) $additional_params = array();
    if($product_source == 'manuf_logo'){
        $manufacturers = &JTable::getInstance('manufacturer', 'jshop');
        $on_image_click_behavior = 'link';
    }
    else{
        $product = &JTable::getInstance('product', 'jshop');
        $on_image_click_behavior = $params->get('on_image_click_behavior','link');
        if($on_image_click_behavior == 'lightbox'){
            $document->addStyleSheet(JURI::root().'components/com_jshopping/css/jquery.lightbox-0.5.css');
            $document->addScript(JURI::root().'components/com_jshopping/js/jquery/jquery-1.6.2.min.js');
            $document->addScript(JURI::root().'components/com_jshopping/js/jquery/jquery-noconflict.js');            
            $document->addScript(JURI::root().'components/com_jshopping/js/jquery/jquery.media.js');
            $document->addScript(JURI::root().'components/com_jshopping/js/jquery/jquery.lightbox-0.5.pack.js');
            $document->addScript(JURI::root().'modules/'.$module->module.'/js/lightbox.init.php');
        }
    }

    if($params->get('ribbon_behavior','scroll')){
        $document->addScript(JURI::root().'modules'.DS.$module->module .DS.'js'.DS.'slider.js');
        $document->addScript(JURI::root().'modules/'.$module->module.'/js/settings.php?id='.$module->id);        
    }


    switch($product_source){
        case 'random':
            $array_categories = array();//OK
            $product_count = ($product_count == 0)?100000:$product_count;
            $list=$product->getRandProducts($product_count, $array_categories);
            break;
        case 'latest'://OK
            $array_categories = array();
            $product_count = ($product_count == 0)?100000:$product_count;
            $list=$product->getLastProducts($product_count, $array_categories);
            break;
        case 'toprated'://OK
            $product_count = ($product_count == 0)?100000:$product_count;
            $list=$product->getTopRatingProducts($product_count);
            break;
        case 'bestsellers'://OK
            $array_categories = array();
            $product_count = ($product_count == 0)?100000:$product_count;
            $list=$product->getBestSellers($product_count, $array_categories);
            break;
        case 'label'://OK
            $arr_labels = $params->get('labels_list','');
            $product_count = ($product_count == 0)?100000:$product_count;
            $list=array();
            foreach($arr_labels as $label_id){
                if($product_count-count($list) > 0) $list = array_merge($list,$product->getProductLabel($label_id, $product_count-count($list)));
            }
            break;
        case 'categories'://OK
            $filters = array();
            $filters['categorys'] = (count($params->get('category_tree')))?$params->get('category_tree'):array(0);
            if(in_array(0,$filters['categorys'])){
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('category_id');
                $query->from('#__jshopping_categories');
                $query->where('category_publish = 1');
                $db->setQuery($query);
                $filters['categorys'] = $db->loadResultArray();
            }
            $list=$product->getAllProducts($filters, null,  null, 0, $product_count);
            break;
        case 'id'://OK
            $filters = array();
            $tmplist=$product->getAllProducts($filters, null,  null, 0, 0);
            $arr_prods = array_map('trim',explode(",",$params->get('products_search')));
            $list = array();
            foreach($tmplist as $prod){
                if( in_array($prod->product_id,$arr_prods)) $list[] = $prod;
            }
            break;
        case 'manufacturer'://OK
            $filters = (in_array(0,$params->get('manuf_list')))?array():array('manufacturers'=>$params->get('manuf_list'));
            $list=$product->getAllProducts($filters, null,  null, 0, $params->get('count_products'));
            break;
        case 'manuf_logo'://OK
            $list=array();
            $tmpL=$manufacturers->getAllManufacturers(1);
            $mL = $params->get('manuf_list');
            $list = array();
            if(in_array(0,$mL)){
                $list = $tmpL;
            }
            else{
                foreach($tmpL as $man){
                    if(in_array($man->manufacturer_id,$mL)) $list[] = $man;
                }
            }
            break;
        default:
            return false;
    }
    if($params->get('count_products') != 0){
        $list = array_slice($list,0,$params->get('count_products'));
    }
    require(JModuleHelper::getLayoutPath('mod_jshopping_products_wfl'));
?>
