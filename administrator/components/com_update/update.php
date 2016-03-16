<?php

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_update'))
{
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('UpdateHelper', __DIR__ . '/helpers/update.php');

$controller	= JControllerLegacy::getInstance('Update');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
