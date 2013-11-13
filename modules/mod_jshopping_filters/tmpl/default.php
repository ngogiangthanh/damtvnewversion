<script type="text/javascript">
function modFilterclearPriceFilter(){
    jQuery("#fprice_from").val("");
    jQuery("#fprice_to").val("");
    document.jshop_filters.submit();
}
</script>
<div class="jshop_filters">
<form action="<?php print $_SERVER['REQUEST_URI'];?>" method="post" name="jshop_filters" onsubmit="return checkForm();">

    <?php if (is_array($filter_manufactures) && count($filter_manufactures)) {?>
    <input type="hidden" name="manufacturers[]" value="0" />
    <span class="box_manufacrurer">
        <?php// print JText::_('MANUFACTURER').":"?>Hãng Sản Xuất<br/>
        <?php foreach($filter_manufactures as $v){ ?>
        <input type="checkbox" name="manufacturers[]" value="<?php print $v->id;?>" <?php if (in_array($v->id, $manufacturers)) print "checked";?> onclick="document.jshop_filters.submit();"> <?php print $v->name;?><br/>
        <?php }?>
    </span>
    <br/>
    <?php }?>
    
    <?php if (is_array($filter_categorys) && count($filter_categorys)) {?>
    <input type="hidden" name="categorys[]" value="0" />
    <span class="box_manufacrurer">
        <?php //print JText::_('CATEGORY').":"?>Loại Sản Phẩm<br/>
        <?php foreach($filter_categorys as $v){ ?>
        <input type="checkbox" name="categorys[]" value="<?php print $v->id;?>" <?php if (in_array($v->id, $categorys)) print "checked";?> onclick="document.jshop_filters.submit();"> <?php print $v->name;?><br/>
        <?php }?>
    </span>
    <br/>
    <?php }?>
    
    <?php if ($show_prices){?>
    <span class="filter_price"><?php //print JText::_('PRICE')?>Giá:&nbsp;<br/>
        <span class="box_price_from"><?php //print JText::_('FROM')?> Từ:&nbsp;
            <select name = "fprice_from" id="fprice_from">
                <option value="0" <?php if ($fprice_from == "0") echo "selected='selected'"?>>---Chọn Giá Nhỏ Nhất---</option>
                <option value="1000000" <?php if ($fprice_from == "1000000") echo "selected='selected'"?>>> 1.000.000</option>
                <option value="2000000" <?php if ($fprice_from == "2000000") echo "selected='selected'"?>>> 2.000.000</option>
                <option value="4000000" <?php if ($fprice_from == "4000000") echo "selected='selected'"?>>> 4.000.000</option>
                <option value="8000000" <?php if ($fprice_from == "8000000") echo "selected='selected'"?>>> 8.000.000</option>
                <option value="16000000" <?php if ($fprice_from == "16000000") echo "selected='selected'"?>>> 16.000.000</option>
            </select>
            
         <span class="box_price_to"><?php //print JText::_('TO')?>&nbsp;Đến&nbsp;
             <select name = "fprice_to" id="fprice_to">
                <option value="0" <?php if ($fprice_to == "0") echo "selected='selected'"?>>---Chọn Giá Lớn Nhất---</option>
                <option value="2000000" <?php if ($fprice_to == "2000000") echo "selected='selected'"?>><= 2.000.000</option>
                <option value="4000000" <?php if ($fprice_to == "4000000") echo "selected='selected'"?>><= 4.000.000</option>
                <option value="8000000" <?php if ($fprice_to == "8000000") echo "selected='selected'"?>><= 8.000.000</option>
                <option value="12000000" <?php if ($fprice_to == "12000000") echo "selected='selected'"?>><= 12.000.000</option>
                <option value="16000000" <?php if ($fprice_to == "16000000") echo "selected='selected'"?>><= 16.000.000</option>
            </select>
        &nbsp;<?php print $jshopConfig->currency_code?>
    </span>    
    <br/>
    <input type="submit" class="btn btn-primary" value="<?php //print JText::_('GO')?>Thực Hiện">    
    <input type="button" class="btn btn-special" onclick="modFilterclearPriceFilter();return false;" value="Bỏ Lọc"/>
    <?php }?>
    
    <?php if (is_array($characteristic_displayfields) && count($characteristic_displayfields)){?>
    <br/>
        <div class="filter_characteristic">
        <?php foreach($characteristic_displayfields as $ch_id){?>   
            <?php if (is_array($characteristic_fieldvalues[$ch_id])){?>
                <div class="characteristic_name"><?php print $characteristic_fields[$ch_id]->name;?></div>
                <input type="hidden" name="extra_fields[<?php print $ch_id?>][]" value="0" />            
                <?php foreach($characteristic_fieldvalues[$ch_id] as $val_id=>$val_name){?>
                    <input type="checkbox" name="extra_fields[<?php print $ch_id?>][]" value="<?php print $val_id;?>" <?php if (is_array($extra_fields_active[$ch_id]) && in_array($val_id, $extra_fields_active[$ch_id])) print "checked";?> onclick="document.jshop_filters.submit();" /> <?php print $val_name;?><br/>
                <?php }?>
            <br/>
            <?php }?>
        <?php }?>
        </div>
    <?php } ?>
</form>
    <script type="text/javascript">
        function checkForm(){
            var giaTruoc = document.getElementById('fprice_from').value;
            var giaSau = document.getElementById('fprice_to').value;
            alert(giaTruoc + " -"+ giaSau);
            if(giaTruoc > giaSau)
                {
                    alert('Vui lòng chọn lại giá lớn nhất muốn lọc!');
                    jshop_filters.fprice_to.focus();
                    return false;
                }
                else{
                    return true;
                }
        }
    </script>
</div>