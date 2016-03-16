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

    require_once JPATH_SITE . '/components/com_jshopping/lib/factory.php';
    require_once JPATH_SITE . '/components/com_jshopping/lib/functions.php';

    class plgJshoppingproductsVrFilter extends JPlugin
    {

        private $enable = false;
        private $type = null;
        private $adv_query = null;
        private $advQuery = null;
        private $activeFilters = null;
        private $modParams = null;
        private $subCategoryies = array();

        function onBeforeLoadProductList()
        {

            $this->app = JFactory::getApplication();

            $controller = $this->app->input->getCmd('controller');
            $task = $this->app->input->getCmd('task');
            $category_id = $this->app->input->getInt('category_id');
            $manufacturer_id = $this->app->input->getInt('manufacturer_id');

            if ($controller == 'category' && $category_id) {
                $context = 'jshoping.list.front.product';
                $contextfilter = 'jshoping.list.front.product.cat.' . $category_id;
            } else {
                return;
            }

            return;
        }

        function _filterAllowValue($data)
        {
            $this->allProductExtraField = JSFactory::getAllProductExtraField();
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $key = intval($key);
                    if (is_array($value)) {
                        foreach ($value as $k => $v) {
                            $k = intval($k);
                            $v = urldecode($v);
                            if (($this->allProductExtraField[$key]->type == 1 && $v != '') || $v) {
                                $data[$key][$k] = "'" . $this->db->escape($v) . "'";
                            } else {
                                unset($data[$key][$k]);
                            }
                        }
                    }
                }
            }
            return $data;
        }

        function _getExtendedQuery($restype, $adv_result, $adv_from, $adv_query, $order_query, $filters)
        {

            if ($this->type != 'category' && $this->type != 'manufacturer' && $this->type != 'vendor' && $this->type != 'all_products') {
                return false;
            } else if ($this->adv_query) {
                return true;
            }

            $this->adv_query = '';
            $db = JFactory::getDBO();

            $this->jshopConfig = JSFactory::getConfig();

            $this->_query = array();
            $jinput = JFactory::getApplication()->input;


            /*if ($_SERVER['HTTP_X_FORWARDED_FOR']=='176.241.128.145') {
                var_dump($filters);
                die;
            }*/

            $filters = array();
            if (JRequest::getInt('manufacturer_id')) {

                $filters['manufacturers'] = array(JRequest::getInt('manufacturer_id'));
            }

            $category_id = JRequest::getInt('category_id');
            $this->category_id = $category_id;
            $manufacturer_id = $jinput->get('manufacturer_id');
            $manufacturer_ids = $jinput->get('man', '', 'STRING');
            if ($manufacturer_id) {
                $filters['manufacturers'][] = $manufacturer_id;
            } elseif ($manufacturer_ids) {
                $manalias = JSFactory::getAliasManufacturer();
                foreach (explode(',', $manufacturer_ids) as $manurl) {
                    if(strlen($manurl)>0){
                        $manid_search = array_search($manurl, $manalias);
                        $filters['manufacturers'][] = $manid_search;
                    }
                }
            }

            if (is_array($filters['manufacturers']) && count($filters['manufacturers'])) {
                $this->activeFilters = true;
                $this->_query['manufacturers'] = ' AND prod.product_manufacturer_id IN (' . implode(',', $filters['manufacturers']) . ')';
            } else if ($this->app->input->getVar('manufacturers') != null) {
                $this->_query['manufacturers'] = '';
            }

            $price_url = $jinput->get('price', '', 'STRING');
            if ($price_url) {
                $filters['price_from'] = explode(':', $price_url)[0];
                $filters['price_to'] = explode(':', $price_url)[1];
                if ($filters['price_from'] || $filters['price_to']) {
                    $this->activeFilters = true;
                    $this->activeFiltersPrice = 2;

                    $whereProdPrice = array();
                    if ($filters['price_from']) {
                        $whereProdPrice[] = 'prod.product_price >= ' . (int)$filters['price_from'];
                    }
                    if ($filters['price_to']) {
                        $whereProdPrice[] = 'prod.product_price <= ' . (int)$filters['price_to'];
                    }

                    $where = 'WHERE (' . implode(' AND ', $whereProdPrice) . ')';

                    $query = 'SELECT DISTINCT prod.product_id
						FROM `#__jshopping_products` AS prod
						' . $where;
                    $db->setQuery($query);
                    $row = $db->loadColumn();
                    if (!count($row)) {
                        $row = array('0');
                    }
                    $this->_query['prices'] = ' AND prod.product_id IN (' . implode(',', $row) . ')';
                    $this->adv_query .= $this->_query['prices'];
                } else {
                    $this->_query['prices'] = '';
                }
            }
            if ($whereProdPrice) {
                $whereProdPrice = ' AND ' . implode(' AND ', $whereProdPrice);
            } else {
                $whereProdPrice = '';
            }

            $vrfilterbugcheck = 23;

            $filters_url = $jinput->get('filters', '', 'STRING');

            if ($filters_url) {
                $explode_tmp = explode(';',$filters_url);
                foreach ($explode_tmp as $exp) {
                    $explode_tmp_val = explode(':',$exp);
                    $extra_field_active_id = array_shift($explode_tmp_val);
                    if ($extra_field_active_id) {
                        $active_extra_fields[$extra_field_active_id] = explode(',',array_shift($explode_tmp_val));
                    }
                }
                $filters['extra_fields']=$active_extra_fields;

                $this->allProductExtraField = JSFactory::getAllProductExtraField();
                foreach ( $this->allProductExtraField as $tkey=>$textra) {
                    if (in_array($category_id,$textra->cats)) {
                        $filters['extra_fields_all'][] = $tkey;
                    }
                }
                $vrfilterbugcheck = 0;
            }else{
                $this->allProductExtraField = JSFactory::getAllProductExtraField();
                foreach ( $this->allProductExtraField as $tkey=>$textra) {
                    if (in_array($category_id,$textra->cats)) {
                        $filters['extra_fields'][] = $tkey;
                        $filters['extra_fields_all'][] = $tkey;
                        $vrfilterbugcheck = 23;
                    }
                }
            }

            $select = '';
            if($filters['extra_fields_all']){
                foreach($filters['extra_fields_all'] as $key=>$r_filter){
                    $select .= 'prod.`extra_field_'.$r_filter.'`, ';
                }
            }
            if ($select != '') {
                $select = ", ".substr($select,0,strlen($select)-2);
            }

            $query = "SELECT product_id, product_manufacturer_id {$select} FROM `#__jshopping_products` AS prod
                 LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
                    WHERE pr_cat.category_id = " . $category_id . " AND prod.product_original_price > 0 AND prod.product_publish = 1 " . $whereProdPrice . "
                ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();

            $query1 = "SELECT product_id, product_manufacturer_id {$select} FROM `#__jshopping_products` AS prod
                 LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
                    WHERE pr_cat.category_id = " . $category_id . " AND prod.product_original_price > 0 AND prod.product_publish = 1 " . $this->_query['manufacturers'] . "
                ";
            $db->setQuery($query1);
            $rows1 = $db->loadObjectList();


            $vr_array = array();

            foreach ($filters['extra_fields'] as $extraFieldId => $extraFieldValues) {
                if (is_array($extraFieldValues) && count($extraFieldValues)) {
                    foreach ($extraFieldValues as $extraFieldValue) {
                        $vr_array[$extraFieldId][] = (string)$extraFieldValue;
                    }
                }
            }

            $array_filters = array();
            $products_active_ids = array();
            $products_active_manufacturers = array();

            foreach ( $rows as $vr ) {

                if (count($filters['manufacturers']) == 0 && $vrfilterbugcheck == 23) {
                    $vr_f = array();
                    foreach($filters['extra_fields_all'] as $l_filter){
                        $vr_f[$l_filter]=$vr->{'extra_field_'.$l_filter};
                    }
                    foreach ($vr_f as $key_vr_f => $value_vr_f) {
                        if (is_numeric($value_vr_f)) {
                            $array_filters[$key_vr_f][$value_vr_f][] = $vr->product_id;
                        }
                    }
                    $products_active_ids[] = $vr->product_id;
                    $products_active_manufacturers[$vr->product_manufacturer_id][] = $vr->product_id;
                } elseif (count($filters['manufacturers']) > 0 && $vrfilterbugcheck == 23) {
                    $vr_f = array();
                    foreach($filters['extra_fields_all'] as $l_filter){
                        $vr_f[$l_filter]=$vr->{'extra_field_'.$l_filter};
                    }
                    foreach ($rows1 as $vr1) {
                        if ($vr1->product_id==$vr->product_id) {
                            foreach ($vr_f as $key_vr_f => $value_vr_f) {
                                if (is_numeric($value_vr_f)) {
                                    $array_filters[$key_vr_f][$value_vr_f][] = $vr1->product_id;
                                }
                            }
                            $products_active_ids[] = $vr1->product_id;
                        }
                    }
                    $products_active_manufacturers[$vr->product_manufacturer_id][] = $vr->product_id;
                } elseif (count($filters['manufacturers']) == 0 && $vrfilterbugcheck != 23) {
                    $process_to_add_id = 0;
                    $process_to_add_id_unset = 0;
                    $vr_f = array();
                    foreach($filters['extra_fields_all'] as $l_filter){
                        $vr_f[$l_filter]=$vr->{'extra_field_'.$l_filter};
                    }
                    foreach ($vr_array as $key_active_filter => $value_active_filter) {
                        $unset_vr_array = $vr_array;
                        unset($unset_vr_array[$key_active_filter]);
                        if (count($unset_vr_array)>0) {
                            foreach ($unset_vr_array as $unset_key_active_filter => $unset_value_active_filter) {
                                if (in_array($vr_f[$unset_key_active_filter], $vr_array[$unset_key_active_filter])) {
                                    $process_to_add_id_unset = 1;
                                } else {
                                    $process_to_add_id_unset = 0;
                                    break;
                                }
                            }
                            if ($process_to_add_id_unset == 1) {
                                $vr_f = array();
                                foreach($filters['extra_fields_all'] as $l_filter){
                                    $vr_f[$l_filter]=$vr->{'extra_field_'.$l_filter};
                                }
                                foreach ($vr_f as $key_vr_f => $value_vr_f) {
                                    if (is_numeric($value_vr_f)) {
                                        if (!array_search($vr1->product_id, $array_filters[$key_vr_f][$value_vr_f]) && $key_active_filter == $key_vr_f && $value_vr_f != $value_active_filter) {
                                            $array_filters[$key_vr_f][$value_vr_f][] = $vr1->product_id;
                                        }
                                    }
                                }
                            }
                        } else {
                            foreach ($vr_f as $key_vr_f => $value_vr_f) {
                                if (is_numeric($value_vr_f)) {
                                    if (!array_search($vr1->product_id, $array_filters[$key_vr_f][$value_vr_f]) && $key_active_filter == $key_vr_f && $value_vr_f != $value_active_filter) {
                                        $array_filters[$key_vr_f][$value_vr_f][] = $vr1->product_id;
                                    }
                                }
                            }
                        }

                        if (in_array($vr_f[$key_active_filter], $vr_array[$key_active_filter])) {
                            $process_to_add_id = 1;
                        } else {
                            $process_to_add_id = 0;
                            break;
                        }
                    }
                    if ($process_to_add_id == 1) {
                        $vr_f = array();
                        foreach($filters['extra_fields_all'] as $l_filter){
                            $vr_f[$l_filter]=$vr->{'extra_field_'.$l_filter};
                        }
                        foreach ($vr_f as $key_vr_f => $value_vr_f) {
                            if (is_numeric($value_vr_f)) {
                                $array_filters[$key_vr_f][$value_vr_f][] = $vr->product_id;
                            }
                        }
                        $products_active_manufacturers[$vr->product_manufacturer_id][] = $vr->product_id;
                        $products_active_ids[] = $vr->product_id;
                    }

                } elseif (count($filters['manufacturers']) > 0 && $vrfilterbugcheck != 23) {
                    $process_to_add_id = 0;
                    $process_to_add_id_unset = 0;
                    $vr_f = array();
                    foreach($filters['extra_fields_all'] as $l_filter){
                        $vr_f[$l_filter]=$vr->{'extra_field_'.$l_filter};
                    }
                    foreach ($vr_array as $key_active_filter => $value_active_filter) {
                        $unset_vr_array = $vr_array;
                        unset($unset_vr_array[$key_active_filter]);
                        if (count($unset_vr_array)>0) {
                            foreach ($unset_vr_array as $unset_key_active_filter => $unset_value_active_filter) {
                                if (in_array($vr_f[$unset_key_active_filter], $vr_array[$unset_key_active_filter])) {
                                    $process_to_add_id_unset = 1;
                                } else {
                                    $process_to_add_id_unset = 0;
                                    break;
                                }
                            }
                            if ($process_to_add_id_unset == 1) {
                                foreach ($rows1 as $vr1) {
                                    if ($vr1->product_id == $vr->product_id) {
                                        $vr_f = array();
                                        foreach($filters['extra_fields_all'] as $l_filter){
                                            $vr_f[$l_filter]=$vr1->{'extra_field_'.$l_filter};
                                        }
                                        foreach ($vr_f as $key_vr_f => $value_vr_f) {
                                            if (is_numeric($value_vr_f)) {
                                                if (!array_search($vr1->product_id, $array_filters[$key_vr_f][$value_vr_f]) && $key_active_filter == $key_vr_f && $value_vr_f != $value_active_filter) {
                                                    $array_filters[$key_vr_f][$value_vr_f][] = $vr1->product_id;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            foreach ($rows1 as $vr1) {
                                if ($vr1->product_id == $vr->product_id) {
                                    $vr_f = array();
                                    foreach($filters['extra_fields_all'] as $l_filter){
                                        $vr_f[$l_filter]=$vr1->{'extra_field_'.$l_filter};
                                    }
                                    foreach ($vr_f as $key_vr_f => $value_vr_f) {
                                        if (is_numeric($value_vr_f)) {
                                            if (!array_search($vr1->product_id, $array_filters[$key_vr_f][$value_vr_f]) && $key_active_filter == $key_vr_f && $value_vr_f != $value_active_filter) {
                                                $array_filters[$key_vr_f][$value_vr_f][] = $vr1->product_id;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (in_array($vr_f[$key_active_filter], $vr_array[$key_active_filter])) {
                            $process_to_add_id = 1;
                        } else {
                            $process_to_add_id = 0;
                            break;
                        }
                    }
                    if ($process_to_add_id == 1) {
                        foreach ($rows1 as $vr1) {
                            if ($vr1->product_id == $vr->product_id) {
                                $vr_f = array();
                                foreach($filters['extra_fields_all'] as $l_filter){
                                    $vr_f[$l_filter]=$vr1->{'extra_field_'.$l_filter};
                                }
                                foreach ($vr_f as $key_vr_f => $value_vr_f) {
                                    if (is_numeric($value_vr_f)) {
                                        if (!array_search($vr1->product_id,$array_filters[$key_vr_f][$value_vr_f])) {
                                            $array_filters[$key_vr_f][$value_vr_f][] = $vr1->product_id;
                                        }
                                    }
                                }
                                $products_active_ids[] = $vr1->product_id;
                            }
                        }
                        $products_active_manufacturers[$vr->product_manufacturer_id][] = $vr->product_id;
                    }
                }
            }
            if (count($products_active_ids) == 0) {
                $this->_queryProductIds = ' AND ( prod.product_id IN (0))';
            } else {
                $this->_queryProductIds = ' AND ( prod.product_id IN (' . implode(',', $products_active_ids) . '))';
            }

            $this->adv_query .= $this->_queryProductIds;
            $this->_products_active_ids = $products_active_ids;
            $this->_products_manufacturers = $products_active_manufacturers;
            $this->_array_filters = $array_filters;

            return true;
        }

        function onBeforeQueryCountProductList($type, &$adv_result, &$adv_from, &$adv_query, &$filters)
        {
            $order_query = '';
            if ($this->type === null) {
                $this->type = $type;
            }
            if ($this->advQuery === null) {
                $this->advQuery = $adv_query;
            }
            $this->enable = $this->_getExtendedQuery('count', $adv_result, $adv_from, $adv_query, $order_query, $filters);
            if ($this->enable && $this->adv_query) {
                if ($this->type == 'category' && count($this->subCategoryies)) {
                    $adv_result = 'COUNT(distinct prod.product_id)';
                }
                $adv_query = $this->adv_query;
            }
        }

        function onBeforeQueryGetProductList($type, &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters)
        {

            if ($this->type === null) {
                $this->type = $type;
            }
            if ($this->advQuery === null) {
                $this->advQuery = $adv_query;
            }
            $this->enable = $this->_getExtendedQuery('list', $adv_result, $adv_from, $adv_query, $order_query, $filters);
            if ($this->enable && $this->adv_query) {
                if ($this->type == 'category' && count($this->subCategoryies)) {
                    $order_query = ' GROUP BY prod.product_id ' . $order_query;
                }
                $adv_query = $this->adv_query;
            }
        }

        function onBeforeDisplayProductList(&$products)
        {
            if (!$this->enable) {
                return;
            }

            if (count($this->subCategoryies)) {
                addLinkToProducts($products);
            }
        }

        function onBeforeDisplayProductListView($view)
        {

            if (!$this->enable) {
                return;
            }

            $vrscripton = 3;

            $db = JFactory::getDbo();
            $query = 'SELECT MIN(prod.product_price) FROM `#__jshopping_products` AS prod
            LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
            WHERE prod.product_original_price > 0 AND pr_cat.category_id = '.$view->category_id
                .$this->_queryProductIds;
            $db->setQuery($query);
            $product_min_price = $db->loadResult();


            $query = 'SELECT MAX(prod.product_price) FROM `#__jshopping_products` AS prod
            LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
            WHERE prod.product_original_price > 0 AND pr_cat.category_id = '.$view->category_id
                .$this->_queryProductIds;
            $db->setQuery($query);
            $product_max_price = $db->loadResult();

            if (count($this->_products_manufacturers) > 0) {
                $vrscripton = 2;
                foreach ($this->_products_manufacturers as $key_manufacturers => $vr_manufacturers) {
                    $vrmanufacturers[$key_manufacturers] = count($this->_products_manufacturers[$key_manufacturers]);
                }
            }

            if (count($this->_array_filters) > 0) {
                $vrscripton = 2;
                foreach ($this->_array_filters as $key_filter => $vr_value_filter) {
                    foreach ($vr_value_filter as $key_value_filter => $value_filter) {
                        $vrfilters[$key_filter][$key_value_filter] = count($this->_array_filters[$key_filter][$key_value_filter]);
                    }
                }
            }

            $view->characteristicscount = json_encode($vrfilters);
            $view->manufacturercount = json_encode($vrmanufacturers);
            $view->productminprice = round($product_min_price,2);
            $view->productmaxprice = round($product_max_price,2);
            $view->vrscripton = $vrscripton;
            $view->vrtotal = count($this->_products_active_ids);

            return $view;
        }

    }

?>