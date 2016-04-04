<?php
# $Id: wfljs.php
# package mod_jshopping_products_wfl
# file wfljs.php
# author Aleksey M. Abrosimov wflab
# url http://wflab.ru
# copyright (C) 2013 Web Face Laboratory All rights reserved
# license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL

defined('JPATH_BASE') or die;
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);

jimport('joomla.form.formfield');


class JFormFieldWfljs extends JFormField{
	protected $type = 'Wfljs';
	 public function getLabel(){        
	   return '<span style="display:none">' . parent::getLabel() .'</span>';
	}
	protected function getInput(){
        $ver = new jVersion();
        $cV = version_compare($ver->getShortVersion(),"3.0.0",">=");
        $document = JFactory::getDocument();
        $document->addCustomTag('<script type = "text/javascript" src = "'.JURI::root().DS.'modules'.DS.'mod_jshopping_products_wfl'.DS.'js'.DS.'admin-'.(($cV)?'3':'2.5').'.js"></script>');
        $document->addCustomTag('<link rel="stylesheet" type="text/css" href = "'.JURI::root().DS.'modules'.DS.'mod_jshopping_products_wfl'.DS.'js'.DS.'admin.css">');
		return '<span style="display:none;">javascript loaded</span>';
	}
}
