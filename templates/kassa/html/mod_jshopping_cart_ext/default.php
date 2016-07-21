<div id = "jshop_module_cart" class="btn-cart-md">
    <a class="cart-link" href = "<?php print SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1)?>">
        <!-- Image -->
        <img class="img-responsive" src="/templates/kolbaska/img/cart.png" alt="" />
        <!-- Heading -->
        <h4><?php print _JSHOP_CART?></h4>
        <span><?php print formatprice($cart->getSum(0,1))?></span>
        <div class="clearfix"></div>
    </a>
    <ul class="cart-dropdown" role="menu">
        <li class="delete"><div class="cart-item modulecart">В вашей корзине <div class="countpproducts"><?=$cart->count_product?></div> кг товаров</div></li>
        <?php
        $countprod = 0;
        $array_products = array();
        foreach($cart->products as $value){
            $array_products [$countprod] = $value;
            ?>
            <!--<li class="delete">
                <div class="cart-item">
                    <a href="javascript:void(null)"><i class="fa fa-times" onclick="deleteFromCart('+key+')"></i></a>
                    <img class="img-responsive img-rounded" src="http://kolbaska.com.ua/components/com_jshopping/files/img_products/<?php print $array_products [$countprod]["thumb_image"]; ?>" alt="" />
                    <span class="cart-title"><a href="<?php print $array_products [$countprod]["product_link"]; ?>"><?php print $array_products [$countprod]["product_name"]; ?></a></span>
                    <span class="cart-price pull-right red"><?php print formatprice($array_products [$countprod]["price"] * $array_products [$countprod]["quantity"]); ?> (<?php print $array_products [$countprod]["quantity"]; ?>)</span>
                    <div class="clearfix"></div>
                </div>
            </li>-->
            <?php $countprod++; ?>
        <?php } ?>
        <li>
            <!-- Cart items for shopping list href="<?php print SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1)?>" -->
            <div class="cart-item">
                <a class="btn btn-danger" data-toggle="modal" href="#shoppingcart1"><?php print _JSHOP_CHECKOUT?></a>
            </div>
        </li>
    </ul>
    <div class="clearfix"></div>
</div>

