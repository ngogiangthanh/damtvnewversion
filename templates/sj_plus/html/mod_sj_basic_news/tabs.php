<?php
/**
* @package Sj Basic News
* @version 3.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @copyright (c) 2012 YouTech Company. All Rights Reserved.
* @author YouTech Company http://www.smartaddons.com
*
*/
defined('_JEXEC') or die;?>

<?php 
	$options=$params->toObject();
	$image_config = array(
    	'output_width'  => $params->get('item_image_width'),
    	'output_height' => $params->get('item_image_height'),
    	'function'		=> $params->get('item_image_function'),
    	'background'	=> $params->get('item_image_background')
    );
?>
<?php	
	if (!empty($list)) { ?>
	<?php if ( !empty($options->pretext)){ ?>
         <div class="bsn-pretext"><?php echo $options->pretext; ?></div>
    <?php } ?>
	<div class="bsn-wrap theme1">
		<?php $count = 0; foreach ($list as $items) {
		 /*var_dump($this->themes);die; */	
		 $count++; 
		 if($count == count($list)){
			 $iditem = ' last-item';
		 }else if($count == 1){
			 $iditem = ' first-item';
		 }else{
			 $iditem = '';
		 }
		 ?>
		<div class="item post<?php if($params->get('showline')){ echo ' showlinebottom'.$iditem; } ?>">
			   
			<?php if ($options->item_image_display==1 ){?>
				<div class="bsn-image">
						<a style="width:<?php echo $params->get('item_image_width'); ?>px; height:<?php echo $params->get('item_image_height'); ?>px;" class="alignleft" title="<?php echo $items->title?>" target="<?php echo $options->item_link_target; ?>" href="<?php echo $items->link?>">
							<img src="<?php echo Ytools::resize($items->image, $image_config);?>" title="<?php echo $items->title ;?>" alt="<?php echo $items->title ;?>"/>
						</a>
				</div>
			<?php } ?>
				
			<h2 class="basicnews-title">
				<a title="<?php echo $items->title?>" target="<?php echo $options->item_link_target; ?>" href="<?php echo $items->link?>"><?php echo YTools::truncate($items->title,$options->item_title_max_characs);?></a>
			</h2>
				
			<?php if ($options->item_desc_display == 1 ){?>
				<p class="basicnews-desc"><?php echo Ytools::truncate($items->description,$options->item_desc_max_characs);?></p>
			<?php } ?>
				   
			<?php if( $options->item_date_display==1 || $options->cat_title_display==1 ){?>
				<div class="row-fluid clear basicnews-info">
					<?php if ($options->item_date_display == 1):?>
							<div class="pull-left basic-date"><?php echo JText::sprintf( JHTML::_('date',$items->created, 'l , M d Y')); ?></div>
					<?php endif; ?>
					
					<?php if($options->cat_title_display==1) {?>
							<div class="pull-right cattitle"><i class="icon-eye-open" rel="tooltip" title="Hits"></i><?php echo $items->hits; ?> </div>
					 <?php }  ?>
					
				</div>
			<?php } ?>
			<!--
			<a class="more" title="<?php echo $items->title?>" target="<?php echo $options->item_link_target; ?>" href="<?php echo $items->link?>">
				<?php //echo JText::_('READ_MORE'); ?>
			</a>
			-->
			
		 </div>  
		<?php }  ?>
	  
	</div>
	<?php if ( !empty($options->posttext)){ ?>
         <div class="bsn-posttext"><?php echo $options->posttext; ?></div>
    <?php } ?>
<?php  } else { ?>
<p>Has no connect to show!</p>
<?php } ?>


