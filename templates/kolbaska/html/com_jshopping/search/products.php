<?php 
/**
* @version      4.3.1 13.08.2013
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
            <h2><?php print $this->search?></h2>
            <!-- Paragraph -->
            <!-- Border -->
            <div class="border"></div>
        </div>
        <div class="row">
            <?php

            if (count($this->rows)){ ?>
                <div class="jshop_list_product">
                    <?php
                    //include(dirname(__FILE__)."/../".$this->template_block_form_filter);
                    if (count($this->rows)){
                        include(dirname(__FILE__)."/../".$this->template_block_list_product);
                    }
                    if ($this->display_pagination){
                        include(dirname(__FILE__)."/../".$this->template_block_pagination);
                    }
                    ?>
                </div>
            <?php }?>
        </div>
    </div>
</div>

