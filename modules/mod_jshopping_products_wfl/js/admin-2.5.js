/*
# $Id: admin-2.5.js
# package mod_jshopping_products_wfl
# file admin.js
# author Aleksey M. Abrosimov wflab
# url http://wflab.ru
# copyright (C) 2013 Web Face Laboratory All rights reserved
# license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/
function disable(){
    Array.from(arguments).each(function(el){
        if($(el)) $(el).getParent().addClass('noDisplay');
    });
}
function enable(){
    Array.from(arguments).each(function(el){
        if($(el)) $(el).getParent().removeClass('noDisplay');
    });
}
window.addEvent('load',function(){
    $('jform_params_products_source').addEvent('change',function(el){
        switch(this.value){
            case 'random': // getRandProducts($count, $array_categories = null)
                disable('jformparamscategory_tree','list_for_select_prod','jform_params_labels_list','jformparamsmanuf_list');
                enable('jform_params_count_products');
                break;
            case 'latest': //getLastProducts($count, $array_categories = null)
                disable('jformparamscategory_tree','list_for_select_prod','jform_params_labels_list','jformparamsmanuf_list');
                enable('jform_params_count_products');
                break;
            case 'toprated': //getTopRatingProducts($count)
                disable('jformparamscategory_tree','list_for_select_prod','jform_params_labels_list','jformparamsmanuf_list');
                enable('jform_params_count_products');
                break;
            case 'bestsellers': //getBestSellers($count, $array_categories = null)
                disable('jformparamscategory_tree','list_for_select_prod','jform_params_labels_list','jformparamsmanuf_list');
                enable('jform_params_count_products');
                break;
            case 'label'://getProductLabel($label_id, $count)
                disable('jformparamscategory_tree','list_for_select_prod','jformparamsmanuf_list');
                enable('jform_params_count_products','jform_params_labels_list');
                break;
            case 'categories'://getAllProducts($filters, $order1 = null, $orderby1 = null, $limitstart = 0, $limit = 0) $filters['categorys'] = array
                disable('list_for_select_prod','jform_params_labels_list','jformparamsmanuf_list');
                enable('jform_params_count_products','jformparamscategory_tree');
                break;
            case 'id'://getAllProducts($filters, $order1 = null, $orderby1 = null, $limitstart = 0, $limit = 0) $filters['categorys'] = array
                disable('jform_params_count_products','jformparamscategory_tree','jform_params_labels_list','jformparamsmanuf_list');
                enable('list_for_select_prod');
                break;
            case 'manufacturer':
                disable('jformparamscategory_tree','list_for_select_prod','jform_params_labels_list');
                enable('jform_params_count_products','jformparamsmanuf_list');
            case 'manuf_logo':
                disable('jformparamscategory_tree','list_for_select_prod','jform_params_labels_list', 'jform_params_on_image_click_behavior');
                enable('jform_params_count_products','jformparamsmanuf_list');
            default:
                return false;
        }
    });
    $('jform_params_ribbon_behavior0').addEvent('click',function(ev){
        disable('jform_params_effect_speed','jform_params_effect_block');
    });
    $('jform_params_ribbon_behavior1').addEvent('click',function(ev){
        enable('jform_params_effect_speed','jform_params_effect_block');
    });
    if($('jform_params_ribbon_behavior0').getAttribute('checked')) disable('jform_params_effect_speed','jform_params_effect_block');
    if($('jform_params_ribbon_behavior1').getAttribute('checked')) enable('jform_params_effect_speed','jform_params_effect_block');

    $('jform_params_products_source').fireEvent('change');

    $('jform_params_count_products').addEvent('change',function(ev){if(this.value.trim() === '') this.value = 0});
    $('prod_search_btn').addEvent('click',function(){
        var text = $("prod_search").get('value');
        var url = 'index.php?option=com_jshopping&controller=products&task=search_related&start=0&no_id=0&text='+encodeURIComponent(text)+"&ajax=1";
        function showResponse(data){
            $("list_for_select_prod").set('html',data);
            var nCl = new Element('div',{'class':'p_close','text':'X'});
            nCl.inject($("list_for_select_prod"),'before');
            nCl.addEvent('click',function(ev){$("list_for_select_prod").setStyle('display','none'); this.destroy()});
            $$('.block_related_inner input').each(function(el){
                el.removeAttribute('onclick')
                el.addEvent('click',function(ev){
                    var curId = this.getParents('.block_related')[0].get('id').replace('serched_product_','');
                    var existVals = ($('jforms_prod_ids').get('value'))?$('jforms_prod_ids').get('value').split(','):[];
                    if(!existVals.contains(curId)) {
                        existVals.push(curId);
                        $('jforms_prod_ids').set('value',existVals.toString());
                    }
                });
            });
            $("list_for_select_prod").setStyle('display','block');
        }
        var nR = new Request.HTML({url:url,onComplete:function(tree,els,html){showResponse(html)}}).get();
    });

});
