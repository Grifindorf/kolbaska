<?php
/**
 * @version      4.9.0 22.10.2014
 * @author       MAXXmarketing GmbH
 * @package      Jshopping
 * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
 * @license      GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
$order = $this->order;
$order_history = $this->order_history;
$order_item = $this->order_items;
$lists = $this->lists;
$print = $this->print;
?>
<html>
<body>


<link href="/templates/kassa/css/main.css" rel="stylesheet" type="text/css">
<style type="text/css">
    body { background: none; color: #000;}
    table.printOrder { width: 90%; margin: 80px; color: #616161; }
    td.print_head { border-bottom: 1px solid #9a9a9a; }
    .leader { font-size: 26px; padding-top: 10px; height: 30px; }
    .tovars2 { border: none;}
    .tovars_img2 {
        display: block;
        border:1px solid #555555;
        width: 165px;
        height: 123px;}
    .tovars_img2 img{
        width: 165px;
        height: 123px;}
    .shopping table td {text-align:center;}
    .shopping table td.city { font-size:30px; text-decoration:underline; font-weight: bold; }
    .shopping table td.oblast { font-size:12px;}
    .shopping table td.nova_poshta { font-size:18px; }
    .shopping table td.name { font-size:18px; font-weight:bold;}
    .shopping table td.telefon { font-size:14px; }
    .shopping table td.plateg, .shopping table td.manager, .shopping table td.delivery { font-size:14px; font-weight:bold;white-space: nowrap; text-align: right; }
    .shopping table td.plateg > span > span, .shopping table td.manager > span > span, .shopping table td.delivery > span > span { padding-right: 5px; }
    .shopping table td.plateg > span, .shopping table td.manager > span, .shopping table td.delivery > span { margin-right: 33%; }
    .shopping table td.manager { border-bottom: 1px solid #9a9a9a; padding-bottom: 5px; }
    .shopping table td.plateg { border-top: 1px solid #9a9a9a; padding-top: 5px; }
    .shopping table td.zakaznumber { font-size: 60px; }
    .shopping table td.plateg img, .shopping table td.manager img, .shopping table td.delivery img{ vertical-align:middle; }
    .shopping input, .shopping textarea, .shopping select { border:none; }
    .print_excel td, .print_excel th {
        padding: 4px;
    }
    @media print {
        .shopping table td.plateg > span, .shopping table td.manager > span, .shopping table td.delivery > span { margin-right: 0% !important; }
        .pageend { page-break-after: always; }
        .block, x:-moz-any-link { page-break-before: always; }
        .barcode {
            float: right;
        }
    }
    .barcode {
        float: right;
    }
</style>
<script type="text/javascript">
    function PrintWindow(){
        window.print();
        //CheckWindowState();
    }

    function CheckWindowState(){
        if(document.readyState != "complete"){
            setTimeout(function(){CheckWindowState();}, 2000);
        }
    }

    PrintWindow();
</script>

<table width="100%">
    <tr>
        <td colspan="4">
            <img src="/images/kolbaska.com.ua.png" width="246" height="71"/>
        </td>
        <td style="font-size:26px; text-align:right; font-weight: bold;" colspan="4">
            <img src="/templates/kassa/img/icon-phone-nakladna.png" width="23" height="16"> (044) 578-21-33
        </td>
    </tr>
</table>
<br>
<hr>
<br>
<?php
$dayofweek = array(
    "Воскресенье",
    "Понедельник",
    "Вторник",
    "Среда",
    "Четверг",
    "Пятница",
    "Суббота"
);
?>
<span style="font-size:26px;">Заказ №<?=$order->order_id?> от <?=$order->order_date?></span>
<!--<img class="barcode" src="data:image/jpg;base64,<?=$barcode['img']?>">-->
<br><br>
Архипчук А.А.  магазин Продукти  вул. Радужна 67  067 774 91 94
<hr>
<br>
<table class="print_excel">
    <tr>
        <th>№</th>
        <th>Код товара</th>
        <th>Наименование</th>
        <th>Кол.</th>
        <th>Цена (грн.)</th>
        <th>Сумма</th>
    </tr>
<?php $i=0; foreach ($order_item as $item){ $i++;


        $price = $item->product_item_price;

        $price_sum = $item->product_quantity * ($price - ($price*0.05));
        $content .= '<tr><td>'.$i.'</td>
						<td>'.$item->product_ean.'</td>
						<td>'.$item->product_name.'</td>
						<td>'.$item->product_quantity.'</td>
						<td>'.($price - ($price*0.05)).'</td>
						<td>'.$price_sum.'</td>
						</tr>';
        $totalsumm_all += $price_sum;

}

echo $content.'</table>
			<hr>';

echo '<table border="0" width="100%">';


/*
if($order['plateg'] == $paymentsDetails['2']['name']){
    $bankPercent = round((($totalsumm_all - $discount_proc - $coupon_proc - $order['amountDeductedOrder']) * 0.01) + 2, 2);
    echo '<tr> <td colspan="4"></td><td  colspan="2">Услуги банка (+1%):</td><td border="1" align="right" style="border:1px #000 solid">'.$bankPercent.' грн.</td></tr>';
}else{
    $bankPercent = 0;
}*/

echo '<tr> <td colspan="7">&nbsp;</td></tr>
			<tr><td colspan="4"></td><td  colspan="2"><b>Сумма к оплате</b>:</td><td border="1" align="right" style="border:2px #000 solid">'.round(($totalsumm_all), 2).' грн.</td></tr>
			</table>';
 ?>


</body>
</html>
