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

$db = JFactory::getDbo();

$query = "SELECT * FROM `#__jshopping_categories` WHERE `category_publish` = 1 AND `category_parent_id` = 0";
$db->setQuery($query);
$categories = $db->loadObjectList();

?>

<div class="dishes padd">
    <div class="container">
        <div class="default-heading">
            <h2>Прайс</h2>
            <div class="border"></div>
        </div>
        <div class="row">
<?php
foreach($categories as $category){
    ?>
            <div class="col-md-3 col-sm-6">
                <div class="dishes-item-container">
                    <div class="dish-details">
                        <a href="/<?=str_replace(' ','_',$category->{'alias_ru-RU'})?>.xls">
                            <div class="img-frame">
                                <img class="img-responsive" src="/components/com_jshopping/files/img_categories/<?=$category->category_image?>" />
                            </div>
                            <?php
                            echo $category->{'name_ru-RU'};
                            ?>
                            <br/>
                            <img src="/templates/kolbaska/images/updates-downloading-updates-icon.png" width="32" />
                            <?php echo round((filesize("/var/www/kolbaska/".str_replace(' ','_',$category->{'alias_ru-RU'}).".xls")/1024),2); ?> KB
                        </a>
                    </div>
                </div>
            </div>
    <?php
}
?>
       </div>
    </div>
</div>

