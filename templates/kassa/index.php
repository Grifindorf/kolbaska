<!DOCTYPE html>
<html>
<head>
    <jdoc:include type="head" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="/templates/kolbaska/favicon.ico" type="image/x-icon">
    <script>
        $ = jQuery;
    </script>

</head>

<body>


<?php

$discount = true;
$_SESSION['lang'] = 'ru';
/*
 * $_SESSION['typeOFprice'] переключатель типа просчета стоимости
 * Опт - true
 * Розница - false
 */

if (!$_SESSION['userID']) $_SESSION['userID']=61;

if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
    JError::raiseError(500,"Please install component \"joomshopping\"");
}

jimport('joomla.application.component.model');

require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php');
require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
JSFactory::loadCssFiles();
JSFactory::loadLanguageFile();
$jshopConfig = JSFactory::getConfig();
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_jshopping/models');
$cart = JModelLegacy::getInstance('cart', 'jshop');
$cart->load('cart');
$cart->addLinkToProducts(1);
$countprod = 0;
$array_products = array();
?>
<div class="vrbody">
    <div class="wrappercontroller top">
        <div class="content">
            <div class="headerleftcontroll">
                <div class="tenpx"></div>
                <div class="headerbutton buttonbig"><a onclick="checkRealSumm()" href="javascript:void(null)">Продажа (F9)</a></div>
                <div class="headerbutton buttonbig"><a onclick="productsAdd()" href="javascript:void(null)">Принять товар</a></div>
                <!--<div class="headerbutton"><a onclick="processBarCodeWriteOff()" href="javascript:void(null)">Списание <img src="/template/img/lock-icon.png" /></a></div>-->
                <div class="headerbutton"><a onclick="clearCart()" href="javascript:void(null)">Очистить заказ</a></div>
                <!--<div class="headerbutton"><a onclick="RollBackBarCode()" href="javascript:void(null)">Возврат</a></div>-->
                <!--<div class="headerbutton"><a onclick="processBarCodeDefect()" href="javascript:void(null)">Брак <img src="/template/img/lock-icon.png" /></a></div>-->
                <!--<div class="headerbutton"><a id="managertext" onclick="openManagerWindow()" href="javascript:void(null)">Михайло</a></div>-->
            </div>

            <div class="headerrightcontroll">
                <div class="controlblock">
                    <div class="tenpx"></div>
                    <!--<div class="headerbutton"><a onclick="setAsideCart()" href="javascript:void(null)">Отложить чек</a></div>
                    <div class="headerbutton">
                        <a class="clientlink" onclick="openClientWindow()" href="javascript:void(null)">
                            <?php if ($_SESSION['userID']==61) { echo '+ клиент'; } else { echo $userData['Company'].'<br/>'.$userData['Phone'];} ?>
                        </a>
                    </div>-->
                </div>
                <div class="cartpreviewsumm <?php echo ($_SESSION['typeOFprice'] ? 'green' : 'red');?>">
                    <div class="cartupdate">
                        <div class="previewsumm">
                            Сумма: <span id="BarCodeCartSummWithoutDiscount"><?php echo number_format($cart->price_product, 2, ".", " "); ?></span> грн.
                            Скидка: <span><?php echo ( ($userData['CardNumber']>0 && $_SESSION['userID']!=61 && $_SESSION['userID']!=62 ) ? '2' : '0'); ?></span>% (<span id="DiscountSumm"><?php echo ( ($userData['CardNumber']>0 && $_SESSION['userID']!=61 && $_SESSION['userID']!=62 ) ? number_format(($CartInfo['realSumm2']-$CartInfo['realSumm']), 2, ".", " ") : '0'); ?></span> грн.)</div>
                        <div class="fullsumm">К оплате: <span id="BarCodeCartSumm"><?php echo number_format($cart->price_product, 2, ".", " "); ?></span> грн.</div>
                        <div>Количество товаров: <?=$cart->count_product?></div>
                    </div>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <div class="content information">
        <table width="100%" id="listProductsBarCode" cellspacing="0">
            <?php

            if (count($cart->products)>0) {
                $count = 0;
                foreach ($cart->products as $key=>$good) {
                    $fullprice = $good['price']*$good['quantity'];
                    echo "<tr id='{$good['product_id']}'>";
                    echo "<td width='20px'><a onclick='removegood({$key})' href='javascript:void(null)'><img src='/templates/kassa/img/delete-icon.png' /></a></td>";
                    echo "<td width='40px' align='center' class='tdborder'>".($count+1)."</td>";
                    echo "<td width='40px' align='center' class='tdborder'>".$good['ean']."</td>";
                    echo "<td class='tdborder'><div>{$good['product_name']}</div></td>";
                    echo "<td width='60px' align='center' class='tdborder'><div><input class='changeqtty' data-id='{$good['product_id']}' value='{$good['quantity']}'/></div></td>";
                    echo "<td width='90px' align='center' class='tdborder'><span>{$good['price']}</span> грн.</td>";
                    echo "<td width='130px' align='center' class='tdborder'><span>{$fullprice}</span> грн.</td>";
                    //echo "<td class='tdborder'>-{$good['discountSize']}%</td>";
                    echo "</tr>";
                    $count++;
                }
            }
            ?>
            <tr class="tradd">
                <td width='20px'></td>
                <td width='40px' align='center' class='tdborder' id="lastinsertindex"><?php echo (!is_array($cart->products) ? '1' : count($cart->products)+1 ); ?></td>
                <td class='tdborder' colspan="2">
                    <div>
                        <input type="text" id="BarCodeSearch" name="barcode" value="" />
                    </div>
                </td>
                <td width='60px' align='center' class='tdborder'></td>
                <td width='90px' align='center' class='tdborder'></td>
                <td width='130px' align='center' class='tdborder'></td>
                <!--<td class='tdborder'></td>-->
            </tr>
        </table>
        <div class="page-buffer"></div>
    </div>


</div>

<div id="vrfooter">
    <div class="wrappercontroller">
        <div class="content">
            <div class="headerleftcontroll">
                <div class="tenpx"></div>
                <!--<div class="headerbutton"><a href="/admin/getlistasidecarts">Чеки</a></div>
                <div class="headerbutton"><a href="/admin/getlistordersfromshop">Архив</a></div>-->
            </div>
            <div class="headerrightcontroll">
                <div class="tenpx"></div>
                <div class="controlblock">
                    <div class="headerbutton <?php echo ($_SESSION['typeOFprice'] ? 'green' : 'red');?>"><a onclick="changeOptRozniza()" href="javascript:void(null)"><?php echo ($_SESSION['typeOFprice'] ? 'Опт' : 'Розница');?></a></div>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>

