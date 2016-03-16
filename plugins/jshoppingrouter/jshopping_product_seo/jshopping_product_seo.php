<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgJshoppingRouterJshopping_product_seo extends JPlugin {
	var $query = array();
	var $segments = array();
	
	function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
	}
	
	function onBeforeBuildRoute(&$query, &$segments)
    {
		$this->query = $query;
		$this->segments = $segments;

        if ($this->query['controller'] == 'product') {

            $shim = shopItemMenu::getInstance();

            $this->query['Itemid']=$shim->list[1]->id;

        }

	}
	
	function onAfterBuildRoute(&$query, &$segments) 
    {
		if (isset($this->query['controller'])){
			$controller = $this->query['controller'];
		}else{
			$controller = "";
		}
        if ($this->query['controller'] == 'product') {
            $shim = shopItemMenu::getInstance();

            $this->query['Itemid']=$shim->list[1]->id;

        }

		if ($controller=="product" && $this->query['task']=="view" && $this->query['category_id'] && $this->query['product_id']){
			$prodalias = JSFactory::getAliasProduct();
			$shim = shopItemMenu::getInstance();
			$categoryitemidlist = $shim->getListCategory();
			if (($categoryitemidlist[$this->query['category_id']] && $prodalias[$this->query['product_id']]) || (isset($prodalias[$this->query['product_id']]))) {
				$segments = $this->segments;

				$segments[] = $prodalias[$this->query['product_id']];

			}
		}
	}
	
	function onBeforeParseRoute(&$vars, &$segments) 
    {
        if ( $this->_checkJSCategoryCurentMenuItem() )            return NULL;

		$prodalias = JSFactory::getAliasProduct();
		$segments[0] = getSeoSegment($segments[0]);
		if ($segments[0] && !$segments[1] && in_array($segments[0], $prodalias)) {
			$this->segments = $segments;
			$segments[0] = 'content';
		}

	}
	
	function onAfterParseRoute(&$vars, &$segments) 
    {
		if (is_array($this->segments) && count($this->segments) && ($segments[0] == 'content')) {
			$vars = array();
			$prodalias = JSFactory::getAliasProduct();
			$product_id = array_search($this->segments[0], $prodalias, true);
			if (!$product_id){
				JError::raiseError(404, _JSHOP_PAGE_NOT_FOUND);
			}
			$vars['controller'] = "product";
			$vars['task'] = "view";
			$product = JTable::getInstance('product', 'jshop');
			$product->load($product_id);
			
			$vars['category_id'] = $product->getCategory();
            if (!$vars['category_id']) {
                $vars['category_id']=3;
            }
			$vars['product_id'] = $product_id;
            //var_dump($segments);
		}
	}
    
    function _checkJSCategoryCurentMenuItem()
    {
        $menu = JFactory::getApplication()->getMenu();    
        $menuItem = $menu->getActive();
        $link = $menuItem->query;
        
        if ($link["option"] == "com_jshopping" && $link["controller"] == "category" && intval($link["category_id"]) > 0) {
            return TRUE;
        }
    }
}
?>