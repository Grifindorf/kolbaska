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
            <h2><?php print $this->category->name?></h2>
            <?php
            $db = JFactory::getDbo();

            $query = "SELECT COUNT(`product_id`) FROM `#__jshopping_products_to_categories` WHERE `category_id` = ".$this->category->category_id;
            $db->setQuery($query);
            $count_products = $db->loadResult();

            ?>
            <span>Количество товаров: <?=$count_products?></span>
            <!-- Paragraph -->
            <!-- Border -->
            <div class="border"></div>
        </div>
        <div class="row">

        </div>
    </div>
</div>

<?php include(dirname(__FILE__)."/products.php");?>

<div class="dishes">
    <div class="container">
<div class="description">
    <?php print $this->category->description?>
</div>
        </div>
    </div>

<script>

</script>