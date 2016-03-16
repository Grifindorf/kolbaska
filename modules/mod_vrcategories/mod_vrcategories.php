<?php
/**
* @version      4.0.4 02.06.2014
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

    defined('_JEXEC') or die('Restricted access');
    error_reporting(error_reporting() & ~E_NOTICE);
    if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
        JError::raiseError(500,"Please install component \"joomshopping\"");
    } 
    
    require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
    require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');

    $mainframe = JFactory::getApplication();
    $db = JFactory::getDBO();
    $jshopConfig = JSFactory::getConfig();
    $params = $mainframe->getParams();
    $category_id = 0;

    $ordering = $jshopConfig->category_sorting==1 ? "ordering" : "name";
    $category = JSFactory::getTable('category', 'jshop');
    $category->load($category_id);
    $categories = $category->getChildCategories($ordering, 'asc', 1);

    $image_category_path = $jshopConfig->image_category_live_path;

    require(JModuleHelper::getLayoutPath('mod_vrcategories',$layout));
?>