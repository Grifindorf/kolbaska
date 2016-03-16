<?php
/**
* @version      4.9.0 13.08.2013
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
$db = JFactory::getDbo();
$query = "SELECT * FROM `#__currency`";
$db->setQuery($query);
$results = $db->loadObjectList();
foreach($results as $result){
    $array_currency[$result->id]['name'] = $result->name;
    $array_currency[$result->id]['value'] = $result->value;
}
?>
<script>
    var currency = new Array();
<?php
foreach($results as $result){
    ?>
    currency[<?=$result->id?>] = new Array();
    currency[<?=$result->id?>][0] = '<?=$result->name?>';
    currency[<?=$result->id?>][1] = '<?=$result->value?>';
    <?php
}
?>
</script>

<div id="main-page" class="tab-pane">
    <div class="col100">
    <table class="admintable" width="90%">
        <tr>
            <td class="key" style="width:180px;">
                <?php echo _JSHOP_PUBLISH;?>
            </td>
            <td>
                <input type="checkbox" name="product_publish" id="product_publish" value="1" <?php if ($row->product_publish) echo 'checked="checked"'?> />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="admintable" width="90%">
                    <tr>
                        <td class="key">
                            Поставщик
                        </td>
                        <td>
                            <select id="provider" name="product_provider_id">
                                <option <?=( ($row->product_provider_id==0 or $row->product_provider_id=='') ? 'selected="selected"' : '')?> value="0">Выберите поставщика</option>
                                <?php
                                $query = "SELECT * FROM `#__jshopping_providers`";
                                $db->setQuery($query);
                                $results = $db->loadObjectList();
                                $providers = array();
                                foreach($results as $result){
                                    $providers[$result->manufacturer_id]['name'] = $result->{'name_ru-RU'};
                                    $providers[$result->manufacturer_id]['opt'] = $result->opt_percent;
                                    $providers[$result->manufacturer_id]['rozn'] = $result->rozn_percent;
                                    $providers[$result->manufacturer_id]['currency'] = $result->currency;
                                    $providers[$result->manufacturer_id]['currency_name'] = $array_currency[$result->currency]['name'];
                                    ?>
                                        <option <?=($row->product_provider_id==$result->manufacturer_id ? 'selected="selected"' : '')?> data-currency="<?=$result->currency?>" data-opt="<?=$result->opt_percent?>" data-rozn="<?=$result->rozn_percent?>" value="<?=$result->manufacturer_id?>"><?=$result->{'name_ru-RU'}?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="key">
                            Цена:
                        </td>
                        <td>
                            <input type="text" name="product_original_price" id="product_original_price" value="<?=$row->product_original_price?>" /> <span class="currency_code"><?=($row->product_provider_id > 0 ? $providers[$row->product_provider_id]['currency_name'] : '')?></span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

     <tr>
       <td class="key">
         Цена опт*
       </td>
       <td>
         <input type="text" name="product_price" id="product_price" value="<?php echo $row->product_price?>" <?php if (!$this->withouttax){?> onkeyup="updatePrice2(<?php print $jshopConfig->display_price_admin;?>)" <?php }?> /> <?php echo $this->lists['currency'];?>
       </td>
     </tr>
     <tr>
        <td class="key">
           Цена розничная
        </td>
        <td>
            <input type="text" name="product_old_price" id="product_old_price" value="<?php echo $row->product_old_price?>" />
        </td>
     </tr>
     <tr>
         <td class="key">
             Штрихкод на упаковке
         </td>
         <td>
             <input type="text" name="barcode2" id="barcode2" value="<?php echo $row->barcode2?>" />
         </td>
     </tr>
     <?php if ($jshopConfig->admin_show_weight){?>
     <tr>
       <td class="key">
         <?php echo _JSHOP_PRODUCT_WEIGHT;?>
       </td>
       <td>
         <input type="text" name="product_weight" id="product_weight" value="<?php echo $row->product_weight?>" /> <?php print sprintUnitWeight();?>
       </td>
     </tr>
	 <?php }?>
     <tr>
       <td class="key">
         <?php echo _JSHOP_EAN_PRODUCT;?>
       </td>
       <td>
         <input type="text" name="product_ean" id="product_ean" value="<?php echo $row->product_ean?>" onkeyup="updateEanForAttrib()"; />
       </td>
     </tr>

     <tr>
       <td class="key">
         <?php echo _JSHOP_QUANTITY_PRODUCT;?>*
       </td>
       <td>
         <div id="block_enter_prod_qty" style="padding-bottom:2px;<?php if ($row->unlimited) print "display:none;";?>">
             <input type="text" name="product_quantity" id="product_quantity" value="<?php echo $row->product_quantity?>" <?php if ($this->product_with_attribute){?>readonly="readonly"<?php }?> />
             <?php if ($this->product_with_attribute){ echo JHTML::tooltip(_JSHOP_INFO_PLEASE_EDIT_AMOUNT_FOR_ATTRIBUTE); } ?>
         </div>
         <div>         
            <input type="checkbox" name="unlimited" value="1" onclick="ShowHideEnterProdQty(this.checked)" <?php if ($row->unlimited) print "checked";?> /> <?php print _JSHOP_UNLIMITED;?>
         </div>         
       </td>
     </tr>
     <tr>
         <td class="key">
             <?php echo _JSHOP_UNIT_MEASURE;?>
         </td>
         <td>
             <?php echo $lists['basic_price_units'];?>
         </td>
     </tr>
     <tr>
         <td class="key">
             Вес/Объем
         </td>
         <td>
             <input type="text" name="weight" id="weight" value="<?php echo $row->weight?>" />
             <select name="weight_unit">
                 <option value="кг." <?=($row->weight_unit == 'кг.' ? 'selected="selected"' : '')?>>кг.</option>
                 <option value="г." <?=($row->weight_unit == 'г.' ? 'selected="selected"' : '')?>>г.</option>
                 <option value="л." <?=($row->weight_unit == 'л.' ? 'selected="selected"' : '')?>>л.</option>
                 <option value="мл." <?=($row->weight_unit == 'мл.' ? 'selected="selected"' : '')?>>мл.</option>
             </select>
         </td>
     </tr>
     <tr>
       <td class="key">
         <?php echo _JSHOP_NAME_MANUFACTURER;?>
       </td>
       <td>
         <?php echo $lists['manufacturers'];?>
       </td>
     </tr>
     <tr>
       <td class="key">
         <?php echo _JSHOP_CATEGORIES;?>*
       </td>
       <td>
         <?php echo $lists['categories'];?>
       </td>
     </tr>

     <?php if ($jshopConfig->admin_show_vendors && $this->display_vendor_select) { ?>
     <tr>
       <td class="key">
         <?php echo _JSHOP_VENDOR;?>
       </td>
       <td>
         <?php echo $lists['vendors'];?>
       </td>
     </tr>
     <?php }?>
     
     <?php if ($jshopConfig->admin_show_delivery_time) { ?>
     <tr>
       <td class="key">
         <?php echo _JSHOP_DELIVERY_TIME;?>
       </td>
       <td>
         <?php echo $lists['deliverytimes'];?>
       </td>
     </tr>
     <?php }?>
     
     <?php if ($jshopConfig->admin_show_product_labels) { ?>
     <tr>
       <td class="key">
         <?php echo _JSHOP_LABEL;?>
       </td>
       <td>
         <?php echo $lists['labels'];?>
       </td>
     </tr>
     <?php }?>

     <tr>
         <td class="key"><?php echo _JSHOP_URL; ?></td>
         <td>
             <input type="text" name="product_url" id="product_url" value="<?php echo $row->product_url?>" size="80" />
         </td>
     </tr>
     <tr>
         <td class="key">
             <?php echo _JSHOP_TEMPLATE_PRODUCT;?>
         </td>
         <td>
             <?php echo $lists['templates'];?>
         </td>
     </tr>


     <?php if ($jshopConfig->return_policy_for_product){?>
     <tr>
       <td class="key">
         <?php echo _JSHOP_RETURN_POLICY_FOR_PRODUCT;?>
       </td>
       <td>
         <?php echo $lists['return_policy'];?>
       </td>
     </tr>
     <?php if (!$jshopConfig->no_return_all){?>  
     <tr>
       <td class="key">
         <?php echo _JSHOP_NO_RETURN;?>
       </td>
       <td>
         <input type="hidden" name="options[no_return]"  value="0" />
         <input type="checkbox" name="options[no_return]" value="1" <?php if ($row->product_options['no_return']) echo 'checked = "checked"';?> />
       </td>
     </tr>
     <?php }?>
     <?php }?>
     <?php $pkey='plugin_template_info'; if ($this->$pkey){ print $this->$pkey;}?>
   </table>


        <script>
            jQuery(document).ready(function(){
                jQuery('#provider').on('change',function(){
                    var opt_percent = jQuery("#provider option:selected").attr('data-opt');
                    var rozn_percent = jQuery("#provider option:selected").attr('data-rozn');
                    var currency_t = jQuery("#provider option:selected").attr('data-currency');
                    jQuery('.currency_code').html(currency[currency_t][0]);
                });
                jQuery('#product_original_price').on('change',function(){
                    var currency_t = jQuery("#provider option:selected").attr('data-currency');
                    var price_value = parseFloat(jQuery('#product_original_price').val());
                    if(currency_t>1){
                        price_value = parseFloat(price_value * currency[currency_t][1]);
                    }
                    var price_opt = price_value + parseFloat((price_value/100)*parseFloat(jQuery("#provider option:selected").attr('data-opt')));
                    var price_rozn = price_value + parseFloat((price_value/100)*parseFloat(jQuery("#provider option:selected").attr('data-rozn')));
                    jQuery('#product_price').val(price_opt.toFixed(2));
                    jQuery('#product_old_price').val(price_rozn.toFixed(2));
                });
            });
        </script>

   </div>
   <div class="clr"></div>
</div>