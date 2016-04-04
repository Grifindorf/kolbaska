<?php
# $Id: productsearch.php
# package mod_jshopping_products_wfl
# file settings.php
# author Aleksey M. Abrosimov wflab
# url http://wflab.ru
# copyright (C) 2013 Web Face Laboratory All rights reserved
# license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
defined('_JEXEC') or die('Restricted access');
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);

jimport('joomla.form.formfield');

class JFormFieldProductsearch extends JFormField {

        protected $type = 'Productsearch';

        // getLabel() left out

        public function getInput() {
            return '<div id="list_for_select_prod"></div><input type="text" id="jforms_prod_ids" name="'.$this->name.'" value="'.$this->value.'"><input type="text" id="prod_search" value=""><input type="button" value="Поиск" id="prod_search_btn">';
        }
}
