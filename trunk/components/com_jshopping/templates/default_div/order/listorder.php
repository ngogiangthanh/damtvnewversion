<?php defined('_JEXEC') or die(); ?>
<div class="jshop">
    <h1><?php echo _JSHOP_MY_ORDERS ?></h1>
    
    <?php if (count($this->orders)) {?>
      <?php foreach ($this->orders as $order){?>
		<div class = "jshop_orderlist">
		<div>
            <div class="order_info_N">
              	<span class="strong"><?php echo _JSHOP_ORDER_NUMBER?>:</span> <?php echo $order->order_number ?>
            </div> 
            <div class="order_info_status">
              	<span class="strong"><?php echo _JSHOP_ORDER_STATUS?>:</span> <?php echo $order->status_name ?>
			</div> 
		</div>
		<div class="clear"> </div>
		<div>
			<div class="table_order_list">
				<div class="order_info_products">
					<div>
						<span class="jshop_name"><?php echo _JSHOP_ORDER_DATE?>:</span> 
						<span><?php echo formatdate($order->order_date, 0)?></span>
					</div>
					<div>
						<span class="jshop_name"><?php echo _JSHOP_PRODUCTS?>:</span>
						<span><?php echo $order->count_products ?></span>
					</div>

				</div>
				<div class="order_info">
					<div>
						<span class="jshop_name"><?php echo _JSHOP_EMAIL_BILL_TO?>:</span> 
						<span><?php echo $order->f_name ?> <?php echo $order->l_name ?></span>
					</div>
					<div>
						<span class="jshop_name"><?php echo _JSHOP_EMAIL_SHIP_TO?>:</span>
						<span><?php echo $order->d_f_name ?> <?php echo $order->d_l_name ?></span>
					</div>
				</div>
				<div class="clear"> </div>
				
				<div class="botom">
					<div class="priceord"><?php echo formatprice($order->order_total, $order->currency_code)?></div><?php echo $order->_ext_price_html?>
					<a class="order_href_details" href = "<?php echo $order->order_href ?>"><?php echo _JSHOP_DETAILS?></a> 			
				</div>
			</div>
		</div>
		</div>
		<div class="clear"> </div>
      <?php } ?>
    <?php }else{ ?>
      <div class="order_info_noorders"><?php echo _JSHOP_NO_ORDERS?> </div>
    <?php } ?>
</div>