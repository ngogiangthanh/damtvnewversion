<?php defined('_JEXEC') or die(); ?>
<?php
@$lang =& JFactory::getLanguage();
include(dirname(__FILE__) . "/lang/".$lang->getTag().".php"); ?>
<div class="col100">
	<fieldset class="adminform">
		<table class="admintable" width="100%">
			<tr>
				<td style="width:250px;" class="key">
					<?php echo _JSHOP_TESTMODE;?>
				</td>
				<td>
					<?php
					print JHTML::_('select.booleanlist', 'pm_params[testmode]', 'class = "inputbox" size = "1"', $params['testmode']);
					echo " " . JHTML::tooltip(_JSHOP_BAOKIM_TESTMODE_DESCRIPTION);
					?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_BAOKIM_EMAIL; ?>
				</td>
				<td>
					<input type="text" class="inputbox" name="pm_params[email_received]" size="45"
					       value="<?php echo $params['email_received'] ?>"/>
					<?php echo JHTML::tooltip(_JSHOP_BAOKIM_EMAIL_DESCRIPTION); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_BAOKIM_MERCHANT_ID; ?>
				</td>
				<td>
					<input type="text" class="inputbox" name="pm_params[merchant_id]" size="45"
					       value="<?php echo $params['merchant_id'] ?>"/>
					<?php echo JHTML::tooltip(_JSHOP_BAOKIM_MERCHANT_ID_DESCRIPTION); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_BAOKIM_SECURITY_CODE; ?>
				</td>
				<td>
					<input type="text" class="inputbox" name="pm_params[security_code]" size="45"
					       value="<?php echo $params['security_code'] ?>"/>
					<?php echo JHTML::tooltip(_JSHOP_BAOKIM_SECURITY_CODE_DESCRIPTION); ?>
				</td>
			</tr>
			<tr style="height: 17px"></tr>
			<tr>
				<td class="key">
				</td>
				<td>
					<strong>
						<?php echo _JSHOP_BAOKIM_BPN_DESCRIPTION; ?>
					</strong>
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_BAOKIM_BPN_FILE; ?>
				</td>
				<td>
					<input type="text" class="inputbox" name="pm_params[bpn_file]" size="45"
					       value="<?php echo $params['bpn_file'] ?>"/>
					<?php echo JHTML::tooltip(_JSHOP_BAOKIM_BPN_FILE_DESCRIPTION); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_TRANSACTION_END; ?>
				</td>
				<td>
					<?php
				@	print JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_end_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_end_status']);
					echo " " . JHTML::tooltip(_JSHOP_BAOKIM_TRANSACTION_END_DESCRIPTION);
					?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_TRANSACTION_PENDING; ?>
				</td>
				<td>
					<?php
					echo JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_pending_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_pending_status']);
					echo " " . JHTML::tooltip(_JSHOP_BAOKIM_TRANSACTION_PENDING_DESCRIPTION);
					?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_TRANSACTION_FAILED; ?>
				</td>
				<td>
					<?php
					echo JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_failed_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_failed_status']);
					echo " " . JHTML::tooltip(_JSHOP_BAOKIM_TRANSACTION_FAILED_DESCRIPTION);
					?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>