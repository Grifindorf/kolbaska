<?php
/**
 * @version      4.9.1 13.08.2013
 * @author       MAXXmarketing GmbH
 * @package      Jshopping
 * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
 * @license      GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>

<div class="dishes">
    <div class="container">
        <!-- Default Heading -->
        <div class="default-heading">
            <!-- Crown image -->
            <!-- Heading -->
	        <h1><?=_JSHOP_PRODUCTS_CATEGORY?></h1>
            <!-- Paragraph -->
            <!-- Border -->
            <div class="border"></div>
        </div>
        <div class="row">

            <?php if (count($categories)) : ?>

                <?php foreach ($categories as $k => $category) : ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="dishes-item-container">
                            <!-- Image Frame -->
                            <div class="img-frame" onclick="window.location.href='<?php print $category->category_link;?>'">
                                <!-- Image -->
                                <img src="<?php print $image_category_path;?>/<?php if ($category->category_image) print $category->category_image;?>" class="img-responsive" alt="" />
                                <!-- Block for on hover effect to image -->
                                <div class="img-frame-hover">
                                    <!-- Hover Icon -->
                                    <a href="<?php print $category->category_link;?>"><i class="fa fa-cutlery"></i></a>
                                </div>
                            </div>
                            <!-- Dish Details -->
                            <div class="dish-details">
                                <!-- Heading -->
                                <a href="<?php print $category->category_link;?>"><h3><?php print $category->name?><span class="titledescr"><?php print $category->titledescr?></span></h3></a>

                            </div>
                        </div>
                    </div>
                <?php endforeach;?>

            <?php endif; ?>

        </div>
    </div>
</div>