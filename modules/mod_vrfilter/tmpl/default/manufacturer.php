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

?>
<li class="menu">
    <a href="#">Производитель</a>
<div id="manufacturers">
    <?php
    $tmp_active_manufacturers = $active_manufacturers;
    foreach ($manufacturers as $idman=>$man) {
        if ( count($active_manufacturers)==0 ) {
            echo '<div class="inputoption">
                <a id="manufacturer'.$idman.'" href="'.$category_url.'/'.($filters_url ? '?man='.$man['url'].'&filters='.$filters_url : '').(!$filters_url&&$link_price ? '?man='.$man['url'] : '').(!$filters_url&&!$link_price ? $man['url'] : '').($link_price ? '&'.$link_price : '').'">'.$man['name'].'<span> ('.count($man['products']).')</span></a>
            </div>';
        } elseif (count($active_manufacturers)==1) {
            if ( $active_manufacturers[$idman] ) {
                echo '<div class="inputoption active">
                    <a id="manufacturer'.$idman.'" href="'.$category_url.($filters_url ? '?filters='.$filters_url : '').($filters_url&&$link_price ? '&'.$link_price : '').(!$filters_url&&$link_price ? '?'.$link_price : '').'">'.$man['name'].'</a>
                </div>';
            } else {
                $href="man=".$manalias[current($active_manufacturers)].",".$man['url'];
                echo '<div class="inputoption">
                    <a id="manufacturer'.$idman.'" href="'.$category_url.'/?'.$href.$filters_href.($link_price ? '&'.$link_price : '').'">'.$man['name'].'<span> (+'.count($man['products']).')</span></a>
                </div>';
            }
        } elseif (count($active_manufacturers)==2) {
            if ( $tmp_active_manufacturers[$idman] ) {
                unset($active_manufacturers[$idman]);
                $current_man_id = array_shift($active_manufacturers);
                $href=$manalias[$current_man_id];
                $active_manufacturers[$idman]=$idman;
                $active_manufacturers[$current_man_id]=$current_man_id;
                echo '<div class="inputoption active">
                    <a id="manufacturer'.$idman.'" href="'.$category_url.'/'.( $filters_href ? '?man='.$href.$filters_href : $href ).($link_price ? '&'.$link_price : '').'">'.$man['name'].'</a>
                </div>';
            } else {
                $href="man=";
                foreach ($active_manufacturers as $act_man) {
                        $href .= $manalias[$act_man].",";
                }
                $href .= $man['url'];
                echo '<div class="inputoption">
                    <a id="manufacturer'.$idman.'" href="'.$category_url.'/?'.$href.$filters_href.($link_price ? '&'.$link_price : '').'">'.$man['name'].'<span> (+'.count($man['products']).')</span></a>
                </div>';
            }
        } else {
            if ( $tmp_active_manufacturers[$idman] ) {
                unset($active_manufacturers[$idman]);
                $count_active_manufacturers = count($active_manufacturers);
                $href="man=";
                foreach ($active_manufacturers as $act_man) {
                    if ($count_active_manufacturers>1) {
                        $href .= $manalias[$act_man].",";
                    } else {
                        $href .= $manalias[$act_man];
                    }
                    $count_active_manufacturers--;
                }
                $active_manufacturers[$idman]=$idman;
                echo '<div class="inputoption active">
                    <a id="manufacturer'.$idman.'" href="'.$category_url.'/?'.$href.$filters_href.($link_price ? '&'.$link_price : '').'">'.$man['name'].'</a>
                </div>';
            } else {
                $href="man=";
                foreach ($active_manufacturers as $act_man) {
                    $href .= $manalias[$act_man].",";
                }
                $href .= $man['url'];
                echo '<div class="inputoption">
                    <a id="manufacturer'.$idman.'" href="'.$category_url.'/?'.$href.$filters_href.($link_price ? '&'.$link_price : '').'">'.$man['name'].'<span> (+'.count($man['products']).')</span></a>
                </div>';
            }
        }
    }
    ?>
</div>
</li>