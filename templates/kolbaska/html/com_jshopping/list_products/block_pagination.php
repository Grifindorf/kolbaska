<?php 
/**
* @version      4.8.0 13.08.2013
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>

    <?php print $this->pagination?>

<?php

if ($this->total > 20 && ($_GET['start']+20) < $this->total) {

    $link = explode('?', $_SERVER['REQUEST_URI'], 2);

    if($_GET['start']=='') {
        $page = 1;
    } else {
        $page = ($_GET['start'] / 20)+1;
    }
    ?>
        <div id="buttonloadmoreproducts" data-page="<?=$page?>" data-link="<?=$link[0]?>" data-total="<?=$this->total?>" onclick="loadmoreproducts(<?=$this->category->category_id?>)" class="buttonloadmoreproducts">Показать еще 20 товаров</div>
    <?php
}

?>
