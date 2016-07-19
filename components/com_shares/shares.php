<?php

defined('_JEXEC') or die;

$controller = JControllerLegacy::getInstance('Shares');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
