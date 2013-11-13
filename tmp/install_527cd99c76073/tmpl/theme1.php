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
	font-family:'BebasNeueRegular';
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

<div class="ytc-content-slideshow" style="width: <?php echo $thumb_width?>px; height:<?php echo $thumb_height?>px;z-index:4">
	<div id="featured<?php echo $module->id;?>" style="width: <?php echo $thumb_width?>px; height:<?php echo $thumb_height?>px;">
		<?php foreach($items as $key=>$item) {?>
			<a href="<?php echo ($link_image)?$item['link']:"#";?>" target="<?php echo $target;?>" onclick="<?php if($link_image):?>javascript: return true; <?php else: ?>javascript: return false; <?php endif;?>">
				<img src="<?php echo $item['thumb']?>" caption="<?php echo $item['sub_title']?>" alt="<?php echo $item['title']?>" href="<?php echo $item['link'];?>" linktarget = "<?php echo $target;?>" presrc = "<?php echo $item['pre']?>" nexsrc = "<?php echo $item['nex']?>" key="<?php echo $key;?>" style="width: <?php echo $thumb_width?>px; height:<?php echo $thumb_height?>px;"/>
			</a>		
		<?php } ?> 
	</div> 
	
	<div class="captions" id="caption_<?php echo $module->id;?>" style="bottom:50px;width: <?php echo ($thumb_width)?>px; height:40px; background:<?php echo ($background!='null')?$background:'#000000';?>"><p style="color:<?php echo $title_color;?>; display:<?php echo ($caption_show)?'block':'none'?>"></p></div>
	<div id="cover_buttons_<?php echo $module->id;?>" class="cover-theme1" style="width: <?php echo $thumb_width?>px;">	
		<div style="width:auto; float:right" class="<?php echo $theme;?>">
			<ul id="image_button_<?php echo $module->id;?>">
				<?php foreach($items as $key=>$item) {?>
					<li class="<?php echo ($key==0)?"button_img_selected":"button_img";?>" value="<?php echo $key;?>"></li>
				<?php } ?> 
			</ul>
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

