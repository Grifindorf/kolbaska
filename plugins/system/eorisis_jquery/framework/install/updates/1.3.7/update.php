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

//	Changes in version 1.3.7
//	Set new param names/values

function update_137($params)
{
	if ($params)
	{
		foreach ($params as $name => $value)
		{
			switch ($name)
			{
				case 'jq_lib_version_choice':
				{
					switch ($value)
					{
						case '0': $new_value = 1; break;
						case '1': $new_value = 2; break;
						case '2': $new_value = 3; break;
						default: $new_value = 1;
					}
					$params->$name = $new_value;
				} break;

				case 'jq_lib_source_specific':
				{
					$params->jq_lib_source = $params->$name;
					unset($params->$name);
				} break;

				case 'jq_lib_source_specific_cdn':
				{
					$params->jq_lib_source_cdn = $params->$name;
					unset($params->$name);
				} break;
			}

			//	Set to 'Spesific' and not to the default 'Auto' for users that update
			$params->ui_version_choice = 2;
			$params->tb_version_choice = 2;
			$params->chosen_version_choice = 2;
		}

		return $params;
	}
}

$this->ext_params = update_137($this->ext_params);
