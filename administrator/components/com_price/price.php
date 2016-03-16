<?php

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_price'))
{
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('PriceHelper', __DIR__ . '/helpers/price.php');

$controller	= JControllerLegacy::getInstance('Price');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
