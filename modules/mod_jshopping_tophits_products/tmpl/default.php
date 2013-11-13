<link rel="stylesheet" type="text/css" href="templates/protostar/css/style.css" />
<script type="text/javascript" language="javascript" src="templates/protostar/js/script.js"></script>
<table width="100%" class="tophits_products" >
    <div class="block_item">
<?php foreach($last_prod as $curr){ ?>
        <?php if ($show_image) { ?>
        <td class="item_image" align="center" valign='bottom' style="border: 1px dashed #aaa">
            <a href="<?php print $curr->product_link?>">               
                <span onmouseover="tooltip.show('<table  style=\'font-size: 14px;\' ><tr><td><img src=\'<?php print $jshopConfig->image_product_live_path?>/<?php if ($curr->product_thumb_image) print $curr->product_thumb_image; else print $noimage?>\'/></td><td>&nbsp;&nbsp;Sản Phẩm:&nbsp;<?php print $curr->name?>&nbsp;&nbsp;</td></tr></table>');" onmouseout="tooltip.hide();">
                <img src = "<?php print $jshopConfig->image_product_live_path?>/<?php if ($curr->product_thumb_image) print $curr->product_thumb_image; else print $noimage?>" alt="" />
                </span>
            </a>
        <?php } ?>
            <br/>
            <a href="<?php print $curr->product_link?>"><h4><?php print $curr->name?></h4></a>
        </td>
<?php } ?>
    </tr>       
</table>