<div style="display: none">
    <div id="ClientWindow" class="modal" style="width: 500px;">
        <h2>Введите информацию о клиенте</h2>

        <div style="display: block;">
            <div id="bobroidsTabs">
                <div class="tabsLinks">
                    <div tabname="ext" class="oneTabItem active">Поиск</div>
                    <div subclass="blue" tabname="new" class="oneTabItem blue">Новый</div>
                    <!--<div subclass="blue" tabname="editUser" class="oneTabItem blue">Редактировать</div>-->
                </div>
                <div style="width: 100%; padding-top: 10px; box-sizing: border-box; margin-top: -1px; border-top: 1px solid #ddd;" class="tabInfo">
                    <div style="display: block;" id="ext">
                        <h3>Выбор существующего клиента:</h3>
                        <br>
                        <div>
                            <div style="display: block;" class="oneInput">
                                <input type="text" id="clientcardnumber"><label for="clientcardnumber">Номер карты</label>
                            </div>
                            или
                            <br>
                            <div style="display: block;" class="oneInput">
                                <input type="text" id="oldPhone"><label for="oldPhone">Телефон клиента</label>
                            </div>
                            или
                            <br>
                            <div style="display: block;" class="oneInput">
                                <input type="text" id="SearchSurname"><label for="SearchSurname">Фамилия</label>
                            </div>
                            <center><button onclick="findSomeUser(this)" class="coolButton green">Искать</button></center>
                        </div>
                    </div>
                    <div style="display: none;" id="new">
                        <h3>Добавление нового клиента:</h3>
                        <br>
                        <div>
                            <div style="display: block;" class="oneInput">
                                <input type="text" id="newSurname"><label for="newSurname">Фамилия</label>
                            </div>
                            <div style="display: block;" class="oneInput">
                                <input type="text" id="newName"><label for="newName">Имя</label>
                            </div>
                            <div style="display: block;" class="oneInput">
                                <input type="text" id="newCity"><label for="newCity">Город, Область</label>
                            </div>
                            <div style="display: block;" class="oneInput">
                                <input type="text" id="newPhone"><label for="newPhone">Номер телефона</label>
                            </div>
                            <div style="display: block;" class="oneInput">
                                <input type="text" id="newEmail"><label for="newEmail">Электронная почта</label>
                            </div>
                            <div style="display: block;" class="oneInput">
                                <input type="text" id="newClientCardNumber"><label for="newClientCardNumber">Номер карты</label>
                            </div>
                            <center><button onclick="addNewUser()" class="coolButton green">Добавить</button></center>
                        </div>
                    </div>
                    <!--<div style="display: none;" id="editUser">
                    <h3>Редактирование клиента:</h3>
                    <?php
                    if($userData['CardNumber']>0 && $_SESSION['userID']!=61 && $_SESSION['userID']!=62){
                        ?>
                            <div>
                                Скидка: 2%, Номер карты: <?=$userData['CardNumber'];?>
                            </div>
                        <?php
                    }
                    ?>
                    <br>
                    <div>
                        <div style="display: block;" class="oneInput">
                            <input type="text" id="editSurname" value="<?=$userData['Company'];?>"><label for="editSurname">Ф.И.О.</label>
                        </div>
                        <div style="display: block;" class="oneInput">
                            <input type="text" id="editCity" value="<?=$userData['City'];?>"><label for="editCity">Город, Область</label>
                        </div>
                        <div style="display: block;" class="oneInput">
                            <input type="text" id="editPhone" value="<?=$userData['Phone'];?>"><label for="editPhone">Номер телефона</label>
                        </div>
                        <div style="display: block;" class="oneInput">
                            <input type="text" id="editEmail" value="<?=$userData['Email'];?>"><label for="editEmail">Электронная почта</label>
                        </div>
                        <div style="display: block;" class="oneInput">
                            <input type="text" id="editClientCardNumber" value="<?=$userData['CardNumber'];?>"><label for="editClientCardNumber">Номер карты</label>
                        </div>
                        <center><button onclick="editUser()" class="coolButton green">Редактировать</button></center>
                    </div>
                </div>-->
                </div>
            </div>
        </div>

    </div>

    <div id="ErrorQttyWindow" class="modal" style="width: 600px;"></div>

    <div id="ManagerWindow" class="modal" style="width: 420px;">
        <h2>Выберите менеджера</h2>
        <?php
        /*foreach($managers as $manager){
            echo '<div class="headerbutton" data-id="'.$manager['id'].'">'.$manager['name'].'</div>';
        }*/
        ?>
    </div>

    <div id="CheckPermissionWindowDefect" class="modal" style="width: 400px;">
        <h2>Введите логин и пароль администратора</h2>
        <table width="100%">
            <tr>
                <td>
                    Логин:
                </td>
                <td>
                    <input type="text" name="logincheck" id="logincheck" value="" />
                </td>
            </tr>
            <tr>
                <td>
                    Пароль:
                </td>
                <td>
                    <input type="password" name="passwordcheck" id="passwordcheck" value="" />
                </td>
            </tr>
        </table>
        <button onclick="checkPermissionDefect()" class="coolButton button-small">Подтвердить</button>
    </div>
    <div id="CheckPermissionWindowWriteOFF" class="modal" style="width: 400px;">
        <h2>Введите логин и пароль администратора</h2>
        <table width="100%">
            <tr>
                <td>
                    Логин:
                </td>
                <td>
                    <input type="text" name="logincheck" id="logincheck" value="" />
                </td>
            </tr>
            <tr>
                <td>
                    Пароль:
                </td>
                <td>
                    <input type="password" name="passwordcheck" id="passwordcheck" value="" />
                </td>
            </tr>
        </table>
        <button onclick="checkPermissionWriteOFF()" class="coolButton button-small">Подтвердить</button>
    </div>
    <div id="CheckRealSumm" class="modal" style="width: 400px;">
        <h2>Введите сумму к оплате</h2>
        <table width="100%">
            <tr>
                <td>
                    Текущая сумма:
                </td>
                <td>
                    <input type="text" disabled name="currentsumm" id="currentsumm" value="" /> грн.
                </td>
            </tr>
            <tr>
                <td>
                    К оплате:
                </td>
                <td>
                    <input type="text" name="summkoplate" id="summkoplate" value="" /> грн.
                </td>
            </tr>
        </table>
        <!--<h2 onclick="expandDiscountRule()">Добавить скидку</h2>
        <div id="newRule" style="display: none;">
            <br>
			<span>
				<img src="<?=$GLOBALS['CDN_LINK']?>/template/images/add.svg" style="max-height: 30px;" alt="Добавить условие" title="Добавить условие" id="addTerm"><label style="line-height: 30px; vertical-align: middle; height: 30px; display: inline-block; margin-top: -20px; margin-left: 10px;" for="addTerm">Добавить условие</label>
			</span>
            <div id="terms">

            </div>
            <div id="discount">
                <label for="discountPercent">Скидка:&nbsp;</label><input type="text" id="discountPercent" style="width: 30px" max="100" maxlength="3"><label for="discountPercent">%</label>
            </div>
            <br>
            <span>
                <a href="javascript:void(null);" onclick="expandHiddenRule()">Посмотреть на правило</a>
            </span>
            <br style="margin-bottom: 10px;">
            <div id="hiddenRule" style="display: none; min-height: 20px; padding: 5px; margin-bottom: 10px;">

            </div>
            <button id="addTermToBase">Добавить правило</button>
        </div>-->
        <button onclick="processBarCodeOrder()" class="coolButton button-small">Оформить заказ</button>
    </div>
</div>

<form id="clearCart" method="post"></form>
<form id="setAsideCart" method="post"></form>
<form id="processBarCodeOrder" method="post"></form>
<form id="addBarCodeOrder" method="post"></form>

<div id="breaksound"></div>

