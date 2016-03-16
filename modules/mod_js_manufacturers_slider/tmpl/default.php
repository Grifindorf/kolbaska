<?php
/**
 *  @package	mod_js_manufacturers_slider
 *  @copyright	Copyright (c)2013 ELLE / JoomExt.ru
 *  @license	GNU GPLv3 http://www.gnu.org/licenses/gpl.html or later
 *  @version 	1.0 Stable
 */

defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">
    jQuery(document).ready(function() {
         jQuery(function() {
            jQuery("#extcarousel").jCarouselLite({
          <?php if($auto_start){?> auto: 800,<?php }?>
            btnNext: ".next",
            btnPrev: ".prev",
            speed: 400
             });
        });
    });
</script>
<div class="js_mf">
<div class="prev"></div>
<div id="extcarousel">
<ul>
<?php foreach($list as $curr){ ?>
    <?php if ($curr->manufacturer_logo){?>
    <li>
        <a href = "<?php print $curr->link?>">           
                   <img align = "absmiddle" src = "<?php print $jshopConfig->image_manufs_live_path."/".$curr->manufacturer_logo?>" alt = "<?php print $curr->name?>" />
            <?php if ($show_name){?>
            <span class="name" ><?php print $curr->name?></span>
			<?php } ?>          
        </a>
         <span class="iefix"></span>
        </li>
	<?php } ?>
<?php } ?>
</ul>
</div>
<div class="next"></div>
<div class="clr"></div>
<?php echo JText::_('MOD_JS_SLIDER_MODULE'); ?>
</div>