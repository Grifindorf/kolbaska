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

//	Changes in version 1.3.0
//	Set new param names/values

function update_130($params)
{
	if ($params)
	{
		$names = array(
			'local_urls_js',
			'local_urls_css'
		);

		foreach ($params as $name => $value)
		{
			$new_value = null;

			if (in_array($name, $names))
			{
				switch ($value)
				{
					case '0': $new_value = 1; break;
					case '1': $new_value = 2; break;
					case '2': $new_value = 3; break;
					default: $new_value = 1;
				}
				$params->$name = $new_value;
			}
			else
			{
				switch ($name)
				{
					case 'jq_lib_version_custom':
					{
						$params->jq_lib_custom_version = $params->$name;
						unset($params->$name);
					} break;

					case 'jq_lib_version_custom_type':
					{
						$params->jq_lib_custom_version_type = $params->$name;
						unset($params->$name);
					} break;

					case 'ui_elem':
					{
						if ($value == 'js_css')
						{
							$params->$name = 'all';
						}
					} break;
				}
			}
		}

		return $params;
	}
}

$this->ext_params = update_130($this->ext_params);
