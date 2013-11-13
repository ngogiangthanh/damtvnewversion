<script type="text/javascript">
function check_pm_ideal(){
    var ar_focus = new Array();
    var error = 0;
    unhighlightField('payment_form');
    if (isEmpty($F_("params[pm_ideal][bank_id]"))) {
        ar_focus[ar_focus.length] = "params[pm_ideal][bank_id]";
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

<select name="params[pm_ideal][bank_id]" id = "params[pm_ideal][bank_id]">
    <option value=''><?php print _I_SELECT_BANK?></option>
    <?php foreach($bank_array as $bank_id => $bank_name) { ?>
    <option value="<?php echo $bank_id ?>" <?php if ($params['bank_id']==$bank_id) print "selected"?>><?php echo $bank_name ?></option>
    <?php } ?>
</select>