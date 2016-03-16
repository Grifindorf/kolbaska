<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

/*
 <?php echo JHtml::tooltipText('COM_SEARCH_SEARCH_IN_PHRASE'); ?>
<?php echo JRoute::_('index.php?option=com_search&filter_results=0');?>
<?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
 */

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');

$listDirn	= 'DESC';
$listOrder  = 'a.ordering';
$saveOrder	= $listOrder == 'a.ordering';

    $saveOrderingUrl = 'index.php?option=com_shares&task=shares.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);

?>

<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'shares.add')
        {
            window.location.href="/administrator/index.php?option=com_shares&view=share&layout=edit";
        }else{
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
    }
</script>


<form action="<?php echo JRoute::_('index.php?option=com_shares&view=shares'); ?>" method="post" name="adminForm" id="adminForm">

    <div id="j-main-container">

        <?php
        // Search tools bar
        echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
        ?>

        <?php if (empty($this->items)) { ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
        <?php } else { ?>
            <table class="table table-striped" id="articleList">
                <thead>
                <tr>
                    <th>
                        Порядок
                    </th>
                    <th width="1%" class="hidden-phone">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th width="1%" style="min-width:55px" class="nowrap center">
                        <?php echo JText::_('JSTATUS'); ?>
                    </th>
                    <!--<th>
                        Назначить товары
                    </th>-->
                    <th>
                        <?php echo JText::_('JGLOBAL_TITLE'); ?>
                    </th>

                    <th width="10%" class="nowrap hidden-phone">
                        Дата старта
                    </th>
                    <th width="10%" class="nowrap hidden-phone">
                        Дата окончания
                    </th>

                    <th width="1%" class="nowrap hidden-phone">
                        <?php echo JText::_('JGRID_HEADING_ID'); ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->items as $i => $item) {
                    ?>
                    <tr class="row<?php echo $i % 2; ?>" ">
                    <td class="order nowrap center hidden-phone">
                        <span class="sortable-handler">
								<i class="icon-menu"></i>
							</span>
                            <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
                    </td>
                        <td class="center hidden-phone">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td class="center">
                            <div class="btn-group">
                                <?php if($item->enabled==1){?>
                                    <a title="" onclick="document.getElementById('cb<?php echo $i;?>').checked = true;Joomla.submitbutton('shares.unpublish')" href="javascript:void(0);" class="btn btn-micro hasTooltip" data-original-title="Снять с публикации"><i class="icon-publish"></i></a>
                                <?php } else { ?>
                                    <a title="" onclick="document.getElementById('cb<?php echo $i;?>').checked = true;Joomla.submitbutton('shares.publish')" href="javascript:void(0);" class="btn btn-micro hasTooltip" data-original-title="Опубликовать"><i class="icon-unpublish"></i></a>
                                <?php } ?>
                            </div>
                        </td>
                    <!--<td class="center hidden-phone">
                        <a href="<?php echo JRoute::_('index.php?option=com_shares&task=products.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
                            Назначить товары</a>
                    </td>-->
                        <td class="has-context">
                            <div class="pull-left">

                                    <a href="<?php echo JRoute::_('index.php?option=com_shares&task=share.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
                                        <?php echo $this->escape($item->name); ?></a>

                                <span class="small">
									<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->url)); ?>
								</span>
                            </div>
                        </td>

                        <td class="nowrap small hidden-phone">
                            <?php echo JHtml::_('date', $item->date_start, JText::_('DATE_FORMAT_LC4')); ?>
                        </td>
                        <td class="nowrap small hidden-phone">
                            <?php echo JHtml::_('date', $item->date_end, JText::_('DATE_FORMAT_LC4')); ?>
                        </td>

                        <td class="center hidden-phone">
                            <?php echo (int) $item->id; ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
        <?php echo $this->pagination->getListFooter(); ?>

    </div>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
