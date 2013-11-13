/*
Configure menu styles below
NOTE: To edit the link colors, go to the STYLE tags and edit the ssm2Items colors
*/
YOffset=100; // no quotes!!
XOffset=0;
staticYOffset=30; // no quotes!!
slideSpeed=20 // no quotes!!
waitTime=100; // no quotes!! this sets the time the menu stays out for after the mouse goes off it.
menuBGColor="black";
menuIsStatic="yes"; //this sets whether menu should stay static on the screen
menuWidth=200; // Must be a multiple of 10! no quotes!!
menuCols=2;
hdrFontFamily="Tahoma";
hdrFontSize="2";
hdrFontColor="white";
hdrBGColor="#170088";
hdrAlign="left";
hdrVAlign="center";
hdrHeight="15";
linkFontFamily="Tahoma";
linkFontSize="2";
linkBGColor="white";
linkOverBGColor="#FFFF99";
linkTarget="_top";
linkAlign="Left";
barBGColor="#444444";
barFontFamily="Tahoma";
barFontSize="2";
barFontColor="white";
barVAlign="center";
barWidth=20; // no quotes!!
barText="Menu"; // <IMG> tag supported. Put exact html for an image to show.

///////////////////////////

// ssmItems[...]=[name, link, target, colspan, endrow?] - leave 'link' and 'target' blank to make a header
ssmItems[0]=["Lựa chọn"] //create header
ssmItems[1]=["<img src='http://damtv.meximas.com/templates/protostar/images/home.png' align='absmiddle' width='40px'/>&nbsp;&nbsp;<b>Trang Chủ DAMTV</b></a>", "index.php", "_blank"]
ssmItems[2]=["<img src='http://damtv.meximas.com/templates/protostar/images/cart.png' align='absmiddle' width='40px'/>&nbsp;&nbsp;<b>Giỏ Hàng Của Bạn</b></a>", "index.php?option=com_jshopping&controller=cart&task=view","_blank"]
ssmItems[3]=["<img src='http://damtv.meximas.com/templates/protostar/images/search.png' align='absmiddle' width='40px'/>&nbsp;&nbsp;<b>Công Cụ Tìm Kiếm</b></a>", "index.php/component/jshopping/search/","_blank"]
buildMenu();

//-->