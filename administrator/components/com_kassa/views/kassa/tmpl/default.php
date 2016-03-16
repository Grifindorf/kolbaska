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

    $saveOrderingUrl = 'index.php?option=com_kassa&task=kassa.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);

?>

<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'kassa.add')
        {
            window.location.href="/administrator/index.php?option=com_kassa&view=share&layout=edit";
        }else{
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
    }
</script>


<form action="<?php echo JRoute::_('index.php?option=com_kassa&view=kassa'); ?>" method="post" name="adminForm" id="adminForm">

</form>
