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

?>

<section class="col span_9_of_12 vr right">

    <!--<nav class="breadcrumbs">

        <?php
        $this->searchmodules = JModuleHelper::getModules('breadcrumbsvr');
        foreach ($this->searchmodules as $searchmodule)
        {
            $output = JModuleHelper::renderModule($searchmodule, array('style' => 'none'));
            $params = new JRegistry;
            $params->loadString($searchmodule->params);
            echo $output;
        }
        ?>

    </nav>-->

<h1 class="headline no-margin"><span>Акции и спецпредложения</span></h1>

        <?php if (empty($this->items)) { ?>

        <?php } else { ?>

                <?php foreach ($this->items as $i => $item) {
                    ?>
<section class="row">
    <div class="col span_5_of_12 tag-holder">
        <a href="<?php echo JRoute::_('index.php?option=com_shares&view=share&id='.$item->id); ?>">
            <img alt="" class="full-width special" src="<?php if (file_exists($item->image)) { echo $item->image; } else { echo '/images/actions/'.$item->image; }  ?>">
        </a>
    </div>
    <div class="col span_7_of_12">
        <h2 class="special-h2-title" style="margin-bottom: 0;"><?php echo $item->name; ?></h2>
        <span class="upper-text brand-color special">
            <span class="bold">
                До конца акции осталось
                <?php
                $datetime1 = new DateTime(date('Y-m-d'));
                $datetime2 = new DateTime($item->date_end);
                $interval = $datetime1->diff($datetime2);
                echo '<span style="font-size:16px;">'.$interval->format('%a').'</span> ';
                if ($interval->format('%a')==1 or $interval->format('%a')==21 or $interval->format('%a')==31 or $interval->format('%a')==41 or $interval->format('%a')==51 or $interval->format('%a')==61 or $interval->format('%a')==71 or $interval->format('%a')==81 or $interval->format('%a')==91) {
                    echo 'день';
                } elseif ( $interval->format('%a') <=4 && $interval->format('%a') >=2
                    or $interval->format('%a') <=24 && $interval->format('%a') >=22
                    or $interval->format('%a') <=34 && $interval->format('%a') >=32
                    or $interval->format('%a') <=44 && $interval->format('%a') >=42
                    or $interval->format('%a') <=54 && $interval->format('%a') >=52
                    or $interval->format('%a') <=64 && $interval->format('%a') >=62
                    or $interval->format('%a') <=74 && $interval->format('%a') >=72
                    or $interval->format('%a') <=84 && $interval->format('%a') >=82
                    or $interval->format('%a') <=94 && $interval->format('%a') >=92
                        ) {
                    echo 'дня';
                } else {
                    echo 'дней';
                }
                ?>
            </span>
        </span>
        <hr class="no-top-margin smallmargin">
        <div class="sharesdesc">
            <p style="margin: 0;"><?php echo $item->description; ?></p>
        </div>
        <a class="brand-color" href="<?php echo JRoute::_('index.php?option=com_shares&view=share&id='.$item->id); ?>">Подробнее</a>
    </div>
</section>
                <hr/>
                <?php } ?>

        <?php } ?>
        <?php echo $this->pagination->getPagesLinks(); ?>

</section>

<aside class="col span_3_of_12 panel left">
    <span class="title">Акции по категориям</span>
    <div class="panel-body">
        <ul class="special-left-menu">
        <?php
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = 0 and category_publish = 1 AND category_id NOT IN (1,2,3,5,1019) order by ordering ASC";
        $db->setQuery($query);
        $results = $db->loadObjectList();
        foreach ($results as $res) {
            echo '<li class="id' . $res->category_id . '">';
            echo '<img src="/components/com_jshopping/files/img_categories/' . $res->category_image . '" /><a href="'.JRoute::_('index.php?option=com_shares&view=shares&category_id='.$res->category_id).'?limitstart=0">'.$res->{'name_ru-RU'}.'</a>';
            echo '</li>';
        }
        echo '<li class="id' . $res->category_id . '">';
        echo '<img src="/images/all-special-li-img.png" /><a href="/action">Все акции</a>';
        echo '</li>';
        ?>
        </ul>
    </div>
</aside>