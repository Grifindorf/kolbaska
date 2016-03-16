<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_breadcrumbs
 *
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 * @since       1.5
 */
class ModBreadCrumbsHelper
{
	/**
	 * Retrieve breadcrumb items
	 *
	 * @param   JRegistry  &$params  module parameters
	 *
	 * @return array
	 */
	public static function getList(&$params)
	{
		// Get the PathWay object from the application
		$app		= JFactory::getApplication();
		$pathway	= $app->getPathway();
		$items		= $pathway->getPathWay();

		$count = count($items);

		// Don't use $items here as it references JPathway properties directly
		$crumbs	= array();

		for ($i = 0; $i < $count; $i ++)
		{
			if ($items[$i]->name == 'product') {
				$vr_dot = 2;
			}
			if ($items[$i]->name != 'product') {
				$crumbs[$i] = new stdClass;
				$crumbs[$i]->name = stripslashes(htmlspecialchars($items[$i]->name, ENT_COMPAT, 'UTF-8'));
				if ($vr_dot == 2) {
					$items[$i]->link = str_replace('product','category',$items[$i]->link);
					$crumbs[$i]->link = JRoute::_($items[$i]->link);
					if (!$items[$i]->link) {
						$db = JFactory::getDBO();
						$query = "SELECT jsm.`name_ru-RU`,jsm.`alias_ru-RU` FROM `#__jshopping_manufacturers` as jsm
                              INNER JOIN `#__jshopping_products` jsh ON jsh.product_manufacturer_id = jsm.manufacturer_id
                            WHERE jsh.product_id = ".$app->input->get('product_id')." ";
						$db->setQuery($query);
						$manufacturer_result = $db->LoadAssoc();
						$crumbs[$i]->name = $manufacturer_result['name_ru-RU'];
						$items[$i]->link = $crumbs[$i-1]->link.'/'.$manufacturer_result['alias_ru-RU'];
						$crumbs[$i]->link = $crumbs[$i-1]->link.'/'.$manufacturer_result['alias_ru-RU'];
					}
				} else {
					$crumbs[$i]->link = JRoute::_($items[$i]->link);
				}
			}
		}

		if ($params->get('showHome', 1))
		{
			$item = new stdClass;
			$item->name = htmlspecialchars($params->get('homeText', JText::_('MOD_BREADCRUMBS_HOME')));
			$item->link = JRoute::_('index.php?Itemid=' . $app->getMenu()->getDefault()->id);
			array_unshift($crumbs, $item);
		}

		return $crumbs;
	}

	/**
	 * Set the breadcrumbs separator for the breadcrumbs display.
	 *
	 * @param   string  $custom  Custom xhtml complient string to separate the
	 * items of the breadcrumbs
	 *
	 * @return  string	Separator string
	 *
	 * @since   1.5
	 */
	public static function setSeparator($custom = null)
	{
		$lang = JFactory::getLanguage();

		// If a custom separator has not been provided we try to load a template
		// specific one first, and if that is not present we load the default separator
		if ($custom == null)
		{
			if ($lang->isRTL())
			{
				$_separator = JHtml::_('image', 'system/arrow_rtl.png', null, null, true);
			}
			else
			{
				$_separator = JHtml::_('image', 'system/arrow.png', null, null, true);
			}
		}
		else
		{
			$_separator = htmlspecialchars($custom);
		}

		return $_separator;
	}
}
