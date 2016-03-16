<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Routing class from com_content
 *
 * @package     Joomla.Site
 * @subpackage  com_content
 * @since       3.3
 */
class SharesRouter extends JComponentRouterBase
{
	/**
	 * Build the route for the com_content component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function build(&$query)
	{
		$segments = array();

		// Get a menu item based on Itemid or currently active
		$app = JFactory::getApplication();
		$menu = $app->getMenu();

		// We need a menu item.  Either the one specified in the query, or the current active one if none specified
		if (empty($query['Itemid']))
		{
			$menuItem = $menu->getActive();
			$menuItemGiven = false;
		}
		else
		{
			$menuItem = $menu->getItem($query['Itemid']);
			$menuItemGiven = true;
		}

		// Check again
		if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_shares')
		{
			$menuItemGiven = false;
			unset($query['Itemid']);
		}

		if (isset($query['view']))
		{
			$view = $query['view'];
		}
		else
		{
			// We need to have a view in the query or it is an invalid URL
			return $segments;
		}

		// Are we dealing with an article or category that is attached to a menu item?
		if (($menuItem instanceof stdClass) && $menuItem->query['view'] == $query['view'] && isset($query['id']) && $menuItem->query['id'] == (int) $query['id'])
		{
			unset($query['view']);

			if (isset($query['layout']))
			{
				unset($query['layout']);
			}

			unset($query['id']);

			return $segments;
		}

		if ($view == 'shares' || $view == 'share')
		{
			if (!$menuItemGiven)
			{
				$segments[] = $view;
			}

            if ($query['category_id']) {
                $db = JFactory::getDbo();
                $dbQuery = $db->getQuery(true)
                    ->select('`alias_ru-RU`')
                    ->from('#__jshopping_categories')
                    ->where('category_id=' . (int) $query['category_id']);
                $db->setQuery($dbQuery);
                $alias = $db->loadResult();
                $query['category_id'] = $alias;
                $segments[]=$query['category_id'];
                unset($query['category_id']);
            }

			unset($query['view']);

			if ($view == 'share')
			{
				if (isset($query['id']))
				{

					// Make sure we have the id and the alias
					if (strpos($query['id'], ':') === false)
					{
						$db = JFactory::getDbo();
						$dbQuery = $db->getQuery(true)
							->select('url')
							->from('#__shares')
							->where('id=' . (int) $query['id']);
						$db->setQuery($dbQuery);
						$alias = $db->loadResult();
						$query['id'] = $alias;
					}
				}
				else
				{
					// We should have these two set for this view.  If we don't, it is an error
					return $segments;
				}
			}
			else
			{
				if (isset($query['id']))
				{
					$catid = $query['id'];
				}
				else
				{
					// We should have id set for this view.  If we don't, it is an error
					return $segments;
				}
			}

			if ($menuItemGiven && isset($menuItem->query['id']))
			{
				$mCatid = $menuItem->query['id'];
			}
			else
			{
				$mCatid = 0;
			}

			if ($view == 'share')
			{

					$id = $query['id'];

				$segments[] = $id;
			}

			unset($query['id']);
			unset($query['catid']);
		}

		/*
		 * If the layout is specified and it is the same as the layout in the menu item, we
		 * unset it so it doesn't go into the query string.
		 */
		if (isset($query['layout']))
		{
			if ($menuItemGiven && isset($menuItem->query['layout']))
			{
				if ($query['layout'] == $menuItem->query['layout'])
				{
					unset($query['layout']);
				}
			}
			else
			{
				if ($query['layout'] == 'default')
				{
					unset($query['layout']);
				}
			}
		}

		$total = count($segments);

		return $segments;
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
	public function parse(&$segments)
	{
		$total = count($segments);
		$vars = array();


		// Get the active menu item.
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();
		$db = JFactory::getDbo();

		// Count route segments
		$count = count($segments);

		/*
		 * Standard routing for articles.  If we don't pick up an Itemid then we get the view from the segments
		 * the first segment is the view and the last segment is the id of the article or category.
		 */
		if (!isset($item))
		{

			$vars['view'] = $segments[0];
			$vars['id'] = $segments[$count - 1];

			return $vars;
		}

        $db = JFactory::getDbo();
        $dbQuery = $db->getQuery(true)
            ->select('category_id')
            ->from('#__jshopping_categories')
            ->where('`alias_ru-RU`=' . $db->quote($segments[0]) .' AND category_parent_id = 0 ');
        $db->setQuery($dbQuery);
        $category_id = $db->loadResult();
        $vars['id'] = $category_id;

		/*
		 * If there is only one segment, then it points to either an article or a category.
		 * We test it first to see if it is a category.  If the id and alias match a category,
		 * then we assume it is a category.  If they don't we assume it is an article
		 */
		if ($count == 1)
		{
			// We check to see if an alias is given.  If not, we assume it is an article


			// First we check if it is a category

				$query = $db->getQuery(true)
					->select($db->quoteName('url'))
					->from($db->quoteName('#__shares'))
					->where($db->quoteName('id') . ' = ' . (int) $id);
				$db->setQuery($query);
				$share = $db->loadObject();

            $dbQuery = $db->getQuery(true)
                ->select('id')
                ->from('#__shares')
                ->where('url=' . $db->quote($segments[$count - 1]) );
            $db->setQuery($dbQuery);
            $aliasvr = $db->loadResult();

				if ($share)
				{
					if ($share->url == $alias)
					{
						$vars['view'] = 'share';
						$vars['id'] = (int) $aliasvr;

						return $vars;
					}
				}

		}

		/*
		 * If there was more than one segment, then we can determine where the URL points to
		 * because the first segment will have the target category id prepended to it.  If the
		 * last segment has a number prepended, it is an article, otherwise, it is a category.
		 */
		if (!$advanced)
		{
			$article_id = $aliasvr;

			if ($article_id > 0)
			{
				$vars['view'] = 'share';
				$vars['id'] = $aliasvr;
			}

			return $vars;
		}

		// We get the category id from the menu item and search from there
		$id = $item->query['id'];

		$vars['id'] = $id;
		$found = 0;

		foreach ($segments as $segment)
		{
			$segment = str_replace(':', '-', $segment);

			if ($found == 0)
			{

					$cid = $segment;

				$vars['id'] = $cid;

					$vars['view'] = 'share';
			}

			$found = 0;
		}

		return $vars;
	}
}

/**
 * Content router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function SharesBuildRoute(&$query)
{
	$router = new SharesRouter;

	return $router->build($query);
}

function SharesParseRoute($segments)
{
	$router = new SharesRouter;

	return $router->parse($segments);
}
