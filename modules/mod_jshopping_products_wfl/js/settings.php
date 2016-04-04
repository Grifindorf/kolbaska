<?php
# $Id: settings.php
# package mod_jshopping_products_wfl
# file settings.php
# author Aleksey M. Abrosimov wflab
# url http://wflab.ru
# copyright (C) 2013 Web Face Laboratory All rights reserved
# license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
define('_JEXEC', 1);
defined('_JEXEC') or die('Restricted access');
define('DS', DIRECTORY_SEPARATOR);
define('WFLD','../../..');
if (file_exists(WFLD . '/defines.php')) {
    include_once WFLD . '/defines.php';
}
if (!defined('_JDEFINES')) {
    define('JPATH_BASE', WFLD.'/');
    require_once JPATH_BASE.'/includes/defines.php';
}
require_once JPATH_BASE.'/includes/framework.php';
require_once(JPATH_BASE.'/libraries/joomla/factory.php');
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('params');
$query->from('#__modules');
$query->where("id='".$_GET['id']."'");
$db->setQuery($query);
$result = json_decode($db->loadResult());
$retRes = array();

$retRes['ribbon_behavior'] = $result->ribbon_behavior;
$retRes['duration'] = ($result->effect_speed)?$result->effect_speed:500;
$retRes['effect_block'] = ($result->effect_block)?$result->effect_block:'single';
$retRes['id'] = $_GET['id'];
$retRes['orientation'] = ($result->ribbon_orientation)?$result->ribbon_orientation:'hor';

header("content-type: application/x-javascript");
$js ="if(typeof(jQuery) != 'undefined') {jQuery.noConflict();}";
$js.= "window.addEvent('load',function(){";
$js.= "wflSliders['wfls".$_GET['id']."'] = new wflSlider(".json_encode($retRes).")";
$js.="});";
echo $js;
?>
