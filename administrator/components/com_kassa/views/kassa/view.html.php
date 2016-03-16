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
class KassaViewKassa extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $this->items         = $this->get('Items');
        $this->pagination    = $this->get('Pagination');
        $this->state         = $this->get('State');
        $this->filterForm    = $this->get('FilterForm');
        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

		$this->canDo	= JHelperContent::getActions('com_kassa');

        $this->layout   = $this->getLayout();

        if ($this->getLayout() !== 'modal')
        {
            $this->addToolbar();
        }

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
        $canDo = JHelperContent::getActions('com_kassa');
        $user  = JFactory::getUser();

        //JToolbarHelper::title(JText::_('COM_CONTENT_ARTICLES_TITLE'), 'stack article');

        $layout  = $this->layout;

		JToolbarHelper::title(JText::_('Kassa'), 'kassa');

        if ($layout == 'edit')
        {
            JToolbarHelper::apply('kassa.apply');
            JToolbarHelper::cancel('kassa.cancel');
        }

		if ($layout == 'default')
		{
            JToolbarHelper::addNew('kassa.add');
            JToolbarHelper::publish('kassa.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('kassa.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolbarHelper::trash('kassa.delete');
		}

		JToolbarHelper::divider();
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_kassa');
		}
		JToolbarHelper::divider();
	}

    protected function getSortFields()
    {
        return array(
            'a.ordering'     => JText::_('JGRID_HEADING_ORDERING'),
            'a.state'        => JText::_('JSTATUS'),
            'a.title'        => JText::_('JGLOBAL_TITLE'),
            'category_title' => JText::_('JCATEGORY'),
            'access_level'   => JText::_('JGRID_HEADING_ACCESS'),
            'a.created_by'   => JText::_('JAUTHOR'),
            'language'       => JText::_('JGRID_HEADING_LANGUAGE'),
            'a.created'      => JText::_('JDATE'),
            'a.id'           => JText::_('JGRID_HEADING_ID'),
            'a.featured'     => JText::_('JFEATURED')
        );
    }

}
