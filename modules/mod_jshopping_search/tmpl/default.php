<script type = "text/javascript">
function isEmptyValue(value){
    var pattern = /\S/;
    return ret = (pattern.test(value)) ? (true) : (false);
}
</script>
<form id="search-form" name="searchForm" action="<?php print SEFLink("index.php?option=com_jshopping&controller=search&task=result&Itemid=144", 1);?>" method="post" class="form-inline search-form">
    <input type="hidden" name="setsearchdata" value="1">
    <input type = "hidden" name = "category_id" value = "<?php print $category_id?>" />
    <input type = "hidden" name = "search_type" value = "<?php print $search_type;?>" />
    <div class="input-group">
        <input autocomplete="off" name="search" id="search" maxlength="100" class="inputbox search-query form-control" type="text" size="40" value="Поиск.."  onblur="if (this.value=='') this.value='Поиск..';" onfocus="if (this.value=='Поиск..') this.value='';" />
        <span class="input-group-btn">
            <button class="btn btn-default" type="button" onclick="this.form.submit();"><i class="fa fa-search"></i></button>
        </span>
    </div>
    <div id="resSearch">

    </div>
</form>