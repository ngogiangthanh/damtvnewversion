<?php defined('_JEXEC') or die(); ?>
<form action="<?php print $this->action;?>" method="post" name="sort_count" id="sort_count">
<?php if ($this->config->show_sort_product || $this->config->show_count_select_products){?>
<div class="block_sorting_count_to_page">
    <?php if ($this->config->show_sort_product){?>
        <span class="box_products_sorting"><?php print _JSHOP_ORDER_BY.": ".$this->sorting?><img src="<?php print $this->path_image_sorting_dir?>" alt="orderby" onclick="submitListProductFilterSortDirection()" /></span>
    <?php }?>
    <?php if ($this->config->show_count_select_products){?>
        <span class="box_products_count_to_page"><?php print _JSHOP_DISPLAY_NUMBER.": ".$this->product_count?></span>
    <?php }?>
</div>
<?php }?>

<?php if ($this->config->show_product_list_filters && $this->filter_show){?>
    <?php if ($this->config->show_sort_product || $this->config->show_count_select_products){?>
    <div class="margin_filter"></div>
    <?php }?>
    
    <div class="jshop filters">    
        <?php if ($this->filter_show_category){?>
        <span class="box_category"><?php print _JSHOP_CATEGORY.": ".$this->categorys_sel?></span>
        <?php }?>
        <?php //if ($this->filter_show_manufacturer){?>
<!--        <span class="box_manufacrurer"><?php //print _JSHOP_MANUFACTURER.": ".$this->manufacuturers_sel?></span>-->
        <?php //}?>
        <?php print $this->_tmp_ext_filter_box;?>
        
        <?php if (getDisplayPriceShop()){?>
        <span class="filter_price"><?php print _JSHOP_PRICE?>&nbsp;
            <span class="box_price_from"><?php print _JSHOP_FROM?>&nbsp;  
                <select name = "price_from" id="price_from">
                <option value="" <?php if ($this->filters['price_from'] == "") echo "selected='selected'"?>>---Chọn Giá Nhỏ Nhất---</option>
                <option value="1000000" <?php if ($this->filters['price_from'] == "1000000") echo "selected='selected'"?>>> 1.000.000</option>
                <option value="2000000" <?php if ($this->filters['price_from'] == "2000000") echo "selected='selected'"?>>> 2.000.000</option>
                <option value="4000000" <?php if ($this->filters['price_from'] == "4000000") echo "selected='selected'"?>>> 4.000.000</option>
                <option value="8000000" <?php if ($this->filters['price_from'] == "8000000") echo "selected='selected'"?>>> 8.000.000</option>
                <option value="16000000" <?php if ($this->filters['price_from'] == "16000000") echo "selected='selected'"?>>> 16.000.000</option>
            </select></span>
            <span class="box_price_to"><?php print _JSHOP_TO?>&nbsp;
                 <select name = "price_to" id="price_to">
                <option value="" <?php if ($this->filters['price_to'] == "") echo "selected='selected'"?>>---Chọn Giá Lớn Nhất---</option>
                <option value="2000000" <?php if ($this->filters['price_to'] == "2000000") echo "selected='selected'"?>><= 2.000.000</option>
                <option value="4000000" <?php if ($this->filters['price_to'] == "4000000") echo "selected='selected'"?>><= 4.000.000</option>
                <option value="8000000" <?php if ($this->filters['price_to'] == "8000000") echo "selected='selected'"?>><= 8.000.000</option>
                <option value="12000000" <?php if ($this->filters['price_to'] == "12000000") echo "selected='selected'"?>><= 12.000.000</option>
                <option value="16000000" <?php if ($this->filters['price_to'] == "16000000") echo "selected='selected'"?>><= 16.000.000</option>
            </select>
          &nbsp;  <?php print $this->config->currency_code?>
        </span>
        <?php }?>
        
        <?php print $this->_tmp_ext_filter;?>
        <br/>
        <input type="button" class="btn btn-primary" value="<?php print _JSHOP_GO?>" onclick="submitListProductFilters();" />&nbsp;
        <input type="button" class="btn btn-special" onclick="clearProductListFilter();return false;" value="<?php print _JSHOP_CLEAR_FILTERS?>"/>
    </div>
<?php }?>
<input type="hidden" name="orderby" id="orderby" value="<?php print $this->orderby?>" />
<input type="hidden" name="limitstart" value="0" />
</form>