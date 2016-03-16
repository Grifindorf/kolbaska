<?php

$app = JFactory::getApplication();
$input = $app->input;

?>

    <nav class="breadcrumbs">

        <?php
        $this->searchmodules = JModuleHelper::getModules('breadcrumbsvr');
        foreach ($this->searchmodules as $searchmodule)
        {
            $output = JModuleHelper::renderModule($searchmodule, array('style' => 'none'));
            $params = new JRegistry;
            $params->loadString($searchmodule->params);
            echo $output;
        }
        ?>

    </nav>

<h1 class="no-margin"><?php echo $this->item->name; ?></h1>

<hr class="no-top-margin smallmargin">

<article>

<div class="row">

    <div class="col span_7_of_12">
        <span class="upper-text brand-color" style="font-size:12px;"><span class="bold">Срок действия акции c <span style="font-size: 18px;"><?php echo $this->item->date_start; ?></span> до <span style="font-size: 18px;"><?php echo $this->item->date_end; ?></span> </span></span>
    </div>
    <div class="col span_5_of_12" style="float: right; width: 40%;">
        <div class="social-buttons">
            <div class="social-buttons">
                <div style="float: left;">
                    <div id="vk_like"></div>
                </div>
                <script type="text/javascript">
                    VK.Widgets.Like("vk_like", {type: "button"});
                </script>
                <div style="float: left; ">
                    <div class="fb-like" data-href="http://dexline.com.ua<?php echo $_SERVER['REQUEST_URI']; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
                </div>
                <div style="float: left; margin-left: 35px;">
                    <div class="g-plusone" data-size="medium"></div>
                </div>
                <script src="https://apis.google.com/js/platform.js" async defer>
                    {lang: 'ru'}
                </script>
            </div></div>
    </div>
</div>

<hr class="no-top-margin smallmargin">

    <div class="row" style="position: relative;">
        <div class="col span_7_of_12">
            <div class="tag-holder">
                <img alt="" class="full-width special-width" src="<?php if (file_exists($this->item->image)) { echo $this->item->image; } else { echo '/images/actions/'.$this->item->image; }  ?>">
            </div>
        </div>
        <div class="span_5_of_12 sharedescription" style="position: absolute; right: 0; width: 40%;">
            <div class="shdescblock">
                <?php echo $this->item->text; ?>
            </div>
        </div>


        <div style="position: absolute; width: 470px; bottom: 0px; right: 0;">
            <?php
            $db = JFactory::getDbo();

            $query = "SELECT * FROM `#__shares` WHERE id= ".$this->item->id."";
            $db->setQuery($query);
            $share = $db->LoadAssoc();
            ?>

            <?php if ($this->item->type==6) { ?>
                <section class="promo-block red" style="margin-bottom: 0px;">
                    <span class="title" style="right: 20%">До конца акции осталось</span>
                    <div class="row" style="padding-top: 2px; padding-left: 28px;">
                        <div class="col span_8_of_12" style="position: absolute; width: 260px;">
                            <div class="is-countdown" id="countdown">
                            </div>
                        </div>
                    </div>
                        <span class="promo-tagvr vrdiscount">
                            <span class="big">Супер</span> <span class="small">цена</span>
                        </span>
                </section>
            <?php } ?>
            <?php if ($this->item->type==4) { ?>
                <section class="promo-block yellow" style="margin-bottom: 0px;">
                    <span class="title" style="right: 20%">До конца акции осталось</span>
                    <div class="row" style="padding-top: 2px; padding-left: 28px;">
                        <div class="col span_8_of_12" style="position: absolute; width: 260px;">
                            <div class="is-countdown" id="countdown">
                            </div>
                        </div>
                    </div>
                    <span class="promo-tag present"></span>
                </section>
            <?php } ?>
            <?php if ($this->item->type==7) { ?>
                <section class="promo-block green" style="margin-bottom: 0px;">
                    <span class="title" style="right: 20%">До конца акции осталось</span>
                    <div class="row" style="padding-top: 2px; padding-left: 28px;">
                        <div class="col span_8_of_12" style="position: absolute; width: 260px;">
                            <div class="is-countdown" id="countdown">
                            </div>
                        </div>
                    </div>
                    <span class="promo-tag delivery"></span>
                </section>
            <?php } ?>
            <?php if ($this->item->type==1) { ?>
                <section class="promo-block red" style="margin-bottom: 0px;">
                    <span class="title" style="right: 20%">До конца акции осталось</span>
                    <div class="row" style="padding-top: 2px; padding-left: 28px;">
                        <div class="col span_8_of_12" style="position: absolute; width: 260px;">
                            <div class="is-countdown" id="countdown">
                            </div>
                        </div>
                    </div>
                        <span class="promo-tagvr vrdiscount">
                            <span class="smallb">Кредит</span> <span class="bigb">0%</span>
                        </span>
                </section>
            <?php } ?>

            <?php
            $vardate = explode('-',$share['date_end']);
            ?>

            <script src="/templates/dexline/js/jquery.plugin.js" type="text/javascript"></script>
            <script src="/templates/dexline/js/jquery.countdown.js" type="text/javascript"></script>

            <script>
                var newEnd = new Date();
                newEnd = new Date(<?php echo $vardate[0]; ?>, <?php echo $vardate[1]; ?> - 1, <?php echo $vardate[2]; ?>+1);
                jQuery('#countdown.is-countdown').countdown({until: newEnd, format: 'dHMS', layout: '<span class="countdown-row countdown-show4"><span class="countdown-section" style="margin-right: 6px;"><span class="countdown-amount">{dn}</span><span class="countdown-period">Дней</span></span><span class="countdown-section"><span class="countdown-amount">{hn}</span><span class="countdown-period">Часов</span></span><span class="countdown-section"><span class="countdown-amount">{mn}</span><span class="countdown-period">Минут</span></span><span class="countdown-section"><span class="countdown-amount">{sn}</span><span class="countdown-period">Секунд</span></span></span>'});
            </script>
        </div>

        </div>
