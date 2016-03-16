$(document).ready(function() {
    $('#modalvideo .close').click(function(){
        $( '#modalvideoframe' ).attr( 'src', function ( i, val ) { return val; });
    });
	$('.btn-cart-md .cart-link').click(function(e){
		e.preventDefault();
        $('#shoppingcart1').modal('show');
	});
});

function centerModals($element) {
    var $modals;
    if ($element.length) {
        $modals = $element;
    } else {
        $modals = $('.modal-vcenter:visible');
    }
    $modals.each( function(i) {
        var $clone = $(this).clone().css('display', 'block').appendTo('body');
        var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
        top = top > 0 ? top : 0;
        $clone.remove();
        $(this).find('.modal-content').css("margin-top", top);
    });
}
$('.modal-vcenter').on('show.bs.modal', function(e) {
    centerModals($(this));
});
$(window).on('resize', centerModals);

/* *************************************** */ 
/* Tool Tip JS */
/* *************************************** */  

$('.my-tooltip').tooltip();

/* *************************************** */ 
/* Scroll to Top */
/* *************************************** */  
		
$(document).ready(function() {
	$(".totop").hide();
	
	$(window).scroll(function(){
	if ($(this).scrollTop() > 300) {
		$('.totop').fadeIn();
	} else {
		$('.totop').fadeOut();
	}
	});
	$(".totop a").click(function(e) {
		e.preventDefault();
		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	});
    if($( window ).width() > 1200) {
        var scroll = $(document).scrollTop();
        if (scroll > 105) {
            $('.navbar-nav .menuimage').hide();
            $('.navbar-nav .dropdown-menu img').show();
            $('.logo img').attr('style', 'max-width:150px;');
            $('.header').attr('style', 'position: fixed; top: 0;');
            $('.wrapper:eq(0)').attr('style','padding-top:150px;');
        }
    }

    $(document).on('change','.quantity',function(e){
        if(parseInt($(this).val()) > 0){
            $.ajax({
                type: 'POST',
                url: '/index.php?option=com_jshopping&controller=cart&task=refreshAjax',
                data: {quantity: parseInt($(this).val()), key: $(this).attr('attr-key')},
                success: function (data) {
                    UpdateCart(data);
                }
            });
        }else{
            $(this).val(1);
        }
    });

    $(document).on('change','.quantitycategory',function(e){
        if(parseInt($(this).val()) > 0){
            if($('#product-'+$(this).attr('attr-key')).hasClass('inCart')){
                $.ajax({
                    type: 'POST',
                    url: '/index.php?option=com_jshopping&controller=cart&task=refreshAjax',
                    data: {quantity: parseInt($(this).val()), key: $(this).attr('attr-key')},
                    success: function(data){
                        UpdateCart(data);
                    }
                });
            }
        }else{
            $(this).val(1);
        }
    });

});
/* *************************************** */

$(document).scroll(function(){
    if($( window ).width() > 1200){
        var scroll = $(document).scrollTop();
        if(scroll>105){
            $('.navbar-nav .menuimage').hide();
            $('.navbar-nav .dropdown-menu img').show();
            $('.logo img').attr('style','max-width:150px;');
            $('.header').attr('style','position: fixed; top: 0;');
            $('.header-top').addClass('small');
            $('.wrapper:eq(0)').attr('style','padding-top:150px;');
        }else{
            $('.navbar-nav .menuimage').show();
            $('.logo img').removeAttr('style');
            $('.header').removeAttr('style');
            $('.header-top').removeClass('small');
            $('.wrapper:eq(0)').removeAttr('style');
        }
    }
});

function deleteFromCart(key){
    $.ajax({
        type: 'GET',
        url: '/index.php?option=com_jshopping&controller=cart&task=deleteAjax&number_id='+key,
        success: function (data) {
            UpdateCart(data);
            if($('#product-'+key)){
                $('#product-'+key).removeClass('inCart');
                if($('#product-'+key).hasClass('shopping-item')){
                    $('#product-'+key+' .buttonbuycategory').addClass('br-red');
                    $('#product-'+key+' .buttonbuycategory').removeClass('br-green');
                    $('#product-'+key+' .visible-xs a').addClass('br-red');
                    $('#product-'+key+' .visible-xs a').removeClass('br-green');
                    $('#product-'+key+' a.hidden-xs').html('Купить');
                    $('#product-'+key+' .visible-xs a').html('Купить');
                }
                if($('#product-'+key).hasClass('single-item-content')){
                    $('.shopping-item .btn-danger').addClass('br-red');
                    $('.shopping-item .btn-danger').removeClass('br-green');
                    $('.shopping-item .btn-danger').html('Купить');
                }
                $('#product-'+key+' .quantitycategory').val(1);
            }
        }
    });
}

