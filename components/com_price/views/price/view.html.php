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
 * View class for a list of search terms.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_search
 * @since       1.5
 */
class PriceViewPrice extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        //$this->items         = $this->get('Items');
        //$this->pagination    = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

		$this->canDo	= JHelperContent::getActions('com_price');

        $this->layout   = $this->getLayout();

        $mainframe = &JFactory::getApplication();
        $pathway =& $mainframe->getPathway();
        $breadcrumb = $pathway->setPathway(array());
        $pathway->addItem( 'Прайс','');

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
}
