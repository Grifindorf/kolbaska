<?php 
/*
# ------------------------------------------------------------------------
# Extensions for Joomla 2.5.x - Joomla 3.x
# ------------------------------------------------------------------------
# Copyright (C) 2011-2014 Ext-Joom.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Ext-Joom.com
# Websites:  http://www.ext-joom.com 
# Date modified: 02/04/2014 - 13:00
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die;
error_reporting(E_ALL & ~E_NOTICE);

$document 			= JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_ext_scrollbar_img/css/default.css');

$moduleclass_sfx	= $params->get('moduleclass_sfx');
$ext_id 			= "mod_".$module->id;
$ext_load_core 		= (int)$params->get('ext_load_core', 1);
$ext_load_more 		= (int)$params->get('ext_load_more', 1);

if ($ext_load_core > 0) {
	$document->addScript(JURI::base() . 'media/system/js/mootools-core.js');
}
if ($ext_load_more > 0) {
	$document->addScript(JURI::base() . 'media/system/js/mootools-more.js');
}
$document->addScript(JURI::base() . 'modules/mod_ext_scrollbar_img/js/scrollbar.js');


$width				= (int)$params->get('width');
$sum_width			= (int)$params->get('sum_width');
$height				= (int)$params->get('height');
$top				= $params->get('top');
$duration_scroll	= (int)$params->get('duration_scroll', 2000);
$duration_knob		= (int)$params->get('duration_knob', 1000);
$width_knob			= (int)$params->get('width_knob', 100);
$height_knob		= (int)$params->get('height_knob', 15);
$height_bar			= (int)$params->get('height_bar', 17);
$color_bar			= $params->get('color_bar');
$color_knob			= $params->get('color_knob');

	
// Load img params
//---------------------------------------------------------------------

$names = array('img', 'alt', 'url', 'target');
$max = 10;
foreach($names as $name) {
    ${$name} = array();
    for($i = 1; $i <= $max; ++$i)
        ${$name}[] = $params->get($name . $i);
}	

require JModuleHelper::getLayoutPath('mod_ext_scrollbar_img', $params->get('layout', 'default'));
echo JText::_(COP_JOOMLA);		
?>