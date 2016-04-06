<?php
/*
# ------------------------------------------------------------------------
# Extensions for Joomla 2.5.x - Joomla 3.x
# ------------------------------------------------------------------------
# Copyright (C) 2011-2014 Ext-Joom.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Ext-Joom.com
# Websites:  http://www.ext-joom.com 
# Date modified: 06/12/2013 - 13:00
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die;
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}
?>

<script type="text/javascript">
		jQuery.noConflict();
jQuery(document).ready(function(){
	jQuery("#owl-example-<?php echo $ext_id;?>").owlCarousel({
		items : <?php echo $ext_items; ?>,
		itemsCustom : <?php echo $ext_itemscustom; ?>,
		itemsDesktop : <?php echo $ext_itemsdesktop; ?>,
		itemsDesktopSmall : <?php echo $ext_itemsdesktopsmall; ?>,
		itemsTablet : <?php echo $ext_itemstablet; ?>,
		itemsTabletSmall : <?php echo $ext_itemstabletsmall; ?>,
		itemsMobile : <?php echo $ext_itemsmobile; ?>,

		navigation : <?php echo $ext_navigation; ?>,
		pagination : <?php echo $ext_pagination; ?>,		
		paginationNumbers : <?php echo $ext_paginationnumbers; ?>


	});


});
</script>

<div class="mod_ext_owl_carousel_k2_content <?php echo $moduleclass_sfx ?>">	
	
	<div id="owl-example-<?php echo $ext_id; ?>" class="owl-carousel owl-theme" >
	<?php if(count($items)): ?>
		<?php foreach ($items as $key=>$item):	?>
		<div class="ext-item-wrap">	 

			<?php if($params->get('itemTitle')): ?>
			<div class="ext-itemtitle">
				<a class="moduleItemTitle" href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
			</div>
			<?php endif; ?>


		    <?php if($params->get('itemImage') || $params->get('itemIntroText')): ?>
			
			    <?php if($params->get('itemImage') && isset($item->image)): ?>
				<div class="ext-itemimage">
					<a class="ext-moduleitemimage" href="<?php echo $item->link; ?>" title="<?php echo JText::_('K2_CONTINUE_READING'); ?> &quot;<?php echo K2HelperUtilities::cleanHtml($item->title); ?>&quot;">
						<img src="<?php echo $item->image; ?>" alt="<?php echo K2HelperUtilities::cleanHtml($item->title); ?>"/>
					</a>
				</div>
			    <?php endif; ?>

				<?php if($params->get('itemIntroText')): ?>
				<div class="ext-itemintrotext"><?php echo $item->introtext; ?></div>
				<?php endif; ?>
		  
			<?php endif; ?>

			<?php if($params->get('itemExtraFields') && count($item->extra_fields)): ?>
			<div class="ext-moduleitemextrafields">
				<b><?php echo JText::_('K2_ADDITIONAL_INFO'); ?></b>
				<ul>
					<?php foreach ($item->extra_fields as $extraField): ?>
							<?php if($extraField->value != ''): ?>
							<li class="type<?php echo ucfirst($extraField->type); ?> group<?php echo $extraField->group; ?>">
								<?php if($extraField->type == 'header'): ?>
								<h4 class="moduleItemExtraFieldsHeader"><?php echo $extraField->name; ?></h4>
								<?php else: ?>
								<span class="moduleItemExtraFieldsLabel"><?php echo $extraField->name; ?></span>
								<span class="moduleItemExtraFieldsValue"><?php echo $extraField->value; ?></span>
								<?php endif; ?>
								<div class="clr"></div>
							</li>
							<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
 

			<?php if($params->get('itemCategory')): ?>
			<?php echo JText::_('K2_IN') ; ?> <a class="moduleItemCategory" href="<?php echo $item->categoryLink; ?>"><?php echo $item->categoryname; ?></a>
			<?php endif; ?>

		 
			<?php if($params->get('itemReadMore') && $item->fulltext): ?>
			<a class="moduleItemReadMore" href="<?php echo $item->link; ?>">
				<?php echo JText::_('K2_READ_MORE'); ?>
			</a>
			<?php endif; ?>
	  
		</div>
		<?php endforeach; ?>
	<?php endif; ?>	
	</div>
	
	<div style="clear:both;"></div>
</div>
