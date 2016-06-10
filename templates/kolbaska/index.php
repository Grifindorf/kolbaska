<!DOCTYPE html>
<html>
<head>

    <jdoc:include type="head" />

    <link rel="shortcut icon" href="/templates/kolbaska/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="/templates/kolbaska/css/bootstrap.css" rel="stylesheet">
    <link href="/templates/kolbaska/css/font-awesome.min.css" rel="stylesheet">
    <link href="/templates/kolbaska/css/fontello.css" rel="stylesheet">
    <link href="/templates/kolbaska/css/style.css" rel="stylesheet">

    <link href="/templates/kolbaska/css/style-pagination.css" rel="stylesheet">

    <!--[if IE]><link rel="stylesheet" href="/templates/kolbaska/css/ie-style.css"><![endif]-->

    <link href="/templates/kolbaska/css/less-style.css" rel="stylesheet">
    <link href="/templates/kolbaska/css/settings.css" rel="stylesheet">
    <link href="/templates/kolbaska/css/flexslider.css" rel="stylesheet" media="screen" />
    <link href="/templates/kolbaska/css/prettyPhoto.css" rel="stylesheet">
    <? if ($_SERVER['REQUEST_URI'] == '/') { ?>
    <link href="/templates/kolbaska/css/main.css" rel="stylesheet">
    <? } ?>
    <script>
        $ = jQuery;
    </script>

</head>

<body>

<?php
/*$db = JFactory::getDbo();
$query = "SELECT `product_id`,`alias_ru-RU` as `aliasru`,`alias_uk-UA` as `aliasua`,`name_ru-RU` as `nameru`,`name_uk-UA` as `nameua` FROM `#__jshopping_products`";
$db->setQuery($query);
$results = $db->loadObjectList();
foreach($results as $result){
    if($result->aliasru==''){
        $aliasru=JApplication::stringURLSafe($result->nameru);
        $query ="UPDATE `#__jshopping_products` SET `alias_ru-RU` = '".$aliasru."' WHERE `product_id` = '".$result->product_id."' ";
        $db->setQuery($query);
        $db->execute();
    }
    if($result->aliasua==''){
        $aliasua=JApplication::stringURLSafe($result->nameua);
        $query ="UPDATE `#__jshopping_products` SET `alias_ru-RU` = '".$aliasua."' WHERE `product_id` = '".$result->product_id."' ";
        $db->setQuery($query);
        $db->execute();
    }

}*/
?>

