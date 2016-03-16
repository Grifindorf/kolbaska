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

            );

        }

		parent::__construct($config);
	}

    protected function getStoreId($id = '')
    {
        // Compile the store id.

        return parent::getStoreId($id);
    }

    protected function getListQuery()
    {
        // Create a new query object.
        /*$db = $this->getDbo();
        $query = $db->getQuery(true);
        $user = JFactory::getUser();
        $app = JFactory::getApplication();
        $input = $app->input;

        if ($input->get('id')) {
            $vrcategoryfilter = 'AND category_parent_id='.$input->get('id');
        }
        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.id, a.type, a.name, a.url, a.description, a.text, a.ordering,' .
                'a.image, a.date_start, a.date_end, a.enabled, a.meta_title, a.meta_keywords, a.meta_description'
            )
        );
        $query->from('#__shares AS a');

        // Filter by published state

        $query->where('a.enabled = 1 and banner=0 '.$vrcategoryfilter.' and date_end >= '.$db->quote(date('Y-m-d')).'');

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'a.id');
        $orderDirn = $this->state->get('list.direction', 'desc');

        $query->order('ordering DESC');

        return $query;*/
        return 1;
    }

    public function getItems()
    {
        $items = parent::getItems();

        $input = JFactory::getApplication()->input;

        return $items;
    }


    public function getStart()
    {
        return $this->getState('list.start');
    }
	/**
	 * Method to reset the add shares.
	 *
	 * @return  boolean
	 * @since   1.6
	 */

}
