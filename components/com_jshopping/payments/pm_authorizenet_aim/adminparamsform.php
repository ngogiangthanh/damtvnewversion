<div class="col100">
<fieldset class="adminform">
<table class="admintable" width = "100%" >
 <tr>
   <td style="width:250px;" class="key">
     <?php echo _Test_Server;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.booleanlist', 'pm_params[testserver]', 'class = "inputbox" size = "1"', $params['testserver']);
     ?>
   </td>
 </tr>
 <tr>
   <td class="key">
     <?php echo _JSHOP_TESTMODE;?>
   </td>
   <td>
     <?php              
     print JHTML::_('select.booleanlist', 'pm_params[testmode]', 'class = "inputbox" size = "1"', $params['testmode']);     
     ?>
   </td>
 </tr> 
 <tr>
   <td  class="key">
     <?php echo _LOGIN_ID; ?>
   </td>
   <td>
     <input type = "text" class = "inputbox" name = "pm_params[login]" value = "<?php echo $params['login']?>" />
   </td>
 </tr>
 <tr>
   <td  class="key">
     <?php echo _Transaction_Key; ?>
   </td>
   <td>
     <input type = "text" class = "inputbox" name = "pm_params[tran_key]" value = "<?php echo $params['tran_key']?>" />
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
 </tr>
</table>
</fieldset>
</div>
<div class="clr"></div>