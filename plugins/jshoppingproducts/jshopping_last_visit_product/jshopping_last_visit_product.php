<?php
/**
* @package Joomla
* @subpackage JoomShopping
* @author Nevigen.com
* @website http://nevigen.com/
* @email support@nevigen.com
* @copyright Copyright © Nevigen.com. All rights reserved.
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @license agreement http://nevigen.com/license-agreement.html
**/

defined('_JEXEC') or die;

class plgJshoppingproductsJshopping_last_visit_product extends JPlugin {

    function onBeforeDisplayProductView($view) {
		$session = JFactory::getSession();
		$last_visited_products = $session->get('last_visited_products', array());
		if (isset($last_visited_products[$view->product->product_id])) {
			unset($last_visited_products[$view->product->product_id]);
		}
		$product = new stdClass();
		$product->name = $view->product->name;
		$product->orig_image = $view->product->image;
		$product->image = $view->product->image;
		$product->product_thumb_image = $view->product->product_thumb_image;
		$product->product_id = $view->product->product_id;
		$product->currency_id = $view->product->currency_id;
		$product->tax_id = $view->product->product_tax_id;
		$product->orig_product_price = $view->product->product_price;
		$product->product_price = $view->product->product_price;
		$product->product_old_price = $view->product->product_old_price;
		$product->min_price = $view->product->min_price;
		$product->different_prices = $view->product->different_prices;
		$product->product_manufacturer_id = $view->product->product_manufacturer_id;
		$product->vendor_id = $view->product->vendor_id;
		$product->delivery_times_id = $view->product->delivery_times_id;
		$product->label_id = $view->product->label_id;
		$product->category_id = $view->category_id;
		$last_visited_products[$product->product_id] = $product;
		$session->set('last_visited_products', $last_visited_products);
    }

}
?>