function addToCart(id,type){
    if($('#product-'+id).hasClass('inCart') && type){
        $('#shoppingcart1').modal('show');
        return false;
    }
    $.ajax({
        type: 'POST',
        url: '/index.php?option=com_jshopping&controller=cart&task=addAjax&product_id='+id,
        data: {quantity:$('#product-'+id+' #quantity'+id).val()},
        success: function(data){
            if($('#product-'+id).hasClass('shopping-item')){
                $('#product-'+id+' .buttonbuycategory').removeClass('br-red');
                $('#product-'+id+' .buttonbuycategory').addClass('br-green');
                $('#product-'+id+' .visible-xs a').removeClass('br-red');
                $('#product-'+id+' .visible-xs a').addClass('br-green');
                $('#product-'+id+' a.hidden-xs').html('В корзине');
                $('#product-'+id+' .visible-xs a').html('В корзине');
            }
            if($('#product-'+id).hasClass('single-item-content')){
                $('.shopping-item .btn-danger').removeClass('br-red');
                $('.shopping-item .btn-danger').addClass('br-green');
                $('.shopping-item .btn-danger').html('В корзине');
            }
            UpdateCart(data);
            if(!$('#product-'+id).hasClass('inCart')){
                $('#product-'+id).addClass('inCart');
            }
        }
    });
};

function changeQtty(id,type){
    var change = 0;
    if(type=='plus'){
        $('#product-'+id+' #quantity'+id).val(parseInt($('#product-'+id+' #quantity'+id).val())+1);
        change = 1;
    }
    if(type=='minus'){
        if($('#product-'+id+' #quantity'+id).val()>1){
            $('#product-'+id+' #quantity'+id).val(parseInt($('#product-'+id+' #quantity'+id).val())-1);
            change = 1;
        }
    }
    if($('#product-'+id).hasClass('inCart') && change > 0){
        addToCart(id,false);
    }
}

function UpdateCart(data){
    $.ajax({
        type: 'POST',
        url: '/index.php?option=com_jshopping&controller=cart&task=refreshAjax',
        data: {},
        success: function(data){
            var text;
            var img, link, name, count, price, tprice, alink;
            var responce = JSON.parse(data);
            if(responce.price_product > 0){
                var summcart = responce.price_product.toFixed(2);
            }else{
                var summcart = 0;
            }
            if(responce.price_product_opt>0){
            }else{
                responce.price_product_opt=0;
            }

            $('#jshop_module_cart .cart-link span').html(summcart+' грн.');
            $('#jshop_module_cart .cart-dropdown li.delete').remove();
            $('#counttooptcart').html(parseInt(responce.price_product_opt).toFixed(2));
            if(responce.type_cart_price == 3){
                $('#topcartinfoopt').text('Минимальная сумма заказа 500грн.');
                $('#summoptfromrozn').text('Минимальная сумма заказа 500грн.');
                $('#oneclickorderbutton').addClass('disabled');
                $('#fullclickorderbutton').addClass('disabled');
            }
            if(responce.type_cart_price == 2){
                $('#topcartinfoopt').text('Вы покупаете по розничным ценам');
                $('#summoptfromrozn').text('Для пересчета заказа на оптовые цены нужно добавить товара на сумму '+(1500-responce.price_product_opt).toFixed(2)+' грн.');
                $('#oneclickorderbutton').removeClass('disabled');
                $('#fullclickorderbutton').removeClass('disabled');
                //$('#counttooptcarttext').show();
            }
            if(responce.type_cart_price == 1){
                $('#topcartinfoopt').text('Вы покупаете по оптовым ценам');
                $('#summoptfromrozn').text('Вы покупаете по оптовым ценам');
                $('#oneclickorderbutton').removeClass('disabled');
                $('#fullclickorderbutton').removeClass('disabled');
                //$('#counttooptcarttext').hide();
            }
            text = '';
            text = text + '<li class="delete"><div class="cart-item modulecart">В вашей корзине <div class="countpproducts">'+responce.count_product+'</div> товаров</div></li>';
            $('#jshop_module_cart .cart-dropdown').prepend(text);
            
            text = '';
            $.each(responce.products,function(key,value){
                text = text + '<tr class="delete">';
                $.each(value,function(keyy,valuee){
                    if(keyy == 'thumb_image'){
                        img = '<img class="img-responsive img-rounded" src="http://kolbaska.com.ua/components/com_jshopping/files/img_products/'+valuee+'" alt="" />';
                    }
                    if(keyy == 'href'){
                        link = valuee;
                    }
                    if(keyy == 'product_name'){
                        name = valuee;
                    }
                    if(keyy == 'quantity'){
                        count = valuee;
                    }
                    if(responce.type_cart_price == 1){
                        if(keyy == 'price'){
                            price = valuee;
                        }
                    }else{
                        if(keyy == 'price_rozn'){
                            price = valuee;
                        }
                    }
                });
                text = text + '<td width="180px"><a href="'+link+'">'+img+'</a></td>';
                text = text + '<td width="180px"><a href="'+link+'">'+name+'</a></td>';
                text = text + '<td width="140px"><span class="btn btn-default btn-sm" onclick="minusQuantity('+key+')">&nbsp;-</span><input type="text" class="quantity" attr-key="'+key+'" id="quantity'+key+'" value="'+count+'"><span class="btn btn-default btn-sm" onclick="plusQuantity('+key+')">+</span></td>';
                text = text + '<td width="140px">'+(count*price).toFixed(2)+' грн</td>';
                text = text + '<td width="140px"><span onclick="deleteFromCart('+key+')" class="btn btn-default">Удалить</span></td>';
                text = text + '</tr>';
            });

            $('#shoppingcart1 span.summ').html(summcart);
            $('#shoppingcart1 tr.delete').remove();
            $('#shoppingcart1 tbody').prepend(text);
        }
    });
}

