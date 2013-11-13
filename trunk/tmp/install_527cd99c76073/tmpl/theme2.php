<?php
/*------------------------------------------------------------------------
 # Ytc Content Slideshow  - Version 1.2
 # Copyright (C) 2009-2010 The YouTech Company. All Rights Reserved.
 # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Author: The YouTech Company
 # Websites: http://www.ytcvn.com
 -------------------------------------------------------------------------*/
?>

<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if(count($items)>0):?>
<style>
#caption_<?php echo $module->id;?> p a {
	color:<?php echo $title_color;?>!important;
	text-decoration:none;
}
</style>
<script type="text/javascript">
$jYtc(document).ready(function($) {
	$jYtc('#featured<?php echo $module->id;?>').slidecycle({
		fx:     '<?php echo $effect;?>',
		timeout: <?php echo $timer_speed;?>,
		speed:  <?php echo $slideshow_speed;?>, 
		next:   '#next<?php echo $module->id;?>', 
		prev:   '#prev<?php echo $module->id;?>',
		pause: <?php echo ($play)?1:0;?>,
		divId: <?php echo $module->id;?>,
		readmoreImg:'<?php echo $readmore_img;?>',
		theme:'<?php echo $theme;?>',
        startingSlide:<?php echo $start?>,
		linktarget:'<?php echo $target;?>',
		linkcaption:<?php echo $link_caption;?>,
		autoPlay:<?php echo $auto_play;?>
	});
});
</script>

<div class="ytc-content-slideshow" style="width: <?php echo $thumb_width?>px; height:<?php echo $thumb_height?>px; z-index:4; position:relative">

	<div id="featured<?php echo $module->id;?>" style="width: <?php echo $thumb_width?>px; height:<?php echo $thumb_height?>px;position:relative">
		<?php foreach($items as $key=>$item) {?>
			<a href="<?php echo ($link_image)?$item['link']:"#";?>" target="<?php echo $target;?>" onclick="<?php if($link_image):?>javascript: return true; <?php else: ?>javascript: return false; <?php endif;?>">
				<img src="<?php echo $item['thumb']?>" caption="<?php echo $item['sub_title']?>" alt="<?php echo $item['title']?>" href="<?php echo $item['link'];?>" linktarget = "<?php echo $target;?>" presrc = "<?php echo $item['pre']?>" nexsrc = "<?php echo $item['nex']?>" key="<?php echo $key;?>" style="width: <?php echo $thumb_width?>px; height:<?php echo $thumb_height?>px;"/>
			</a>
		<?php } ?> 
	</div>
	
	<div id="content_box_<?php echo $module->id;?>" class="content-box" style="left:<?php echo (($thumb_width/2)>350)?($thumb_width-430):($thumb_width>350)?($thumb_width-380):0;?>px; position:absolute">	
		<div class="right">
			<div class="center">
				<div id="caption_<?php echo $module->id;?>" style="color:<?php echo $title_color;?>;padding:5px 0px 0px 5px; font-size:16px; font-weight:bold"><p style="text-align:left; color:<?php echo $title_color;?> !important; display:<?php echo ($caption_show)?'block':'none'?>"></p></div>
				<div id="current_content_<?php echo $module->id;?>" style="height:65px; overflow:hidden; text-align:left; color:<?php echo $description_color;?>; display:<?php echo ($show_description)?'block':'none'?>;padding:0px 0px 5px 5px"></div>
				<div id="read_more_content_<?php echo $module->id;?>" class="readmore" style="text-align:right; position:relative;display:<?php echo ($show_readmore)?'block':'none'?>"></div>
			</div>
		</div>
	</div>
	
	<div id="cover_buttons_<?php echo $module->id;?>" style="float:right; width: <?php echo (count($items)*27+60);?>px; position:relative">
		<div class="<?php echo $theme;?>">	
			<div class="right" style="width:<?php echo $width_buttom_middle?>px;">
				<div class="center">			
						<div style="float:left; position:relative; z-index:2; display:<?php echo ($prenext_show)?'block':'none'?>">
							<div id="prev<?php echo $module->id;?>" class="preview">&nbsp;&nbsp;&nbsp;&nbsp;</div>
						</div> 
						<div style="position:relative; z-index:2; float:right; display:<?php echo ($prenext_show)?'block':'none'?>">
						<div id="next<?php echo $module->id;?>" class="next">&nbsp;&nbsp;&nbsp;&nbsp;</div>
						</div>
						<div style="position:relative; z-index:1;" class="buttons_<?php echo $theme;?>">					
							<ul id="image_button_<?php echo $module->id;?>">
								<?php foreach($items as $key=>$item) {?>
									<li class="<?php echo ($key==0)?"button_img_selected":"button_img";?>" value="<?php echo $key;?>"><p><?php echo ($key+1);?></p></li>
								<?php } ?> 
							</ul>
						</div>						
				</div>
			</div>		
		</div>	
	</div>
				
</div>

<div style="display:none">
<?php foreach($items as $key=>$item) {?>
	<div id="arrContent_<?php echo $module->id;?>_<?php echo $key;?>"><?php echo $item['sub_content']?></div>
<?php } ?> 
</div>
<?php else: echo JText::_('Has no content to show!');?>
<?php endif;?>
<?php if($note): ?>
<br/>
<div style="text-align:left">
	<p><?php  echo $note;?></p>
</div>
<?php endif;?>