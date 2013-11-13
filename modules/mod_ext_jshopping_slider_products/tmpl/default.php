<?php
/*
# ------------------------------------------------------------------------
# Extensions for Joomla 2.5.x - Joomla 3.x
# ------------------------------------------------------------------------
# Copyright (C) 2011-2013 Ext-Joom.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Ext-Joom.com
# Websites:  http://www.ext-joom.com 
# Date modified: 16/09/2013 - 13:00
# ------------------------------------------------------------------------
*/

// No direct access.
defined('_JEXEC') or die;
if ($ext_mode == 'vertical') {
	$ext_vertical='_v';
} else {
		$ext_vertical='';
	}
	
$document->addCustomTag('
<style type="text/css">
#ext_jshopping_slider'.$id_sfx.' ul li {
	background: none; 
	width:'.$ext_width.'px; 
	height: '.$ext_height.'px; 
}
#ext_jshopping_slider'.$id_sfx.' .ext_prev a,
#ext_jshopping_slider'.$id_sfx.' .ext_next a {
	height: '.$ext_height.'px;
}
</style>
');		
?>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#ext_slider_products<?php if ($id_sfx !='') echo $id_sfx; ?>').bxSlider({
	prevSelector:'#ext_prev<?php if ($id_sfx !='') echo $id_sfx; ?><?php echo $ext_vertical;?>',
	nextSelector:'#ext_next<?php if ($id_sfx !='') echo $id_sfx; ?><?php echo $ext_vertical;?>',
	mode: '<?php echo $ext_mode;?>', 
	speed: <?php echo $ext_speed;?>,
	controls: <?php echo $ext_controls; ?>,
	auto: <?php echo $ext_auto;?>,
	pause: <?php echo $ext_pause?>,
	autoDelay: <?php echo $ext_auto_delay; ?>,
	autoHover: <?php echo $ext_autohover; ?>,
	pager: <?php echo $ext_pager;?>,
	pagerType: '<?php echo $ext_pager_type;?>',
	pagerLocation: '<?php echo $ext_pager_location;?>',
	pagerShortSeparator: '<?php echo $ext_pager_saparator;?>',
	displaySlideQty: <?php echo $ext_display_slide_qty;?>,
	moveSlideQty: <?php echo $ext_move_slide_qty;?>		
	});
});
</script>
<link rel="stylesheet" type="text/css" href="templates/protostar/css/style.css" />
<script type="text/javascript" language="javascript" src="templates/protostar/js/script.js"></script>	

<div class="mod_ext_jshopping_slider_products <?php echo $moduleclass_sfx;?>">
	<div id="ext_jshopping_slider<?php if ($id_sfx !='') echo $id_sfx; ?>" class="ext_jshopping_slider">
		<div id="ext_prev<?php if ($id_sfx !='') echo $id_sfx; ?><?php echo $ext_vertical;?>" class="ext_prev<?php echo $ext_vertical;?>"></div>
			<ul id="ext_slider_products<?php if ($id_sfx !='') echo $id_sfx; ?>">
			<?php foreach($last_prod as $curr){ ?>
				<li>
				   <div class="block_item" style="border: 1px dashed #aaa;height: 108px">
<!--						<div class="item_name">
						   <h3><a href="<?php //print $curr->product_link?>"><?php //print $curr->name?></a></h3>
						</div>-->
			
					   <?php if ($show_image) { ?>
                                    <span onmouseover="tooltip.show('<table  style=\'font-size: 14px;\' ><tr><td rowspan=\'3\'><img src=\'<?php print $jshopConfig->image_product_live_path?>/<?php if ($curr->product_thumb_image) print $curr->product_thumb_image; else print $noimage?>\'/></td></tr><tr><td>&nbsp;&nbsp;Sản Phẩm:&nbsp;<?=$curr->name?>&nbsp;&nbsp;</td></tr><tr><td>&nbsp;&nbsp;Giá:&nbsp;<?=formatprice($curr->product_price);?>&nbsp;&nbsp;</td></tr></table>');" onmouseout="tooltip.hide();">
         					<div class="item_image">
							<?php if ($curr->label_id AND $ext_label_prod > 0){?>
								<div class="product_label">
									<?php if ($curr->_label_image){?>
										<img src="<?php print $curr->_label_image?>" alt="<?php print htmlspecialchars($curr->_label_name)?>" />
									<?php }else{?>
										<span class="label_name"><?php print $curr->_label_name;?></span>
									<?php }?>
								</div>
							<?php }?>
								<a href="<?php print $curr->product_link?>"><img src = "<?php print $jshopConfig->image_product_live_path?>/<?php if ($curr->product_thumb_image) print $curr->product_thumb_image; else print $noimage?>" alt="" /></a>
						</div>
                                    </span>
					   <?php } ?>
					   
					   <?php if ($curr->_display_price){?>
						<div class="item_price">
						   <?php //print formatprice($curr->product_price);?>
						</div>
						<?php }?>
					   
						<?php if ($ext_short_desc > 0 AND $curr->short_description) { ?>
						<div class="short_description">
							<?php print $curr->short_description?>
						</div>
						<?php } ?>
						
						<?php if ($ext_review_mark > 0) { ?>
						<div class="review_mark">
							<?php print showMarkStar($curr->average_rating);?>
						</div>
						<?php } ?>
						
						<?php if ($ext_count_commentar > 0) { ?>
						<div class="count_commentar">
							<?php print sprintf(_JSHOP_X_COMENTAR, $curr->reviews_count);?>
						</div>				
						<?php } ?>
						<?php if ($ext_item_detal > 0) { ?>
						<div class="item_detal">
						   <a href="<?php print $curr->product_link?>"><?php echo $ext_buttom_text; ?></a>
						</div>
						<?php } ?>
				   </div>
				</li>	
			<?php } ?>
			</ul>
		<div id="ext_next<?php if ($id_sfx !='') echo $id_sfx; ?><?php echo $ext_vertical;?>" class="ext_next<?php echo $ext_vertical;?>"></div>
	</div>
	<div style="clear:both">
            <br/>
        </div>
</div>