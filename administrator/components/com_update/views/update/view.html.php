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
class UpdateViewUpdate extends JViewLegacy
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{


        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

		$this->canDo	= JHelperContent::getActions('com_update');

        $this->layout   = $this->getLayout();

        JToolbarHelper::title('Обновление товаров', 'stack article');

		parent::display($tpl);
	}

}
