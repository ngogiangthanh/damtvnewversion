<div class="col100">
<fieldset class="adminform">
<table class="admintable" width = "100%" >
<tr>
   <td style="width:250px;" class="key">
     <?php echo _I_TESTMODE;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.booleanlist', 'pm_params[testmode]', 'class = "inputbox" size = "1"', $params['testmode']);     
     ?>
   </td>
 </tr>
 <tr>
   <td  class="key">
     <?php echo _I_PARTNERID; ?>
   </td>
   <td>
     <input type = "text" class = "inputbox" name = "pm_params[partnerid]" size="45" value = "<?php echo $params['partnerid']?>" />
   </td>
 </tr>
 <tr>
   <td class="key">
     <?php echo _I_TRANSACTION_END;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_end_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_end_status'] );     
     ?>
   </td>
 </tr>
 <tr>
   <td class="key">
     <?php echo _I_TRANSACTION_PENDING;?>
   </td>
   <td>
     <?php 
     echo JHTML::_('select.genericlist',$orders->getAllOrderStatus(), 'pm_params[transaction_pending_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_pending_status']);
     ?>
   </td>
 </tr>
 <tr>
   <td class="key">
     <?php echo _I_TRANSACTION_FAILED;?>
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