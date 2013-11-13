<?php defined('_JEXEC') or die(); ?>
<table class = "jshop" id = "jshop_menu_order">
  <tr>
    <?php 
    $buoc = 1;
    foreach($this->steps as $step){?>
      <td class = "jshop_order_step">
        <?php print "Bước ".$buoc++.": ".$step;?>
      </td>
    <?php }?>
  </tr>
</table>