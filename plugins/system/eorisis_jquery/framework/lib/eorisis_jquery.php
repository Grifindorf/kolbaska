<?php
defined('_JEXEC') or die;

/**
 * ------------------------------------------------------------------
 * Software:		eorisis Framework
 * @author		eorisis https://eorisis.com
 * @copyright	Copyright (C) 2012-2016 eorisis. All Rights Reserved.
 * ------------------------------------------------------------------
**/

class eorisis_jquery
{
	protected static $loaded = array(
		'lib' => null,
		'noconflict' => null
	);

	//	--------------------------------------------------

	public static function set_loaded($key, $value)
	{
		if (in_array($key, array('lib', 'noconflict')))
		{
			$value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
			self::$loaded[$key] = $value;
		}
	}

	//	--------------------------------------------------

	public static function loaded($value = null)
	{
		if ($value)
		{
			return self::$loaded[$value];
		}

		return (object)self::$loaded;
	}

	//	--------------------------------------------------

	public static function auto_version($elem, $version_default, $version_latest, $j3)
	{
		if ($j3)
		{
			//	https://eorisis.com/blog/javascript-frameworks
			switch ($elem)
			{
				//	--------------------------------------------------
				//	jQuery library (Since Joomla 3.0.0)

				case 'jq_lib':
					//	jQuery 1.11.3 : Joomla 3.4.4-RC2 to 3.5.0
					//	jQuery 1.11.2 : Joomla 3.4.0-Beta1 to 3.4.4-RC
					//	jQuery 1.11.1 : Joomla 3.3.1 to 3.4.0-Alpha
					//	jQuery 1.11.0 : Joomla 3.2.3 to 3.3.0
					//	jQuery 1.10.2 : Joomla 3.2.0 to 3.2.2
					//	jQuery 1.8.3  : Joomla 3.1.0 to 3.1.6
					//	jQuery 1.8.1  : Joomla 3.0.0 to 3.0.4
					if (version_compare(JVERSION, '3.4.4-rc2', '>='))
					{
						return $version_default; // jQuery 1.11.3
					}
					elseif (version_compare(JVERSION, '3.4.0-beta1', '>=') and
							version_compare(JVERSION, '3.4.4-rc', '<='))
					{
						return '1.11.2'; // Joomla 3.4.0-beta1 to 3.4.4-rc
					}
					elseif (version_compare(JVERSION, '3.3.1', '>=') and
							version_compare(JVERSION, '3.4.0-alpha', '<='))
					{
						return '1.11.1'; // Joomla 3.3.1 to 3.4.0-alpha
					}
					elseif (version_compare(JVERSION, '3.2.3', '>=') and
							version_compare(JVERSION, '3.3.0', '<='))
					{
						return '1.11.0'; // Joomla 3.2.3 to 3.3.0
					}
					elseif (version_compare(JVERSION, '3.2.0', '>=') and
							version_compare(JVERSION, '3.2.2', '<='))
					{
						return '1.10.2'; // Joomla 3.2.0 to 3.2.2
					}
					elseif (version_compare(JVERSION, '3.1.0', '>=') and
							version_compare(JVERSION, '3.1.6', '<='))
					{
						return '1.8.3'; // Joomla 3.1.0 to 3.1.6
					}
					elseif (version_compare(JVERSION, '3.1.0', '<'))
					{
						return '1.8.1'; // Joomla 3.0.0 to 3.0.4
					}

				//	--------------------------------------------------
				//	jQuery Migrate (Since Joomla 3.2.0)

				case 'migrate':
					//	jQuery Migrate 1.2.1 : Joomla 3.2.0 to 3.5.0
					//	Note: Joomla 3.0.0 to 3.1.6 has no Migrate in the core because it uses jQuery library up to version 1.8.3
					if (version_compare(JVERSION, '3.2.0', '>='))
					{
						return $version_default; // jQuery Migrate 1.2.1
					}

				//	--------------------------------------------------
				//	jQuery UI (Since Joomla 3.0.0)

				case 'ui':
					//	jQuery UI 1.9.2 (Joomla 3.2.0 to 3.5.0)
					//	jQuery UI 1.8.23 (Joomla 3.0.0 to 3.1.6)
					if (version_compare(JVERSION, '3.2.0', '>='))
					{
						return $version_default; // UI 1.9.2
					}
					elseif (version_compare(JVERSION, '3.2.0', '<'))
					{
						return '1.8.23'; // Joomla 3.0.0 to 3.1.6
					}

				//	--------------------------------------------------
				//	Twitter Bootstrap (Since Joomla 3.0.0)

				case 'tb':
					//	Bootstrap 2.3.2 [Custom Modified] (Joomla 3.1.4 to 3.5.0)
					//	Bootstrap 2.1.0 [Custom Modified] (Joomla 3.0.0 to 3.1.1)

					//	Bootstrap 2.3.2 appears to work fine in all cases
					return $version_default; // Twitter Bootstrap 2.3.2

				//	--------------------------------------------------
				//	Chosen (Since Joomla 3.0.0)

				case 'chosen':
					//	Chosen 0.14.0 [Custom Modified] (Joomla 3.2.3 to 3.5.0)
					//	Chosen 0.9.8 [Custom Modified] (Joomla 3.0.0 to 3.2.2)

					//	Chosen 0.9.12 appears to work fine in all cases
					return $version_default; // Chosen 0.9.12
			}
		}

		return $version_latest;
	}
}
