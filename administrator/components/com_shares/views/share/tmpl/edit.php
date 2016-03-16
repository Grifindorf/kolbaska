<?php

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$input = $app->input;

?>

<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'share.cancel')
        {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
        if (task == 'share.save')
        {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_shares&layout=edit&id=' . (int) $input->get('id')); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

    <div id="j-main-container" class="span12">
        <div id="j-main-container">

            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'base')); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'base', JText::_('Акция', true)); ?>

            <?php echo $this->form->renderFieldset('basic'); ?>

                <?php
                $db = JFactory::getDbo();
                $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = 0 and category_publish = 1 order by ordering ASC";
                $db->setQuery($query);
                $results = $db->loadObjectList();
                $vroption = '';
                $vrparentoption = '';
                foreach ($results as $res) {
                    $vrparentoption .= '<option value="'.$res->category_id.'" '.($res->category_id == $this->form->getData()->get('category_parent_id') ? 'selected="selected"':'' ).'>'.$res->{'name_ru-RU'}.'</option>';
                    $vroption .= '<option disabled="disabled">'.$res->{'name_ru-RU'}.'</option>';

                    $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res->category_id." and category_publish = 1 order by ordering ASC";
                    $db->setQuery($query);
                    $results1 = $db->loadObjectList();

                    foreach($results1 as $res1){
                        $vroption .= '<option disabled="disabled"> - '.$res1->{'name_ru-RU'}.'</option>';

                        $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = ".$res1->category_id." and category_publish = 1 order by ordering ASC";
                        $db->setQuery($query);
                        $results2 = $db->loadObjectList();
                        foreach($results2 as $res2){
                            $vroption .= '<option value="'.$res2->category_id.'" '.($res2->category_id == $this->form->getData()->get('category_banner_id') ? 'selected="selected"':'' ).'> - - '.$res2->{'name_ru-RU'}.'</option>';
                        }
                    }

                }
                ?>

            <script>
                jQuery('#jform_category_parent_id').append('<?php echo $vrparentoption; ?>');
                jQuery('#jform_category_banner_id').append('<?php echo $vroption; ?>');
                jQuery('#jform_type').on('change',function(){
                    if(this.value == 10){

                    }
                });
            </script>

            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'products', JText::_('Товары', true)); ?>

            <?php //echo $this->item->type; ?>

            <?php
            if ( $this->item->type != 0 ) {

                if ( $this->item->type == 1 ) {
                    include('functionskredit.php');
                }

                if ( $this->item->type == 4 ) {
                    include('functionsbonus.php');
                }

                if ( $this->item->type == 2 ) {
                    include('functionsvmeste.php');
                }

                if ( $this->item->type == 6 ) {
                    include('functionsprice.php');
                }

                if ( $this->item->type == 7 ) {
                    include('functionsdostavka.php');
                }

            }
            ?>

            <?php //include('include.php'); ?>

            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>



        </div>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
        <?php echo JHtml::_('form.token'); ?>
</form>