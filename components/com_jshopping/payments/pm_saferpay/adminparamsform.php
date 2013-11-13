<div class="col100">
	<fieldset class="adminform">
		<table class="admintable" width = "100%" >
			<tr>
				<td class="key"> 
					<?php echo _Account_ID?>:
				</td>
				<td>
					<input type = "text" class = "inputbox" name = "pm_params[accountid]" value = "<?php echo $params['accountid']?>" style="width:200px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> 
					<?php echo _spPassword?>:
				</td>
				<td>
					<input type = "text" class = "inputbox" name = "pm_params[sppassword]" value = "<?php echo $params['sppassword']?>" style="width:200px;" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_TRANSACTION_END;?>
				</td>
				<td>
				<?php              
					print JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_end_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_end_status'] );
				?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_TRANSACTION_PENDING;?>
				</td>
				<td>
				<?php 
					echo JHTML::_('select.genericlist',$orders->getAllOrderStatus(), 'pm_params[transaction_pending_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_pending_status']);
				?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_TRANSACTION_FAILED;?>
				</td>
				<td>
				<?php 
					echo JHTML::_('select.genericlist',$orders->getAllOrderStatus(), 'pm_params[transaction_failed_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_failed_status']);
				?>
				</td>
			</tr>
			<tr>
				<td class="key">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			
			<tr>
				<td class="key">
					<?php echo _SUCCESS_LINK?>:
				</td>
				<td>
				<?php 
					print JURI::root(). "index.php?option=com_jshopping&controller=checkout&task=step7&act=return&js_paymentclass=pm_saferpay";
				?>
				</td>
			</tr>
			
			<tr>
				<td class="key">
					<?php echo _FAIL_LINK?>:
				</td>
				<td>
				<?php 
					print JURI::root(). "index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=pm_saferpay";
				?>
				</td>
			</tr>
			
			<tr>
				<td class="key">
					<?php echo _BACK_LINK?>:
				</td>
				<td>
				<?php 
					print JURI::root(). "index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=pm_saferpay";
				?>
				</td>
			</tr>
			
			<tr>
				<td class="key">
					<?php echo _NOTIFY_URL?>:
				</td>
				<td>
				<?php 
					print JURI::root(). "index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=pm_saferpay&no_lang=1";
				?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>