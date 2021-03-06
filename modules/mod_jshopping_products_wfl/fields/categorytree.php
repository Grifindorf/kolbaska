<?php
# $Id: categorytree.php
# package mod_jshopping_products_wfl
# file categorytree.php
# author Aleksey M. Abrosimov wflab
# url http://wflab.ru
# copyright (C) 2013 Web Face Laboratory All rights reserved
# license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
defined('_JEXEC') or die('Restricted access');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);

jimport('joomla.form.formfield');
require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS."lib".DS."factory.php");
require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS."lib".DS."jtableauto.php");
require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS.'tables'.DS.'config.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS."lib".DS."functions.php");
JTable::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jshopping'.DS.'tables');

class JFormFieldCategorytree extends JFormField {

        protected $type = 'Categorytree';

        public function getInput() {
            $categories = buildTreeCategory(1);
            $first_el = JHTML::_('select.option', 0, JText::_('JALL'), 'category_id', 'name' );
            array_unshift($categories, $first_el);
            return JHTML::_('select.genericlist', $categories, $this->name, 'size="10" multiple class = "inputbox" ', 'category_id', 'name', $this->value);
        }
}
