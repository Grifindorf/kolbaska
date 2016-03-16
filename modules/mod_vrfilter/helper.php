<?php
/**
 * @package Joomla
 * @subpackage JoomShopping
 * @author Dmitry Stashenko
 * @website http://nevigen.com/
 * @email support@nevigen.com
 * @copyright Copyright © Nevigen.com. All rights reserved.
 * @license Proprietary. Copyrighted Commercial Software
 * @license agreement http://nevigen.com/license-agreement.html
 **/

defined('_JEXEC') or die;

class modVrFilterHelper {

    function __construct() {
        $this->allProductExtraField = new stdClass();
        $this->allProductExtraFieldValueDetail = new stdClass();
        $this->app = JFactory::getApplication();
        $this->db = JFactory::getDBO();
        $this->join = ' LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)';
        $this->where = ' WHERE prod.product_publish = 1 AND prod.product_original_price >0';

        $this->category_id = $this->app->input->getInt('category_id');

        if($this->category_id){
            $this->subCategoryies = $this->getSubCategories($this->category_id);

            if (count($this->subCategoryies)) {
                $this->where .= ' AND pr_cat.category_id IN ('.$this->category_id.','.implode(',',$this->subCategoryies).')';
            } else {
                $this->where .= ' AND pr_cat.category_id = '.$this->category_id;
            }
        }
    }

    function getSubCategories($category_id) {
        static $subCategories, $categoryList;

        if (!isset($categoryList)) {
            $categoryList = $subCategories = array();
            $allCategories = JTable::getInstance('Category', 'jshop')->getAllCategories();
            foreach ($allCategories as $row) {
                $categoryList[$row->category_parent_id][] = $row->category_id;
            }
        }

        if (isset($categoryList[$category_id])) {
            foreach ($categoryList[$category_id] as $subcategory_id) {
                $subCategories[] = $subcategory_id;
                self::_getSubCategories($subcategory_id);
            }
        }

        return $subCategories;
    }

    function filterAllowValue($data){

        return $data;
    }

    function getDisplayPrices() {
        if ($this->jshopConfig->displayprice == 2 && !$this->user->id) {
            return;
        }
        if ($this->jshopConfig->display_price_admin == $this->jshopConfig->display_price_front) {
            $join = '';
            $selectTax = '';
        } else {
            $join = 'LEFT JOIN `#__jshopping_taxes` AS tax ON prod.product_tax_id = tax.tax_id';
            if ($this->jshopConfig->display_price_admin) {
                $selectTax = ' * ';
            } else {
                $selectTax = ' / ';
            }
            $selectTax .= '(1 + tax.tax_value / 100)';
        }
        $select = 'MIN(prod.product_price / cr.currency_value'.$selectTax.') AS prod_price_min, MAX(prod.product_price / cr.currency_value'.$selectTax.') AS prod_price_max';
        if ($this->jshopConfig->product_list_show_min_price){
            $select .= ', MIN(prod.min_price / cr.currency_value'.$selectTax.') AS min_price_min, MAX(prod.min_price / cr.currency_value'.$selectTax.') AS min_price_max';
        }
        if ($this->params->attributes_prices) {
            $join .= ' LEFT JOIN `#__jshopping_products_attr` as attr ON prod.product_id = attr.product_id';
            $select .= ', MIN(attr.price / cr.currency_value'.$selectTax.') AS attr_price_min, MAX(attr.price / cr.currency_value'.$selectTax.') AS attr_price_max';
        }
        $query = 'SELECT
				'.$select.'
				FROM `#__jshopping_products` AS prod
				'.$this->join.'
				LEFT JOIN `#__jshopping_currencies` AS cr ON prod.currency_id = cr.currency_id
				'.$join.'
				'.$this->where;
        $this->db->setQuery($query);
        $row = $this->db->loadObject();
        $this->priceRange->from = 0;
        $this->priceRange->to = $row->prod_price_max;
        if ($this->jshopConfig->product_list_show_min_price){
            if ($row->min_price_min !== null && (float)$row->min_price_min < $row->prod_price_min) {
                $this->priceRange->from = (float)$row->min_price_min;
            }
            if ((float)$row->min_price_max > $row->prod_price_max) {
                $this->priceRange->to = (float)$row->min_price_max;
            }
        }
        if ($this->params->attributes_prices) {
            if ($row->attr_price_min !== null && (float)$row->attr_price_min < $row->prod_price_min) {
                $this->priceRange->from = (float)$row->attr_price_min;
            }
            if ($row->attr_price_max !== null && (float)$row->attr_price_max > $row->prod_price_max) {
                $this->priceRange->to = (float)$row->attr_price_max;
            }
        }
        $percentageDiscount = 1 - $this->userShop->percent_discount / 100;
        $this->priceRange->from = floor($this->priceRange->from * $this->jshopConfig->currency_value * $percentageDiscount);
        $this->priceRange->to = ceil($this->priceRange->to * $this->jshopConfig->currency_value * $percentageDiscount);
    }

    function getDisplayManufacturers($order_by='name') {
        $query = 'SELECT DISTINCT man.manufacturer_id AS id, man.`'.$this->lang->get('name').'` AS name, man.`'.$this->lang->get('alias').'` AS alias, man.`'.$this->lang->get('short_description').'` AS short_desc
				FROM `#__jshopping_products` AS prod
				'.$this->join.'
				LEFT JOIN `#__jshopping_manufacturers` AS man ON prod.product_manufacturer_id=man.manufacturer_id
				'.$this->where.'
				AND man.manufacturer_id > 0
				ORDER BY '.$order_by;
        $this->db->setQuery($query);

        return $this->db->loadObjectList();
    }

    function getDisplayCharacteristics() {
        $this->allProductExtraField = JSFactory::getAllProductExtraField();
        $this->allProductExtraFieldValueDetail = JSFactory::getAllProductExtraFieldValueDetail();
        $displayCharacteristics = array();
        $select = '';
        foreach ($this->allProductExtraField as $extraField) {
            if (!in_array($extraField->id, $this->params->characteristics_id)) {
                $select .= 'extra_field_'.$extraField->id.' AS `'.$extraField->id.'`, ';
            }
        }
        if ($select != '') {
            $select = substr($select,0,strlen($select)-2);
        }
        if ($select) {
            $query = 'SELECT DISTINCT '.$select.' FROM `#__jshopping_products` AS prod
					'.$this->join.'
					'.$this->where;
            $this->db->setQuery($query);
            $rows = $this->db->loadAssocList();
            foreach ($rows as $row) {
                foreach ($row as $k=>$v) {
                    if ($this->allProductExtraField[$k]->type == 1) {
                        $displayCharacteristics[$k][] = $v;
                    } else {
                        $v_arr = explode(',', $v);
                        foreach ($v_arr as $val) {
                            $displayCharacteristics[$k][] = isset($this->allProductExtraFieldValueDetail[$k][$val]) ? $val : '';
                        }
                    }
                }
            }
        }

        return $displayCharacteristics;
    }

}