<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

?>

<ul itemtype="http://data-vocabulary.org/Breadcrumb" itemscope class="breadcrumb<?php echo $moduleclass_sfx; ?>">
    <?php
    if ($params->get('showHere', 1))
    {
        echo '<li class="active">' . JText::_('MOD_BREADCRUMBS_HERE') . '&#160;</li>';
    }
    else
    {
        //echo '<li class="active"><span class="divider icon-location"></span></li>';
    }

    // Get rid of duplicated entries on trail including home page when using multilanguage
    for ($i = 0; $i < $count; $i++)
    {
        if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i - 1]->link) && $list[$i]->link == $list[$i - 1]->link)
        {
            unset($list[$i]);
        }
    }

    // Find last and penultimate items in breadcrumbs list
    end($list);
    $last_item_key = key($list);
    prev($list);
    $penult_item_key = key($list);

    // Make a link if not the last item in the breadcrumbs
    $show_last = $params->get('showLast', 1);

    // Generate the trail
    foreach ($list as $key => $item) :
        if ($key != $last_item_key)
        {

            // Render all but last item - along with separator
            echo '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
            if (!empty($item->link))
            {
                echo '<a itemprop="url" href="' . $item->link . '" class="pathway"><span itemprop="title">' . $item->name . '</span></a>';
            }
            else
            {
                echo '<span itemprop="title">' . $item->name . '</span>';
            }

            if (($key != $penult_item_key) || $show_last)
            {
                //echo '<span class="divider">' . $separator . '</span>';
            }

            echo '</li>';
        }
        elseif ($show_last && !empty($item->link))
        {
            // Render last item if reqd.
            echo '<li itemprop="child" itemtype="http://data-vocabulary.org/Breadcrumb">';
            echo '<a itemprop="url" href="' . $item->link . '"><span itemprop="title">' . $item->name . '</span></a>';
            echo '</li>';
        }
        elseif ($show_last && empty($item->link))
        {
            // Render last item if reqd.
            echo '<li>';
            echo '<span itemprop="title">' . $item->name . '</span>';
            echo '</li>';
        }
    endforeach; ?>
</ul>
