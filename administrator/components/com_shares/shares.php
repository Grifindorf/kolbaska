<?php

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_shares'))
{
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('SharesHelper', __DIR__ . '/helpers/shares.php');

$controller	= JControllerLegacy::getInstance('Shares');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
