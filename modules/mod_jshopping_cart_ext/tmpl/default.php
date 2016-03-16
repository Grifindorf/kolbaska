<div id = "jshop_module_cart">
<table class = "module_cart_detail" width = "100%">
<?php 
  $countprod = 0;
  $array_products = array();
  foreach($cart->products as $value){
    $array_products [$countprod] = $value;
?> 
      <tr class="<?php  if ( ($countprod + 2) % 2 > 0) { print 'odd'; } else { print 'even'; }  ?>">
        <td class="name"><?php print $array_products [$countprod]["product_name"]; ?></td>
        <?php if ($show_count =='1') {?>
        <td class="qtty"><?php print $array_products [$countprod]["quantity"]; ?> x </td>
        <td class="summ"><?php print formatprice($array_products [$countprod]["price"]); ?></td>
        <?php }else {?>  
        <td class="qtty"> </td>
        <td class="summ"><?php print formatprice($array_products [$countprod]["price"] * $array_products [$countprod]["quantity"]); ?></td>        
        <?php }?>
      </tr>
    <?php $countprod++; ?>
<?php } ?>
</table>
<table width = "100%">
<tr>
    <td>
      <!-- <span id = "jshop_quantity_products"><?php print $cart->count_product?></span>&nbsp;<?php print JText::_('PRODUCTS')?> -->
      <span id = "jshop_quantity_products"><strong><?php print JText::_('SUM_TOTAL')?>:</strong>&nbsp;</span>&nbsp;
    </td>
    <td>
      <span id = "jshop_summ_product"><?php print formatprice($cart->getSum(0,1))?></span>
    </td>
</tr>
<tr>
    <td colspan="2" align="right" class="goto_cart">
      <a href = "<?php print SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1)?>"><?php print JText::_('GO_TO_CART')?></a>
    </td>
</tr>
</table>
</div>

