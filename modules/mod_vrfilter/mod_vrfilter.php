<?php

defined('_JEXEC') or die;

if (!JComponentHelper::isEnabled('com_jshopping')){
    return;
}

require_once JPATH_SITE.'/components/com_jshopping/lib/factory.php';
require_once JPATH_SITE.'/components/com_jshopping/lib/functions.php';
require_once dirname(__FILE__).'/helper.php';

JSFactory::loadCssFiles();
JSFactory::loadLanguageFile();

$modHelper = new modVrFilterHelper();

require JModuleHelper::getLayoutPath($module->module, 'default.php');

?>