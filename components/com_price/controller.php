<?php

defined('_JEXEC') or die;


class PriceController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 * @since   1.6
	 */
    public function __construct($config = array())
    {
        $this->input = JFactory::getApplication()->input;

        parent::__construct($config);
    }

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
        $cachable = true;

        // Set the default view name and format from the Request.
        // Note we are using a_id to avoid collisions with the router and the return page.
        // Frontend is a bit messier than the backend.
        $id    = $this->input->getInt('a_id');
        $vName = $this->input->getCmd('view', 'price');
        $this->input->set('view', $vName);

        $user = JFactory::getUser();

        $safeurlparams = array('catid' => 'INT', 'id' => 'INT', 'cid' => 'ARRAY', 'year' => 'INT', 'month' => 'INT', 'limit' => 'UINT', 'limitstart' => 'UINT',
            'showall' => 'INT', 'return' => 'BASE64', 'filter' => 'STRING', 'filter_order' => 'CMD', 'filter_order_Dir' => 'CMD', 'filter-search' => 'STRING', 'print' => 'BOOLEAN', 'lang' => 'CMD', 'Itemid' => 'INT');

        parent::display($cachable, $safeurlparams);

        return $this;
    }
}
