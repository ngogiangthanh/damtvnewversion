<div class="col100">
<fieldset class="adminform">
<table class="admintable" width = "100%" >
 <tr>
   <td  class="key">
     <?php echo _MB_EMAIL; ?>
   </td>
   <td>
     <input type = "text" class = "inputbox" name = "pm_params[email_received]" size="45" value = "<?php echo $params['email_received']?>" />
   </td>
 </tr>
 
 <tr>
   <td  class="key">
     <?php echo _MB_MERCHANT_ID; ?>
   </td>
   <td>
     <input type = "text" class = "inputbox" name = "pm_params[merchant_id]" size="45" value = "<?php echo $params['merchant_id']?>" />
   </td>
 </tr>
 
 <tr>
   <td  class="key">
     <?php echo _MB_SECRETWORD; ?>
   </td>
   <td>
     <input type = "text" class = "inputbox" name = "pm_params[secretword]" size="45" value = "<?php echo $params['secretword']?>" />
   </td>
 </tr>
 
 <tr>
   <td class="key">
     <?php echo _MB_TRANSACTION_END;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_end_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_end_status'] );     
     ?>
   </td>
 </tr>
 <tr>
   <td class="key">
     <?php echo _MB_TRANSACTION_PENDING;?>
   </td>
   <td>
     <?php 
     echo JHTML::_('select.genericlist',$orders->getAllOrderStatus(), 'pm_params[transaction_pending_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_pending_status']);
     ?>
   </td>
 </tr>
 <tr>
   <td class="key">
     <?php echo _MB_TRANSACTION_FAILED;?>
   </td>
   <td>
     <?php 
     echo JHTML::_('select.genericlist',$orders->getAllOrderStatus(), 'pm_params[transaction_failed_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_failed_status']);
     ?>
   </td>
 </tr>
</table>
</fieldset>
</div>
<div class="clr"></div>