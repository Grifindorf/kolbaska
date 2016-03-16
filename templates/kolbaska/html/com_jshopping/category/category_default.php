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

<?php if($_GET['temp']!='ajax'){ ?>

<?php if (count($this->categories)) : ?>

<div class="dishes padd">
    <div class="container">
        <!-- Default Heading -->
        <div class="default-heading">
            <!-- Crown image -->
            <!-- Heading -->
            <h1><?php print $this->category->name?></h1>
            <!-- Paragraph -->
            <!-- Border -->
            <div class="border"></div>
        </div>
        <div class="row">

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
                                <a href="<?php print $category->category_link;?>"><h3><?php print $category->name?><span class="titledescr"><?php print $category->titledescr?></span></h3></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>

            <?php endif; ?>

        </div>
    </div>
</div>
    <div class="dishes">
        <div class="container">
            <div class="description">
                <?php print $this->category->description?>
                <p><?php print $this->category->name?> с доставкой в Киев, Харьков, Одессу, Львов, Днепропетровск, Донецк, Винницу, Луганск, Луцк, Житомир, Запорожье, Ивано-Франковск, Николаев, Полтаву, Ровно, Сумы, Тернополь, Ужгород, Херсон, Хмельницкий, Черкассы, Чернигов, Черновцы.</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!count($this->categories)) : ?>
<div class="dishes padd">
    <div class="container">
        <!-- Default Heading -->
        <div class="default-heading">
            <!-- Crown image -->
            <!-- Heading -->
            <h1><?php print $this->category->name?>  <?php
                if (JRequest::getInt('manufacturer_id')) {
                    $manalias = JSFactory::getNameManufacturer();
                    print $manalias[JRequest::getInt('manufacturer_id')];
                }
                ?></h1>
            <?php
            $db = JFactory::getDbo();

            $query = "SELECT COUNT(`product_id`) FROM `#__jshopping_products` WHERE `product_id` IN (SELECT `product_id` FROM `#__jshopping_products_to_categories` WHERE `category_id` = ".$this->category->category_id.") AND `product_original_price` > 0 AND `product_publish` = 1";
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
            <p><?php print $this->category->name?> с доставкой в Киев, Харьков, Одессу, Львов, Днепропетровск, Донецк, Винницу, Луганск, Луцк, Житомир, Запорожье, Ивано-Франковск, Николаев, Полтаву, Ровно, Сумы, Тернополь, Ужгород, Херсон, Хмельницкий, Черкассы, Чернигов, Черновцы.</p>
        </div>
    </div>
</div>

<?php endif; ?>

<?php } else {

    include(dirname(__FILE__)."/products.php");
    die;

} ?>