<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgJshoppingProductsJshoppingOrderBy extends JPlugin 
{
	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}
    
	function onBeforeQueryGetProductList( $category, &$adv_result, &$adv_from, &$adv_query, &$order_query )
	{
		if ($category == "category" || $category == "manufacturer") 
		{
			$adv_result .= ", IF(prod.product_quantity>0,1,0) as qflag"; 
			$order_explode = explode(" ", $order_query);
			$order_query = 'ORDER BY qflag DESC, '.$order_explode[3].' '.$order_explode[4];
		}
	}
}
?>