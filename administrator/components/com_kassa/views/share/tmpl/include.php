<?php
if ( $this->item->type != 0 ) {

$result = array();

$query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
$db->setQuery($query);
$result = $db->LoadAssoc();

echo '<input name="jform[typesearch]" id="typesearch" value="'.$result['typesearch'].'" type="hidden"/>';

echo '<input name="jform[typebonusproduct]" value="'.$result['singlebonus'].'" id="typeproduct" type="hidden"/>';

if ( $this->item->type ==4 ) {
?>
<div class="span4">

    <div>
        Выберите тип добавления акции: <br/><br/>
        <input type="radio" value="0" <?php echo ($result['typesearch']==0 ? 'checked="checked"':'') ?> name="grouptype" /> Категория
        <br/>
        <!--<input type="radio" value="1" <?php echo ($result['typesearch']==1 ? 'checked="checked"':'') ?> name="grouptype" /> Категория с выбором товаров
                            <br/>-->
        <input type="radio" value="2" <?php echo ($result['typesearch']==2 ? 'checked="checked"':'') ?> name="grouptype" /> Бренд
        <br/>
        <!--<input type="radio" value="3" <?php echo ($result['typesearch']==3 ? 'checked="checked"':'') ?> name="grouptype" /> Бренд с выбором товаров
                            <br/>-->
        <input type="radio" value="6" <?php echo ($result['typesearch']==6 ? 'checked="checked"':'') ?> name="grouptype" /> Поиск товаров
    </div>

</div>
<div id="resultselect" class="span8">

    Выбрано:

    <div id="category" <?php echo ($result['typesearch']==0 || $result['typesearch']==1 ? '':'style="display:none;"') ?>>Категории: <br/>
        <table>
            <tbody>
            <?php
            if (json_decode($result['category_id'])) {
                foreach (json_decode($result['category_id']) as $category) {
                    $query = "SELECT * FROM `#__jshopping_categories` WHERE category_id = " . $category . " ";
                    $db->setQuery($query);
                    $result_category = $db->LoadAssoc();
                    ?>
                    <tr id="category<?php echo $category; ?>">
                        <td style="width: 400px">
                            <a target="_blank"
                               href="/category/<?php echo $result_category['alias_ru-RU']; ?>">
                                                        <span
                                                            class="title"><?php echo $result_category['name_ru-RU']; ?></span>
                            </a>
                        </td>
                        <td style="padding-left: 10px;">
                            <input type="hidden" name="jform[typecategory][]"
                                   value="<?php echo $category; ?>"/>
                            <a onclick="unselect('category','<?php echo $category; ?>')"
                               href="javascript:void(null)">убрать</a>
                        </td>
                    </tr>
                <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>

    <div id="brend" <?php echo ($result['typesearch']==2 || $result['typesearch']==3 ? '':'style="display:none;"') ?>>Бренды: <br/>
        <table><tbody>
            <?php
            if (json_decode($result['brend_id'])) {
                foreach ( json_decode($result['brend_id']) as $brend ) {
                    $query = "SELECT * FROM `#__jshopping_manufacturers` WHERE manufacturer_id = ".$brend." ";
                    $db->setQuery($query);
                    $result_brend = $db->LoadAssoc();
                    ?>
                    <tr id="category<?php echo $brend; ?>">
                        <td style="width: 400px">
                            <a target="_blank" href="/category/<?php echo $result_brend['alias_ru-RU']; ?>">
                                <span class="title"><?php echo $result_brend['name_ru-RU']; ?></span>
                            </a>
                        </td>
                        <td style="padding-left: 10px;">
                            <input type="hidden" name="jform[typecategory][]" value="<?php echo $brend; ?>" />
                            <a onclick="unselect('category','<?php echo $brend; ?>')" href="javascript:void(null)">убрать</a>
                        </td>
                    </tr>
                <?php
                }
            }
            ?>
            </tbody></table>
    </div>

    <div id="product" <?php echo ($result['typesearch']==6 ? '':'style="display:none;"') ?>>Товары: <br/>
        <table id="productlist"><tbody>
            <?php
            if (json_decode($result['product_id'])) {
                foreach (json_decode($result['product_id']) as $product) {
                    $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = " . $product . " ";
                    $db->setQuery($query);
                    $result_product = $db->LoadAssoc();
                    ?>
                    <tr id="product<?php echo $product; ?>">
                        <td style="width: 400px">
                            <img width="100" src="/components/com_jshopping/files/img_products/<?php echo $result_product['image']; ?>">
                            <a target="_blank"
                               href="/category/<?php echo $result_product['alias_ru-RU']; ?>">
                                                    <span
                                                        class="title"><?php echo $result_product['name_ru-RU']; ?></span>
                            </a> &nbsp;<span><?php echo number_format($result_product['product_price']); ?> грн.</span>
                        </td>
                        <td style="padding-left: 10px;">
                            <input type="hidden" name="jform[typeproduct][]"
                                   value="<?php echo $product; ?>"/>
                            <a onclick="unselect('product','<?php echo $product; ?>')"
                               href="javascript:void(null)">убрать</a>
                        </td>
                    </tr>
                <?php
                }
            }
            ?>
            </tbody></table></div>

    <hr/>

    <div id="singlebonusproductdiv" <?php echo ($result['typesearch']==0 || $result['typesearch']==2 || $result['typesearch']== 6 ? '':'style="display:none;"') ?>>Бонусный товар:
        <table><tbody>
            <?php
            if ($result['singlebonus']) {
                $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$result['singlebonus']." ";
                $db->setQuery($query);
                $result_product = $db->LoadAssoc();
                ?>
                <tr id="product<?php echo $result['singlebonus']; ?>">
                    <td style="width: 400px">
                        <img width="100" src="/components/com_jshopping/files/img_products/<?php echo $result_product['image']; ?>">
                        <a target="_blank" href="/category/<?php echo $result_product['alias_ru-RU']; ?>">
                            <span class="title"><?php echo $result_product['name_ru-RU']; ?></span>
                        </a>&nbsp;<span><?php echo number_format($result_product['product_price']); ?> грн.</span>
                        <hr/>
                        Цена на бонусный товар: <input type="text" name="jform[typebonusproductprice]" value="<?php echo $result['singlebonusprice']; ?>" />
                    </td>
                    <td style="padding-left: 10px;">
                        <input type="hidden" name="jform[typebonusproduct]" value="<?php echo $result['singlebonus']; ?>" />
                        <a onclick="unselect('product','<?php echo $result['singlebonus']; ?>')" href="javascript:void(null)">убрать</a>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody></table></div>

    <hr/>

    <div id="singlebonusproduct" <?php echo ($result['typesearch']==0 || $result['typesearch']== 2 || $result['typesearch']== 6 ? '':'style="display: none;"'); ?>>
        Введите название бонусного товара: <input type="text" id="searchproduct" />
    </div>

    <div id="resSearchProduct">
        <table><tbody></tbody></table>
    </div>

    <hr/>

    <div id="grouptype" class="grouptype">
        <?php
        if ($result['typesearch']==0 || $result['typesearch']==1) { ?>
            Введите название категории:  <input type="text" id="search" name="jform[search]" />
        <?php } elseif ($result['typesearch']==2 || $result['typesearch']==3) { ?>
            Введите название бренда:  <input type="text" id="search" name="jform[search]" />
        <?php } elseif ($result['typesearch']==6) { ?>
            Введите название товара:  <input type="text" id="search" name="jform[search]" />
        <?php } ?>
    </div>
    <div id="resSearch"><table><tbody></tbody></table></div>


</div>

<div style="height: 50px; clear: both;"></div>

<script>
    jQuery(document).ready(function(){

        var group0 = 'Введите название категории:  <input type="text" id="search" name="jform[search]" />';
        var group1 = 'Введите название бренда: <input type="text" id="search" name="jform[search]" />';
        var group6 = 'Введите название товара: <input type="text" id="search" name="jform[search]" />';
        var group;
        var dataval = [];

        jQuery('input[name=grouptype]').change(function(){
            jQuery("#resSearch table tbody").html('');
            jQuery("#resSearchProduct table tbody").html('');
            jQuery("#singlebonusproductdiv table tbody").html('');

            jQuery('div.grouptype').html('');

            jQuery('div#resultselect #category table tbody').html('');
            jQuery('div#resultselect #brend table tbody').html('');
            jQuery('div#resultselect #product table#productlist tbody').html('');
            jQuery('div#resultselect #category').hide('');
            jQuery('div#resultselect #brend').hide('');
            jQuery('div#resultselect #product').hide('');
            jQuery('div#singlebonusproductdiv').hide();
            jQuery('div#singlebonusproduct').hide();

            jQuery('input#typecategory').val('');
            jQuery('input#typebrend').val('');
            jQuery('input#typeproduct').val('');

            if (jQuery(this).val()==0 || jQuery(this).val()==1 ) { group = group0; }
            if (jQuery(this).val()==2 || jQuery(this).val()==3 ) { group = group1; }
            if (jQuery(this).val()==6) { group = group6; }

            jQuery('div#grouptype').append(group);
            jQuery('#typesearch').val(jQuery(this).val());

        });

        jQuery('input#search').live('keyup', function (e) {

            jQuery("#resSearch table tbody").html('');
            jQuery("#resSearchProduct table tbody").html('');
            searchtext = jQuery(this).val();
            searchlength = searchtext.length;
            dataval = [];
            dataval[0] = searchtext;
            dataval[1] = jQuery('#typesearch').val();

            if ( searchlength > 2) {
                jQuery.ajax({
                    type: "POST",
                    url: "/index.php?option=com_ajax&plugin=joomshoppingajaxsearch&format=json",
                    data: {"q_search": dataval},
                    cache: false,
                    complete: function(response){

                        jQuery("#resSearch table tbody").html('');

                        if (jQuery('#typesearch').val()==0 || jQuery('#typesearch').val()==1 ) {
                            jQuery(response.responseJSON['data'][0]).each(function(){
                                jQuery("#resSearch table tbody").append('<tr id="category'+this.id+'"><td style="width: 400px"><a target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a></td><td id="removecategory'+this.id+'" style="padding-left: 10px;"><a onclick="select(\'category\','+this.id+')" href="javascript:void(null)">выбрать</a></td></tr>');
                            });
                        }

                        if(jQuery('#typesearch').val()==2 || jQuery('#typesearch').val()==3){
                            jQuery(response.responseJSON['data'][0]).each(function(){
                                jQuery("#resSearch table tbody").append('<tr id="brend'+this.id+'"><td style="width: 400px"><a target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a></td><td id="removebrend'+this.id+'" style="padding-left: 10px;"><a onclick="select(\'brend\','+this.id+')" href="javascript:void(null)">выбрать</a></td></tr>');
                            });
                        }

                        if(jQuery('#typesearch').val()==6){
                            jQuery(response.responseJSON['data'][0]).each(function(){
                                jQuery("#resSearch table tbody").append('<tr id="product'+this.id+'"><td style="width: 400px"><img width="100" src="/components/com_jshopping/files/img_products/'+this.image+'"><a target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a>&nbsp;<span class="price">'+(parseFloat(this.price).toFixed(0)).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ')+' грн.</span></td><td id="removeproduct'+this.id+'" style="padding-left: 10px;"><a onclick="select(\'product\','+this.id+')" href="javascript:void(null)">выбрать</a></td></tr>');
                            });
                        }

                    }
                });
            }
        });

        jQuery('input#searchproduct').live('keyup', function (e) {

            jQuery("#resSearchProduct table tbody").html('');
            searchtext = jQuery(this).val();
            searchlength = searchtext.length;
            dataval = [];
            dataval[0] = searchtext;
            dataval[1] = 6;

            if ( searchlength > 2) {
                jQuery.ajax({
                    type: "POST",
                    url: "/index.php?option=com_ajax&plugin=joomshoppingajaxsearch&format=json",
                    data: {"q_search": dataval},
                    cache: false,
                    complete: function(response){
                        jQuery("#resSearchProduct").html('');
                        jQuery(response.responseJSON['data'][0]).each(function(){
                            jQuery("#resSearchProduct").append('<tr id="product'+this.id+'"><td style="width: 400px"><img width="100" src="/components/com_jshopping/files/img_products/'+this.image+'"><a target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a>&nbsp;<span class="price">'+(parseFloat(this.price).toFixed(0)).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ')+' грн.</span><hr/>Цена на бонусный товар: <input type="text" name="jform[typebonusproductprice]" value="<?php echo $result['singlebonusprice']; ?>" /></td><td id="removeproduct'+this.id+'" style="padding-left: 10px;"><a onclick="selectbonus(\'product\','+this.id+')" href="javascript:void(null)">выбрать</a></td></tr>');
                        });
                    }
                });
            }
        });

    });

    function select(type,id) {
        if (type=="category"){
            jQuery('div#resultselect #category').show();
            if (jQuery('#typesearch').val()==0) {
                jQuery('div#resultselect #singlebonusproduct').show();
            }
            jQuery('div#resultselect #category table tbody').append(jQuery('#category'+id));
            jQuery('#removecategory'+id).remove();
            jQuery('#category'+id).append('<td style="padding-left: 10px;"><input type="hidden" name="jform[typecategory][]" value="'+id+'" /><a onclick="unselect(\'category\','+id+')" href="javascript:void(null)">убрать</a></td>');
        }
        if (type=="brend"){
            jQuery('div#resultselect #brend').show();
            if (jQuery('#typesearch').val()==2) {
                jQuery('div#resultselect #singlebonusproduct').show();
            }
            jQuery('div#resultselect #brend table tbody').append(jQuery('#brend'+id));
            jQuery('#removebrend'+id).remove();
            jQuery('#brend'+id).append('<td style="padding-left: 10px;"><input type="hidden" name="jform[typebrend][]" value="'+id+'" /><a onclick="unselect(\'brend\','+id+')" href="javascript:void(null)">убрать</a></td>');
        }
        if (type=="product"){
            jQuery('div#resultselect #product').show();

            jQuery('div#resultselect #singlebonusproduct').show();

            jQuery('div#resultselect #product table#productlist tbody').append(jQuery('#product'+id));
            jQuery('#removeproduct'+id).remove();
            jQuery('#product'+id).append('<td style="padding-left: 10px;"><input type="hidden" name="jform[typeproduct][]" value="'+id+'" /><a onclick="unselect(\'product\','+id+')" href="javascript:void(null)">убрать</a></td>');
        }
    }

    function selectbonus(type,id) {
        if (type=="product"){
            jQuery('div#singlebonusproductdiv').show();
            jQuery('div#singlebonusproductdiv table tbody').append(jQuery('#product'+id));
            jQuery('#removeproduct'+id).remove();
            jQuery('#product'+id).append('<td style="padding-left: 10px;"><input type="hidden" name="jform[typebonusproduct]" value="'+id+'" /><a onclick="unselect(\'product\','+id+')" href="javascript:void(null)">убрать</a></td>');
        }
    }

    function unselect(type,id) {
        if (type=="category"){
            jQuery('#category'+id).remove();
        }
        if (type=="brend"){
            jQuery('#brend'+id).remove();
        }
        if (type=="product"){
            jQuery('#product'+id).remove();
        }
    }
</script>
<?php
}


if ( $this->item->type == 6 ) {

    ?>
    <div class="span4">

        <div>
            Выберите тип добавления акции: <br/><br/>
            <input type="radio" value="0" <?php echo ($result['typesearch']==1 ? 'checked="checked"':'') ?> name="grouptype" /> Категория
            <br/>
            <!--<input type="radio" value="1" <?php echo ($result['typesearch']==1 ? 'checked="checked"':'') ?> name="grouptype" /> Категория с выбором товаров
                            <br/>-->
            <input type="radio" value="2" <?php echo ($result['typesearch']==2 ? 'checked="checked"':'') ?> name="grouptype" /> Бренд
            <br/>
            <!--<input type="radio" value="3" <?php echo ($result['typesearch']==3 ? 'checked="checked"':'') ?> name="grouptype" /> Бренд с выбором товаров
                            <br/>-->
            <input type="radio" value="6" <?php echo ($result['typesearch']==6 ? 'checked="checked"':'') ?> name="grouptype" /> Поиск товаров
        </div>

    </div>
    <div id="resultselect" class="span8">

        Выбрано:

        <div id="category" <?php echo ($result['typesearch']==1 ? '':'style="display:none;"') ?>>Категории: <br/>
            <table>
                <tbody>
                <?php
                if (json_decode($result['category_id'])) {
                    foreach (json_decode($result['category_id']) as $category) {
                        $query = "SELECT * FROM `#__jshopping_categories` WHERE category_id = " . $category . " ";
                        $db->setQuery($query);
                        $result_category = $db->LoadAssoc();
                        ?>
                        <tr id="category<?php echo $category; ?>">
                            <td style="width: 400px">
                                <a target="_blank"
                                   href="/category/<?php echo $result_category['alias_ru-RU']; ?>">
                                                        <span
                                                            class="title"><?php echo $result_category['name_ru-RU']; ?></span>
                                </a>
                            </td>
                            <td style="padding-left: 10px;">
                                <input type="hidden" name="jform[typecategory][]"
                                       value="<?php echo $category; ?>"/>
                                <a onclick="unselect('category','<?php echo $category; ?>')"
                                   href="javascript:void(null)">убрать</a>
                            </td>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>

        <div id="brend" <?php echo ($result['typesearch']==2 || $result['typesearch']==3 ? '':'style="display:none;"') ?>>Бренды: <br/>
            <table><tbody>
                <?php
                if (json_decode($result['brend_id'])) {
                    foreach ( json_decode($result['brend_id']) as $brend ) {
                        $query = "SELECT * FROM `#__jshopping_manufacturers` WHERE manufacturer_id = ".$brend." ";
                        $db->setQuery($query);
                        $result_brend = $db->LoadAssoc();
                        ?>
                        <tr id="category<?php echo $brend; ?>">
                            <td style="width: 400px">
                                <a target="_blank" href="/category/<?php echo $result_brend['alias_ru-RU']; ?>">
                                    <span class="title"><?php echo $result_brend['name_ru-RU']; ?></span>
                                </a>
                            </td>
                            <td style="padding-left: 10px;">
                                <input type="hidden" name="jform[typecategory][]" value="<?php echo $brend; ?>" />
                                <a onclick="unselect('category','<?php echo $brend; ?>')" href="javascript:void(null)">убрать</a>
                            </td>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody></table>
        </div>

        <div id="product" <?php echo ($result['typesearch']==6 ? '':'style="display:none;"') ?>>Товары: <br/>
            <table id="productlist"><tbody>
                <?php
                if (json_decode($result['product_id'])) {
                    foreach (json_decode($result['product_id']) as $product) {
                        $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = " . $product . " ";
                        $db->setQuery($query);
                        $result_product = $db->LoadAssoc();
                        ?>
                        <tr id="product<?php echo $product; ?>">
                            <td style="width: 400px">
                                <img width="100" src="/components/com_jshopping/files/img_products/<?php echo $result_product['image']; ?>">
                                <a target="_blank"
                                   href="/category/<?php echo $result_product['alias_ru-RU']; ?>">
                                                    <span
                                                        class="title"><?php echo $result_product['name_ru-RU']; ?></span>
                                </a> &nbsp;<span><?php echo number_format($result_product['product_price']); ?> грн.</span>
                                <br/>Изменение цены %: <input name="jform[changeprice][<?php echo $product; ?>]" value="<?php echo json_decode($result['products_price'])->$product;?>" />
                            </td>
                            <td style="padding-left: 10px;">
                                <input type="hidden" name="jform[typeproduct][]"
                                       value="<?php echo $product; ?>"/>
                                <a onclick="unselect('product','<?php echo $product; ?>')"
                                   href="javascript:void(null)">убрать</a>
                            </td>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody></table></div>

        <hr/>

        <div id="singlebonusproductdiv" <?php echo ($result['typesearch']==1 || $result['typesearch']==2 || $result['typesearch']== 6 ? '':'style="display:none;"') ?>>Бонусный товар:
            <table><tbody>
                <?php
                if ($result['singlebonus']) {
                    $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$result['singlebonus']." ";
                    $db->setQuery($query);
                    $result_product = $db->LoadAssoc();
                    ?>
                    <tr id="product<?php echo $result['singlebonus']; ?>">
                        <td style="width: 400px">
                            <img width="100" src="/components/com_jshopping/files/img_products/<?php echo $result_product['image']; ?>">
                            <a target="_blank" href="/category/<?php echo $result_product['alias_ru-RU']; ?>">
                                <span class="title"><?php echo $result_product['name_ru-RU']; ?></span>
                            </a>&nbsp;<span><?php echo number_format($result_product['product_price']); ?> грн.</span>
                            <hr/>
                            Цена на бонусный товар: <input type="text" name="jform[typebonusproductprice]" value="<?php echo $result['singlebonusprice']; ?>" />
                        </td>
                        <td style="padding-left: 10px;">
                            <input type="hidden" name="jform[typebonusproduct]" value="<?php echo $result['singlebonus']; ?>" />
                            <a onclick="unselect('product','<?php echo $result['singlebonus']; ?>')" href="javascript:void(null)">убрать</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
                </tbody></table></div>

        <hr/>

        <div id="singlebonusproduct" <?php echo ($result['typesearch']==1 || $result['typesearch']== 2 || $result['typesearch']== 6 ? '':'style="display: none;"'); ?>>
            Введите название бонусного товара: <input type="text" id="searchproduct" />
        </div>

        <div id="resSearchProduct">
            <table><tbody></tbody></table>
        </div>

        <hr/>

        <div id="grouptype" class="grouptype">
            <?php
            if ($result['typesearch']==1 ) { ?>
                Введите название категории:  <input type="text" id="search" name="jform[search]" />
            <?php } elseif ($result['typesearch']==2 || $result['typesearch']==3) { ?>
                Введите название бренда:  <input type="text" id="search" name="jform[search]" />
            <?php } elseif ($result['typesearch']==6) { ?>
                Введите название товара:  <input type="text" id="search" name="jform[search]" />
            <?php } ?>
        </div>
        <div id="resSearch"><table><tbody></tbody></table></div>


    </div>

    <div style="height: 50px; clear: both;"></div>

    <script>
        jQuery(document).ready(function(){

            var group0 = 'Введите название категории:  <input type="text" id="search" name="jform[search]" />';
            var group1 = 'Введите название бренда: <input type="text" id="search" name="jform[search]" />';
            var group6 = 'Введите название товара: <input type="text" id="search" name="jform[search]" />';
            var group;
            var dataval = [];

            jQuery('input[name=grouptype]').change(function(){
                jQuery("#resSearch table tbody").html('');
                jQuery("#resSearchProduct table tbody").html('');
                jQuery("#singlebonusproductdiv table tbody").html('');

                jQuery('div.grouptype').html('');

                jQuery('div#resultselect #category table tbody').html('');
                jQuery('div#resultselect #brend table tbody').html('');
                jQuery('div#resultselect #product table#productlist tbody').html('');
                jQuery('div#resultselect #category').hide('');
                jQuery('div#resultselect #brend').hide('');
                jQuery('div#resultselect #product').hide('');
                jQuery('div#singlebonusproductdiv').hide();
                jQuery('div#singlebonusproduct').hide();

                jQuery('input#typecategory').val('');
                jQuery('input#typebrend').val('');
                jQuery('input#typeproduct').val('');

                if (jQuery(this).val()==0 || jQuery(this).val()==1 ) { group = group0; }
                if (jQuery(this).val()==2 || jQuery(this).val()==3 ) { group = group1; }
                if (jQuery(this).val()==6) { group = group6; }

                jQuery('div#grouptype').append(group);
                jQuery('#typesearch').val(jQuery(this).val());

            });

            jQuery('input#search').live('keyup', function (e) {

                jQuery("#resSearch table tbody").html('');
                jQuery("#resSearchProduct table tbody").html('');
                searchtext = jQuery(this).val();
                searchlength = searchtext.length;
                dataval = [];
                dataval[0] = searchtext;
                dataval[1] = jQuery('#typesearch').val();

                if ( searchlength > 2) {
                    jQuery.ajax({
                        type: "POST",
                        url: "/index.php?option=com_ajax&plugin=joomshoppingajaxsearch&format=json",
                        data: {"q_search": dataval},
                        cache: false,
                        complete: function(response){

                            jQuery("#resSearch table tbody").html('');

                            if (jQuery('#typesearch').val()==1 ) {
                                jQuery(response.responseJSON['data'][0]).each(function(){
                                    jQuery("#resSearch table tbody").append('<tr id="category'+this.id+'"><td style="width: 400px"><a target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a></td><td id="removecategory'+this.id+'" style="padding-left: 10px;"><a onclick="select(\'category\','+this.id+')" href="javascript:void(null)">выбрать</a></td></tr>');
                                });
                            }

                            if(jQuery('#typesearch').val()==2 || jQuery('#typesearch').val()==3){
                                jQuery(response.responseJSON['data'][0]).each(function(){
                                    jQuery("#resSearch table tbody").append('<tr id="brend'+this.id+'"><td style="width: 400px"><a target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a></td><td id="removebrend'+this.id+'" style="padding-left: 10px;"><a onclick="select(\'brend\','+this.id+')" href="javascript:void(null)">выбрать</a></td></tr>');
                                });
                            }

                            if(jQuery('#typesearch').val()==6){
                                jQuery(response.responseJSON['data'][0]).each(function(){
                                    jQuery("#resSearch table tbody").append('<tr id="product'+this.id+'"><td style="width: 400px"><img width="100" src="/components/com_jshopping/files/img_products/'+this.image+'"><a target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a>&nbsp;<span class="price">'+(parseFloat(this.price).toFixed(0)).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ')+' грн.</span></td><td id="removeproduct'+this.id+'" style="padding-left: 10px;"><a onclick="select(\'product\','+this.id+')" href="javascript:void(null)">выбрать</a></td></tr>');
                                });
                            }

                        }
                    });
                }
            });

            jQuery('input#searchproduct').live('keyup', function (e) {

                jQuery("#resSearchProduct table tbody").html('');
                searchtext = jQuery(this).val();
                searchlength = searchtext.length;
                dataval = [];
                dataval[0] = searchtext;
                dataval[1] = 6;

                if ( searchlength > 2) {
                    jQuery.ajax({
                        type: "POST",
                        url: "/index.php?option=com_ajax&plugin=joomshoppingajaxsearch&format=json",
                        data: {"q_search": dataval},
                        cache: false,
                        complete: function(response){
                            jQuery("#resSearchProduct").html('');
                            jQuery(response.responseJSON['data'][0]).each(function(){
                                jQuery("#resSearchProduct").append('<tr id="product'+this.id+'"><td style="width: 400px"><img width="100" src="/components/com_jshopping/files/img_products/'+this.image+'"><a target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a>&nbsp;<span class="price">'+(parseFloat(this.price).toFixed(0)).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ')+' грн.</span><hr/>Цена на бонусный товар: <input type="text" name="jform[typebonusproductprice]" value="<?php echo $result['singlebonusprice']; ?>" /></td><td id="removeproduct'+this.id+'" style="padding-left: 10px;"><a onclick="selectbonus(\'product\','+this.id+')" href="javascript:void(null)">выбрать</a></td></tr>');
                            });
                        }
                    });
                }
            });

        });

        function select(type,id) {
            if (type=="category"){
                jQuery('div#resultselect #category').show();
                jQuery('div#resultselect #category table tbody').append(jQuery('#category'+id));
                jQuery('#removecategory'+id).remove();
                jQuery('#category'+id).append('<td style="padding-left: 10px;"><input type="hidden" name="jform[typecategory][]" value="'+id+'" /><a onclick="unselect(\'category\','+id+')" href="javascript:void(null)">убрать</a></td>');
            }
            if (type=="brend"){
                jQuery('div#resultselect #brend').show();
                jQuery('div#resultselect #brend table tbody').append(jQuery('#brend'+id));
                jQuery('#removebrend'+id).remove();
                jQuery('#brend'+id).append('<td style="padding-left: 10px;"><input type="hidden" name="jform[typebrend][]" value="'+id+'" /><a onclick="unselect(\'brend\','+id+')" href="javascript:void(null)">убрать</a></td>');
            }
            if (type=="product"){
                jQuery('div#resultselect #product').show();
                jQuery('div#resultselect #product table#productlist tbody').append(jQuery('#product'+id));
                jQuery('#removeproduct'+id).remove();
                jQuery('#product'+id+' td:first-child').append('<br/>Изменение цены %: <input name="jform[changeprice]['+id+']" value="" />');
                jQuery('#product'+id).append('<td style="padding-left: 10px;"><input type="hidden" name="jform[typeproduct][]" value="'+id+'" /><a onclick="unselect(\'product\','+id+')" href="javascript:void(null)">убрать</a></td>');
            }
        }

        function selectbonus(type,id) {
            if (type=="product"){
                jQuery('div#singlebonusproductdiv').show();
                jQuery('div#singlebonusproductdiv table tbody').append(jQuery('#product'+id));
                jQuery('#removeproduct'+id).remove();
                jQuery('#product'+id).append('<td style="padding-left: 10px;"><input type="hidden" name="jform[typebonusproduct]" value="'+id+'" /><a onclick="unselect(\'product\','+id+')" href="javascript:void(null)">убрать</a></td>');
            }
        }

        function unselect(type,id) {
            if (type=="category"){
                jQuery('#category'+id).remove();
            }
            if (type=="brend"){
                jQuery('#brend'+id).remove();
            }
            if (type=="product"){
                jQuery('#product'+id).remove();
            }
        }
    </script>
<?php
}


}
?>
