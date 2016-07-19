<?php 
/**
* @version      4.8.0 05.11.2013
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>
<?php print $product->_tmp_var_start;?>

    <div class="shopping-item <?=($product->in_cart==1 ? 'inCart' : '')?> " id="product-<?=$product->product_id?>">
        <div class="flag flag-<?=strtolower($product->extra_field[1]['class'])?>"></div>
        <div class="description-pcategory">
            <?php print $product->short_description?>
        </div>
        <!-- Image -->
        <a class="categoryproductimg" href="<?php print $product->product_link?>"><img alt="<?php echo str_replace('"','\'',$product->name)?> - оптовый интернет-магазин kolbaska.com.ua" title="<?php echo str_replace('"','\'',$product->name)?> - оптовый интернет-магазин kolbaska.com.ua" class="img-responsive" src="<?php print $product->image?>" alt="" /></a>
        <!-- Shopping item name / Heading -->
        <p class="productcode"><?php print $product->product_ean?></p>
        <h4 class="pull-left"><a href="<?php print $product->product_link?>"><?php print $product->name?></a></h4>

        <?php
           /* if($_SERVER['REMOTE_ADDR']=='217.66.102.198' && $product->product_provider_id==3){
                ?>
                <input type="text" name="product_original_price" id="product_original_price<?=$product->product_id?>" value="<?=$product->product_original_price?>" />
                <button onclick="savePrice(<?=$product->product_id?>)">Save</button>
                <?php
            }*/
        ?>
        <div class="prices-category">
            <span class="item-price pull-right <?=($this->count_products_in_cart > 0 ? 'active' : 'active')?>"><?= formatprice($product->product_price)?>/<?=$product->basic_price_unit_id_name?></span>
            <span class="item-price pull-right <?=($this->count_products_in_cart < 5 ? '' : '')?>"><?=JText::_('ROZNIZA')?> - <?= formatprice($product->product_old_price)?>/<?=$product->basic_price_unit_id_name?></span>
        </div>

        <!--<span class="item-price pull-right"><?=($this->count_products_in_cart > 4 ? formatprice($product->product_price) : formatprice($product->product_old_price))?><?php print $product->_tmp_var_price_ext;?>/кг</span>-->
        <div class="clearfix"></div>
        <!-- Buy now button -->
        <?php $key = $product->in_cart_key;
        $quantity = ( $product->in_cart_qtty > 0 ? $product->in_cart_qtty : 1);
        ?>
        <div class="quantitychangecategory">
            <span class="btn btn-default btn-sm" onclick="changeQtty(<?=$product->product_id?>,'minus')">&nbsp;-</span><input type="text" class="quantitycategory" attr-key="<?=$product->product_id?>" id="quantity<?=$product->product_id?>" value="<?php print $quantity; ?>"><span class="btn btn-default btn-sm" onclick="changeQtty(<?=$product->product_id?>,'plus')">+</span>
        </div>
        <div class="visible-xs">
            <a class="btn btn-danger btn-sm <?=($product->in_cart==1 ? 'br-green' : 'br-red')?>" onclick="addToCart(<?=$product->product_id?>,true)" href="javascript:void(null)"><?=($product->in_cart==1 ? JText::_('INCART') : _JSHOP_BUY)?></a>
        </div>
        <!-- Shopping item hover block & link -->
        <div class="buttonbuycategoryWrap">
	        <div class="item-hover <?=($product->in_cart==1 ? 'br-green' : 'br-red')?> hidden-xs buttonbuycategory" ></div>
            <a class="link hidden-xs" onclick="addToCart(<?=$product->product_id?>,true)" href="javascript:void(null)"><?=($product->in_cart==1 ? JText::_('INCART') : _JSHOP_BUY)?></a>
        </div>
    </div>

<?php print $product->_tmp_var_end?>