<link rel="stylesheet" type="text/css" href="/templates/kassa/css/sweetalert.css">
<script type="text/javascript" src="/templates/kassa/js/min.sweetalert.js"></script>
<script type="text/javascript" src="/templates/kassa/js/jquery.the-modal.js"></script>
<script type="text/javascript" src="/templates/kassa/js/bobroidsTabs.js"></script>
<script>
/*
document.querySelector("#addTerm").addEventListener('click', function(){ addTerm(); someChanged(); }, false);
document.querySelector("#addTermToBase").addEventListener('click', function(){ addTermToBase(); }, false);
document.querySelector("#discountPercent").addEventListener('change', function(){ someChanged(); }, false);

function expandHiddenRule(){
    if(document.querySelector("#hiddenRule").style.display == 'none'){
        document.querySelector("#hiddenRule").style.display = 'block';
        document.querySelector("#hiddenRule").innerHTML = getStringTerm();
    }else{
        document.querySelector("#hiddenRule").style.display = 'none';
    }
}
function expandDiscountRule(){
    if(document.querySelector("#newRule").style.display == 'none'){
        document.querySelector("#newRule").style.display = 'block';
    }else{
        document.querySelector("#newRule").style.display = 'none';
    }
}
function addTerm(){
    var termBlock = document.createElement("div");
    var term = document.createElement("select");
    var type = document.createElement("select");
    var value = document.createElement("input");
    var deleter = document.createElement("img");

    var options = {
        GoodGroup: {
            name: 'Категория товара',
            value: 'GoodGroup'
        }
    };

    termBlock.setAttribute('class', 'oneTerm');

    term.setAttribute('class', 'term');
    term.style.width = '200px';
    term.addEventListener('change', function(e){ checkSelect(e); someChanged(); }, false);
    for(var key in options){
        var elem = document.createElement('option');
        elem.innerHTML = options[key].name;
        elem.value = options[key].value;
        term.appendChild(elem);
    }

    //$(term).trigger('change');
    type.setAttribute('class', 'type');
    type.style.width = '40px';
    type.innerHTML = '<option value="=">=</option><option value="<="><=</option><option value=">=">>=</option><option value="!=">!=</option>';
    type.addEventListener('change', function(){ someChanged(); }, false);

    value.type = "text";
    value.setAttribute('class', 'value');
    value.style.width = '100px';
    value.style.height = '26px';
    value.addEventListener('change', function(){ someChanged(); }, false);

    deleter.src = '<?=$GLOBALS['CDN_LINK']?>/template/images/remove.svg';
    deleter.setAttribute('class', 'delete');
    deleter.addEventListener('click', function(e){ deleteTerm(e); someChanged(); }, false);

    termBlock.appendChild(term);
    termBlock.appendChild(type);
    termBlock.appendChild(value);
    termBlock.appendChild(deleter);
    document.querySelector("#terms").appendChild(termBlock);
    checkSelectTarget(term);
}
function addTermToBase(){
    var term = getStringTerm();
    //if(term.match(/^THEN.)){
        alert('Вам не кажется, что правило какое-то неправильное?');
    }else{
        $.ajax({
            type: 'POST',
            data: {
                "do": "addKassaPriceRule",
                "rule": term
            },success: function(data){
                checkNewPrice();
            }
        });
    }
}
function someChanged(){
    document.querySelector("#hiddenRule").innerHTML = getStringTerm();
}
function getStringTerm(){
    var obj = getRuleObj();
    var ruleString = '';

    for(var key in obj){
        if(ruleString.length != 0){
            ruleString = ruleString + 'AND ';
        }else{
            ruleString = 'IF '
        }
        ruleString = ruleString + '(' + obj[key].term + ' ' + obj[key].type + ' ' + obj[key].value + ') ';
    }
    ruleString = ruleString + 'THEN Discount = ' + document.querySelector("#discountPercent").value;
    return ruleString;
}
function getRuleObj(){
    var root = document.querySelector("#terms");
    var terms = root.querySelectorAll(".oneTerm");
    var obj = new Object();

    for(var i = 0; i < terms.length; i++){
        obj[i] = {
            term: terms[i].querySelector(".term").value,
            type: terms[i].querySelector(".type").value,
            value: terms[i].querySelector(".value").value
        };
    }
    return obj;
}
function checkSelectTarget(event){
    var value, deleter = document.createElement("img");

    event.parentNode.querySelector(".value").remove();
    event.parentNode.querySelector(".delete").remove();

    if(event.value == 'GoodGroup' || event.value == 'WithoutBlyamba' || event.value == 'virtualCategory'){
        event.parentNode.querySelector("select.type option[value='=']").selected = true;

        if(event.value == 'GoodGroup'){
            value = document.createElement('select');
            value.style.width = '100px';
            value.setAttribute('class', 'value categories');
            value.addEventListener('change', function(){ someChanged(); }, false);

            $.ajax({
                type: 'POST',
                data: {
                    "do": "getCategoriesListForKassa"
                },
                success: function(data) {
                    var options = JSON.parse(data);

                    for(var key in options){
                        var elem = document.createElement('option');
                        elem.value = options[key].Code;
                        elem.innerHTML = options[key].Name;
                        value.appendChild(elem);
                    }
                }
            });
        }else if(event.value == 'virtualCategory'){
            value = document.createElement('select');
            value.style.width = '100px';
            value.setAttribute('class', 'value categories');
            value.addEventListener('change', function(){ someChanged(); }, false);

            $.ajax({
                type: 'POST',
                data: {
                    "do": "getVirtualCategoriesList"
                },
                success: function(data) {
                    var options = JSON.parse(data);

                    for(var key in options){
                        var elem = document.createElement('option');
                        elem.value = options[key].Code;
                        elem.innerHTML = options[key].Name;
                        value.appendChild(elem);
                    }
                }
            });
        }else{
            value = document.createElement('input');
            value.style.width = '100px';
            value.setAttribute('class', 'value blyambaState');
            value.readOnly = true;
            value.disabled = true;
            value.value = 'true';
        }

        deleter.src = '<?=$GLOBALS['CDN_LINK']?>/template/images/remove.svg';
        deleter.setAttribute('class', 'delete');
        deleter.addEventListener('click', function(e){ deleteTerm(e); someChanged(); }, false);

        event.parentNode.appendChild(value);
        event.parentNode.appendChild(deleter);
    }else{
        event.parentNode.querySelector("select.type option[value='<=']").disabled = false;
        event.parentNode.querySelector("select.type option[value='>=']").disabled = false;

        value = document.createElement('input');
        value.setAttribute('class', 'value');
        value.style.width = '100px';
        value.style.height = '26px';
        value.addEventListener('change', function(){ someChanged(); }, false);
        if(event.value == 'Date'){
            value.placeholder = 'dd.mm.yyyy';
        }else{
            value.placeholder = '';
        }
        event.parentNode.appendChild(value);

        deleter.src = '<?=$GLOBALS['CDN_LINK']?>/template/images/remove.svg';
        deleter.setAttribute('class', 'delete');
        deleter.addEventListener('click', function(e){ deleteTerm(e); someChanged(); }, false);
        event.parentNode.appendChild(deleter);
    }
}
function checkSelect(event){
    var value, deleter = document.createElement("img");

    event.target.parentNode.querySelector(".value").remove();
    event.target.parentNode.querySelector(".delete").remove();

    if(event.target.value == 'GoodGroup' || event.target.value == 'WithoutBlyamba' || event.target.value == 'virtualCategory'){
        event.target.parentNode.querySelector("select.type option[value='=']").selected = true;
        //event.target.parentNode.querySelector("select.type option[value='<=']").disabled = true;
        //event.target.parentNode.querySelector("select.type option[value='>=']").disabled = true;

        if(event.target.value == 'GoodGroup'){
            value = document.createElement('select');
            value.style.width = '100px';
            value.setAttribute('class', 'value categories');
            value.addEventListener('change', function(){ someChanged(); }, false);

            $.ajax({
                type: 'POST',
                data: {
                    "do": "getCategoriesList_ajax"
                },
                success: function(data) {
                    var options = JSON.parse(data);

                    for(var key in options){
                        var elem = document.createElement('option');
                        elem.value = options[key].Code;
                        elem.innerHTML = options[key].Name;
                        value.appendChild(elem);
                    }
                }
            });
        }else if(event.target.value == 'virtualCategory'){
            value = document.createElement('select');
            value.style.width = '100px';
            value.setAttribute('class', 'value categories');
            value.addEventListener('change', function(){ someChanged(); }, false);

            $.ajax({
                type: 'POST',
                data: {
                    "do": "getVirtualCategoriesList"
                },
                success: function(data) {
                    var options = JSON.parse(data);

                    for(var key in options){
                        var elem = document.createElement('option');
                        elem.value = options[key].Code;
                        elem.innerHTML = options[key].Name;
                        value.appendChild(elem);
                    }
                }
            });
        }else{
            value = document.createElement('input');
            value.style.width = '100px';
            value.setAttribute('class', 'value blyambaState');
            value.readOnly = true;
            value.disabled = true;
            value.value = 'true';
        }

        deleter.src = '<?=$GLOBALS['CDN_LINK']?>/template/images/remove.svg';
        deleter.setAttribute('class', 'delete');
        deleter.addEventListener('click', function(e){ deleteTerm(e); someChanged(); }, false);

        event.target.parentNode.appendChild(value);
        event.target.parentNode.appendChild(deleter);
    }else{
        event.target.parentNode.querySelector("select.type option[value='<=']").disabled = false;
        event.target.parentNode.querySelector("select.type option[value='>=']").disabled = false;

        value = document.createElement('input');
        value.setAttribute('class', 'value');
        value.style.width = '100px';
        value.style.height = '26px';
        value.addEventListener('change', function(){ someChanged(); }, false);
        if(event.target.value == 'Date'){
            value.placeholder = 'dd.mm.yyyy';
        }else{
            value.placeholder = '';
        }
        event.target.parentNode.appendChild(value);

        deleter.src = '<?=$GLOBALS['CDN_LINK']?>/template/images/remove.svg';
        deleter.setAttribute('class', 'delete');
        deleter.addEventListener('click', function(e){ deleteTerm(e); someChanged(); }, false);
        event.target.parentNode.appendChild(deleter);
    }
}
*/





