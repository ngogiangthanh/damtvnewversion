<?php 
/**
* @version      4.3.1 13.08.2013
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
$jshopConfig=JSFactory::getConfig();
JFilterOutput::objectHTMLSafe( $jshopConfig, ENT_QUOTES);
$vendor=$this->vendor;
$lists=$this->lists;
JHTML::_('behavior.tooltip');
include(dirname(__FILE__)."/submenu.php");
?>
<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<input type="hidden" name="task" value="">
<input type="hidden" name="tab" value="5">
<input type="hidden" name="vendor_id" value="<?php print $vendor->id;?>">

<div class="col100">
<fieldset class="adminform">
    <legend><?php echo _JSHOP_STORE_INFO ?></legend>
    <table class="admintable" width="100%" >
    <tr>
     <td class="key">
       <?php echo _JSHOP_STORE_NAME;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="shop_name" value="<?php echo $vendor->shop_name?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_STORE_COMPANY;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="company_name" value="<?php echo $vendor->company_name?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_STORE_URL;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="url" value="<?php echo $vendor->url?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_LOGO;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="logo" value="<?php echo $vendor->logo?>" />
     </td>
    </tr>    
    <tr>
     <td  class="key">
       <?php echo _JSHOP_STORE_ADRESS;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="adress" value="<?php echo $vendor->adress?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_STORE_CITY;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="city" value="<?php echo $vendor->city?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_STORE_ZIP;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="zip"  value="<?php echo $vendor->zip?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_STORE_STATE;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="state" value="<?php echo $vendor->state?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_STORE_COUNTRY;?>
     </td>
     <td>
       <?php echo $lists['countries'];?>
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_STORE_DATE_FORMAT;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="store_date_format" value="<?php echo $jshopConfig->store_date_format?>" />
     </td>
    </tr>
    </table>
</fieldset>
</div>
<div class="clr"></div>

<div class="col100">
<fieldset class="adminform">
    <legend><?php echo _JSHOP_CONTACT_INFO ?></legend>
    <table class="admintable" width="100%" >
    <tr>
     <td  class="key">
       <?php echo _JSHOP_CONTACT_FIRSTNAME;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="f_name" value="<?php echo $vendor->f_name?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_CONTACT_LASTNAME;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="l_name" value="<?php echo $vendor->l_name?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_CONTACT_MIDDLENAME;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="middlename" value="<?php echo $vendor->middlename?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_CONTACT_PHONE;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="phone" value="<?php echo $vendor->phone?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_CONTACT_FAX;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="fax" value="<?php echo $vendor->fax?>" />
     </td>
    </tr> 
    <tr>
     <td  class="key">
       <?php echo _JSHOP_EMAIL;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="email" value="<?php echo $vendor->email?>" />
     </td>
    </tr>
    </table>
</fieldset>
</div>
<div class="clr"></div>

<div class="col100">
<fieldset class="adminform">
    <legend><?php echo _JSHOP_BANK ?></legend>
    <table class="admintable" width="100%" >
    <tr>
     <td  class="key">
       <?php echo _JSHOP_BENEF_BANK_NAME;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_bank_info" value="<?php echo $vendor->benef_bank_info?>" />
     </td>
    </tr>

    <tr>
     <td  class="key">
       <?php echo _JSHOP_BENEF_BIC;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_bic" value="<?php echo $vendor->benef_bic?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_BENEF_CONTO;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_conto" value="<?php echo $vendor->benef_conto?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_BENEF_PAYEE;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_payee" value="<?php echo $vendor->benef_payee?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_BENEF_IBAN;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_iban" value="<?php echo $vendor->benef_iban?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_BENEF_SWIFT;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_swift" value="<?php echo $vendor->benef_swift?>" />
     </td>
    </tr>
    </table>
</fieldset>
</div>
<div class="clr"></div>

<div class="col100">
<fieldset class="adminform">
    <legend><?php echo _JSHOP_INTERM_BANK ?></legend>
    <table class="admintable" width="100%" >
    <tr>
     <td  class="key">
       <?php echo _JSHOP_INTERM_NAME;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="interm_name" value="<?php echo $vendor->interm_name?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_INTERM_SWIFT;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="interm_swift" value="<?php echo $vendor->interm_swift?>" />
     </td>
    </tr>
    </table>
</fieldset>
</div>
<div class="clr"></div>

<div class="col100">
<fieldset class="adminform">
    <table class="admintable" width="100%" >
    <tr>
     <td  class="key">
       <?php echo _JSHOP_IDENTIFICATION_NUMBER;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="identification_number" value="<?php echo $vendor->identification_number?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_TAX_NUMBER;?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="tax_number" value="<?php echo $vendor->tax_number?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo _JSHOP_ADDITIONAL_INFORMATION;?>
     </td>
     <td>
        <textarea rows="5" cols="55" name="additional_information"><?php echo $vendor->additional_information?></textarea>
     </td>
    </tr>
    </table>
</fieldset>
</div>
<div class="clr"></div>

<div class="col100">
<fieldset class="adminform">
    <legend><?php echo _JSHOP_PDF_CONFIG ?></legend>
    <table class="admintable" width="100%" >
    <tr>
    <td  class="key">
       <?php echo _JSHOP_PDF_HEADER?>
       <?php echo JHTML::tooltip(_JSHOP_PDF_ONLYJPG)?>
    </td>
    <td>
        <input size="55" type="file" name="header" value="" />
    </td>
    </tr>

    <tr>
    <td  class="key">
       <?php echo _JSHOP_IMAGE_WIDTH?>
       <?php echo JHTML::tooltip(_JSHOP_PDF_INMM)?>
    </td>
    <td>
        <input size="55" type="text" class="inputbox" name="pdf_parameters[pdf_header_width]" value="<?php echo $jshopConfig->pdf_header_width?>" />
    </td>
    </tr>
    <tr>
    <td  class="key">
       <?php echo _JSHOP_IMAGE_HEIGHT?>
       <?php echo JHTML::tooltip(_JSHOP_PDF_INMM)?>
    </td>
    <td>
        <input size="55" type="text" class="inputbox" name="pdf_parameters[pdf_header_height]" value="<?php echo $jshopConfig->pdf_header_height?>" />
    </td>
    </tr>
    <tr>
    <td> </td>
    </tr>
    <tr>
    <td  class="key">
       <?php echo _JSHOP_PDF_FOOTER?>
       <?php echo JHTML::tooltip(_JSHOP_PDF_ONLYJPG)?>
    </td>
    <td>
        <input size="55" type="file" name="footer" value="" />
    </td>
    </tr>
    <tr>
    <td  class="key">
       <?php echo _JSHOP_IMAGE_WIDTH?>
       <?php echo JHTML::tooltip(_JSHOP_PDF_INMM)?>
    </td>
    <td>
        <input size="55" type="text" class="inputbox" name="pdf_parameters[pdf_footer_width]" value="<?php echo $jshopConfig->pdf_footer_width?>" />
    </td>
    </tr>
    <tr>
    <td  class="key">
       <?php echo _JSHOP_IMAGE_HEIGHT?>
       <?php echo JHTML::tooltip(_JSHOP_PDF_INMM)?>
    </td>
    <td>
        <input size="55" type="text" class="inputbox" name="pdf_parameters[pdf_footer_height]" value="<?php echo $jshopConfig->pdf_footer_height?>" />
    </td>
    </tr>
    <tr>
    <td></td>
    <td >
        <?php print _JSHOP_PDF_PREVIEW_INFO1;?>
        <a target="_blank" href="index.php?option=com_jshopping&controller=config&task=preview_pdf"><?php echo _JSHOP_PDF_PREVIEW?></a>
    </td>
    </tr>
    </table>

</fieldset>
</div>
<div class="clr"></div>

<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>

</form>