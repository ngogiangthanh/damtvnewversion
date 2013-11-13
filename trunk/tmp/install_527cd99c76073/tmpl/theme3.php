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

<div class="ytc-content-slideshow" style="width: <?php echo $thumb_width?>px; height:<?php echo $thumb_height?>px;z-index:4; position:relative">

	<div id="featured<?php echo $module->id;?>" style="width: <?php echo $thumb_width?>px; height:<?php echo $thumb_height?>px;position:relative">
		<?php foreach($items as $key=>$item) {?>
			<a href="<?php echo ($link_image)?$item['link']:"#";?>" target="<?php echo $target;?>" onclick="<?php if($link_image):?>javascript: return true; <?php else: ?>javascript: return false; <?php endif;?>">
				<img src="<?php echo $item['thumb']?>" caption="<?php echo $item['sub_title']?>" alt="<?php echo $item['title']?>" href="<?php echo $item['link'];?>" linktarget = "<?php echo $target;?>" presrc = "<?php echo $item['pre']?>" nexsrc = "<?php echo $item['nex']?>" key="<?php echo $key;?>" style="width: <?php echo $thumb_width?>px; height:<?php echo $thumb_height?>px;"/>
			</a>
		<?php } ?> 
	</div>
	
	<div class="captions" id="caption_<?php echo $module->id;?>" style="bottom:50px;width: <?php echo ($thumb_width)?>px; height:40px; background:<?php echo ($background!='null')?$background:'#000000';?>"><p style="color:<?php echo $title_color;?>; display:<?php echo ($caption_show)?'block':'none'?>"></p></div>	
	<div id="cover_buttons_<?php echo $module->id;?>" class="<?php echo $theme;?>" >	
		<div class="buttons">
			<div style="float:left; position:relative; z-index:2; top:1px; display:<?php echo ($prenext_show)?'block':'none'?>">
				<div id="prev<?php echo $module->id;?>" class="preview">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			</div>
			<div style="position:relative; z-index:2; float:right; top:1px; display:<?php echo ($prenext_show)?'block':'none'?>">
				<div id="next<?php echo $module->id;?>" class="next">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			</div>
			<div class="cover_pause">
				<div id="play_pause_<?php echo $module->id;?>" class="pause">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			</div>	
		</div>	
		<div class="number_slides">
			<div id="number_slides_<?php echo $module->id;?>" style="float:left; position:relative;">1</div>
            <div style="float:right;width:58px;">/<?php echo count($items);?></div>
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