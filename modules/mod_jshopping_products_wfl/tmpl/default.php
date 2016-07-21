<?php
# $Id: default.php
# package mod_jshopping_products_wfl
# file default.php
# author Aleksey M. Abrosimov wflab
# url http://wflab.ru
# copyright (C) 2013 Web Face Laboratory All rights reserved
# license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/mod_jshopping_products_wfl/style.css');
if($ribbon_behavior == 'scroll'){
    $suff = ($ribbon_orientation == 'hor')?"horizontal":'vertical';
}
else{
    $suff = "static";
}
?>

<div class="rand_products_wfl_<?php echo $suff; ?>" id="rand_products_wfl_<?php echo $module->id;?>">

<?php if($ribbon_behavior == 'scroll'): 
            $ver = new jVersion();
            $cV = version_compare($ver->getShortVersion(),"3.0.0",">=");  
            if($cV){
                JHTML::_('behavior.framework',true);
            }
            else{
                JHTML::_('behavior.mootools');
            }  
?>
    <span class="jspw_ribon_button_lt">&nbsp;</span>
<?php endif;
    $styles="overflow:hidden;";
    $styles.=($ribbon_orientation == 'hor')?"height:".$block_height."px;":"width:".$block_width."px;";
    $styles2 = "width:".$block_width."px;height:".$block_height."px;".(($ribbon_orientation == 'hor')?"float:left;":'');
?>
    <div class="jspw_ribbon" style="<?php echo $styles; ?>">
        <div class="jspw_<?php echo $ribbon_behavior; ?>" >
			<?php
			foreach($list as $prod):
			$alt=$prod->name;
			if($product_source == 'manuf_logo'){
			$prodLink = SEFLink('index.php?option=com_jshopping&controller=manufacturer&task=view&manufacturer_id='.$prod->manufacturer_id, 1);
			$imgSrc = $jshopConfig->image_manufs_live_path."/".$prod->manufacturer_logo;
			}
			else{
			$prodLink = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$prod->category_id.'&product_id='.$prod->product_id, 1);
			$buyLink = SEFLink('index.php?option=com_jshopping&controller=cart&task=add&category_id='.$prod->category_id.'&product_id='.$prod->product_id, 1);
			$fullImg = $jshopConfig->image_product_live_path."/".str_replace('thumb','full',$prod->product_thumb_image);
			$imgSrc = $jshopConfig->image_product_live_path."/".str_replace('thumb_','',$prod->product_thumb_image);?>
			<?php } ?>
			<div class="jspw_prod" style="<?php echo $styles2;?>">
				<div class="jspw_prodwrap">
					<div class="jspw_imgwrap">
						<a href="<?php echo ($on_image_click_behavior=='link')?$prodLink:$fullImg; ?>" rel="nofollow" <?php if($on_image_click_behavior=='lightbox') echo 'class="lightbox"';?> title="<?php echo $prod->name;?>"><img src="<?php echo $imgSrc; ?>" class="jspw_img" alt="<?php echo $alt; ?>"></a>
					</div>
					<div class="jspw_nameblock">
						<?php if(in_array('caption',$additional_params)):?>
							<span class="jspw_caption"><a href="<?php echo $prodLink; ?>" rel="nofollow"><?php echo $prod->name;?></a></span>
						<?php endif; ?>
						<?php if(in_array('add_manufacturer_name',$additional_params) && $prod->manufacturer->name):?>
							<span class="jspw_manufacturer_name"><?php echo $prod->manufacturer->name;?></span>
						<?php endif; ?>
					</div>
					<?php if(in_array('description',$additional_params) && $prod->short_description):?>
						<span class="jspw_descr"><?php echo $prod->short_description;?></span>
					<?php endif; ?>
					<?php if(in_array('add_old_price',$additional_params) && $prod->product_old_price):?>
						<div class="old_pricewrap">
							<span class="jspw_old_price"><?php echo formatprice($prod->product_old_price,null,1);?></span>
						</div>
					<?php endif; ?>
					<?php if(in_array('price',$additional_params) && $prod->product_price):?>
						<span class="jspw_price"><?php echo formatprice($prod->product_price,null,1);?></span>
					<?php endif; ?>
					<?php if(in_array('add_label',$additional_params) && $prod->_label_image):?>
						<img src="<?php echo $prod->_label_image; ?>" class="label_image" >
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
        </div>
    </div>
<?php if($ribbon_behavior == 'scroll'): ?>
    <span class="jspw_ribon_button_rb">&nbsp;</span>
<?php endif; ?>

</div>
