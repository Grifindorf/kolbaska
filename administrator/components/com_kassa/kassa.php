<?php

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_kassa'))
{
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('KassaHelper', __DIR__ . '/helpers/kassa.php');

$controller	= JControllerLegacy::getInstance('Kassa');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
