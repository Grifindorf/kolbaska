<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgJshoppingAdminJshopping_product_seo extends JPlugin {
	function __construct(& $subject, $config){
		parent::__construct($subject, $config);
		JSFactory::loadExtLanguageFile('addon_jshopping_product_seo');
	}
	
	function _checkExistAlias($field, $table, $alias, $lang) {
		$db = JFactory::getDBO();
        $query = "SELECT `$field` FROM `#__jshopping_$table` WHERE `alias_".$lang."` = '".$db->escape($alias)."'";
        $db->setQuery($query);
        $res = $db->loadResult();        
        if ($res){
            return $res;//error
        }else{
            return 0;//ok
        }
	}
	
	function _checkExistAlias2Product(&$post, $language){
		$product_id = $this->_checkExistAlias('product_id', 'products', $post['alias_'.$language], $language);
		if ($product_id) {
			$post['alias_'.$language] = "";
			JError::raiseWarning("", sprintf(_JSHOP_ERROR_ALIAS_ALREADY_EXIST_PRODUCT, $language, $product_id));
		}
    }
	
	function _checkExistAlias2Category(&$post, $language){
		$category_id = $this->_checkExistAlias('category_id', 'categories', $post['alias_'.$language], $language);
		if ($category_id) {
			$post['alias_'.$language] = "";
			JError::raiseWarning("", sprintf(_JSHOP_ERROR_ALIAS_ALREADY_EXIST_CATEGORY, $language, $category_id));
		}
    }
	
	function _checkExistAlias2Manufacturer(&$post, $language){
		$manufacturer_id = $this->_checkExistAlias('manufacturer_id', 'manufacturers', $post['alias_'.$language], $language);
		if ($manufacturer_id) {
			$post['alias_'.$language] = "";
			JError::raiseWarning("", sprintf(_JSHOP_ERROR_ALIAS_ALREADY_EXIST_MANUFACTURER, $language, $manufacturer_id));
		}
    }
	
	function onBeforeDisplaySaveProduct(&$post, &$product) {
		$_lang = JModelLegacy::getInstance('languages', 'JshoppingModel');
		$languages = $_lang->getAllLanguages(1);
		foreach($languages as $lang){
			if ($post['alias_'.$lang->language] != '') {
				$this->_checkExistAlias2Category($post, $lang->language);
				$this->_checkExistAlias2Manufacturer($post, $lang->language);
			}
		}
	}
	
	function onBeforeSaveCategory(&$post) {
		$jshopConfig = JSFactory::getConfig();
		$_lang = JModelLegacy::getInstance('languages', 'JshoppingModel');
		$languages = $_lang->getAllLanguages(1);
		foreach($languages as $lang){
			if ($jshopConfig->create_alias_product_category_auto && $post['alias_'.$lang->language]=="") $post['alias_'.$lang->language] = $post['name_'.$lang->language];
            $post['alias_'.$lang->language] = JApplication::stringURLSafe($post['alias_'.$lang->language]);
			if ($post['alias_'.$lang->language] != '') {
				$this->_checkExistAlias2Product($post, $lang->language);
				//$this->_checkExistAlias2Manufacturer($post, $lang->language);
			}
		}
	}
	
	function onBeforeSaveManufacturer(&$post) {
		$_lang = JModelLegacy::getInstance('languages', 'JshoppingModel');
		$languages = $_lang->getAllLanguages(1);
		foreach($languages as $lang){
			if ($post['alias_'.$lang->language] != '') {
				$this->_checkExistAlias2Product($post, $lang->language);
				//$this->_checkExistAlias2Category($post, $lang->language);
			}
		}
	}
}
?>