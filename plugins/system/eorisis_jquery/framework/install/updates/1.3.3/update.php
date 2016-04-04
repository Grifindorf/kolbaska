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

//	Changes in version 1.3.3
//	Set new param names/values

function update_133($params)
{
	if ($params)
	{
		$names = array(
			'jq_lib_source_specific',
			'migrate_source',
			'ui_source',
			'tb_source',
			'chosen_source',
			'easing_source'
		);

		foreach ($params as $name => $value)
		{
			if (in_array($name, $names))
			{
				if ($value == '0')
				{
					$params->$name = 2;
				}
			}
			else
			{
				switch ($name)
				{
					case 'jq_lib_custom_version_type':
					{
						if ($value == '2')
						{
							$params->$name = '0';
						}
					} break;

					case 'local_urls_js':
					{
						$params->local_urls = $params->$name;
						unset($params->$name);
					} break;

					case 'local_urls_css': unset($params->$name); break;
				}
			}
		}

		return $params;
	}
}

$this->ext_params = update_133($this->ext_params);