function minusQuantity(key){
    $.ajax({
        type: 'POST',
        url: '/index.php?option=com_jshopping&controller=cart&task=refreshAjax',
        data: {quantity: parseInt($('#quantity'+key).val()) - 1 , key: key},
        success: function(data){
            UpdateCart(data);
            if($('#product-'+key).hasClass('shopping-item')){
                $('#product-'+key+' #quantity'+key).val( parseInt($('#quantity'+key).val()) - 1 );
            }
            if($('#product-'+key).hasClass('single-item-content')){
                $('#product-'+key+' #quantity'+key).val( parseInt($('#quantity'+key).val()) - 1 );
            }
        }
    });
}

function plusQuantity(key){
    $.ajax({
        type: 'POST',
        url: '/index.php?option=com_jshopping&controller=cart&task=refreshAjax',
        data: {quantity: parseInt($('#quantity'+key).val()) + 1 , key: key},
        success: function(data){
            UpdateCart(data);
            if($('#product-'+key).hasClass('shopping-item')){
                $('#product-'+key+' #quantity'+key).val( parseInt($('#quantity'+key).val()) + 1 );
            }
            if($('#product-'+key).hasClass('single-item-content')){
                $('#product-'+key+' #quantity'+key).val( parseInt($('#quantity'+key).val()) + 1 );
            }
        }
    });
}

function oneclickorder(){
    var phone = $('#telephone').val();
    if(phone.length>18){
        $.ajax({
            type: 'POST',
            url: '/index.php?option=com_jshopping&controller=checkout&task=step5saveAjax',
            data: {phone: phone},
            success: function(data){
                window.location.href = window.location.protocol+'//'+window.location.host+data;
            }
        });
    }else{
        $('#telephone').attr('style','border-color: red;')
    }
}

function checkorderpermission(type){
    $.ajax({
        type: 'POST',
        url: '/index.php?option=com_jshopping&controller=cart&task=refreshAjax',
        data: {},
        success: function(data){
            var responce = JSON.parse(data);
            if(responce.price_product >= 500){
                if(type==1){
                    oneclickorder();
                }
                if(type==2){
                    var datahref = $('#fullclickorderbutton').attr('data-href');
                    window.location.href = window.location.protocol+'//'+window.location.host+datahref;
                }
            }else{

            }

        }
    });
}

function savePrice(id){
    var price = parseFloat($('#product_original_price'+id).val());
    var price_opt = price + parseFloat((price/100)*7);
    var price_rozn = price_opt + parseFloat((price_opt/100)*15);
    var opt = price_opt.toFixed(2);
    $.ajax({
        type: 'POST',
        url: '/index.php?option=com_jshopping&controller=cart&task=savePrice',
        data: {id: id, price: price, price_opt: opt, price_rozn: price_rozn.toFixed(2)},
        success: function(data){
            //window.location.href = 'http://kolbaska.com.ua'+data;
        }
    });
}

function loadmoreproducts(id){
    var data_page = parseInt($('#buttonloadmoreproducts').attr('data-page'));
    var link = $('#buttonloadmoreproducts').attr('data-link');
    var data_total = parseInt($('#buttonloadmoreproducts').attr('data-total'));
    var page = data_page * 21;
    var page_next = page + 21;
    var page_new = data_page + 1;
    $.ajax({
        type: 'GET',
        url: link+'?start='+page+'&temp=ajax',
        data: {},
        success: function(data){
            $('.container .shopping-content .row').append(data);
            $('#buttonloadmoreproducts').attr('data-page',page_new);
            $('.cd-pagination li a.current:last-child').parent('li').next('li').children('a').addClass('current');
            if(page_next > data_total){
                $('#buttonloadmoreproducts').remove();
            }
        }
    });
}