window.onload = function() {
    document.getElementById("BarCodeSearch").focus();
    window.scrollTo(0,document.body.scrollHeight);
    /*var manager = getCookie('BarCodeManager');
    if (!manager) {
        document.cookie="BarCodeManager=25; path=/; expires=''";
        manager = 25;
    }
    $('#ManagerWindow .headerbutton[data-id='+manager+']').addClass('green');
    $('#managertext').html($('#ManagerWindow .headerbutton[data-id='+manager+']').html());*/
};
window.onclick = function() {
    document.getElementById("BarCodeSearch").focus();
};
/*$('#ManagerWindow .headerbutton').on('click',function(){
    document.cookie="BarCodeManager="+$(this).attr('data-id')+"; path=/; expires=''";
    $('#ManagerWindow .headerbutton').removeClass('green');
    $('#ManagerWindow .headerbutton[data-id='+$(this).attr('data-id')+']').addClass('green');
    $('#managertext').html($(this).html());
    $('#ManagerWindow').modal().close();
});*/
$('.changeqtty').on('click',function(){
    window.onclick = function() {};
});
$('.changeqtty').focusout(function() {
    document.getElementById("BarCodeSearch").focus();
    window.onclick = function() {
        document.getElementById("BarCodeSearch").focus();
    };
});

/*
function findSomeUser(e){
    e = e.parentNode.parentNode;
    var val, type = '';

    if(e.querySelector("#clientcardnumber").value.replace(/[^0-9]/gm, '') != ''){
        val = e.querySelector("#clientcardnumber").value.replace(/[^0-9]/gm, '');
        type = 'cardnumber';
    }else if(e.querySelector("#oldPhone").value.replace(/[^0-9]/gm, '') != ''){
        val = e.querySelector("#oldPhone").value.replace(/[^0-9]/gm, '');
        type = 'phone';
    }else if(e.querySelector("#SearchSurname").value.replace(/\s+/gm, '') != ''){
        val = e.querySelector("#SearchSurname").value.replace(/\s+/gm, '');
        type = 'fio';
    }

    if(val != '' && val != undefined){
        $.ajax( {
            type: 'POST',
            data: {
                "do": "findSomeUser",
                "type": type,
                "value": val
            },
            success: function(data) {
                data = JSON.parse(data);
                if(data.ID == 0){
                    swal("Ошибка!", "Пользователя с такими данными не найдено!", "error")
                }else{
                    swal({
                            title: "",
                            text: "Найден клиент " + data.Company + " \n\r (номер телефона " + data.Phone + ", email " + data.eMail + ")",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Да",
                            cancelButtonText: "Отменить",
                            closeOnConfirm: false
                        },
                        function(){
                            swal({
                                title: "Изменено!",
                                text: "Заказ записан на клиента " + data.Company + ".",
                                type: "success",
                                closeOnConfirm: false
                            }, function(){
                                location.reload();
                            });
                            $.ajax( {
                                type: 'POST',
                                data: {
                                    "do": "ChangeClientForCart",
                                    "customer": data.ID
                                }
                            });
                        });
                }
            }
        });
    }else{
        swal("Ошибка!", "Данные, которые вы ввели, не являются корректными для поиска!", "error");
    }
}

function addNewUser(){
    $.ajax({
        type: 'POST',
        data: {
            "do": 			 "addPartner",
            "surname": 		 document.querySelector("#newSurname").value,
            "name": 		 document.querySelector("#newName").value,
            "city": 		 document.querySelector("#newCity").value,
            "verified_city": 1,
            "phone": 		 document.querySelector("#newPhone").value,
            "e-mail": 		 document.querySelector("#newEmail").value,
            "cardNumber":    document.querySelector("#newClientCardNumber").value,
            "isForBobroid":	 true
        },
        success: function(data){
            data = data.replace(/[^0-9]/gm, '');
            swal({
                title: "Изменено!",
                text: "Заказ записан на клиента " + document.querySelector("#newName").value + " " + document.querySelector("#newSurname").value + ".",
                type: "success",
                closeOnConfirm: false
            }, function(){
                location.reload();
            });
            $.ajax( {
                type: 'POST',
                data: {
                    "do": "ChangeClientForCart",
                    "customer": data
                }
            });
        }
    });
}

function editUser(){
    $.ajax({
        type: 'POST',
        data: {
            "do": 			 "editPartner",
            "surname": 		 document.querySelector("#editSurname").value,
            "city": 		 document.querySelector("#editCity").value,
            "phone": 		 document.querySelector("#editPhone").value,
            "email": 		 document.querySelector("#editEmail").value,
            "cardnumber": 	 document.querySelector("#editClientCardNumber").value,
        },
        success: function(data){
            swal({
                title: "Изменено!",
                type: "success",
                closeOnConfirm: false
            }, function(){
                location.reload();
            });
        }
    });
}
*/
function openClientWindow() {
    window.onclick = function() {};
    bobroidsTabs.init("#ClientWindow");
    $('#ClientWindow').modal().open({
        onClose: function(el, options){
            window.onclick = function() {
                document.getElementById("BarCodeSearch").focus();
            };
        }
    });
    document.getElementById("clientcardnumber").focus();
}
function checkPermissionDefect(){
    $.ajax({
        type: 'POST',
        data: {
            "do": "checkpermission",
            "login": $('#logincheck').val(),
            "pass": $('#passwordcheck').val()
        },
        success: function(data){
            if(!isNaN(data) && data>0){
                processBarCodeDefect(1);
            }
        }
    });
}
function checkPermissionWriteOFF(){
    $.ajax({
        type: 'POST',
        data: {
            "do": "checkpermission",
            "login": $('#logincheck').val(),
            "pass": $('#passwordcheck').val()
        },
        success: function(data){
            if(!isNaN(data) && data>0){
                processBarCodeWriteOff(1);
            }
        }
    });
}
function openManagerWindow() {
    window.onclick = function() {};
    $('#ManagerWindow').modal().open({
        onClose: function(el, options){
            window.onclick = function() {
                document.getElementById("BarCodeSearch").focus();
            };
        }
    });
}
/*function minusamount(goodid) {
 $.ajax({
 type: 'POST',
 data: {
 "do": "decrementItemForBarCode",
 "itemid": goodid
 },
 success: function(data){
 console.log(JSON.parse(data).goods);
 $('#BarCodeCartSumm').html(JSON.parse(data).realSumm);
 $(JSON.parse(data).goods).each(function(index,value){
 $('#'+value.goodID+' td:eq(0)').html(index+1);
 $('#'+value.goodID+' td:eq(3) span').html(value.count);
 $('#'+value.goodID+' td:eq(4) span').html(value.fullPrice);
 });
 }
 });
 }
 function plusamount(goodid,amount) {
 $.ajax({
 type: 'POST',
 data: {
 "do": "incrementItemForBarCode",
 "itemid": goodid
 },
 success: function(data){
 $('#BarCodeCartSumm').html(JSON.parse(data).realSumm);
 $(JSON.parse(data).goods).each(function(index,value){
 $('#'+value.goodID+' td:eq(0)').html(index+1);
 $('#'+value.goodID+' td:eq(3) span').html(value.count);
 $('#'+value.goodID+' td:eq(4) span').html(value.fullPrice);
 });
 }
 });
 }*/





