<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Search component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_search
 * @since       1.5
 */
class SharesHelper
{
    public static $extension = 'com_shares';

    public static function filterText($text)
    {
        JLog::add('ContentHelper::filterText() is deprecated. Use JComponentHelper::filterText() instead.', JLog::WARNING, 'deprecated');

        return JComponentHelper::filterText($text);
    }
}
