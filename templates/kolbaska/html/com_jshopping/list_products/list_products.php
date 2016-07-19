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

<!-- Inner Content -->
<div class="inner-page">
    <div class="shopping">
        <div class="container">
            <!-- Shopping items content -->
            <?php $link_search=explode('/',$_SERVER['REQUEST_URI']); if($link_search[1]!='search') { ?>
            <div class="left-sidebar">
                <aside class="col span_3_of_12 panel left vrfilterblock">
                    <span class="title">Параметры</span>
                    <div class="panel-body">
                        <script type="text/javascript">
                            var productminprice = <?php echo $this->productminprice; ?>;
                            var productmaxprice = <?php echo $this->productmaxprice; ?>;
                        </script>
                        <?php
                        $this->searchmodules = JModuleHelper::getModules('vrfilternew');
                        foreach ($this->searchmodules as $searchmodule)
                        {
                            $output = JModuleHelper::renderModule($searchmodule, array('style' => 'none'));
                            $params = new JRegistry;
                            $params->loadString($searchmodule->params);
                            echo $output;
                        }
                        ?>
                        <script type="text/javascript">
                            var vrscripton = <?php echo $this->vrscripton; ?>;
                            var vrtotal = <?php echo $this->vrtotal; ?>;
                            var manufacturercount = JSON.parse('<?php echo $this->manufacturercount; ?>');
                            var characteristicscount = JSON.parse('<?php echo $this->characteristicscount; ?>');
                            jQuery('#uf_count_product span').html(vrtotal);
                            if (vrscripton==2) {
                                jQuery('.menu #manufacturers a').addClass('disable');
                                jQuery('.menu #manufacturers a span').html(' (0)');
                                jQuery('.menu #extrafields a').addClass('disable');
                                jQuery('.menu #extrafields a span').html(' (0)');
                                jQuery.each(manufacturercount,function(index,value){
                                    if (jQuery('#manufacturer'+index).parent().parent().children('div').hasClass('active')) {
                                        jQuery('#manufacturer'+index+' span').html(' (+'+value+')');
                                    } else {
                                        jQuery('#manufacturer'+index+' span').html(' ('+value+')');
                                    }
                                    jQuery('#manufacturer'+index).removeClass('disable');
                                });
                                jQuery('.menu #manufacturers a').each(function(){
                                    if ( jQuery(this).hasClass('disable') ) {
                                        jQuery(this).attr('href','javascript:void(null)');
                                    }
                                });
                                jQuery.each(characteristicscount,function(index,value){
                                    jQuery.each(characteristicscount[index],function(index1,value1){
                                        if (jQuery('#'+index+'extrafield'+index1).parent().parent().children('div').hasClass('active')) {
                                            jQuery('#'+index+'extrafield'+index1+' span').html(' (+'+value1+')');
                                        } else {
                                            jQuery('#'+index+'extrafield'+index1+' span').html(' ('+value1+')');
                                        }
                                        jQuery('#'+index+'extrafield'+index1).removeClass('disable');
                                    });
                                });

                            }

                            jQuery('.menu #extrafields a').each(function(){
                                if ( jQuery(this).hasClass('disable') ) {
                                    jQuery(this).attr('href','javascript:void(null)');
                                }
                            });

                            if (productminprice==productmaxprice) {
                                productmaxprice=productminprice+1;
                            }

                            //jQuery('#uf_price_from').val(productminprice);
                            //jQuery('#uf_price_to').val(productmaxprice);
                        </script>
                    </div>
                </aside>
            </div>

            <div class="right-sidebar">
                <?php } ?>
            <div class="shopping-content">
                <div class="row">

                    <?php

                    if($this->category->category_parent_id==12){
                        echo '<div class="categorynotification">Цену уточняйте у менеджера</div>';
                    }

                    $count = 0;
                    foreach ($this->rows as $k=>$product) : ?>

                        <div class="col-md-4 col-sm-6">
                            <?php include(dirname(__FILE__)."/".$product->template_block_product);?>
                        </div>
                    <?php
                    $count++;
                        if($count==3) { echo '<div class="clearfix"></div>'; $count = 0; }
                    endforeach; ?>

                </div>
            </div>
    <?php if($link_search[1]!='search') { ?>
            </div>
        <?php } ?>
        </div>
    </div>
</div>

<?php } else {

    foreach ($this->rows as $k=>$product){ ?>

        <div class="col-md-4 col-sm-6">
            <?php include(dirname(__FILE__)."/".$product->template_block_product);?>
        </div>

    <?php }

} ?>