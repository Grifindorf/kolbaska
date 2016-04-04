<?php
defined('_JEXEC') or die;

/**
 * -------------------------------------------------------------------
 * Software:		eorisis Framework
 * @author		eorisis https://eorisis.com
 * @copyright	Copyright (C) 2012-2016 eorisis. All Rights Reserved.
 * @license		http://www.gnu.org/licenses/gpl.html GNU/GPL
 * -------------------------------------------------------------------
**/

//	Changes in version 1.2.0
//	Set new param names

function update_120($params)
{
	if ($params)
	{
		foreach ($params as $name => $value)
		{
			if (strpos($name, 'jq_lib_') === 0)
			{
				continue;
			}

			$new_name = null;

			if ($name == 'jquery_load_area')
			{
				$new_name = 'jq_lib_area';
			}
			elseif ($name == 'jquery_ui_load_area')
			{
				$new_name = 'ui_area';
			}
			else
			{
				if (strpos($name, 'jq_') === 0)
				{
					$new_name = str_replace('jq_', '', $name);
				}

				if (strpos($name, '_load_area') !== false)
				{
					if ($new_name)
					{
						$change_name = $new_name;
					}
					else
					{
						$change_name = $name;
					}

					$new_name = str_replace('_load_area', '_area', $change_name);
				}
				elseif ($name == 'ui_css_media')
				{
					if ($new_name)
					{
						$change_name = $new_name;
					}
					else
					{
						$change_name = $name;
					}

					$new_name = str_replace('css_media', 'css_media_type', $change_name);
				}
			}

			if ($new_name)
			{
				$params->$new_name = $params->$name;
				unset($params->$name);
			}
		}

		return $params;
	}
}

$this->ext_params = update_120($this->ext_params);
