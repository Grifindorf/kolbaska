<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit an article.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_content
 * @since       1.6
 */
class SharesViewShare extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		$this->form		= $this->get('Form');

		$this->item		= $this->get('Item');
        $this->layout   = $this->getLayout();
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
        $canDo = JHelperContent::getActions('com_shares');
        $user  = JFactory::getUser();

        $layout  = $this->layout;

        JToolbarHelper::title(JText::_('Акции'), 'shares');

        if ($layout == 'edit')
        {
            JToolbarHelper::save('share.save');
            JToolbarHelper::cancel('share.cancel');
        }
        if ($canDo->get('core.admin'))
        {
            JToolbarHelper::preferences('com_shares');
        }
	}
}
