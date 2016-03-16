<?php
$result = array();

$query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
$db->setQuery($query);
$result = $db->LoadObjectList();

echo '<input name="jform[typesearch]" id="typesearch" value="'.$this->item->typesearch.'" type="hidden"/>';
?>

<div class="span2 selectgrouptype">
    <div>
        Выберите тип добавления акции: <br/><br/>
        <input type="radio" value="1" <?php echo ($this->item->typesearch==1 ? 'checked="checked"':'') ?> name="grouptype" /> Категория
        <br/>
        <input type="radio" value="2" <?php echo ($this->item->typesearch==2 ? 'checked="checked"':'') ?> name="grouptype" /> Бренд + Категория
        <br/>
        <input type="radio" value="3" <?php echo ($this->item->typesearch==3 ? 'checked="checked"':'') ?> name="grouptype" /> Поиск товаров
    </div>
</div>
<div id="resultselect" class="span10">
    <div id="grouptype" class="span2">

        <?php if ($this->item->typesearch ==1) { ?>
            Введите название категории: <input type="text" id="searchcategory" />
        <?php } ?>
        <?php if ($this->item->typesearch ==2) { ?>
            Введите название бренда: <input type="text" id="searchbrend" />
        <?php } ?>
        <?php if ($this->item->typesearch ==3) { ?>
            Введите название товара: <input type="text" id="searchproduct" />
        <?php } ?>

    </div>
    <div id="browseselect" class="span3"></div>
    <div id="selectedproperties" class="span7">
        <div class="selectedcategories" <?php echo ($this->item->typesearch ==1 ? '':'style="display:none;"') ?>>Категории:<br/>
            <div id="selectedcategories">
                <?php if ($this->item->typesearch==1) { ?>
                    <?php foreach ($result as $res) {
                        $query = "SELECT * FROM `#__jshopping_categories` WHERE category_id = ".$res->category_id." ";
                        $db->setQuery($query);
                        $maincategory = $db->LoadAssoc();
                        ?>
                        <div class="category" id="category<?php echo $res->category_id; ?>">
                            <div class="maincategory span5">
                                <a href="/category/<?php echo $maincategory['alias_ru-RU']; ?>" target="_blank" class="titlecategory">
                                    <span class="title"><?php echo $maincategory['name_ru-RU']; ?></span>
                                </a>
                                <a onclick="delcategory(<?php echo $res->category_id; ?>)" class="actiontodo">удалить</a>
                                <input type="hidden" value="<?php echo $res->category_id; ?>" name="jform[selectedcategory][]">
                            </div>
                            <div class="bonusproduct span7">Добавить бонусный товар:<br><input type="text" block="category" data="<?php echo $res->category_id; ?>" id="searchbonusproduct">
                                <?php foreach ( json_decode($res->bonusproducts) as $bonid=>$bonprod ) {
                                    $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$bonid." ";
                                    $db->setQuery($query);
                                    $product = $db->LoadAssoc();
                                    ?>
                                    <div class="product" id="product<?php echo $bonid; ?>">
                                        <a href="/product/<?php echo $product['alias_ru-RU']; ?>" target="_blank" class="titleproduct">
                                            <span class="title"><?php echo $product['name_ru-RU']; ?></span>
                                        </a>
                                        <a onclick="delproduct(<?php echo $bonid; ?>)" class="actiontodo">удалить</a>
                                        <div id="bonusproductprice">Цена бонусного товара: <input type="text" name="jform[selectedbonusproductprice][<?php echo $res->category_id; ?>][<?php echo $bonid; ?>][]" value="<?php echo $bonprod[0]; ?>"></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="selectedbrends" <?php echo ($this->item->typesearch ==2 ? '':'style="display:none;"') ?>>Бренды:<br/>
            <div id="selectedbrends">
                <?php if ($this->item->typesearch==2) { ?>
                    <?php foreach ($result as $res) {
                        $query = "SELECT * FROM `#__jshopping_manufacturers` WHERE manufacturer_id = ".$res->brend_id." ";
                        $db->setQuery($query);
                        $mainbrend = $db->LoadAssoc();
                        ?>
                        <div class="brend" id="brend<?php echo $res->brend_id; ?>">
                            <div class="mainbrend span5">
                                <a href="javascript:void(null)" target="_blank" class="titlecategory">
                                    <span class="title"><?php echo $mainbrend['name_ru-RU']; ?></span>
                                </a>
                                <a onclick="delbrend(<?php echo $res->brend_id; ?>)" class="actiontodo">удалить</a>
                                <input type="hidden" value="<?php echo $res->brend_id; ?>" name="jform[selectedbrend][]">
                            </div>
                            <div class="brendcategory span12">
                                Добавить категорию:<br><input type="text" data="<?php echo $res->brend_id; ?>" id="searchbrendcategory">
                                <?php foreach (json_decode($res->category_id) as $rescategory) {
                                    $query = "SELECT * FROM `#__jshopping_categories` WHERE category_id = ".$rescategory." ";
                                    $db->setQuery($query);
                                    $maincategory = $db->LoadAssoc();
                                    ?>
                                    <div class="category" id="category<?php echo $rescategory; ?>">
                                        <div class="maincategory span5">
                                            <a href="/category/<?php echo $maincategory['alias_ru-RU']; ?>" target="_blank" class="titlecategory">
                                                <span class="title"><?php echo $maincategory['name_ru-RU']; ?></span>
                                            </a>
                                            <a onclick="delcategory(<?php echo $rescategory; ?>)" class="actiontodo">удалить</a>
                                            <input type="hidden" value="<?php echo $rescategory; ?>" name="jform[selectedcategory][<?php echo $res->brend_id; ?>][]">
                                        </div>
                                        <div class="bonusproduct span7">
                                            Добавить бонусный товар:<br><input type="text" block="category" data="<?php echo $rescategory; ?>" id="searchbonusproduct">
                                            <?php foreach ( json_decode($res->bonusproducts)->$rescategory as $bonid=>$bonprod ) {
                                                $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$bonid." ";
                                                $db->setQuery($query);
                                                $product = $db->LoadAssoc();
                                                ?>
                                                <div class="product" id="product<?php echo $bonid; ?>">
                                                    <a href="/product/<?php echo $product['alias_ru-RU']; ?>" target="_blank" class="titleproduct">
                                                        <span class="title"><?php echo $product['name_ru-RU']; ?></span>
                                                    </a>
                                                    <a onclick="delproduct(<?php echo $bonid; ?>)" class="actiontodo">удалить</a>
                                                    <div id="bonusproductprice">Цена бонусного товара: <input type="text" name="jform[selectedbonusproductprice][<?php echo $rescategory; ?>][<?php echo $bonid; ?>][]" value="<?php echo $bonprod[0]; ?>"></div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="selectedproducts" <?php echo ($this->item->typesearch ==3 ? '':'style="display:none;"') ?> >Товары:<br/>
            <div id="selectedproducts">
                <?php if ($this->item->typesearch ==3) { ?>
                    <?php foreach ($result as $res) {
                            $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$res->product_id." ";
                            $db->setQuery($query);
                            $mainproduct = $db->LoadAssoc();
                        ?>
                        <div class="product" id="product<?php echo $res->product_id; ?>">
                            <div class="mainproduct span5">
                                <a href="/product/<?php echo $mainproduct['alias_ru-RU']; ?>" target="_blank" class="titleproduct">
                                    <span class="title"><?php echo $mainproduct['name_ru-RU']; ?></span>
                                </a>
                                <a onclick="delproduct(<?php echo $res->product_id; ?>)" class="actiontodo">удалить</a>
                                <input type="hidden" value="<?php echo $res->product_id; ?>" name="jform[selectedproduct][]">
                            </div>
                            <div class="bonusproduct span7">Добавить бонусный товар:<br><input type="text" block="product" data="<?php echo $res->product_id; ?>" id="searchbonusproduct">
                                <?php foreach ( json_decode($res->bonusproducts) as $bonid=>$bonprod ) {
                                    $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$bonid." ";
                                    $db->setQuery($query);
                                    $product = $db->LoadAssoc();
                                    ?>
                                    <div class="product" id="product<?php echo $bonid; ?>">
                                        <a href="/product/<?php echo $product['alias_ru-RU']; ?>" target="_blank" class="titleproduct">
                                            <span class="title"><?php echo $product['name_ru-RU']; ?></span>
                                        </a>
                                        <a onclick="delproduct(<?php echo $bonid; ?>)" class="actiontodo">удалить</a>
                                        <div id="bonusproductprice">Цена бонусного товара: <input type="text" name="jform[selectedbonusproductprice][<?php echo $res->product_id; ?>][<?php echo $bonid; ?>][]" value="<?php echo $bonprod[0]; ?>"></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function(){
        var group1 = 'Введите название категории: <input type="text" id="searchcategory" />';
        var group2 = 'Введите название бренда: <input type="text" id="searchbrend" />';
        var group3 = 'Введите название товара: <input type="text" id="searchproduct" />';
        var group;

        jQuery('input[name=grouptype]').change(function(){
            jQuery('#resultselect #grouptype').html('');
            jQuery('#resultselect #browseselect').html('');
            jQuery('#resultselect .selectedcategories').hide('');
            jQuery('#resultselect .selectedbrends').hide('');
            jQuery('#resultselect .selectedproducts').hide('');
            jQuery('#resultselect #selectedcategories').html('');
            jQuery('#resultselect #selectedbrends').html('');
            jQuery('#resultselect #selectedproducts').html('');

            if (jQuery(this).val()==1) { group = group1; }
            if (jQuery(this).val()==2) { group = group2; }
            if (jQuery(this).val()==3) { group = group3; }

            jQuery('div#grouptype').append(group);
            jQuery('#typesearch').val(jQuery(this).val());
        });

        jQuery('input#searchcategory').live('keyup', function (e) {
            var searchtext = jQuery(this).val();
            var searchlength = searchtext.length;
            var dataval = [];
                dataval[0] = searchtext;
                dataval[1] = 1;

            jQuery('#resultselect #browseselect').html('');

            if ( searchlength > 2 ) {
                jQuery.ajax({
                    type: "POST",
                    url: "/index.php?option=com_ajax&plugin=joomshoppingajaxsearch&format=json",
                    data: {"q_search": dataval},
                    cache: false,
                    complete: function(response){

                        jQuery('#resultselect #browseselect').html('');

                        jQuery(response.responseJSON['data'][0]).each(function(){
                            jQuery('#resultselect #browseselect').append('<div id="category'+this.id+'" class="category">'+
                                '<div class="maincategory"><a class="titlecategory" target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a>'+
                                '<a class="actiontodo" id="addcategory'+this.id+'" onclick="addcategory('+this.id+')">добавить</a></div>'+
                                '<div class="bonusproduct" style="display:none;">Добавить бонусный товар:<br/><input type="text" id="searchbonusproduct" data="'+this.id+'" block="category"/></div>'+
                                '</div>');
                        });

                    }
                });
            }
        });

        jQuery('input#searchbrendcategory').live('keyup', function (e) {
            var parentblockid = jQuery(this).attr('data');
            var searchtext = jQuery(this).val();
            var searchlength = searchtext.length;
            var dataval = [];
            dataval[0] = searchtext;
            dataval[1] = 1;

            jQuery('#resultselect #browseselect').html('');

            if ( searchlength > 2 ) {
                jQuery.ajax({
                    type: "POST",
                    url: "/index.php?option=com_ajax&plugin=joomshoppingajaxsearch&format=json",
                    data: {"q_search": dataval},
                    cache: false,
                    complete: function(response){

                        jQuery('#resultselect #browseselect').html('');

                        jQuery(response.responseJSON['data'][0]).each(function(){
                            jQuery('#resultselect #browseselect').append('<div id="category'+this.id+'" class="category">'+
                            '<div class="maincategory"><a class="titlecategory" target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a>'+
                            '<a class="actiontodo" id="addbrendcategory'+this.id+'" onclick="addbrendcategory('+this.id+','+parentblockid+')">добавить</a></div>'+
                            '<div class="bonusproduct" style="display:none;">Добавить бонусный товар:<br/><input type="text" id="searchbonusproduct" data="'+this.id+'" block="category"/></div>'+
                            '</div>');
                        });

                    }
                });
            }
        });

        jQuery('input#searchbrend').live('keyup', function (e) {
            var searchtext = jQuery(this).val();
            var searchlength = searchtext.length;
            var dataval = [];
            dataval[0] = searchtext;
            dataval[1] = 2;

            jQuery('#resultselect #browseselect').html('');

            if ( searchlength > 2 ) {
                jQuery.ajax({
                    type: "POST",
                    url: "/index.php?option=com_ajax&plugin=joomshoppingajaxsearch&format=json",
                    data: {"q_search": dataval},
                    cache: false,
                    complete: function(response){

                        jQuery('#resultselect #browseselect').html('');

                        jQuery(response.responseJSON['data'][0]).each(function(){
                            jQuery('#resultselect #browseselect').append('<div id="brend'+this.id+'" class="brend">'+
                            '<div class="mainbrend"><a class="titlebrend" target="_blank" href="javascript:void(null)"><span class="title">'+this.title+'</span></a>'+
                            '<a class="actiontodo" id="addbrend'+this.id+'" onclick="addbrend('+this.id+')">добавить</a></div>'+
                            '<div class="brendcategory" style="display:none;">Добавить категорию:<br/><input type="text" id="searchbrendcategory" data="'+this.id+'" /></div>'+
                            '</div>');
                        });

                    }
                });
            }
        });

        jQuery('input#searchproduct').live('keyup', function (e) {
            var searchtext = jQuery(this).val();
            var searchlength = searchtext.length;
            var dataval = [];
            dataval[0] = searchtext;
            dataval[1] = 3;

            jQuery('#resultselect #browseselect').html('');

            if ( searchlength > 2 ) {
                jQuery.ajax({
                    type: "POST",
                    url: "/index.php?option=com_ajax&plugin=joomshoppingajaxsearch&format=json",
                    data: {"q_search": dataval},
                    cache: false,
                    complete: function(response){

                        jQuery('#resultselect #browseselect').html('');

                        jQuery(response.responseJSON['data'][0]).each(function(){
                            jQuery('#resultselect #browseselect').append('<div id="product'+this.id+'" class="product">'+
                            '<div class="mainproduct"><a class="titleproduct" target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a>'+
                            '<a class="actiontodo" id="addproduct'+this.id+'" onclick="addproduct('+this.id+')">добавить</a></div>'+
                            '<div class="bonusproduct" style="display:none;">Добавить бонусный товар:<br/><input type="text" id="searchbonusproduct" data="'+this.id+'" block="product"/></div>'+
                            '</div>');
                        });

                    }
                });
            }
        });

        jQuery('input#searchbonusproduct').live('keyup', function (e) {
            var parentblockid = jQuery(this).attr('data');
            var parentblock = jQuery(this).attr('block');
            var searchtext = jQuery(this).val();
            var searchlength = searchtext.length;
            var dataval = [];
            dataval[0] = searchtext;
            dataval[1] = 3;

            jQuery('#resultselect #browseselect').html('');

            if ( searchlength > 2 ) {
                jQuery.ajax({
                    type: "POST",
                    url: "/index.php?option=com_ajax&plugin=joomshoppingajaxsearch&format=json",
                    data: {"q_search": dataval},
                    cache: false,
                    complete: function(response){

                        jQuery('#resultselect #browseselect').html('');

                        jQuery(response.responseJSON['data'][0]).each(function(){
                            jQuery('#resultselect #browseselect').append('<div id="product'+this.id+'" class="product">'+
                            '<a class="titleproduct" target="_blank" href="'+this.href+'"><span class="title">'+this.title+'</span></a>'+
                            '<a class="actiontodo" id="addbonusproduct'+this.id+'" onclick="addbonusproduct('+this.id+','+parentblockid+',\''+parentblock+'\')">добавить</a>'+
                            '</div>');
                        });

                    }
                });
            }
        });

    });
</script>

<script>
    function addcategory(id) {
        jQuery('.selectedcategories').show();
        jQuery('#selectedcategories').append(jQuery('#category'+id));
        jQuery('#addcategory'+id).remove();
        jQuery('#category'+id+' .maincategory').addClass('span5');
        jQuery('#category'+id+' .bonusproduct').addClass('span7');
        jQuery('#category'+id+' .bonusproduct').show();
        jQuery('#category'+id+' .maincategory').append('<a class="actiontodo" onclick="delcategory('+id+')">удалить</a>');
        jQuery('#category'+id+' .maincategory').append('<input type="hidden" name="jform[selectedcategory][]" value="'+id+'" />');
    }
    function delcategory(id) {
        jQuery('#category'+id).remove();
    }
</script>

<script>
    function addbrendcategory(id,blockid) {
        jQuery('#brend'+blockid+' .brendcategory').append(jQuery('#category'+id));
        jQuery('#addbrendcategory'+id).remove();
        jQuery('#category'+id+' .maincategory').addClass('span5');
        jQuery('#category'+id+' .bonusproduct').addClass('span7');
        jQuery('#category'+id+' .bonusproduct').show();
        jQuery('#category'+id+' .maincategory').append('<a class="actiontodo" onclick="delcategory('+id+')">удалить</a>');
        jQuery('#category'+id+' .maincategory').append('<input type="hidden" name="jform[selectedcategory]['+blockid+'][]" value="'+id+'" />');
    }
    function delbrendcategory(id) {
        jQuery('#category'+id).remove();
    }
</script>

<script>
    function addbrend(id) {
        jQuery('.selectedbrends').show();
        jQuery('#selectedbrends').append(jQuery('#brend'+id));
        jQuery('#addbrend'+id).remove();
        jQuery('#brend'+id+' .mainbrend').addClass('span5');
        jQuery('#brend'+id+' .brendcategory').addClass('span12');
        jQuery('#brend'+id+' .brendcategory').show();
        jQuery('#brend'+id+' .mainbrend').append('<a class="actiontodo" onclick="delbrend('+id+')">удалить</a>');
        jQuery('#brend'+id+' .mainbrend').append('<input type="hidden" name="jform[selectedbrend][]" value="'+id+'" />');
    }
    function delbrend(id) {
        jQuery('#brend'+id).remove();
    }
</script>

<script>
    function addproduct(id) {
        jQuery('.selectedproducts').show();
        jQuery('#selectedproducts').append(jQuery('#product'+id));
        jQuery('#addproduct'+id).remove();
        jQuery('#product'+id+' .mainproduct').addClass('span5');
        jQuery('#product'+id+' .bonusproduct').addClass('span7');
        jQuery('#product'+id+' .bonusproduct').show();
        jQuery('#product'+id+' .mainproduct').append('<a class="actiontodo" onclick="delproduct('+id+')">удалить</a>');
        jQuery('#product'+id+' .mainproduct').append('<input type="hidden" name="jform[selectedproduct][]" value="'+id+'" />');
    }
    function delproduct(id) {
        jQuery('#product'+id).remove();
    }
</script>

<script>
    function addbonusproduct(id,blockid,block) {
        jQuery('#'+block+''+blockid+' .bonusproduct').append(jQuery('#product'+id));
        jQuery('#'+block+''+blockid+' #bonusproductprice').show();
        jQuery('#addbonusproduct'+id).remove();
        jQuery('#product'+id).append('<a class="actiontodo" onclick="delproduct('+id+')">удалить</a>');
        jQuery('#product'+id).append('<div id="bonusproductprice">Цена бонусного товара: <input type="text" name="jform[selectedbonusproductprice]['+blockid+']['+id+'][]" /></div>');
        jQuery('#product'+id).append('<input type="hidden" name="jform[selectedbonusproduct]['+blockid+'][]" value="'+id+'" />');
    }
    function delbonusproduct(id) {
        jQuery('#product'+id).remove();
    }
</script>

<style>
    input {
        width: 100px;
    }
    .selectgrouptype, #browseselect, #grouptype {
        border-right: 1px solid #000;
        padding-right: 5px;
        margin-right: 5px;
    }
    .selectedbrends, .selectedcategories, .selectedproducts {
        clear: both;
    }
    .category, .brend, .product {
        clear: both;
    }
    #selectedproperties .category, #selectedproperties .brend, #selectedproperties .product {
        border: 1px solid #000;
        padding: 3px;
        margin: 3px;
        overflow: hidden;
    }
    .titlecategory, .titlebrend, .titleproduct {
        width: 150px;
        margin-right: 20px;
        display: block;
        float: left;
    }
    .actiontodo {
        color: red;
        cursor: pointer;
    }
    #bonusproductprice {
        clear: both;
    }
</style>