function addToCart(){
    var barcode = $('#BarCodeSearch').val();
    $.ajax({
        type: 'GET',
        url: '/index.php?option=com_jshopping&controller=cart&task=addAjaxKassa&product_id='+barcode,
        data: {
            "do": "doBarcodeAddCart",
            "count": 1,
            "barcode": barcode
        },
        success: function(data){
            /*var message = JSON.parse(data);
            if(message.message=='break' || message.message=='showmodal' && !message.goods){
                $('#breaksound').append('<audio style="display:none;" autoplay="autoplay"><source src="/template/img/windows-critical.wav"></audio>');
            }
            if(message.message=='showmodal' && message.goods){
                $('#breaksound').append('<audio style="display:none;" autoplay="autoplay"><source src="/template/img/windows-critical.wav"></audio>');
                $('#ErrorQttyWindow').empty();
                jQuery.each(message.goods,function(igood,val){
                    text = '<div class="productmodal"><div class="productmodaltitle">Товар <span>'+val.name+'</span> закончился на складе:</div>' +
                    '<p>Количество на складе: '+val.countinstore+'</p>' +
                    '<p>Количество в корзине: '+val.countincart+'</p>';
                    '<p>Статус товара: ';
                    if(val.show_img==1){
                        text = text + 'включен</p>';
                    }else{
                        text = text + 'выключен</p>';
                    }
                    text = text + '<hr/><table width="100%"><tr><td><input type="number" id="barcodeaddquttyproducttostore" value="1" /> <button onclick="addGoodQttyStore('+val.id+')" class="coolButton button-small">Добавить наличие</button></td>' +
                    '<td><div class="oneInput"><input type="radio" id="goodShowImg1" value="1" name="show_img" attr-goodid="'+val.id+'"><label for="goodShowImg1">Товар включен</label><br>' +
                    '<input type="radio" id="goodShowImg2" value="0" name="show_img" attr-goodid="'+val.id+'"><label for="goodShowImg2">Товар выключен</label></div></td></tr></table><hr/>';
                    if(val.show_img==1){
                        $('#goodShowImg1').attr('checked','checked');
                    }else{
                        $('#goodShowImg2').attr('checked','checked');
                    }
                    jQuery.each(val.orders,function(iorder,val){
                        text = text + '<span>Заказ № '+iorder+'</span> ' +
                        '<p>Количество товаров в заказе: '+val.count+'</p>' +
                        '<p>Количество данного товара: '+val.qtty+'</p>' +
                        '<a onclick="getGoodFromOtherOrder('+val.sborkaid+','+val.goodcode+')" href="javascript:void(null)">Забрать из заказа</a>';
                    });
                    $('#ErrorQttyWindow').append(text);
                    if(val.show_img==1){
                        $('#goodShowImg1').attr('checked','checked');
                    }else{
                        $('#goodShowImg2').attr('checked','checked');
                    }
                });
                $('#ErrorQttyWindow').append('<button onclick="errorquantityclose()" class="coolButton button-small">Отменить</button>');
                window.onclick = function() {};
                $('#ErrorQttyWindow').modal().open({
                    onClose: function(el, options){
                        window.onclick = function() {
                            document.getElementById("BarCodeSearch").focus();
                        };
                        $('#addBarCodeOrder').submit();
                    }
                });
            }
            if(message.message=='showmodal' && message.good){
                $('#ErrorQttyWindow').empty();
                jQuery.each(message.good,function(igood,val){
                    text = '<div class="productmodal"><img src="<?=$GLOBALS['CDN_LINK']?>/img/catalog/sm/'+val.ico+'" style="max-height: 100px; max-width: 350px;" height="100"><div class="productmodaltitle">Товар <span>'+val.name+'</span> закончился на складе:</div>' +
                    '<p>Количество на складе: '+val.countinstore+'</p>' +
                    '<p>Количество в корзине: '+val.countincart+'</p>' +
                    '<p>Статус товара: ';
                    if(val.show_img==1){
                        text = text + 'включен</p>';
                    }else{
                        text = text + 'выключен</p>';
                    }
                    text = text + '<hr/><table width="100%"><tr><td><input type="number" id="barcodeaddquttyproducttostore" value="1" /> <button onclick="addGoodQttyStore('+val.id+')" class="coolButton button-small">Добавить наличие</button></td>' +
                    '<td><div class="oneInput"><input type="radio" id="goodShowImg1" value="1" name="show_img" attr-goodid="'+val.id+'"><label for="goodShowImg1">Товар включен</label><br>' +
                    '<input type="radio" id="goodShowImg2" value="0" name="show_img" attr-goodid="'+val.id+'"><label for="goodShowImg2">Товар выключен</label></div></td></tr></table>';
                    $('#ErrorQttyWindow').append(text);
                    if(val.show_img==1){
                        $('#goodShowImg1').attr('checked','checked');
                    }else{
                        $('#goodShowImg2').attr('checked','checked');
                    }
                });
                $('#ErrorQttyWindow').append('<button onclick="errorquantityclose()" class="coolButton button-small">Отменить</button>');
                window.onclick = function() {};
                $('#ErrorQttyWindow').modal().open({
                    onClose: function(el, options){
                        window.onclick = function() {
                            document.getElementById("BarCodeSearch").focus();
                        };
                        $('#addBarCodeOrder').submit();
                    }
                });
            }
            if(message.message=='ok'){
                $('#addBarCodeOrder').submit();
            }
            if(message.message=='newuser'){
                $('#addBarCodeOrder').submit();
            }*/
            $('#addBarCodeOrder').submit();
        }
    });
}
$('#ErrorQttyWindow').on('change','input[name=show_img]',function(){
    if($(this).val()==0){
        $.ajax({
            type: 'POST',
            data: {
                "do": "fullySwitchOff",
                "goodID": $(this).attr('attr-goodid')
            },
            success: function(data) {
            }
        });
    }else{
        $.ajax({
            type: 'POST',
            data: {
                "do": "switchOnGood",
                "goodID": $(this).attr('attr-goodid')
            },
            success: function(data) {
            }
        });
    }
});
function addGoodQttyStore(GoodID){
    $.ajax({
        type: 'POST',
        data: {
            "do": "addGoodQttyStore",
            "count": $('#barcodeaddquttyproducttostore').val(),
            "goodid": GoodID
        },
        success: function(data){
            $('#addBarCodeOrder').submit();
        }
    });
}

