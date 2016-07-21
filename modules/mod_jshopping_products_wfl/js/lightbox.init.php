<?php
# $Id: lightbox.init.php 1.0.10 2013-10-14
# package mod_jshopping_products_wfl
# file lightbox.init.php
# author Aleksey M. Abrosimov wflab
# url http://wflab.ru
# copyright (C) 2013 Web Face Laboratory All rights reserved
# license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
define('_JEXEC', 1);
defined('_JEXEC') or die('Restricted access');
define('DS', DIRECTORY_SEPARATOR);
chdir('../../..');
define('WFLD',getcwd());
if (file_exists(WFLD . '/defines.php')) {
    include_once WFLD . '/defines.php';
}
if (!defined('_JDEFINES')) {
    define('JPATH_BASE', WFLD.'/');
    require_once JPATH_BASE.'/includes/defines.php';
}
require_once JPATH_BASE.'/includes/framework.php';
require_once(JPATH_BASE.'/libraries/joomla/factory.php');
header("content-type: application/x-javascript");
$js.="jQuery.noConflict();";
$js = "window.addEvent('load',function(){";
$js.=   'function initJSlightBox(){
            jQuery("a.lightbox").lightBox({
                imageLoading: "'.JURI::root().'img/loading.gif",
                imageBtnClose: "'.JURI::root().'img/close.gif",
                imageBtnPrev: "'.JURI::root().'img/prev.gif",
                imageBtnNext: "'.JURI::root().'img/next.gif",
                imageBlank: "'.JURI::root().'img/blank.gif",
                txtImage: "Image",
                txtOf: "of"
            });
        }
        if(!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)){
            jQuery(function() { initJSlightBox(); });
        }';
$js.="});";
echo $js;
?>