</article>

    <?php
if ( $this->item->date_end >= date('Y-m-d') ) {
    if ($this->item->type==4) {
        if ($this->item->typesearch==1) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                            $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                WHERE jcp.category_id = ".$res->category_id." AND jsp.status=1" ;
                            $db->setQuery($query);
                            $mainproducts = $db->LoadObjectList();
                            foreach ($mainproducts as $products) {
                                foreach ( json_decode($res->bonusproducts) as $bonid=>$bonprod ) {
                                    $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$bonid." ";
                                    $db->setQuery($query);
                                    $product = $db->LoadAssoc();
                                    if ($product) {
                                    ?>
                                    <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                        <div class="item">
                                            <a rel="nofollow" class="img-holder" href="/product/<?php echo $products->{'alias_ru-RU'}; ?>?action=<?php $this->item->id;?>&action_id=<?php echo $bonid; ?>">
                                                <img
                                                    src="/components/com_jshopping/files/img_products/<?php echo $products->image; ?>"
                                                    alt="<?php echo $products->{'name_ru-RU'}; ?>">
                                            </a>
                                            <span class="h4vr">
                                                <a href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                    <span class="title"><?php echo $products->{'name_ru-RU'}; ?></span>
                                                </a>
                                            </span>
                                            <div class="col span_12_of_12" style="margin-left:0;">
                                                <div class="col span_1_of_2" style="width: 44%;">
                                                    <div class="productbonusblock-top"></div>
                                                    <div class="productbonusblock">
                                                        <a href="/product/<?php echo $product['alias_ru-RU']; ?>"><img src="/components/com_jshopping/files/img_products/<?php echo $product['image']; ?>" /></a>
                                                    </div>
                                                </div>
                                                <div class="col span_1_of_2" style="width: 54%;">
                                                    <div class="productbonustext">
                                                        <span>Подарок</span>
                                                        <a href="/product/<?php echo $product['alias_ru-RU']; ?>"><?php echo $product['name_ru-RU']; ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <form method="post" action="">
                                                <span class="price"><?php echo number_format($products->product_price,0,'',' '); ?><span>грн</span></span>
                                                <a rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=cart&task=vradd&category_id='.$products->category_id.'&product_id='.$products->product_id.'&action='.$this->item->id.'&action_id='.$bonid.'&Itemid=139'); ?>"
                                                   class="button btn buy">Купить!</a>
                                            </form>
                                        </div>
                                    </li>
                                <?php $vrcount++; } }
                            }
                        } ?>
                    </ul>
                </section>
            <?php
            }
        }
        if ($this->item->typesearch==2) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                            foreach (json_decode($res->category_id) as $category_id) {
                                $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                INNER JOIN `#__jshopping_manufacturers` jm ON jm.manufacturer_id=jsp.product_manufacturer_id
                                INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                WHERE jm.manufacturer_id = ".$res->brend_id." AND jcp.category_id = $category_id AND jsp.status=1" ;
                                $db->setQuery($query);
                                $mainproducts = $db->LoadObjectList();
                                foreach ($mainproducts as $products) {
                                    foreach ( json_decode($res->bonusproducts)->$category_id as $bonid=>$bonprod ) {
                                        $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$bonid." ";
                                        $db->setQuery($query);
                                        $product = $db->LoadAssoc();
                                        if ($product) {
                                        ?>
                                        <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                            <div class="item">
                                                <a rel="nofollow" class="img-holder" href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                    <img
                                                        src="/components/com_jshopping/files/img_products/<?php echo $products->image; ?>"
                                                        alt="<?php echo $products->{'name_ru-RU'}; ?>">
                                                </a>
                                                <span class="h4vr">
                                                    <a href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                        <span class="title"><?php echo $products->{'name_ru-RU'}; ?></span>
                                                    </a>
                                                </span>
                                                <div class="col span_12_of_12" style="margin-left:0;">
                                                    <div class="col span_1_of_2" style="width: 44%;">
                                                        <div class="productbonusblock-top"></div>
                                                        <div class="productbonusblock">
                                                            <a href="/product/<?php echo $product['alias_ru-RU']; ?>"><img src="/components/com_jshopping/files/img_products/<?php echo $product['image']; ?>" /></a>
                                                        </div>
                                                    </div>
                                                    <div class="col span_1_of_2" style="width: 54%;">
                                                        <div class="productbonustext">
                                                            <span>Подарок</span>
                                                            <a href="/product/<?php echo $product['alias_ru-RU']; ?>"><?php echo $product['name_ru-RU']; ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <form method="post" action="">
                                                    <span class="price"><?php echo number_format($products->product_price,0,'',' '); ?><span>грн</span></span>
                                                    <a rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=cart&task=vradd&category_id='.$category_id.'&product_id='.$products->product_id.'&action='.$this->item->id.'&action_id='.$bonid.'&Itemid=139'); ?>"
                                                       class="button btn buy">Купить!</a>
                                                </form>
                                            </div>
                                        </li>
                                    <?php $vrcount++; } }
                                }
                            }
                        } ?>
                    </ul>
                </section>
            <?php
            }
        }
        if ($this->item->typesearch==3) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                                $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                WHERE jsp.product_id = ".$res->product_id." AND jsp.status=1";
                                $db->setQuery($query);
                                $mainproduct = $db->LoadAssoc();
                                if ($mainproduct) {
                                foreach ( json_decode($res->bonusproducts) as $bonid=>$bonprod ) {
                                    $query = "SELECT * FROM `#__jshopping_products` WHERE product_id = ".$bonid." ";
                                    $db->setQuery($query);
                                    $product = $db->LoadAssoc();
                                    if ($product) {
                                ?>
                                    <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                        <div class="item">
                                            <a rel="nofollow" class="img-holder" href="/product/<?php echo $mainproduct['alias_ru-RU']; ?>">
                                                <img
                                                    src="/components/com_jshopping/files/img_products/<?php echo $mainproduct['image']; ?>"
                                                    alt="<?php echo $mainproduct['name_ru-RU']; ?>">
                                            </a>
                                            <span class="h4vr">
                                                <a href="/product/<?php echo $mainproduct['alias_ru-RU']; ?>">
                                                    <span class="title"><?php echo $mainproduct['name_ru-RU']; ?></span>
                                                </a>
                                            </span>
                                            <div class="col span_12_of_12" style="margin-left:0;">
                                                <div class="col span_1_of_2" style="width: 44%;">
                                                    <div class="productbonusblock-top"></div>
                                                    <div class="productbonusblock">
                                                        <a href="/product/<?php echo $product['alias_ru-RU']; ?>"><img src="/components/com_jshopping/files/img_products/<?php echo $product['image']; ?>" /></a>
                                                    </div>
                                                </div>
                                                <div class="col span_1_of_2" style="width: 54%;">
                                                    <div class="productbonustext">
                                                        <span>Подарок</span>
                                                        <a href="/product/<?php echo $product['alias_ru-RU']; ?>"><?php echo $product['name_ru-RU']; ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <form method="post" action="">
                                                <span class="price"><?php echo number_format($mainproduct['product_price'],0,'',' '); ?><span>грн</span></span>
                                                <a rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=cart&task=vradd&category_id='.$mainproduct['category_id'].'&product_id='.$res->product_id.'&action='.$this->item->id.'&action_id='.$bonid.'&Itemid=139'); ?>"
                                                   class="button btn buy">Купить!</a>
                                            </form>
                                        </div>
                                    </li>
                                <?php $vrcount++; } } }
                        } ?>
                    </ul>
                </section>
            <?php
            }
        }
    }
    if ($this->item->type==6) {
        if ($this->item->typesearch==1) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                            $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                    INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                    WHERE jcp.category_id = ".$res->category_id." AND jsp.status=1" ;
                            $db->setQuery($query);
                            $mainproducts = $db->LoadObjectList();
                            foreach ($mainproducts as $products) { ?>
                                <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                    <div class="item">
                                        <a rel="nofollow" class="img-holder" href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                            <img
                                                src="/components/com_jshopping/files/img_products/<?php echo $products->image; ?>"
                                                alt="<?php echo $products->{'name_ru-RU'}; ?>">
                                        </a>
                                        <span class="h4vr">
                                            <a href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                <span class="title"><?php echo $products->{'name_ru-RU'}; ?></span>
                                            </a>
                                        </span>
                                        <div class="col span_12_of_12" style="margin-left:0;">
                                            <div class="col span_1_of_2" style="width: 44%;">
                                                <div class="productprice-top"></div>
                                                <div class="productbonusblock">
                                                    <span class="specialprice">супер <span>цена</span></span>
                                                </div>
                                            </div>
                                            <div class="col span_1_of_2" style="width: 54%;">
                                                <div class="productpricetext">
                                                    <span class="price old"><?php echo number_format(($products->product_price+($products->product_price/100)*$res->main_product_price),0,'',' '); ?><span>грн</span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <form method="post" action="">
                                            <span class="price"><?php echo number_format($products->product_price,0,'',' '); ?><span>грн</span></span>
                                            <a rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=cart&task=vradd&category_id='.$products->category_id.'&product_id='.$products->product_id.'&action='.$this->item->id.'&Itemid=139'); ?>"
                                               class="button btn buy">Купить!</a>
                                        </form>
                                    </div>
                                </li>
                            <?php $vrcount++; }
                        } ?>
                    </ul>
                </section>
            <?php
            }
        }
        if ($this->item->typesearch==2) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                            foreach (json_decode($res->category_id) as $category_id) {
                                $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                    INNER JOIN `#__jshopping_manufacturers` jm ON jm.manufacturer_id=jsp.product_manufacturer_id
                                    INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                    WHERE jm.manufacturer_id = ".$res->brend_id." AND jcp.category_id = $category_id AND jsp.status=1" ;
                                $db->setQuery($query);
                                $mainproducts = $db->LoadObjectList();
                                foreach ($mainproducts as $products) { ?>
                                    <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                        <div class="item">
                                            <a rel="nofollow" class="img-holder" href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                <img
                                                    src="/components/com_jshopping/files/img_products/<?php echo $products->image; ?>"
                                                    alt="<?php echo $products->{'name_ru-RU'}; ?>">
                                            </a>
                                            <span class="h4vr">
                                                <a href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                    <span class="title"><?php echo $products->{'name_ru-RU'}; ?></span>
                                                </a>
                                            </span>
                                            <div class="col span_12_of_12" style="margin-left:0;">
                                                <div class="col span_1_of_2" style="width: 44%;">
                                                    <div class="productprice-top"></div>
                                                    <div class="productbonusblock">
                                                        <span class="specialprice">супер <span>цена</span></span>
                                                    </div>
                                                </div>
                                                <div class="col span_1_of_2" style="width: 54%;">
                                                    <div class="productpricetext">
                                                        <span class="price old"><?php echo number_format(($products->product_price+($products->product_price/100)*json_decode($res->main_product_price)->$category_id),0,'',' '); ?><span>грн</span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <form method="post" action="">
                                                <span class="price"><?php echo number_format($products->product_price,0,'',' '); ?><span>грн</span></span>
                                                <a rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=cart&task=vradd&category_id='.$category_id.'&product_id='.$products->product_id.'&action='.$this->item->id.'&Itemid=139'); ?>"
                                                   class="button btn buy">Купить!</a>
                                            </form>
                                        </div>
                                    </li>
                                <?php $vrcount++; }
                            }
                        } ?>
                    </ul>
                </section>
            <?php
            }
        }
        if ($this->item->typesearch==3) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ORDER BY `mainordering` ASC ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                            $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                    INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                    WHERE jsp.product_id = ".$res->product_id." AND jsp.status=1";
                            $db->setQuery($query);
                            $mainproduct = $db->LoadAssoc();
                            if ($mainproduct) {
                            ?>
                            <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                <div class="item">
                                    <a rel="nofollow" class="img-holder" href="/product/<?php echo $mainproduct['alias_ru-RU']; ?>">
                                        <img
                                            src="/components/com_jshopping/files/img_products/<?php echo $mainproduct['image']; ?>"
                                            alt="<?php echo $mainproduct['name_ru-RU']; ?>">
                                    </a>
                                    <span class="h4vr">
                                        <a href="/product/<?php echo $mainproduct['alias_ru-RU']; ?>">
                                            <span class="title"><?php echo $mainproduct['name_ru-RU']; ?></span>
                                        </a>
                                    </span>
                                    <div class="col span_12_of_12" style="margin-left:0;">
                                        <div class="col span_1_of_2" style="width: 44%;">
                                            <div class="productprice-top"></div>
                                            <div class="productbonusblock">
                                                <span class="specialprice">супер <span>цена</span></span>
                                            </div>
                                        </div>
                                        <div class="col span_1_of_2" style="width: 54%;">
                                            <div class="productpricetext">
                                                <span class="price old"><?php echo number_format(($mainproduct['product_price']+($mainproduct['product_price']/100)*$res->main_product_price),0,'',' '); ?><span>грн</span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="post" action="">
                                        <span class="price"><?php echo number_format($mainproduct['product_price'],0,'',' '); ?><span>грн</span></span>
                                        <a rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=cart&task=vradd&category_id='.$mainproduct['category_id'].'&product_id='.$res->product_id.'&action='.$this->item->id.'&Itemid=139'); ?>"
                                           class="button btn buy">Купить!</a>
                                    </form>
                                </div>
                            </li>
                        <?php $vrcount++; } } ?>
                    </ul>
                </section>
            <?php
            }
        }
    }
    if ($this->item->type==7) {
        if ($this->item->typesearch==1) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                            $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                        INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                        WHERE jcp.category_id = ".$res->category_id." AND jsp.status=1" ;
                            $db->setQuery($query);
                            $mainproducts = $db->LoadObjectList();
                            foreach ($mainproducts as $products) { ?>
                                <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                    <div class="item">
                                        <a rel="nofollow" class="img-holder" href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                            <img
                                                src="/components/com_jshopping/files/img_products/<?php echo $products->image; ?>"
                                                alt="<?php echo $products->{'name_ru-RU'}; ?>">
                                        </a>
                                        <span class="h4vr">
                                            <a href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                <span class="title"><?php echo $products->{'name_ru-RU'}; ?></span>
                                            </a>
                                        </span>
                                        <div class="col span_12_of_12" style="margin-left:0;">
                                            <div class="col span_1_of_2" style="width: 44%;">
                                                <div class="productprice-top"></div>
                                                <div class="productbonusblock">
                                                    <span class="specialdostavka"><img src="/images/image-special-dostavka.png" /></span>
                                                </div>
                                            </div>
                                            <div class="col span_1_of_2" style="width: 54%;">
                                                <div class="productpricetext">
                                                    <span class="special-dostavka">бесплатная доставка</span>
                                                </div>
                                            </div>
                                        </div>
                                        <form method="post" action="">
                                            <span class="price"><?php echo number_format($products->product_price,0,'',' '); ?><span>грн</span></span>
                                            <a rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=cart&task=vradd&category_id='.$products->category_id.'&product_id='.$products->product_id.'&action='.$this->item->id.'&Itemid=139'); ?>"
                                               class="button btn buy">Купить!</a>
                                        </form>
                                    </div>
                                </li>
                                <?php $vrcount++; }
                        } ?>
                    </ul>
                </section>
            <?php
            }
        }
        if ($this->item->typesearch==2) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                            foreach (json_decode($res->category_id) as $category_id) {
                                $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                        INNER JOIN `#__jshopping_manufacturers` jm ON jm.manufacturer_id=jsp.product_manufacturer_id
                                        INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                        WHERE jm.manufacturer_id = ".$res->brend_id." AND jcp.category_id = $category_id AND jsp.status=1" ;
                                $db->setQuery($query);
                                $mainproducts = $db->LoadObjectList();
                                foreach ($mainproducts as $products) { ?>
                                    <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                        <div class="item">
                                            <a rel="nofollow" class="img-holder" href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                <img
                                                    src="/components/com_jshopping/files/img_products/<?php echo $products->image; ?>"
                                                    alt="<?php echo $products->{'name_ru-RU'}; ?>">
                                            </a>
                                            <span class="h4vr">
                                                <a href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                    <span class="title"><?php echo $products->{'name_ru-RU'}; ?></span>
                                                </a>
                                            </span>
                                            <div class="col span_12_of_12" style="margin-left:0;">
                                                <div class="col span_1_of_2" style="width: 44%;">
                                                    <div class="productprice-top"></div>
                                                    <div class="productbonusblock">
                                                        <span class="specialdostavka"><img src="/images/image-special-dostavka.png" /></span>
                                                    </div>
                                                </div>
                                                <div class="col span_1_of_2" style="width: 54%;">
                                                    <div class="productpricetext">
                                                        <span class="special-dostavka">бесплатная доставка</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <form method="post" action="">
                                                <span class="price"><?php echo number_format($products->product_price,0,'',' '); ?><span>грн</span></span>
                                                <a rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=cart&task=vradd&category_id='.$category_id.'&product_id='.$products->product_id.'&action='.$this->item->id.'&Itemid=139'); ?>"
                                                   class="button btn buy">Купить!</a>
                                            </form>
                                        </div>
                                    </li>
                                    <?php $vrcount++; }
                            }
                        } ?>
                    </ul>
                </section>
            <?php
            }
        }
        if ($this->item->typesearch==3) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                            $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                        INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                        WHERE jsp.product_id = ".$res->product_id." AND jsp.status=1";
                            $db->setQuery($query);
                            $mainproduct = $db->LoadAssoc();
                            if ($mainproduct) {
                            ?>
                            <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                <div class="item">
                                    <a rel="nofollow" class="img-holder" href="/product/<?php echo $mainproduct['alias_ru-RU']; ?>">
                                        <img
                                            src="/components/com_jshopping/files/img_products/<?php echo $mainproduct['image']; ?>"
                                            alt="<?php echo $mainproduct['name_ru-RU']; ?>">
                                    </a>
                                    <span class="h4vr">
                                        <a href="/product/<?php echo $mainproduct['alias_ru-RU']; ?>">
                                            <span class="title"><?php echo $mainproduct['name_ru-RU']; ?></span>
                                        </a>
                                    </span>
                                    <div class="col span_12_of_12" style="margin-left:0;">
                                        <div class="col span_1_of_2" style="width: 44%;">
                                            <div class="productprice-top"></div>
                                            <div class="productbonusblock">
                                                <span class="specialdostavka"><img src="/images/image-special-dostavka.png" /></span>
                                            </div>
                                        </div>
                                        <div class="col span_1_of_2" style="width: 54%;">
                                            <div class="productpricetext">
                                                <span class="special-dostavka">бесплатная доставка</span>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="post" action="">
                                        <span class="price"><?php echo number_format($mainproduct['product_price'],0,'',' '); ?><span>грн</span></span>
                                        <a rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=cart&task=vradd&category_id='.$mainproduct['category_id'].'&product_id='.$res->product_id.'&action='.$this->item->id.'&Itemid=139'); ?>"
                                           class="button btn buy">Купить!</a>
                                    </form>
                                </div>
                            </li>
                            <?php $vrcount++; } } ?>
                    </ul>
                </section>
            <?php
            }
        }
    }
    if ($this->item->type==1) {
        if ($this->item->typesearch==1) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                            $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                            WHERE jcp.category_id = ".$res->category_id." AND jsp.status=1 AND jsp.product_price >= 300 AND jsp.product_price <= 20000" ;
                            $db->setQuery($query);
                            $mainproducts = $db->LoadObjectList();
                            foreach ($mainproducts as $products) { ?>
                                <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                    <div class="item">
                                        <a rel="nofollow" class="img-holder" href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                            <img
                                                src="/components/com_jshopping/files/img_products/<?php echo $products->image; ?>"
                                                alt="<?php echo $products->{'name_ru-RU'}; ?>">
                                        </a>
                                        <span class="h4vr">
                                            <a href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                <span class="title"><?php echo $products->{'name_ru-RU'}; ?></span>
                                            </a>
                                        </span>
                                        <div class="col span_12_of_12" style="margin-left:0;">
                                            <div class="col span_1_of_2" style="width: 44%;">
                                                <div class="productprice-top"></div>
                                                <div class="productbonusblock">
                                                    <span class="specialkredit">0%</span>
                                                </div>
                                            </div>
                                            <div class="col span_1_of_2" style="width: 54%;">
                                                <div class="productpricetext">
                                                    <span class="specialkredit">Кредит</span>
                                                </div>
                                            </div>
                                        </div>
                                        <form method="post" action="">
                                            <span class="price"><?php echo number_format($products->product_price,0,'',' '); ?><span>грн</span></span>
                                            <a rel="nofollow" href="/product/<?php echo $products->{'alias_ru-RU'}; ?>"
                                               class="button btn buy">В кредит</a>
                                        </form>
                                    </div>
                                </li>
                                <?php $vrcount++; }
                        } ?>
                    </ul>
                </section>
            <?php
            }
        }
        if ($this->item->typesearch==2) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                            foreach (json_decode($res->category_id) as $category_id) {
                                $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                            INNER JOIN `#__jshopping_manufacturers` jm ON jm.manufacturer_id=jsp.product_manufacturer_id
                                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                            WHERE jm.manufacturer_id = ".$res->brend_id." AND jcp.category_id = $category_id AND jsp.status=1  AND jsp.product_price >= 300 AND jsp.product_price <= 20000" ;
                                $db->setQuery($query);
                                $mainproducts = $db->LoadObjectList();
                                foreach ($mainproducts as $products) { ?>
                                    <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                        <div class="item">
                                            <a rel="nofollow" class="img-holder" href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                <img
                                                    src="/components/com_jshopping/files/img_products/<?php echo $products->image; ?>"
                                                    alt="<?php echo $products->{'name_ru-RU'}; ?>">
                                            </a>
                                            <span class="h4vr">
                                                <a href="/product/<?php echo $products->{'alias_ru-RU'}; ?>">
                                                    <span class="title"><?php echo $products->{'name_ru-RU'}; ?></span>
                                                </a>
                                            </span>
                                            <div class="col span_12_of_12" style="margin-left:0;">
                                                <div class="col span_1_of_2" style="width: 44%;">
                                                    <div class="productprice-top"></div>
                                                    <div class="productbonusblock">
                                                        <span class="specialkredit">0%</span>
                                                    </div>
                                                </div>
                                                <div class="col span_1_of_2" style="width: 54%;">
                                                    <div class="productpricetext">
                                                        <span class="specialkredit">Кредит</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <form method="post" action="">
                                                <span class="price"><?php echo number_format($products->product_price,0,'',' '); ?><span>грн</span></span>
                                                <a rel="nofollow" href="/product/<?php echo $products->{'alias_ru-RU'}; ?>"
                                                   class="button btn buy">В кредит</a>
                                            </form>
                                        </div>
                                    </li>
                                    <?php $vrcount++; }
                            }
                        } ?>
                    </ul>
                </section>
            <?php
            }
        }
        if ($this->item->typesearch==3) {
            $query = "SELECT * FROM `#__shares_products` WHERE shares_id = ".$this->item->id." ";
            $db->setQuery($query);
            $result = $db->LoadObjectList();
            if ($result) {
                ?>
                <section style="margin-left: 0px;" class="col span_12_of_12 top-4pad vrseen">
                    <div class="headline">
                        <span>Товары, которые участвуют в акции</span>
                    </div>
                    <ul class="catalog tile catalog-short discount slider" id="items-slidervr">
                        <?php
                        $vrcount=0;
                        foreach ($result as $res) {
                            $query = "SELECT jsp.*, jcp.category_id FROM `#__jshopping_products` jsp
                                            INNER JOIN `#__jshopping_products_to_categories` jcp ON jsp.product_id=jcp.product_id
                                            WHERE jsp.product_id = ".$res->product_id." AND jsp.status=1  AND jsp.product_price >= 300 AND jsp.product_price <= 20000";
                            $db->setQuery($query);
                            $mainproduct = $db->LoadAssoc();
                            if ($mainproduct) {
                            ?>
                            <li style="float: left; position: relative; margin-left: 3px; <?php if ( $vrcount == 4 ) { echo 'margin-right: 0px;'; $vrcount=-1; } else { echo 'margin-right: 18px;'; } ?> width: 218px;">
                                <div class="item">
                                    <a rel="nofollow" class="img-holder" href="/product/<?php echo $mainproduct['alias_ru-RU']; ?>">
                                        <img
                                            src="/components/com_jshopping/files/img_products/<?php echo $mainproduct['image']; ?>"
                                            alt="<?php echo $mainproduct['name_ru-RU']; ?>">
                                    </a>
                                    <span class="h4vr">
                                        <a href="/product/<?php echo $mainproduct['alias_ru-RU']; ?>">
                                            <span class="title"><?php echo $mainproduct['name_ru-RU']; ?></span>
                                        </a>
                                    </span>
                                    <div class="col span_12_of_12" style="margin-left:0;">
                                        <div class="col span_1_of_2" style="width: 44%;">
                                            <div class="productprice-top"></div>
                                            <div class="productbonusblock">
                                                <span class="specialkredit">0%</span>
                                            </div>
                                        </div>
                                        <div class="col span_1_of_2" style="width: 54%;">
                                            <div class="productpricetext">
                                                <span class="specialkredit">Кредит</span>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="post" action="">
                                        <span class="price"><?php echo number_format($mainproduct['product_price'],0,'',' '); ?><span>грн</span></span>
                                        <a rel="nofollow" href="/product/<?php echo $mainproduct['alias_ru-RU']; ?>"
                                           class="button btn buy">В кредит</a>
                                    </form>
                                </div>
                            </li>
                            <?php $vrcount++; }
                        } ?>
                    </ul>
                </section>
            <?php
            }
        }
    }

}
    ?>