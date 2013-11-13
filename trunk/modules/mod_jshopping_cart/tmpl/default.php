<div id = "jshop_module_cart">
<table width = "100%" >
<tr>
    <td  width='30%'>
        <a href = "<?php print SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1)?>"><img src='http://localhost/damtv_2.0/templates/protostar/images/cart.png' title='Giỏ hàng của bạn' alt='Giỏ hàng của bạn' width='50px'/><?php //print JText::_('GO_TO_CART')?></a>
    </td>
    <td width='70%'>
      <span id = "jshop_quantity_products">
          Có:&nbsp;<b><?php print $cart->count_product?></span>&nbsp;</b><i><?php print JText::_('PRODUCTS')?></i>
          <br/>Tổng:&nbsp;<span id="jshop_summ_product" style='font-weight: bold'><?php print formatprice($cart->getSum(0,1))?></span>
    </td>
</tr>
</table>
</div>