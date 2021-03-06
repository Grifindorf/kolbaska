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

<div class="dishes padd">
    <div class="container">
        <!-- Default Heading -->
        <div class="default-heading">
            <!-- Crown image -->
            <!-- Heading -->
            <h2><?=_JSHOP_PRODUCTS_CATEGORY?></h2>
            <!-- Paragraph -->
            <!-- Border -->
            <div class="border"></div>
        </div>
        <div class="row">


    <!--<div class="category_description">
        <?php print $this->category->description?>
    </div>-->

        <?php if (count($this->categories)) : ?>

        <?php foreach ($this->categories as $k => $category) : ?>
            <div class="col-md-3 col-sm-6">
                <div class="dishes-item-container">
                    <!-- Image Frame -->
                    <div class="img-frame" onclick="window.location.href='<?php print $category->category_link;?>'">
                        <!-- Image -->
                        <img src="<?php print $this->image_category_path;?>/<?php if ($category->category_image) print $category->category_image; else print $this->noimage;?>" class="img-responsive" alt="" />
                        <!-- Block for on hover effect to image -->
                        <div class="img-frame-hover">
                            <!-- Hover Icon -->
                            <a href="<?php print $category->category_link;?>"><i class="fa fa-cutlery"></i></a>
                        </div>
                    </div>
                    <!-- Dish Details -->
                    <div class="dish-details">
                        <!-- Heading -->
                        <a href="<?php print $category->category_link;?>"><h3><?php print $category->name?></h3></a>
                    </div>
                </div>
            </div>
        <?php endforeach;?>

        <?php endif; ?>

        </div>
    </div>
</div>