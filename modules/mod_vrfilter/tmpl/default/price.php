<?php
/**
* @package Joomla
* @subpackage JoomShopping
* @author Dmitry Stashenko
* @website http://nevigen.com/
* @email support@nevigen.com
* @copyright Copyright © Nevigen.com. All rights reserved.
* @license Proprietary. Copyrighted Commercial Software
* @license agreement http://nevigen.com/license-agreement.html
**/

defined('_JEXEC') or die;

?>

<span class="title-form">Цена</span>
<div id="uf_prices" class="uf_options_price">
    <div class="row uf_price input-prepend input-append">
        <div class="col span_1_of_2">
            <label class="col span_1_of_3" for="low-price">от </label>
            <input type="text" name="pricefrom" value="<?php if ($filter_min_price > 0) { echo $filter_min_price; } else { echo $product_min_price; } ?>" id="uf_price_from" class="col span_2_of_3" id="low-price">
        </div>
        <div class="col span_1_of_2">
            <label class="col span_1_of_3" for="high-price">до </label>
            <input type="text" name="priceto" value="<?php if ($filter_max_price > 0) { echo $filter_max_price; } else { echo $product_max_price; } ?>" class="col span_2_of_3" id="uf_price_to">
        </div>
    </div>
    <div class="vr_price_trackbar">
        <div id="vr_price_trackbar" style="margin: 5px 0 6px 10px;"></div>
    </div>

    <script type="text/javascript" src="/templates/kolbaska/js/jquery.nouislider.all.min.js"></script>
    <script>
        <?php if ($product_min_price==$product_max_price) {
            $product_max_price = $product_max_price +1;
        }?>
        $('#vr_price_trackbar').noUiSlider({
            start: [<?php if ($filter_min_price > 0) { echo $filter_min_price; } else { echo $product_min_price; } ?>, <?php if ($filter_max_price > 0) { echo $filter_max_price; } else { echo $product_max_price; } ?>],
            connect: true,
            step: 1,
            format: wNumb({
                decimals: 2,
                thousand: '',
                prefix: ''
            }),
            range: {
                'min': <?php if ($product_min_price) { echo $product_min_price; } else { echo '0'; } ?>,
                'max': <?php if ($product_max_price) { echo $product_max_price; } else { echo '0'; } ?>
            }
        });
        $('#vr_price_trackbar').Link('lower').to($('#uf_price_from'),null,wNumb({decimals: 2,thousand: '',prefix: '',}));
        $('#vr_price_trackbar').Link('upper').to($('#uf_price_to'),null,wNumb({decimals: 2,thousand: '',prefix: '',}));
        $('#vr_price_trackbar').on('change', function(){
            var linktohref = '<?php echo $current_link; ?>'+'price='+$('#uf_price_from').val()+':'+$('#uf_price_to').val();
            window.location.href = linktohref;
        });
    </script>
</div>