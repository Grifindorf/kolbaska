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

if ($extra_fields) {
    $query = "SELECT id,in_type,`name_ru-RU` as name, cats FROM #__jshopping_products_extra_fields
              WHERE id IN (".implode(',',array_keys($extra_fields)).") ORDER BY ordering";
    $db->setQuery($query);
    $results_filters = $db->loadObjectList();
}
foreach($results_filters as $filter) {
    if (in_array($category_id,unserialize($filter->cats))) {
    ?>
    <li class="menu">
        <a href="#"><?php echo $filter->name; ?></a>
        <div id="extrafields" <?php if ( $filter->in_type == 2 ) { echo 'class="trackbar"'; echo 'attr-filter-id="'.$filter->id.'"'; } ?>>

    <?php
        $query = "SELECT id,`name_ru-RU` as name FROM #__jshopping_products_extra_field_values
                      WHERE field_id = " . $filter->id . " ORDER BY ordering";
        $db->setQuery($query);
        $results_filter_values = $db->loadObjectList();

        foreach ($results_filter_values as $filter_value) {
            if (count($extra_fields[$filter->id][$filter_value->id]) > 0) {
                if (count($active_extra_fields) == 0) {
                    echo '<div class="inputoption">
                                <a id="' . $filter->id . 'extrafield' . $filter_value->id . '" href="' . $category_url . '?' . ($man_href ? $man_href . '&' : '') . 'filters=' . $filter->id . ':' . $filter_value->id . ($link_price ? '&' . $link_price : '') . '">' . $filter_value->name . '<span> (' . count($extra_fields[$filter->id][$filter_value->id]) . ')</span></a>
                              </div>';
                } else {
                    $link_extra_url = "filters=";
                    if ($active_extra_fields[$filter->id] && in_array($filter_value->id, $active_extra_fields[$filter->id]) && count($active_extra_fields[$filter->id]) == 1 && count($active_extra_fields) == 1) {
                        echo '<div class="inputoption active">
                                        <a id="' . $filter->id . 'extrafield' . $filter_value->id . '" href="' . $category_url . '/' . ($man_href_single && !$link_price ? $man_href_single : '') . ($man_href && !$man_href_single ? '?' . $man_href : '') . ($man_href && $man_href_single && $link_price ? '?' . $man_href : '') . ($man_href && $link_price ? '&' . $link_price : '') . (!$man_href && $link_price ? '?' . $link_price : '') . '">' . $filter_value->name . '</a>
                                    </div>';
                    } elseif ($active_extra_fields[$filter->id] && in_array($filter_value->id, $active_extra_fields[$filter->id]) && count($active_extra_fields[$filter->id]) == 1) {
                        $key = array_search($filter_value->id, $active_extra_fields[$filter->id]);
                        /*unset($active_extra_fields[$filter->id]);
                        foreach ($active_extra_fields as $ac_key => $ac_fil) {
                            $link_extra_url .= $ac_key . ':' . implode(',', $ac_fil) . ';';
                        }*/
                        $vr_bug_check = $active_extra_fields;
                        unset($vr_bug_check[$filter->id][$key]);
                        foreach ($vr_bug_check as $ac_key => $ac_fil) {
                            $link_extra_url .= $ac_key . ':' . implode(',', $ac_fil) . ';';
                        }
                        //$active_extra_fields[$filter->id][] = $filter_value->id;
                        echo '<div class="inputoption active">
                                        <a id="' . $filter->id . 'extrafield' . $filter_value->id . '" href="' . $category_url . '?' . ($man_href ? $man_href . '&' : '') . $link_extra_url . ($link_price ? '&' . $link_price : '') . '">' . $filter_value->name . '</a>
                                    </div>';
                    } elseif ($active_extra_fields[$filter->id] && in_array($filter_value->id, $active_extra_fields[$filter->id])) {
                        $key = array_search($filter_value->id, $active_extra_fields[$filter->id]);
                        $vr_bug_check = $active_extra_fields;
                        unset($vr_bug_check[$filter->id][$key]);
                        foreach ($vr_bug_check as $ac_key => $ac_fil) {
                            $link_extra_url .= $ac_key . ':' . implode(',', $ac_fil) . ';';
                        }
                        /*$active_extra_fields[$filter->id][] = $filter_value->id;
                        unset($active_extra_fields[$filter->id][$key]);*/
                        echo '<div class="inputoption active">
                                        <a id="' . $filter->id . 'extrafield' . $filter_value->id . '" href="' . $category_url . '?' . ($man_href ? $man_href . '&' : '') . $link_extra_url . ($link_price ? '&' . $link_price : '') . '">' . $filter_value->name . '</a>
                                    </div>';
                    } elseif ($active_extra_fields[$filter->id] && !in_array($filter_value->id, $active_extra_fields[$filter->id])) {
                        $active_extra_fields[$filter->id][] = $filter_value->id;
                        foreach ($active_extra_fields as $ac_key => $ac_fil) {
                            $link_extra_url .= $ac_key . ':' . implode(',', $ac_fil) . ';';
                        }
                        $key = array_search($filter_value->id, $active_extra_fields[$filter->id]);
                        unset($active_extra_fields[$filter->id][$key]);
                        echo '<div class="inputoption">
                                        <a id="' . $filter->id . 'extrafield' . $filter_value->id . '" href="' . $category_url . '?' . ($man_href ? $man_href . '&' : '') . $link_extra_url . ($link_price ? '&' . $link_price : '') . '">' . $filter_value->name . '<span> (+' . count($extra_fields[$filter->id][$filter_value->id]) . ')</span></a>
                                    </div>';
                    } else {
                        $active_extra_fields[$filter->id][] = $filter_value->id;
                        foreach ($active_extra_fields as $ac_key => $ac_fil) {
                            $link_extra_url .= $ac_key . ':' . implode(',', $ac_fil) . ';';
                        }
                        unset($active_extra_fields[$filter->id]);
                        echo '<div class="inputoption">
                                        <a id="' . $filter->id . 'extrafield' . $filter_value->id . '" href="' . $category_url . '?' . ($man_href ? $man_href . '&' : '') . $link_extra_url . ($link_price ? '&' . $link_price : '') . '">' . $filter_value->name . '<span> (+' . count($extra_fields[$filter->id][$filter_value->id]) . ')</span></a>
                                    </div>';
                    }
                }
            }

        }
            ?>
        </div>
    </li>
    <?php }
}