/*function getGoodFromOtherOrder(sborkaid,goodcode){
    $.ajax({
        type: 'POST',
        data: {
            "do": "getGoodFromOtherOrder",
            "count": 1,
            "sborkaid": sborkaid,
            "goodcode": goodcode
        },
        success: function(data){
            $('#ErrorQttyWindow').modal().close();
            $('#addBarCodeOrder').submit();
        }
    });
}
function getGoodFromOtherOrderCount(sborkaid,goodcode,count){
    $.ajax({
        type: 'POST',
        data: {
            "do": "getGoodFromOtherOrder",
            "count": count,
            "sborkaid": sborkaid,
            "goodcode": goodcode
        },
        success: function(data){
            $('#ErrorQttyWindow').modal().close();
            $('#ErrorQttyWindow').empty();
            processBarCodeOrder();
        }
    });
}
function deleteNotCountFromCart(goodcode,count){
    $.ajax({
        type: 'POST',
        data: {
            "do": "deleteFromCartBarCodeCount",
            "count": count,
            "goodcode": goodcode
        },
        success: function(data){
            $('#ErrorQttyWindow').modal().close();
            $('#ErrorQttyWindow').empty();
            processBarCodeOrder();
        }
    });
}*/
function errorquantityclose(){
    $('#addBarCodeOrder').submit();
}
function removegood(goodid){
    $.ajax({
        type: 'GET',
        url: '/index.php?option=com_jshopping&controller=cart&task=deleteAjax&number_id='+goodid,
        data: {
            "do": "deleteFromCart",
            "itemid": goodid
        },
        success: function(data){
            /*$('#'+goodid).remove();
            $('#BarCodeCartSumm').html(JSON.parse(data).realSumm.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " "));
            $('#BarCodeCartSummWithoutDiscount').html(JSON.parse(data).realSummWithoutDiscount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " "));
            var summdiscount = JSON.parse(data).realSumm - JSON.parse(data).realSummWithoutDiscount;
            $('#DiscountSumm').html(summdiscount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " "));
            $('#lastinsertindex').html(JSON.parse(data).goods.length+1);
            $(JSON.parse(data).goods).each(function(index,value){
                $('#'+value.goodID+' td:eq(1)').html(index+1);
                $('#'+value.goodID+' td:eq(3) span').html(value.count);
                $('#'+value.goodID+' td:eq(4) span').html(value.priceForOne);
                $('#'+value.goodID+' td:eq(5) span').html(value.fullPrice);
            });*/
            $('#addBarCodeOrder').submit();
        }
    });
}

/*function setAsideCart(){
    $.ajax({
        type: 'POST',
        data: {
            "do": "getCartCodeForAsideCart"
        },
        success: function(data){
            document.cookie="cartCode="+data+"; path=/; expires=''";
            $('#setAsideCart').submit();
        }
    });
}*/
$(document).keydown(function(event){
    if (event.which==120) {
        event.preventDefault();
        window.onclick = function() {};

        productsAdd();
        /*$('#ManagerWindow').modal().open({
            onClose: function(el, options){
                //checkRealSummModal();
                //processBarCodeOrder();
                productsAdd();
            }
        });*/
    }
});

