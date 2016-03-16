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

$jinput = JFactory::getApplication()->input;
$category_id = $jinput->get('category_id');
$category_url = JRoute::_('index.php?option=com_jshopping&controller=category&category_id='.$category_id.'&task=view');

$active_manufacturers = array();
$manufacturer_id = $jinput->get('manufacturer_id');
$manufacturer_ids = $jinput->get('man','','STRING');
$price = $jinput->get('price','','STRING');
$filters_url = $jinput->get('filters','','STRING');
if ($manufacturer_id) {
    $manalias = JSFactory::getAliasManufacturer();
    $active_manufacturers[$manufacturer_id] = $manufacturer_id;
} elseif ( $manufacturer_ids ) {
    $manalias = JSFactory::getAliasManufacturer();
    foreach (explode(',',$manufacturer_ids) as $manurl) {
        if(strlen($manurl)>0){
            $manid_search = array_search($manurl,$manalias);
            $active_manufacturers[$manid_search] = $manid_search;
        }
    }
}
if ($active_manufacturers) {
    $man_href="man=";
    $count_active_manufacturers=count($active_manufacturers);
    foreach ($active_manufacturers as $act_man) {
        if ($count_active_manufacturers>1) {
            $man_href .= $manalias[$act_man].",";
        } else {
            $man_href .= $manalias[$act_man];
        }
        $count_active_manufacturers--;
    }
    if (count($active_manufacturers)==1) {
        $vractive_manufacturers = $active_manufacturers;
        $man_href_single = $manalias[array_shift($vractive_manufacturers)];
    }
}
if ($filters_url) {
    $filters_href = "&filters=".$filters_url;
    $explode_tmp = explode(';',$filters_url);
    foreach ($explode_tmp as $exp) {
        $explode_tmp_val = explode(':',$exp);
        $extra_field_active_id = array_shift($explode_tmp_val);
        if ($extra_field_active_id) {
            $active_extra_fields[$extra_field_active_id] = explode(',',array_shift($explode_tmp_val));
        }
    }
}
$current_link = $category_url.'?';
if ($active_manufacturers or $filters_url) {
    if ($active_manufacturers) {
        $current_link .= $man_href;
        $current_link .= '&';
    }
    if ($filters_url) {
        $current_link .= "filters=" . $filters_url;
        $current_link .= '&';
    }
}

$db = JFactory::getDbo();

$select = '';
$array_base_filter = array();
$query = "SELECT id,in_type,`name_ru-RU` as name, cats FROM #__jshopping_products_extra_fields
              ORDER BY ordering";
$db->setQuery($query);
$results_filters = $db->loadObjectList();
foreach($results_filters as $r_filter){
    if (in_array($category_id,unserialize($r_filter->cats))) {
        $select .= 'jsp.`extra_field_'.$r_filter->id.'`, ';
        $array_base_filter[] = $r_filter->id;
    }
}
if ($select != '') {
    $select = ", ".substr($select,0,strlen($select)-2);
}

$query = "SELECT jsp.product_id,jsm.manufacturer_id,jsm.`name_ru-RU` as manufacturer_name,jsm.`alias_ru-RU` as manufacturer_url {$select} FROM #__jshopping_products as jsp
          INNER JOIN `#__jshopping_products_to_categories` jspc USING (product_id)
          INNER JOIN `#__jshopping_manufacturers` jsm ON jsm.manufacturer_id = jsp.product_manufacturer_id
          WHERE jsp.product_original_price > 0 AND jspc.category_id = ".$category_id." ORDER BY jsm.`name_ru-RU`";
$db->setQuery($query);
$results_products = $db->loadObjectList();
$manufacturers = array();
$extra_fields = array();
foreach ($results_products as $res) {
    $manufacturers[$res->manufacturer_id]['name']=$res->manufacturer_name;
    $manufacturers[$res->manufacturer_id]['url']=$res->manufacturer_url;
    $manufacturers[$res->manufacturer_id]['products'][]=$res->product_id;
    foreach($array_base_filter as $base_filter){
        $extra_fields[$base_filter][$res->{'extra_field_'.$base_filter}][] = $res->{'extra_field_'.$base_filter};
    }
}

$query = "SELECT MIN(product_price) FROM #__jshopping_products jsp
          INNER JOIN `#__jshopping_products_to_categories` jspc USING (product_id)
          WHERE jsp.product_original_price > 0 AND jspc.category_id = ".$category_id."";
$db->setQuery($query);
$product_min_price = $db->loadResult();
$product_min_price = round($product_min_price,2);
$query = "SELECT MAX(product_price) FROM #__jshopping_products jsp
          INNER JOIN `#__jshopping_products_to_categories` jspc USING (product_id)
          WHERE jsp.product_original_price > 0 AND jspc.category_id = ".$category_id."";
$db->setQuery($query);
$product_max_price = $db->loadResult();
$product_max_price = round($product_max_price,2);

if ($price) {
    $link_price = 'price='.$price;
    $filter_min_price=explode(':',$price)[0];
    $filter_max_price=explode(':',$price)[1];
}

?>

<div class="vrfilter">
    <div class="price-slider vertical">
	<?php
    $folder = dirname(__FILE__).'/default/';
    @include $folder.'price'.'.php';
    ?>
        <a class="filters-reset" href="<?php echo $category_url; ?>">
            <i class="icon-ccw"></i>
            Сбросить все фильтры
        </a>
    </div>

<script>
    var vrcategory = '<?php echo $category_url ?>';
    var vrmanufacturers = '<?php echo $man_href ?>';
    var vrfilters = '<?php echo json_encode($active_extra_fields); ?>';
    var vrprices = '<?php echo $jinput->get('price','','STRING'); ?>';
</script>

    <ul id="filtersWrap" class="category-menu">
    <?php
    @include $folder.'manufacturer'.'.php';
    @include $folder.'characteristic'.'.php';
    ?>
    </ul>
</div>