<?php

defined('_JEXEC') or die;


class SharesController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 * @since   1.6
	 */
	protected $default_view = 'shares';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean			If true, the view output will be cached
	 * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 * @since   1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
        $view   = $this->input->get('view', 'shares');
        $layout = $this->input->get('layout', 'default');
        $id     = $this->input->getInt('id');

        require_once JPATH_COMPONENT.'/helpers/shares.php';

        if ($view == 'shares' && $layout == 'edit' && !$id)
        {
            // Somehow the person just went to the form - we don't allow that.
            $this->setRedirect(JRoute::_('index.php?option=com_shares&view=shares', false));
            return false;
        }

		parent::display();

        return $this;
	}
}
