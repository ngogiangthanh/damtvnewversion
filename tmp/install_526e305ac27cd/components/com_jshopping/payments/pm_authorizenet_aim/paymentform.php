<table class="pm_authorizenet_aim">
<tr>
     <td width = "200">
       <?php echo _Card_Number;?>
     </td>
     <td>
        <input type = "text" class = "inputbox" name = "params[pm_authorizenet_aim][card_number]" id = "params[pm_authorizenet_aim][card_number]" value="<?php print $params['card_number']?>"/>
     </td>
</tr>
<tr>
    <td width = "200">
       <?php echo _Expiration_Date;?>
     </td>
     <td>       
       <select name="params[pm_authorizenet_aim][month]" id="params[pm_authorizenet_aim][month]">
       <option value=""><?php print _month;?></option>
       <?php for($i=1;$i<=12;$i++){
           if ($i<10) $i = "0".$i;
       ?>       
       <option value="<?php print $i;?>" <?php if ($i==$params['month']) print 'selected';?>><?php print $i;?></option>
       <?php }?>
       </select>
       
       <select name="params[pm_authorizenet_aim][year]" id="params[pm_authorizenet_aim][year]">
       <option value=""><?php print _year;?></option>
       <?php for($i=date('y');$i<=date('y')+20;$i++){?>       
       <option value="<?php print $i;?>" <?php if ($i==$params['year']) print 'selected';?>><?php print $i;?></option>
       <?php }?>
       </select>
     </td>
</tr>
</table>

<script type = "text/javascript">
  function check_pm_authorizenet_aim(){
    var ar_focus = new Array();
    var error = 0;
    unhighlightField('payment_form');
    if (isEmpty($F_("params[pm_authorizenet_aim][card_number]"))) {
        ar_focus[ar_focus.length] = "params[pm_authorizenet_aim][card_number]";
        error = 1;
    }
    if (isEmpty($F_("params[pm_authorizenet_aim][month]"))) {
        ar_focus[ar_focus.length] = "params[pm_authorizenet_aim][month]";
        error = 1;
    }
    if (isEmpty($F_("params[pm_authorizenet_aim][year]"))) {
        ar_focus[ar_focus.length] = "params[pm_authorizenet_aim][year]";
        error = 1;
    }
    
    if (error){
        $_(ar_focus[0]).focus();
        for (var i = 0; i<ar_focus.length; i++ ){
           highlightField(ar_focus[i]);
        }
        return false;
    }
    $_('payment_form').submit();
  }
</script>