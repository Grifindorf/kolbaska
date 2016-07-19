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
		$this->item		= $this->get('Item');
        $this->layout   = $this->getLayout();

        $document = JFactory::getDocument();
        $document->setTitle($this->item->meta_title);
        $document->setDescription($this->item->meta_description);
        $document->setMetaData('keywords',$this->item->meta_keywords);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

        $mainframe = &JFactory::getApplication();
        $pathway =& $mainframe->getPathway();
        $breadcrumb = $pathway->setPathway(array());
        $pathway->addItem( 'Акции и спецпредложения','index.php?Itemid=107');
        $pathway->addItem( $this->item->name,'');

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
}
