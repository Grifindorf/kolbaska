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
<!-- Inner Content -->
<div class="inner-page">
    <div class="shopping">
        <div class="container">
            <!-- Shopping items content -->
            <div class="shopping-content">
                <div class="row">

                    <?php
                    $count = 0;
                    foreach ($this->rows as $k=>$product) : ?>

                        <div class="col-md-3 col-sm-6">
                            <?php include(dirname(__FILE__)."/".$product->template_block_product);?>
                        </div>
                    <?php
                    $count++;
                        if($count==4) { echo '<div class="clearfix"></div>'; $count = 0; }
                    endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</div>