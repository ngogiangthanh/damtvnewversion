<link rel="stylesheet" type="text/css" href="templates/protostar/css/style.css" />
<script type="text/javascript" language="javascript" src="templates/protostar/js/script.js"></script>
<div class="top_rating" style="text-align:center">
<?php foreach($list as $curr){ ?>
    <div class="block_item">
        <?php if ($show_image) { ?>
        <div class="item_image">
            <a href="<?php print $curr->product_link?>">               
                <span onmouseover="tooltip.show('<table  style=\'font-size: 14px;\' ><tr><td><img src=\'<?php print $jshopConfig->image_product_live_path?>/<?php if ($curr->product_thumb_image) print $curr->product_thumb_image; else print $noimage?>\'/></td><td>&nbsp;&nbsp;Sản Phẩm:&nbsp;<?php print $curr->name?>&nbsp;&nbsp;</td></tr></table>');" onmouseout="tooltip.hide();">
                <img src = "<?php print $jshopConfig->image_product_live_path?>/<?php if ($curr->product_thumb_image) print $curr->product_thumb_image; else print $noimage?>" alt="Sản phẩm" />
                </span>
            </a>
        </div>
        <?php } ?>
        <div class="item_name">
            <a href="<?php print $curr->product_link?>"><h4><?php print $curr->name?></h4></a>
        </div>
    </div>       
<?php } ?>
</div>