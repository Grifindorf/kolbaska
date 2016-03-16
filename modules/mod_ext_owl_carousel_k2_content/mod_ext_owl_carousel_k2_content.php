<?php
/*
# ------------------------------------------------------------------------
# Extensions for Joomla 2.5.x - Joomla 3.x
# ------------------------------------------------------------------------
# Copyright (C) 2011-2014 Ext-Joom.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Ext-Joom.com
# Websites:  http://www.ext-joom.com 
# Date modified: 06/12/2013 - 13:00
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die ;
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

$document 					= JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_ext_owl_carousel_k2_content/assets/css/owl.carousel.css');
$document->addStyleSheet(JURI::base() . 'modules/mod_ext_owl_carousel_k2_content/assets/css/owl.theme.css');


$moduleclass_sfx			= $params->get('moduleclass_sfx');
$ext_generate_id			=(int)$params->get('ext_generate_id', 1);
$ext_id						= $params->get('ext_id', '1');
if ($ext_generate_id == 1) {
	$rand1 = rand(1,100);
	$rand2 = rand(1,100);
	$ext_id = $rand1.$rand2;
}

// Load jQuery
//------------------------------------------------------------------------

$ext_jquery_ver				= $params->get('ext_jquery_ver', '1.10.0');
$ext_load_jquery			= (int)$params->get('ext_load_jquery', 1);
$ext_load_base				= (int)$params->get('ext_load_base', 1);

$ext_script = <<<SCRIPT


var jQ = false;
function initJQ() {
	if (typeof(jQuery) == 'undefined') {
		if (!jQ) {
			jQ = true;
			document.write('<scr' + 'ipt type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></scr' + 'ipt>');
		}
		setTimeout('initJQ()', 50);
	}
}
initJQ(); 
 
 if (jQuery) jQuery.noConflict();    
  
  
 

SCRIPT;

if ($ext_load_jquery  > 0) {
	$document->addScriptDeclaration($ext_script);		
}
if ($ext_load_base  > 0) { 
	$document->addCustomTag('<script type = "text/javascript" src = "'.JURI::root().'modules/mod_ext_owl_carousel_k2_content/assets/js/owl.carousel.min.js"></script>');
}

// Options Owl Carousel - http://owlgraphic.com/owlcarousel/#customizing
//------------------------------------------------------------------------

//basic:
$ext_items 					= (int)$params->get('ext_items', 1);
$ext_navigation				= $params->get('ext_navigation', 'false');
$ext_pagination				= $params->get('ext_pagination', 'true');
$ext_paginationnumbers		= $params->get('ext_paginationnumbers', 'false');

//pro:
$ext_itemsdesktop			= $params->get('ext_itemsdesktop', 'false');
$ext_itemsdesktopsmall		= $params->get('ext_itemsdesktopsmall', 'false');
$ext_itemstablet			= $params->get('ext_itemstablet', 'false');
$ext_itemstabletsmall		= $params->get('ext_itemstabletsmall', 'false');
$ext_itemsmobile			= $params->get('ext_itemsmobile', 'false');
$ext_itemscustom			= $params->get('ext_itemscustom', 'false');
// Buy PRO version http://ext-joom.com/en/extensions.html



// K2
//------------------------------------------------------------------------

if (K2_JVERSION != '15')
{
    $language = JFactory::getLanguage();
    $language->load('mod_k2.j16', JPATH_ADMINISTRATOR, null, true);
}
require_once (dirname(__FILE__).DS.'helper.php');

// Get component params
$componentParams = JComponentHelper::getParams('com_k2');

$items = modK2OwlCarouselContentHelper::getItems($params);
if (count($items)){
	require JModuleHelper::getLayoutPath('mod_ext_owl_carousel_k2_content', $params->get('layout', 'default'));
	echo JText::_(COP_JOOMLA);
}