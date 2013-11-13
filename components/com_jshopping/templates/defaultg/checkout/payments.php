<?php defined('_JEXEC') or die(); ?>
<?php print $this->checkout_navigator ?>
<?php print $this->small_cart ?>

<script type="text/javascript">
    var payment_type_check = {};
<?php foreach ($this->payment_methods as $payment) { ?>
        payment_type_check['<?php print $payment->payment_class; ?>'] = '<?php print $payment->existentcheckform; ?>';
<?php } ?>
</script>

<div class="jshop">
    <form id = "payment_form" name = "payment_form" action = "<?php print $this->action ?>" method = "post">
        <?php print $this->_tmp_ext_html_payment_start ?>
        <table id = "table_payments" cellspacing="0" cellpadding="0">
            <?php
            $payment_class = "";
            foreach ($this->payment_methods as $payment) {
                if ($this->active_payment == $payment->payment_id)
                    $payment_class = $payment->payment_class;
                ?>
                <tr>
                    <td style = "padding-top:5px; padding-bottom:5px">
                        <label for = "payment_method_<?php print $payment->payment_id ?>"><input type = "radio" name = "payment_method" id = "payment_method_<?php print $payment->payment_id ?>" onclick = "showPaymentForm('<?php print $payment->payment_class ?>')" value = "<?php print $payment->payment_class ?>" <?php if ($this->active_payment == $payment->payment_id) { ?>checked<?php } ?> />
                        <?php
                            if ($payment->image) {
                                ?><span class="payment_image"><img src="<?php print $payment->image ?>" alt="<?php print htmlspecialchars($payment->name) ?>" /></span><?php
                                }
                                ?><b><?php print $payment->name; ?></b> 
                            <?php if ($payment->price_add_text != '') { ?>
                                (<?php print $payment->price_add_text ?>)
    <?php } ?>
                        </label>
                    </td>
                </tr>
                <tr id = "tr_payment_<?php print $payment->payment_class ?>" <?php if ($this->active_payment != $payment->payment_id) { ?>style = "display:none"<?php } ?>>
                    <td class = "jshop_payment_method">
                        <?php print $payment->payment_description ?>
    <?php print $payment->form ?>
                    </td>
                </tr>
<?php } ?>
            <tr>
                <td>
                    <div class="row_agb" style="border: 1px dashed #aaa">
                        <p>
                            * Thời gian giao hàng:<br/>
                            + Trong phạm vi các quận Ninh Kiều, Cái Răng, Bình Thủy TP. Cần Thơ: 2 đến 4 giờ sau khi xác nhận với hình thức giao hàng trực tiếp.<br/>
                            + Các quận, huyện khác trong TP. Cần Thơ: 4 đến 10 giờ sau khi xác nhận với hình thức giao hàng trực tiếp.<br/>
                            + Các tỉnh lân cận: 1 đến 2 ngày thông qua chuyển phát nhanh, sẽ tính thêm phí bằng với giá chuyển phát.<br/>
                            + Các tỉnh khác: 3 đến 7 ngày tùy theo khoảng cách địa lý.<br/>
                            * Hình thức thanh toán:<br/>
                            + Thanh toán trực tuyến trên web thông qua cổng thanh toán Bảo Kim, Ngân Lượng.(Chưa hoàn chỉnh - không khuyến cáo)<br/>
                            + Thanh toán trực tiếp tại địa chỉ của nơi kinh doanh hoặc giao hàng trực tiếp cho khách hàng tại nhà.<br/>
                            Lưu ý: sẽ tính thêm phí vận chuyển bằng với mức phí của đơn vị chuyển phát với địa chỉ chuyển hàng không ở khu vực Cần Thơ.<br/>
                            * Hình thức giao hàng:<br/>
                            + Chuyển phát nhanh.<br/>
                            + Giao hàng trực tiếp tại địa chỉ khách hàng.<br/>
                            + Giao hàng tại công ty.<br/>
                        </p>
                    </div>
                </td>
            </tr>
        </table>
        <br />
<?php print $this->_tmp_ext_html_payment_end ?>
        <input type = "button" id = "payment_submit" class = "btn btn-primary" name = "payment_submit" value = "<?php print _JSHOP_NEXT ?>" onclick="checkPaymentForm();" />
    </form>
</div>

<?php if ($payment_class) { ?>
    <script type="text/javascript">
        showPaymentForm('<?php print $payment_class; ?>');
    </script>
<?php } ?>