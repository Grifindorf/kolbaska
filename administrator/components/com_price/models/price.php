<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Methods supporting a list of search terms.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_search
 * @since       1.6
 */
class PriceModelPrice extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'type', 'a.type',
                'name', 'a.url',
                'description', 'a.description',
                'text', 'a.text',
                'image', 'a.image',
                'date_start', 'a.date_start',
                'date_end', 'a.date_end',
                'enabled', 'a.enabled',
                'meta_title', 'a.meta_title',
                'meta_keywords', 'a.meta_keywords',
                'meta_description', 'a.meta_description',
                'ordering', 'a.ordering'
            );

        }

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */

    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication();

        // Adjust the context to support modal layouts.
        if ($layout = $app->input->get('layout'))
        {
            $this->context .= '.' . $layout;
        }

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access');
        $this->setState('filter.access', $access);

        $authorId = $app->getUserStateFromRequest($this->context . '.filter.author_id', 'filter_author_id');
        $this->setState('filter.author_id', $authorId);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
        $this->setState('filter.category_id', $categoryId);

        $level = $this->getUserStateFromRequest($this->context . '.filter.level', 'filter_level');
        $this->setState('filter.level', $level);

        $language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
        $this->setState('filter.language', $language);

        $tag = $this->getUserStateFromRequest($this->context . '.filter.tag', 'filter_tag', '');
        $this->setState('filter.tag', $tag);

        // List state information.
        parent::populateState('a.id', 'desc');

        // Force a language
        $forcedLanguage = $app->input->get('forcedLanguage');

        if (!empty($forcedLanguage))
        {
            $this->setState('filter.language', $forcedLanguage);
            $this->setState('filter.forcedLanguage', $forcedLanguage);
        }
    }

    protected function getStoreId($id = '')
    {
        // Compile the store id.

        return parent::getStoreId($id);
    }

    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $user = JFactory::getUser();
        $app = JFactory::getApplication();

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.id, a.type, a.name, a.url, a.description, a.text, a.image, a.date_start, a.date_end, a.ordering, ' .
                'a.enabled, a.meta_title, a.meta_keywords, a.meta_description, a.show_home, a.home_banner, a.show_category, a.category_banner, a.category_banner_id, a.link_enabled, a.custom_link, a.category_parent_id, a.banner, a.typesearch'
            )
        );
        $query->from('#__shares AS a');

        // Filter by published state
        $published = $this->getState('filter.enabled');

        if (is_numeric($published))
        {
            $query->where('a.enabled = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(a.state = 0 OR a.state = 1)');
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'a.ordering');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order('ordering DESC');

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();

        if (JFactory::getApplication()->isSite())
        {
            $user = JFactory::getUser();
            $groups = $user->getAuthorisedViewLevels();

            for ($x = 0, $count = count($items); $x < $count; $x++)
            {
                // Check the access level. Remove articles the user shouldn't see
                if (!in_array($items[$x]->access, $groups))
                {
                    unset($items[$x]);
                }
            }
        }

        return $items;
    }


	/**
	 * Method to reset the add shares.
	 *
	 * @return  boolean
	 * @since   1.6
	 */

    public function publish(&$pks, $values)
    {

        $pks = (array) $pks;

        $db = $this->getDbo();

        $query = "UPDATE `#__shares` SET `enabled` = 1 WHERE `id` in (".implode(',',$pks).") ";
        $db->setQuery($query);
        $db->query();

        return false;
    }

    public function unpublish(&$pks)
    {
        $pks = (array) $pks;

        $db = $this->getDbo();

        $query = "UPDATE `#__shares` SET `enabled` = 0 WHERE `id` in (".implode(',',$pks).") ";
        $db->setQuery($query);
        $db->query();

        return false;
    }

	public function delete(&$pks)
	{
        $pks = (array) $pks;

        $db = $this->getDbo();

        $query = "DELETE FROM `#__shares` WHERE `id` in (".implode(',',$pks).") ";
        $db->setQuery($query);
        $db->query();

		return true;
	}

}
