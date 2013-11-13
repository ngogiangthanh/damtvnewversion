<link rel="stylesheet" type="text/css" href="templates/protostar/css/style.css" />
<script type="text/javascript" language="javascript" src="templates/protostar/js/script.js"></script>
<div class="latest_products">
<?php foreach($last_prod as $curr){ ?>
   <div class="block_item">
       <?php if ($show_image) { ?>
       <div class="item_image">
           <a href="<?php print $curr->product_link?>"><img src = "<?php print $jshopConfig->image_product_live_path?>/<?php if ($curr->product_thumb_image) print $curr->product_thumb_image; else print $noimage?>" alt="" /></a>
       </div>
       <?php } ?><span onmouseover="tooltip.show('<table  style=\'font-size: 14px;\' ><tr><td rowspan=\'3\'><img src=\'<?php print $jshopConfig->image_product_live_path?>/<?php if ($curr->product_thumb_image) print $curr->product_thumb_image; else print $noimage?>\'/></td></tr><tr><td>&nbsp;&nbsp;Sản Phẩm:&nbsp;<?=$curr->name?>&nbsp;&nbsp;</td></tr><tr><td>&nbsp;&nbsp;Giá:&nbsp;<?=formatprice($curr->product_price);?>&nbsp;&nbsp;</td></tr></table>');" onmouseout="tooltip.hide();">
           
       <div class="item_name">  
           <a href="<?php print $curr->product_link?>"><?php print $curr->name?></a>
       </div>
       <?php if ($curr->_display_price){?>
       <div class="item_price" style="color: red;font-weight: bold">
           Giá:&nbsp;<?php print formatprice($curr->product_price);?>
       </div>
           </span>
       <?php }?>
   </div>       
<?php } ?>
</div>