function checkRealSumm(){
    $('#ManagerWindow').modal().open({
        onClose: function(el, options){
            //checkRealSummModal();
            processBarCodeOrder();
        }
    });
}
function checkNewPrice(){
    $.ajax({
        type: 'POST',
        data: {
            "do": "getCartSumm"
        },
        success: function(data){
            var message = JSON.parse(data);
            $('#currentsumm').val(message.realSumm);
            $('#summkoplate').val(message.realSumm);
            window.onclick = function() {};
        }
    });
}
/*function checkRealSummModal(){
    $.ajax({
        type: 'POST',
        data: {
            "do": "getCartSumm"
        },
        success: function(data){
            var message = JSON.parse(data);
            $('#currentsumm').val(message.realSumm);
            $('#summkoplate').val(message.realSumm);
            window.onclick = function() {};
            $('#CheckRealSumm').modal().open({
                onClose: function(el, options){
                    window.onclick = function() {
                        document.getElementById("BarCodeSearch").focus();
                    };
                }
            });
        }
    });
}*/
function processBarCodeOrder(){
    var location = window.location.hostname;
    //var finalsumm = $('#summkoplate').val();
    var text = '';
    var needcount = 0;
    $.ajax({
        type: 'POST',
        url: '/index.php?option=com_jshopping&controller=checkout&task=step5saveAjaxKassa',
        success: function(data){
            /*var message = JSON.parse(data);
            if(message.message=='break'){
                $('#breaksound').append('<audio style="display:none;" autoplay="autoplay"><source src="/template/img/windows-critical.wav"></audio>');
            }
            if(message.message=='showmodal' && message.goods){
                $('#ErrorQttyWindow').empty();
                jQuery.each(message.goods,function(igood,val){
                    needcount = val.countincart - val.countinstore;
                    if(val.message=='notcountinstore'){
                        text = '<div class="productmodal"><div class="productmodaltitle">Товар <span>'+val.name+'</span> закончился на складе</div>' +
                        '<p>Количество на складе: '+val.countinstore+'</p>' +
                        '<p>Количество в корзине: '+val.countincart+'</p>' +
                        '<a onclick="deleteNotCountFromCart('+igood+','+needcount+')" href="javascript:void(null)">Убрать из корзины ('+needcount+' штук)</a></div>';
                        $('#ErrorQttyWindow').append(text);
                    }
                    if(val.message=='inotherorder'){
                        text = '<div class="productmodal"><div class="productmodaltitle">Товар <span>'+val.name+'</span> закончился на складе и используется в заказах:</div>' +
                        '<p>Количество на складе: '+val.countinstore+'</p>' +
                        '<p>Количество в корзине: '+val.countincart+'</p>';
                        jQuery.each(val.orders,function(iorder,val){
                            text = text + '<span>Заказ № '+iorder+'</span> ' +
                            '<p>Количество товаров в заказе: '+val.count+'</p>' +
                            '<p>Количество данного товара: '+val.qtty+'</p>' +
                            '<a onclick="getGoodFromOtherOrderCount('+val.sborkaid+','+val.goodcode+','+val.qtty+')" href="javascript:void(null)">Забрать из заказа</a>';
                        });
                        text = text + '<a onclick="deleteNotCountFromCart('+igood+','+needcount+')" href="javascript:void(null)">Убрать из корзины ('+needcount+' штук)</a></div>';
                        $('#ErrorQttyWindow').append(text);
                    }
                });
                text = '<a onclick="deleteNotCountFromCart(0,0)" href="javascript:void(null)">Убрать из корзины все недостающие товары</a>';
                $('#ErrorQttyWindow').append(text);
                $('#ErrorQttyWindow').append('<button onclick="errorquantityclose()" class="coolButton button-small">Отменить</button>');
                $('#ErrorQttyWindow').modal().open();
            }
            if (!isNaN(message.status)) {
                var params = "menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes";
                <?php if ($discount) {
                    echo 'window.open(window.location.origin+"/admin/printnakladnamagazin/"+message.status, "_blank", params);';
                } else {
                    echo 'window.open(window.location.origin+"/admin/printnakladnamagazin/"+message.status+"/withoutDiscount", "_blank", params);';
                }?>
                $('#processBarCodeOrder').submit();
            }*/

            window.open(window.location.origin+'/administrator/index.php?option=com_jshopping&controller=orders&task=printOrderKassa&order_id='+data+'&tmpl=component', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=yes,resizable=yes,width=800,height=600,directories=no,location=no');
            $('#processBarCodeOrder').submit();
        }
    });
}
/*
function RollBackBarCode(){
    var location = window.location.hostname;
    $.ajax({
        type: 'POST',
        data: {
            "do": "processBarCodeRollBack"
        },
        success: function(data){
            if (!isNaN(data)) {
                var params = "menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes";
                <?php if ($discount) {
                    echo 'window.open(window.location.origin+"/admin/printNakladnaOperations/34/"+data, "_blank", params);';
                } else {
                    echo 'window.open(window.location.origin+"/admin/printNakladnaOperations/34/"+data+"/withoutDiscount", "_blank", params);';
                }?>
                $('#processBarCodeOrder').submit();
            }
        }
    });
}
function processBarCodeDefect(check){
    if (check!=1){
        window.onclick = function() {};
        $('#CheckPermissionWindowDefect').modal().open({
            onClose: function(el, options){
                window.onclick = function() {
                    document.getElementById("BarCodeSearch").focus();
                };
            }
        });
        return;
    }
    var location = window.location.hostname;
    $.ajax({
        type: 'POST',
        data: {
            "do": "processBarCodeDefect"
        },
        success: function(data){
            if (!isNaN(data)) {
                var params = "menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes";
                <?php if ($discount) {
                    echo 'window.open(window.location.origin+"/admin/printNakladnaOperations/3/"+data, "_blank", params);';
                } else {
                    echo 'window.open(window.location.origin+"/admin/printNakladnaOperations/3/"+data+"/withoutDiscount", "_blank", params);';
                }?>
                $('#processBarCodeOrder').submit();
            }
        }
    });
}
function processBarCodeWriteOff(check){
    if (check!=1){
        window.onclick = function() {};
        $('#CheckPermissionWindowWriteOFF').modal().open({
            onClose: function(el, options){
                window.onclick = function() {
                    document.getElementById("BarCodeSearch").focus();
                };
            }
        });
        return;
    }
    var location = window.location.hostname;
    $.ajax({
        type: 'POST',
        data: {
            "do": "processBarCodeWriteOff"
        },
        success: function(data){
            if (!isNaN(data)) {
                var params = "menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes";
                <?php if ($discount) {
                    echo 'window.open(window.location.origin+"/admin/printNakladnaOperations/11/"+data, "_blank", params);';
                } else {
                    echo 'window.open(window.location.origin+"/admin/printNakladnaOperations/11/"+data+"/withoutDiscount", "_blank", params);';
                }?>
                $('#processBarCodeOrder').submit();
            }
        }
    });
}*/



function productsAdd(){
    $.ajax({
        type: 'GET',
        url: '/index.php?option=com_jshopping&controller=cart&task=productsAdd',
        success: function(data){
            $('#clearCart').submit();
        }
    });
}


function clearCart(){
    $.ajax({
        type: 'GET',
        url: '/index.php?option=com_jshopping&controller=cart&task=deleteAjaxAll',
        success: function(data){
            $('#clearCart').submit();
        }
    });
}

function round(a,b){
    b = b || 0;
    return Math.round(a*Math.pow(10,b))/Math.pow(10,b);
}

function ChangeQttyBarcodeAddCart(goodcode, count){
    var quantity = round(count,3);
    $.ajax({
        type: 'POST',
        url: '/index.php?option=com_jshopping&controller=cart&task=addAjax&product_id='+goodcode,
        data: {quantity:quantity},
        success: function(data){
            /*var message = JSON.parse(data);
            if(message.message=='break' || message.message=='showmodal' && !message.goods){
                $('#breaksound').append('<audio style="display:none;" autoplay="autoplay"><source src="/template/img/windows-critical.wav"></audio>');
            }
            if(message.message=='showmodal' && message.goods){
                $('#breaksound').append('<audio style="display:none;" autoplay="autoplay"><source src="/template/img/windows-critical.wav"></audio>');
                $('#ErrorQttyWindow').empty();
                jQuery.each(message.goods,function(igood,val){
                    text = '<div class="productmodal"><div class="productmodaltitle">Товар <span>'+val.name+'</span> закончился на складе:</div>' +
                    '<p>Количество на складе: '+val.countinstore+'</p>' +
                    '<p>Количество в корзине: '+val.countincart+'</p>';
                    '<p>Статус товара: ';
                    if(val.show_img==1){
                        text = text + 'включен</p>';
                    }else{
                        text = text + 'выключен</p>';
                    }
                    text = text + '<hr/><table width="100%"><tr><td><input type="number" id="barcodeaddquttyproducttostore" value="1" /> <button onclick="addGoodQttyStore('+val.id+')" class="coolButton button-small">Добавить наличие</button></td>' +
                    '<td><div class="oneInput"><input type="radio" id="goodShowImg1" value="1" name="show_img" attr-goodid="'+val.id+'"><label for="goodShowImg1">Товар включен</label><br>' +
                    '<input type="radio" id="goodShowImg2" value="0" name="show_img" attr-goodid="'+val.id+'"><label for="goodShowImg2">Товар выключен</label></div></td></tr></table><hr/>';
                    if(val.show_img==1){
                        $('#goodShowImg1').attr('checked','checked');
                    }else{
                        $('#goodShowImg2').attr('checked','checked');
                    }
                    jQuery.each(val.orders,function(iorder,val){
                        text = text + '<span>Заказ № '+iorder+'</span> ' +
                        '<p>Количество товаров в заказе: '+val.count+'</p>' +
                        '<p>Количество данного товара: '+val.qtty+'</p>' +
                        '<a onclick="getGoodFromOtherOrder('+val.sborkaid+','+val.goodcode+')" href="javascript:void(null)">Забрать из заказа</a>';
                    });
                    $('#ErrorQttyWindow').append(text);
                    if(val.show_img==1){
                        $('#goodShowImg1').attr('checked','checked');
                    }else{
                        $('#goodShowImg2').attr('checked','checked');
                    }
                });
                $('#ErrorQttyWindow').append('<button onclick="errorquantityclose()" class="coolButton button-small">Отменить</button>');
                $('#ErrorQttyWindow').modal().open();
            }
            if(message.message=='showmodal' && message.good){
                $('#ErrorQttyWindow').empty();
                jQuery.each(message.good,function(igood,val){
                    text = '<div class="productmodal"><img src="<?=$GLOBALS['CDN_LINK']?>/img/catalog/sm/'+val.ico+'" style="max-height: 100px; max-width: 350px;" height="100"><div class="productmodaltitle">Товар <span>'+val.name+'</span> закончился на складе:</div>' +
                    '<p>Количество на складе: '+val.countinstore+'</p>' +
                    '<p>Количество в корзине: '+val.countincart+'</p>' +
                    '<p>Статус товара: ';
                    if(val.show_img==1){
                        text = text + 'включен</p>';
                    }else{
                        text = text + 'выключен</p>';
                    }
                    text = text + '<hr/><table width="100%"><tr><td><input type="number" id="barcodeaddquttyproducttostore" value="1" /> <button onclick="addGoodQttyStore('+val.id+')" class="coolButton button-small">Добавить наличие</button></td>' +
                    '<td><div class="oneInput"><input type="radio" id="goodShowImg1" value="1" name="show_img" attr-goodid="'+val.id+'"><label for="goodShowImg1">Товар включен</label><br>' +
                    '<input type="radio" id="goodShowImg2" value="0" name="show_img" attr-goodid="'+val.id+'"><label for="goodShowImg2">Товар выключен</label></div></td></tr></table>';
                    $('#ErrorQttyWindow').append(text);
                    if(val.show_img==1){
                        $('#goodShowImg1').attr('checked','checked');
                    }else{
                        $('#goodShowImg2').attr('checked','checked');
                    }
                });
                $('#ErrorQttyWindow').append('<button onclick="errorquantityclose()" class="coolButton button-small">Отменить</button>');
                window.onclick = function() {};
                $('#ErrorQttyWindow').modal().open({
                    onClose: function(el, options){
                        window.onclick = function() {
                            document.getElementById("BarCodeSearch").focus();
                        };
                        $('#addBarCodeOrder').submit();
                    }
                });
            }
            if(message.message=='ok'){
                $('#addBarCodeOrder').submit();
            }*/
            $('#addBarCodeOrder').submit();
        }
    });
}

$(document).on('keydown','.changeqtty',function(event){
    if(event.which==13){
        event.preventDefault();
        ChangeQttyBarcodeAddCart($(this).attr('data-id'),$(this).val());
    }
});
$('#BarCodeSearch').keydown(function(event){
    if (event.which==13) {
        event.preventDefault();
        addToCart();
    }
});

function changeOptRozniza(){
    $.ajax({
        type: 'GET',
        url: '/index.php?option=com_jshopping&controller=cart&task=changeOptRozniza',
        data: {
            "do": "changeOptRozniza"
        },
        success: function(data){
            $('#setAsideCart').submit();
        }
    });
}
</script>

<style>
.headerbutton a.smalltext {
    font-size: 13px;
}
.wrappercontroller.top {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 5;
}
.changeqtty {
    border: 0;
    width: 60px;
    background: #FFFFFF;
}
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}
.page-buffer {
    height: 80px;
}
* html .vrbody {
    height: 100%;
}
.tenpx {
    height: 10px;
    width: 100%;
}
#vrfooter {
    height: 58px;
    position: fixed;
    bottom: 0;
    width: 100%;
}
.productmodal {
    border: 1px solid #cccccc;
    padding: 5px;
    margin-bottom: 5px;
    text-align: center;
}
.productmodal a {
    display: block;
}
.productmodal span {
    font-weight: bold;
}
.productmodaltitle {
    font-weight: bold;
    display: block;
}

