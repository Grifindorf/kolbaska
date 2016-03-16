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
class SharesModelProducts extends JModelAdmin
{
    protected $text_prefix = 'COM_SHARES';

    public $typeAlias = 'com_shares.products';
    /**
     * Constructor.
     *
     * @param   array  An optional associative array of configuration settings.
     * @see     JController
     * @since   1.6
     */


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


    protected function prepareTable($table)
    {
        // Set the publish date to now
        $db = $this->getDbo();

    }

    public function getTable($type = 'Shares', $prefix = 'SharesTable', $config = array())
    {

        return JTable::getInstance($type, $prefix, $config);
    }

    public function getItem($pk = null)
    {

        if ($item = parent::getItem($pk))
        {
            // Convert the params field to an array.

        }
        // Load associated content items
        $app = JFactory::getApplication();


        return $item;
    }


    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_shares.products', 'products', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form))
        {
            return false;
        }
        $jinput = JFactory::getApplication()->input;

        // The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
        if ($jinput->get('a_id'))
        {
            $id = $jinput->get('a_id', 0);
        }
        // The back end uses id so we use that the rest of the time and set it to 0 by default.
        else
        {
            $id = $jinput->get('id', 0);
        }
        // Determine correct permissions to check.
        if ($this->getState('article.id'))
        {
            $id = $this->getState('article.id');
            // Existing record. Can only edit in selected categories.
        }
        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_shares.edit.products.data', array());

        if (empty($data))
        {
            $data = $this->getItem();

            // Prime some default values.
        }

        $this->preprocessData('com_shares.products', $data);

        return $data;
    }

    public function save($data)
    {
        $app = JFactory::getApplication();


        if (parent::save($data))
        {


            return true;
        }

        return false;
    }

    protected function preprocessForm(JForm $form, $data, $group = 'content')
    {
        // Association content items
        $app = JFactory::getApplication();

        parent::preprocessForm($form, $data, $group);
    }
}