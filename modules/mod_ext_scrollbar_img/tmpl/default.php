<?php
/*
# ------------------------------------------------------------------------
# Extensions for Joomla 2.5.x - Joomla 3.x
# ------------------------------------------------------------------------
# Copyright (C) 2011-2014 Ext-Joom.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Ext-Joom.com
# Websites:  http://www.ext-joom.com 
# Date modified: 02/04/2014 - 13:00
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die;
?>
<style type="text/css">
#ext_scrollbar_<?php echo $ext_id;?> {height: <?php echo $height;?>px;}
#ext_scrollbar_<?php echo $ext_id;?> ul {	width: <?php echo $sum_width;?>px;}
#ext_scrollbar_<?php echo $ext_id;?> li {	width: <?php echo $width;?>px;}
#ext_bar_<?php echo $ext_id;?> {
	background-color: <?php echo $color_bar; ?>;
	height: <?php echo $height_bar;?>px;
	}
#ext_knob_<?php echo $ext_id;?> {
	background-color: <?php echo $color_knob; ?>;
	width: <?php echo $width_knob;?>px;
	height: <?php echo $height_knob;?>px;
	}
</style>

<script type="text/javascript">
window.addEvent('domready', function(){
	var myProducts = new ScrollBar('ext_scrollbar_<?php echo $ext_id;?>', 'ext_bar_<?php echo $ext_id;?>', 'ext_knob_<?php echo $ext_id;?>', {
		offset: -1,
		scroll: {
			duration: <?php echo $duration_scroll; ?>,
			transition: 'elastic:out'
		},
		ext_knob: {
			duration: <?php echo $duration_knob; ?>,
			transition: 'elastic:out'
		}
	});

		
});
</script>

<div class="mod_ext_scrollbar_img <?php echo $moduleclass_sfx; ?>">
	<div id="ext_scrollbar_<?php echo $ext_id;?>" class="ext_scrollbar">
		<ul>	
		<?php	
		for($n=0;$n < count($img);$n++) {			
			 if( $img[$n] != '') {
				echo '<li><a href="'.$url[$n].'" target="'.$target[$n].'"><img src="'.$img[$n].'" width="'.$width.'px" height="'.$height.'px"  alt="'.$alt[$n].'"/></a></li>';
			}
		}	
		?>	
		</ul>
	</div>
    <div id="ext_bar_<?php echo $ext_id;?>" class="ext_bar"><div id="ext_knob_<?php echo $ext_id;?>" class="ext_knob"></div></div>
	<div style="clear:both;"></div>
</div>