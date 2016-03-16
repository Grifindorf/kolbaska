<?php

defined('_JEXEC') or die;

$controller = JControllerLegacy::getInstance('Price');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
