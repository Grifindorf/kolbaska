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
        if (task == 'shares.cancel')
        {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
        if (task == 'shares.apply' && document.formvalidator.isValid(document.id('item-form')))
        {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_shares&layout=edit&id=' . (int) $input->get('id')); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

    <div id="j-main-container" class="span6">
        <div id="j-main-container">

            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'base')); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'base', JText::_('Акция', true)); ?>

            <?php echo $this->form->renderFieldset('basic'); ?>

            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'products', JText::_('Товары', true)); ?>


            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>



        </div>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
        <?php echo JHtml::_('form.token'); ?>
</form>