hr {
    margin: 10px 0;
}
.content {
    width: 1000px;
    margin: 0 auto;
    font-family: OpenSans, sans-serif;
}
.content.information {
    min-height: 400px;
    position: absolute;
    left: 50%;
    margin-left: -500px;
    top: 120px;
}
h3 {
    margin-top: 30px;
    margin-bottom: 20px;
}
#listProductsBarCode {
    margin-top: 10px;
    color: #282828;
    font-size: 14px;
    margin-bottom: 30px;
}
#listProductsBarCode #BarCodeSearch {
    border: 0;
    width: 500px;
}
#listProductsBarCode td {
    padding: 10px;
}
#listProductsBarCode td.tdborder {
    border: 1px solid #a9a9a9;
    border-bottom: 0;
    border-right: 0;
}
#listProductsBarCode tr td.tdborder:last-child {
    border-right: 1px solid #a9a9a9;
}
#listProductsBarCode tr:last-child td.tdborder {
    border-bottom: 1px solid #a9a9a9;
}
.tdbordertop {
    border-top: 1px solid #a9a9a9;
}
#listProductsBarCode a {
    text-decoration: none;
}

.wrappercontroller {
    background: #ececec;
}
.wrappercontroller a {
    text-decoration: none;
}

.headerleftcontroll {
    float: left;
    width: 500px;
}
.headerrightcontroll {
    float: right;
}
.headerrightcontroll .controlblock {
    width: 140px;
    float: left;
}

.headerbutton {
    background: #dfdfdf;
    border: 1px solid #aaaaab;
    height: 38px;
    width: 116px;
    display: inline-block;
    line-height: 38px;
    text-align: center;
    margin-bottom: 8px;
    margin-right: 4px;
}
.headerbutton.buttonbig {
    height: 86px;
    line-height: 86px;
    float: left;
}
.headerbutton.green {
    background: #6eba5f;
    border: 1px solid #549a46;
}
.headerbutton.green a, .headerbutton.red a {
    font-weight: bold;
    color: #FFFFFF;
    font-size: 18px;
}
.headerbutton.red {
    background: #e14b4b;
    border: 1px solid red;
}
.headerbutton a {
    color: #595959;
    display: block;
    font-size: 14px;
}
.headerbutton a.clientlink {
    font-size: 12px;
    line-height: 20px;
}
.cartpreviewsumm {
    background: #6eba5f;
    height: 108px;
    padding: 0 20px;
    text-align: right;
    float: right;
}
.cartpreviewsumm.red {
    background: #e14b4b;
}
.cartpreviewsumm .cartupdate {
    padding-top: 30px;
}
.cartpreviewsumm .previewsumm {
    color: #282828;
    font-size: 14px;
}
.cartpreviewsumm .fullsumm {
    color: #FFFFFF;
    font-size: 26px;
}
.cartpreviewsumm .fullsumm span {
    font-weight: bold;
}
#ManagerWindow .headerbutton {
    cursor: pointer;
}

#enable:hover, #disable:hover, #delete:hover, #edit:hover{
    text-decoration: underline;
    cursor: pointer;
}

p{
    text-indent: 0;
}

#terms{
    margin-top: 20px;
}

.oneTerm{
    padding-bottom: 20px;
}

.oneTerm select, .oneTerm input{
    height: 30px;
    border-radius: 0;
    border: 1px solid #999;
    padding: 1px 0;
    margin-left: 10px;
    background: none;
    text-align: center;
}

.oneTerm input{
    text-align: left;
    padding-left: 10px;
}

.oneTerm img{
    max-height: 30px;
    position: absolute;
    margin-left: 10px;
}

#items td{
    padding: 10px 0;
}

#edit{
    display: none;
}
</style>


</body>
</html>