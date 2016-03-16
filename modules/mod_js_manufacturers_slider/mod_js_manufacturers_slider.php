<?php
/**
 *  @package	mod_js_manufacturers_slider
 *  @copyright	Copyright (c)2013 ELLE / JoomExt.ru
 *  @license	GNU GPLv3 http://www.gnu.org/licenses/gpl.html or later
 *  @version 	1.0 Stable
 */



    defined('_JEXEC') or die('Restricted access');
    error_reporting(error_reporting() & ~E_NOTICE);
    
    if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
        JError::raiseError(500,"Please install component \"joomshopping\"");
    } 

    require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
    require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php'); 
 
    JSFactory::loadCssFiles();
    JSFactory::loadLanguageFile();  
    $jshopConfig = JSFactory::getConfig();
    $doc = JFactory::getDocument();
    $doc->addScript(JURI::root(true) . '/modules/mod_js_manufacturers_slider/assets/js/script.js');
    $doc->addStyleSheet ( '/modules/mod_js_manufacturers_slider/assets/css/style.css' );	
    
    $order = $params->get('sort', 'id');
    $direction = $params->get('ordering', 'asc');
    $show_logo = $params->get('show_logo', '1');
    $show_name = $params->get('show_name', '1');
    $auto_start = $params->get('auto_start', '1');
	
	
    $manufacturer_id = JRequest::getInt('manufacturer_id');
    
    $manufacturer = JTable::getInstance('manufacturer', 'jshop');    
    $list = $manufacturer->getAllManufacturers(1, $order, $direction);
    foreach ($list as $key => $value){
        $list[$key]->link = SEFLink('index.php?option=com_jshopping&controller=manufacturer&task=view&manufacturer_id='.$list[$key]->manufacturer_id, 2);
    }    

    require(JModuleHelper::getLayoutPath('mod_js_manufacturers_slider'));
?>