<div class="modal modal-vcenter fade" id="modalvideo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <iframe id="modalvideoframe" width="800" height="600" src="https://www.youtube.com/embed/-oKVbjHonj8" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<!-- Shopping cart Modal -->
<div class="modal modal-vcenter fade" id="shoppingcart1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
                if (file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
                    jimport('joomla.application.component.model');
                    require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php');
                    require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
                    JSFactory::loadCssFiles();
                    JSFactory::loadLanguageFile();
                    JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_jshopping/models');
                    $cart = JModelLegacy::getInstance('cart', 'jshop');
                    $cart->load('cart');
                    $cart->addLinkToProducts(1);
                    $countprod = 0;
                    $array_products = array();
                }
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Корзина <span id="topcartinfoopt"><?=($cart->type_cart_price == 3 ? 'Минимальная сумма заказа 50грн.' : ($cart->type_cart_price == 2 ? JText::_('TPL_KOLBASKA_BYPRICE_ROZN') : JText::_('TPL_KOLBASKA_BYPRICE_OPT') ) )?></span></h4>
            </div>
            <div class="modal-body modalcartproductsoverfloww">
                <!-- Items table -->
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th width="360px"><?=JText::_('TPL_KOLBASKA_PRODUCT')?></th>
                        <th width="140px"><?=JText::_('TPL_KOLBASKA_COUNT')?></th>
                        <th width="140px"><?=JText::_('TPL_KOLBASKA_PRICE')?></th>
                        <th width="140px"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    foreach($cart->products as $key=>$value){
                        $array_products[$countprod] = $value;
                        ?>
                        <tr class="delete">
                            <td width="180px"><a href="<?php print $array_products[$countprod]["href"]; ?>"><img class="img-responsive img-rounded" src="http://kolbaska.com.ua/components/com_jshopping/files/img_products/<?php print $array_products[$countprod]["thumb_image"]; ?>" alt="" /></a></td>
                            <td width="180px"><a href="<?php print $array_products[$countprod]["href"]; ?>"><?php print $array_products[$countprod]["product_name"]; ?></a></td>
                            <td width="140px"><span class="btn btn-default btn-sm" onclick="minusQuantity(<?=$key?>)">&nbsp;-</span><input type="text" class="quantity" attr-key="<?=$key?>" id="quantity<?=$key?>" value="<?php print $array_products[$countprod]["quantity"]; ?>"><span class="btn btn-default btn-sm" onclick="plusQuantity(<?=$key?>)">+</span></td>
                            <td width="140px"><?php print formatprice( ($cart->type_cart_price == 2 || $cart->type_cart_price == 3 ? $array_products[$countprod]["price_rozn"] : $array_products[$countprod]["price"]) * $array_products[$countprod]["quantity"]); ?></td>
                            <td width="140px"><span onclick="deleteFromCart(<?=$key?>)" class="btn btn-default"><?=JText::_('TPL_KOLBASKA_DELETE')?></span></td>
                        </tr>
                        <?php $countprod++; ?>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th style="width: 66%;"><div id="counttooptcarttext"><?=JText::_('TPL_KOLBASKA_SUMMOPTPRICE')?> <span id="counttooptcart"><?=number_format($cart->price_product_opt,2)?></span> грн.</div></th>
                            <th></th>
                            <th></th>
                            <th style="width: 12%;"><?=JText::_('TPL_KOLBASKA_SUMM')?></th>
                            <th style="width: 20%;"><span class="summ"><?=number_format($cart->price_product,2)?></span> грн.</th>
                        </tr>
                        <tr>
                            <th><div id="summoptfromrozn"><?=($cart->type_cart_price == 3 ? 'Минимальная сумма заказа 50грн.' : ($cart->type_cart_price == 2 ? JText::_('TPL_KOLBASKA_SUMMOPTPRICEROZN')." ".number_format( (1500 - $cart->price_product_opt) ,2)." грн" : JText::_('TPL_KOLBASKA_BYPRICE_OPT') ) )?></div></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <div style="float: left;" class="oneclickorder">
                    <input type="text" id="telephone" placeholder="+38 (0XX) XXX-XX-XX" class="phone btn btn-default" name="phone" />
                    <!-- oneclickorder() -->
                    <button type="button" id="oneclickorderbutton" class="btn btn-info <?=($cart->type_cart_price == 3 ? 'disabled' : '')?>" onclick="checkorderpermission(1)">Заказать в 1 клик</button>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Продолжить покупки</button>
                <a href="javascript:void(null)" id="fullclickorderbutton" data-href="<?php print '/component/jshopping/checkout/step2'?>" onclick="checkorderpermission(2)" class="btn btn-info <?=($cart->type_cart_price == 3 ? 'disabled' : '')?>">Оформить заказ</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Model End -->

<!-- Page Wrapper -->
<div class="wrapper <?=($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/uk/') ? 'main-page-wrapper' : 'inner-page-wrapper'?>">

    <!-- Header Start -->

    <div class="header">
	    <div class="header-background"></div>
        <div class="container">
            <!-- Header top area content -->
            <div class="header-top">
                <div class="row">
	                <div class="col-md-8 col-sm-7">
		                <!-- Navigation -->
		                <nav class="navbar navbar-default navbar-right" role="navigation">
			                <div class="container-fluid">
				                <!-- Brand and toggle get grouped for better mobile display -->
				                <div class="navbar-header">
					                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						                <span class="sr-only">Toggle navigation</span>
						                <span class="icon-bar"></span>
						                <span class="icon-bar"></span>
						                <span class="icon-bar"></span>
					                </button>
				                </div>
				                <!-- Collect the nav links, forms, and other content for toggling -->
				                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

					                <jdoc:include type="modules" style="none" name="menu" />

				                </div><!-- /.navbar-collapse -->
			                </div><!-- /.container-fluid -->
		                </nav>
	                </div>
                    <div class="col-md-3 col-sm-2">
                        <jdoc:include type="modules" style="none" name="lanquage" />
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-5 logo_area">
                    <!-- Link -->
                    <a href="<?=JRoute::_('/')?>">
                        <!-- Logo area -->
                        <div class="logo"></div>
	                    <div class="home_link">kolbaska.com.ua</div>
                    </a>
                </div>
	            <div class="col-md-4 col-sm-3 search-block">
		            <!-- Header top right content search box -->
		            <div class=" header-search">
			            <jdoc:include type="modules" style="none" name="search-pos" />
		            </div>
	            </div>
	            <div class="col-md-2 col-sm-3 contact-block">
		            <!-- Header top left content contact -->
		            <div class="header-contact">
			            <!-- Contact number -->
						<div class="contact_table">
							<div class="contact_row">
								<div class="contact_cell tel_codes">
									<ul>
										<li>044</li>
										<li>096</li>
										<li>063</li>
									</ul>
								</div>
								<div class="contact_cell main_tel">
									578-21-33
								</div>
							</div>
						</div>
			            <!--<jdoc:include type="modules" style="none" name="contacts" />-->
		            </div>
	            </div>
	            <!--<div class="col-md-2 col-sm-2" style="line-height: 40px;">
                        <div class="mod-countproducts">
                            <?php
	            $db = JFactory::getDbo();
	            $query = "SELECT COUNT(`product_id`) FROM `#__jshopping_products` WHERE `product_publish` = 1 AND `product_original_price` > 0";
	            $db->setQuery($query);
	            $count_products = $db->loadResult();
	            ?>
                            Количество товаров: <?=$count_products?>
                        </div>
                        <div class="clearfix"></div>
                    </div>-->
	            <div class="col-md-2 col-sm-2 cart-block">
		            <!-- Button Kart -->
		            <jdoc:include type="modules" style="none" name="cart" />
		            <div class="clearfix"></div>
	            </div>
            </div>
        </div> <!-- / .container -->
    </div>

    <!-- Header End -->

    <!-- Slider Start
    #################################
        - THEMEPUNCH BANNER -
    #################################	-->
    <jdoc:include type="modules" style="none" name="slaider" />
    <!-- Slider End -->


    <!-- Main Content -->
    <div class="main-content">

        <!-- Dishes Start -->

        <!-- Pricing Start -->

	    <div class="testimonial padd menu-top">
		    <div class="container">
			    <div class="row">
				    <jdoc:include type="modules" style="none" name="menu-top" />
		        </div>
			</div>
	    </div>


	    <?php if ($this->countModules( 'breadcrumbspos' )) : ?>
		    <div class="banner">
			    <div class="container">
				    <!-- Image -->
				    <jdoc:include type="modules" style="none" name="breadcrumbspos" />
				    <div class="clearfix"></div>
			    </div>
		    </div>
	    <?php endif; ?>


	    <!-- Carousel -->
	    <div class="testimonial carousel">
		    <div class="container">
			    <div class="row">
				    <!-- BLock heading -->
				    <jdoc:include type="modules" style="none" name="carousel" />
			    </div>
		    </div>
	    </div>

	    <!-- Start Bestseller-block1-->
	    <div class="testimonial padd bestseller-block1" <?=($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/uk/') ? 'style="border-bottom: 2px dotted #d3bdb2"' : ''?>">
		    <div class="container">
			    <div class="row">
					    <!-- BLock heading -->
					    <jdoc:include type="modules" style="none" name="bestseller-block1" />
			    </div>
		    </div>
		    <!--<div class="inner_bb1_1">
			    <img src="/templates/kolbaska/images/wood.png">
		    </div>-->
	    </div>
	    <!-- Start Bestseller-block1-->

	    <!-- Start Bestseller-block2-->
	    <div class="testimonial padd bestseller-block2">
		    <div class="container ">
			    <div class="row">
				    <!-- BLock heading -->
				    <jdoc:include type="modules" style="none" name="bestseller-block2" />
			    </div>
		    </div>
		    <!--<div class="inner_bb2_1">
			    <img src="/templates/kolbaska/images/grape.png">
		    </div>-->
	    </div>
	    <!-- Start Bestseller-block2-->

	    <!-- Showcase Start -->
		<?php if($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/uk/'){ ?>
	    <div class="showcase customReviews">
		    <div class="container">
			    <div>

				</div>
			    <div class="row">
				    <div class="col-md-6 col-sm-6">
					    <!-- Showcase section item -->
					    <div class="showcase-item">
						    <jdoc:include type="modules" style="none" name="bottom-block1" />
						    <div class="clearfix"></div>
					    </div>
				    </div>
				    <div class="col-md-6 col-sm-6">
					    <!-- Showcase section item -->
					    <div class="showcase-item">
						    <jdoc:include type="modules" style="none" name="bottom-block2" />
						    <div class="clearfix"></div>
					    </div>
				    </div>
			    </div>
		    </div>
	    </div>
	    <?php } ?>
	    <!-- Showcase End -->

        <!-- Showcase Start -->
	    <div class="showcase">
		    <div class="container">
			    <div class="row">
				    <div class="col-md-6 col-sm-6">
					    <!-- Showcase section item -->
					    <div class="showcase-item">
						    <jdoc:include type="modules" style="none" name="bottom-block3" />
						    <div class="clearfix"></div>
					    </div>
				    </div>
				    <div class="col-md-6 col-sm-6">
					    <!-- Showcase section item -->
					    <div class="showcase-item">
						    <jdoc:include type="modules" style="none" name="bottom-block4" />
						    <div class="clearfix"></div>
					    </div>
				    </div>
			    </div>
		    </div>
	    </div>
        <!-- Showcase End -->

        <!-- Pricing End -->

	    <!-- Text Start -->

	    <jdoc:include type="component" />

	    <!-- Text End -->

        <!-- Testimonial Start -->

	    <div class="testimonial padd menu-bottom">
		    <div class="container">
			    <div class="row">
				    <jdoc:include type="modules" style="none" name="menu-bottom" />
			    </div>
		    </div>
	    </div>

	    <div class="testimonial padd">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <!-- BLock heading -->
                        <jdoc:include type="modules" style="none" name="reviews" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonial End -->


    </div><!-- / Main Content End -->

    <!-- Footer Start -->

    <div class="footer padd">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <!-- Footer widget -->
                    <div class="footer-widget">
	                    <h4>Наши магазины</h4>
	                    <div class="custom">
		                    <div class="contact-details shops">
                                <!--<span>Оптово-розничный склад</span>
                                <span>Киев, ул. Волинская 34/1</span>
                                <div class="clearfix">&nbsp;</div>-->
			                    <span>Магазин “Kolbaska.com.ua"</span>
			                    <span>ст. м. Лесовая, ул. Попудренко 65 </span>
			                    <span>рынок ”Bazar”, место 16Д</span>
			                    <div class="clearfix">&nbsp;</div>
		                    </div>
	                    </div>
                        <!-- Heading -->
                        <jdoc:include type="modules" style="none" name="onlinepayments" />
                    </div> <!--/ Footer widget end -->
                </div>
                <div class="col-md-3 col-sm-6">
                    <!-- Footer widget -->
                    <div class="footer-widget">
                        <!-- Heading -->
                        <jdoc:include type="modules" style="none" name="footer1" />
                    </div> <!--/ Footer widget end -->
                </div>
                <div class="clearfix visible-sm"></div>
                <div class="col-md-3 col-sm-6">
                    <!-- Footer widget -->
                    <div class="footer-widget">
                        <!-- Heading -->
                        <jdoc:include type="modules" style="none" name="joinus" />
                    </div> <!--/ Footer widget end -->
                </div>
                <div class="col-md-3 col-sm-6">
                    <!-- Footer widget -->
                    <div class="footer-widget">
                        <!-- Heading -->
                        <jdoc:include type="modules" style="none" name="footer-contactus" />
                        <!-- Social media icon -->
                        <div class="social">
                            <a href="https://www.facebook.com/profile.php?id=100010951020899" target="_blank" class="facebook"><i class="fa fa-facebook"></i></a>
                            <a href="https://vk.com/club103366284" target="_blank" class="facebook"><i class="fa fa-vk"></i></a>
                        </div>
                    </div> <!--/ Footer widget end -->
                </div>
            </div>
            <!-- Copyright -->
            <div class="footer-copyright">
                <!-- Paragraph -->
                <p>&copy; Copyright 2015 <a href="http://kolbaska.com.ua">Kolbaska.com.ua</a>. Developed by <a href="https://krasota-style.com.ua">krasota-style.com.ua</a></p>
            </div>
        </div>
    </div>

    <!-- Footer End -->

</div><!-- / Wrapper End -->


<!-- Scroll to top -->
<span class="totop"><a href="#"><i class="fa fa-angle-up"></i></a></span>


<!-- Javascript files -->
<!-- jQuery -->
<script src="/templates/kolbaska/js/jquery.js"></script>
<script src="/templates/kolbaska/js/jquery.arcticmodal-0.3.min.js"></script>
<script src="/templates/kolbaska/js/jquery.mask.js"></script>
<!-- Bootstrap JS -->
<script src="/templates/kolbaska/js/bootstrap.js"></script>
<!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
<script type="text/javascript" src="/templates/kolbaska/js/jquery.themepunch.tools.min.js"></script>
<script type="text/javascript" src="/templates/kolbaska/js/jquery.themepunch.revolution.min.js"></script>
<!-- FLEX SLIDER SCRIPTS  -->
<script type="text/javascript" src="/templates/kolbaska/js/jquery.flexslider-min.js"></script>
<!-- Pretty Photo JS -->
<script src="/templates/kolbaska/js/jquery.prettyPhoto.js"></script>
<!-- Respond JS for IE8 -->
<script src="/templates/kolbaska/js/respond.min.js"></script>
<!-- HTML5 Support for IE -->
<script src="/templates/kolbaska/js/html5shiv.js"></script>
<!-- Custom JS -->
<script src="/templates/kolbaska/js/custom.js"></script>

<!-- JS code for this page -->
<script>
    $("#telephone").mask("+38 (000) 000-00-00");
    /* ******************************************** */
    /*  JS for SLIDER REVOLUTION  */
    /* ******************************************** */
    jQuery(document).ready(function() {
        jQuery('.tp-banner').revolution(
            {
                delay:9000,
                startheight:570,

                hideThumbs:10,

                navigationType:"bullet",

                hideArrowsOnMobile:"on",

                touchenabled:"on",
                onHoverStop:"on",

                navOffsetHorizontal:0,
                navOffsetVertical:20,

                stopAtSlide:-1,
                stopAfterLoops:-1,

                shadow:0,

                fullWidth:"on",
                fullScreen:"off"
            });
    });
    /* ******************************************** */
    /*  JS for FlexSlider  */
    /* ******************************************** */

    jQuery(window).load(function(){
        jQuery('.flexslider-recent').flexslider({
            animation:		"fade",
            animationSpeed:	1000,
            controlNav:		true,
            directionNav:	false
        });
        jQuery('.flexslider-testimonial').flexslider({
            animation: 		"fade",
            slideshowSpeed:	5000,
            animationSpeed:	1000,
            controlNav:		true,
            directionNav:	false
        });
    });

    /* Gallery */

    jQuery(".gallery-img-link").prettyPhoto({
        overlay_gallery: false, social_tools: false
    });

</script>


<section class="hidden"><!--modal windows-->

    <section id="write-us-init" class="box-modal">
        <div class="box-modal_close arcticmodal-close"></div>
        <jdoc:include type="modules" style="none" name="writeus" />
    </section>

    <section id="callback-init" class="box-modal">
        <div class="box-modal_close arcticmodal-close"></div>
        <jdoc:include type="modules" style="none" name="callback" />
    </section>

    <section id="message-init" class="box-modal small-margin">
        <div class="box-modal_close arcticmodal-close"></div>
        <jdoc:include type="message" />
    </section>

    <script>
        $(document).ready(function(){
            if ($('#system-message').length>0) {
                $('#message-init').arcticmodal();
            }
        });
    </script>

    <section id="buybyphone-init" class="box-modal">
        <div class="box-modal_close arcticmodal-close"></div>
        <p>Ваша заявка отправлена. Наши менеджера с Вами свяжутся в ближайшее время.</p>
    </section>
    <!-- not edit-->
    <section id="basket_empty" class="box-modal">
        <div class="box-modal_close arcticmodal-close"></div>
        <p class="text-center">В корзине нет товаров!</p>
    </section>

    <section id="basket_process" class="box-modal">
        <div class="box-modal_close arcticmodal-close"></div>
        <p class="text-center">Обработка заказа</p>
    </section>
    <!-- not edit-->


</section>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-33451161-4', 'auto');
    ga('send', 'pageview');

</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
	(function (d, w, c) {
		(w[c] = w[c] || []).push(function() {
			try {
				w.yaCounter34421025 = new Ya.Metrika({
					id:34421025,
					clickmap:true,
					trackLinks:true,
					accurateTrackBounce:true,
					webvisor:true
				});
			} catch(e) { }
		});

		var n = d.getElementsByTagName("script")[0],
			s = d.createElement("script"),
			f = function () { n.parentNode.insertBefore(s, n); };
		s.type = "text/javascript";
		s.async = true;
		s.src = "https://mc.yandex.ru/metrika/watch.js";

		if (w.opera == "[object Opera]") {
			d.addEventListener("DOMContentLoaded", f, false);
		} else { f(); }
	})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/34421025"
                    style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
	(function (d, w, c) {
		(w[c] = w[c] || []).push(function() {
			try {
				w.yaCounter34473860 = new Ya.Metrika({
					id:34473860,
					clickmap:true,
					trackLinks:true,
					accurateTrackBounce:true,
					webvisor:true
				});
			} catch(e) { }
		});

		var n = d.getElementsByTagName("script")[0],
			s = d.createElement("script"),
			f = function () { n.parentNode.insertBefore(s, n); };
		s.type = "text/javascript";
		s.async = true;
		s.src = "https://mc.yandex.ru/metrika/watch.js";

		if (w.opera == "[object Opera]") {
			d.addEventListener("DOMContentLoaded", f, false);
		} else { f(); }
	})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/34473860"
                    style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->


<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new
		Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-71748723-1', 'auto');
	ga('send', 'pageview');

</script>

</body>
</html>