<?php
defined('_JEXEC') or die;

/**
 * -------------------------------------------------------------------
 * Software:			eorisis jQuery
 * Software Type:	Joomla! System Plugin
 * 
 * @author		eorisis https://eorisis.com
 * @copyright	Copyright (C) 2012-2016 eorisis. All Rights Reserved.
 * @license		GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * 
 * 'eorisis jQuery' is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * See /misc/licence.txt
 * -------------------------------------------------------------------
**/

if (!version_compare(JVERSION, 3, '>=')) { jimport('joomla.plugin.plugin'); }

class plgSystemEorisis_jQuery extends JPlugin
{
	protected $lib_path;
	protected $J3;
	protected $doc;
	protected $web_root;
	protected $web_root_relative;
	protected $scheme;
	protected $is_html;
	protected $is_admin;
	protected $scripts;
	protected $stylesheets;
	protected $external_url;
	protected $media_url;
	protected $main_url;
	protected $url;
	protected $cdn;
	protected $loaded_media = array();
	protected $head;
	protected $css_media_type = false;
	protected $css_first;
	protected $css_last = array();
	protected $custom_cdn;

	//	--------------------------------------------------

	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->lib_path = JPATH_SITE.'/plugins/'.$config['type'].'/'.$config['name'].'/framework/lib/';
	}

	//	--------------------------------------------------

	public function onAfterInitialise()
	{
		$this->doc = JFactory::getDocument();
		$this->is_html = ($this->doc->getType() == 'html');

		if (!$this->is_html)
		{
			return;
		}

		require_once($this->lib_path.'eorisis_jquery.php');
		$this->J3 = version_compare(JVERSION, 3, '>=');
		$this->is_admin = JFactory::getApplication()->isAdmin();
		$this->set_urls();

		//	--------------------------------------------------
		//	Load Library and Scripts in Order

		//	jQuery Library, Migrate
		$this->jquery_lib('1.11.3','1.12.2', '1.2.1','1.4.0'); // $lib_version_default, $lib_version_latest, $migrate_version_default, $migrate_version_latest

		//	All scripts depend on jQuery library
		//	They will not load if the lib is not loaded in the same area
		if ($this->url->jq_lib)
		{
			$this->ui('1.9.2', '1.11.4'); // default, latest
			$this->chosen('0.9.12', '1.5.1'); // default, latest
			$this->easing();
			$this->custom_media();
			$this->twitter_bootstrap('2.3.2', '3.3.6'); // default, latest
			$this->no_conflict();
		}
	}

	//	--------------------------------------------------

	public function onBeforeCompileHead()
	{
		if (!$this->is_html)
		{
			return;
		}

		$this->css_order_last();
		$this->head = $this->doc->getHeadData();
		$this->scripts = $this->head['scripts'];

		$remove = $this->remove_media();
		$remove = $this->remove_media_other($remove);
		$this->head_reset($remove);
	}

	//	--------------------------------------------------

	protected function jquery_lib($lib_version_default, $lib_version_latest, $migrate_version_default, $migrate_version_latest)
	{
		if (!$this->area('jq_lib', 1))
		{
			return;
		}

		//	--------------------------------------------------

		$type = '.min';
		switch ($this->params->get('jq_lib_version_choice', 1))
		{
			case 1: // Auto
				$lib_version = eorisis_jquery::auto_version('jq_lib', $lib_version_default, $lib_version_latest, $this->J3); // $version_default, $version_latest
				$url_local = $this->media_url.'lib/jquery-'.$lib_version.'.min.js';
				break;

			case 2: // Specific
				$lib_version = $this->clean_version('jq_lib', $lib_version_default);
				$url_local = $this->media_url.'lib/jquery-'.$lib_version.'.min.js';
				break;

			case 3: // Custom
				$lib_version = $this->clean_version('jq_lib_custom', $lib_version_default);
				if (!$this->params->get('jq_lib_custom_version_type', 1))
				{
					$type = '';
				}
				$url_local = $this->custom_local_path('jq_lib', 'jquery-'.$lib_version.$type.'.js');
				break;
		}

		$url = null;

		//	Source: CDN
		if ($lib_version and ($this->params->get('jq_lib_source', 1) == 1))
		{
			$default = $this->cdn->jquery.'jquery-'.$lib_version.$type.'.js';
			switch ($this->params->get('jq_lib_source_cdn', 1))
			{
				case 1: $url = $default; break;
				case 2: $url = $this->cdn->google.'jquery/'.$this->version_correction($lib_version).'/jquery'.$type.'.js'; break;
				case 3: $url = $this->cdn->cloudflare.'jquery/'.$this->version_correction($lib_version).'/jquery'.$type.'.js'; break;
				default: $url = $default;
			}

			$url = $this->cdn_fallback('jq_lib', $url, $url_local);
		}
		else // Source: Local
		{
			$url = $url_local;
		}

		if (!$url)
		{
			return;
		}

		$this->url->jq_lib = $url;
		$this->load('js', $url);
		eorisis_jquery::set_loaded('lib', true);

		//	--------------------------------------------------
		//	Migrate

		$migrate = $this->params->get('migrate', 1);
		if (($migrate == 2) or // Always Load
			(($migrate == 1) and version_compare($lib_version, '1.9.0', '>='))) // Auto Load
		{
			if ($this->params->get('migrate_version_choice', 1) == 1) // Auto
			{
				$migrate_version = eorisis_jquery::auto_version('migrate', $migrate_version_default, $migrate_version_latest, $this->J3);
			}
			else // Specific
			{
				$migrate_version = $this->clean_version('migrate', $migrate_version_default);
			}

			$url_local = $this->media_url.'plugins/migrate/jquery-migrate-'.$migrate_version.'.min.js';

			//	Source: CDN
			if ($this->params->get('migrate_source', 1) == 1)
			{
				$default = $this->cdn->jquery.'jquery-migrate-'.$migrate_version.'.min.js';
				switch ($this->params->get('migrate_source_cdn', 1))
				{
					case 1: $url = $default; break;
					case 2: $url = $this->cdn->cloudflare.'jquery-migrate/'.$migrate_version.'/jquery-migrate.min.js'; break;
					default: $url = $default;
				}

				$url = $this->cdn_fallback('migrate', $url, $url_local);
			}
			else // Source: Local
			{
				$url = $url_local;
			}

			$this->url->migrate = $url;
			$this->load('js', $url);
		}
	}

	//	--------------------------------------------------

	protected function ui($version_default, $version_latest)
	{
		if (!$this->area('ui', 0))
		{
			return;
		}

		$elem = $this->params->get('ui_elem', 'js');
		$file_js = null;
		$file_css = null;
		$url_js = null;
		$url_css = null;

		//	Use Preset UI Files
		if (!$this->params->get('ui_custom', 0))
		{
			if ($this->params->get('ui_version_choice', 1) == 1) // Auto
			{
				$version = eorisis_jquery::auto_version('ui', $version_default, $version_latest, $this->J3);
			}
			else // Specific
			{
				$version = $this->clean_version('ui', $version_default);
			}

			$file_min = 'jquery-ui.min';

			if ($elem != 'css')
			{
				$file_js = $file_min.'.js';
				$url_js_part = $version.'/'.$file_js;
				$url_js_local = $this->media_url.'ui/'.$url_js_part;
			}

			if ($elem != 'js')
			{
				$theme_default = 'ui-lightness';
				$theme = preg_replace('/[^a-z-]+/', '', $this->params->get('ui_theme', $theme_default));

				//	UI versions below 1.10.1 ..
				if (version_compare($version, '1.10.1', '<='))
				{
					if ($theme == 'base') // ..have a 'base' theme
					{
						$file_css = 'minified/'.$file_min; // It's minified css file is inside a /minified/ dir
					}
					else
					{
						//	UI versions below 1.10.0 have no minified CSS theme files (except the 'base' theme which is checked above)
						if (version_compare($version, '1.10.0', '<='))
						{
							$file_css = 'jquery-ui';
						}
						else
						{
							$file_css = $file_min;
						}
					}
				}
				else
				{
					$file_css = $file_min;
					if ($theme == 'base')
					{
						//	UI version 1.11.3 brought back the 'base' theme
						//	UI version 1.11.4 removed the 'base' theme again
						//	So versions 1.11.4, or 1.11.2 and older will use the default
						if (($version == '1.11.4') or version_compare($version, '1.11.2', '<='))
						{
							$theme = $theme_default;
						}
					}
				}

				$url_css_part = $version.'/themes/'.$theme.'/'.$file_css.'.css';
				$url_css_local = $this->media_url.'ui/'.$url_css_part;
			}

			//	--------------------------------------------------

			//	Source: CDN
			if ($this->params->get('ui_source', 1) == 1)
			{
				$default = $this->cdn->jquery.'ui/';
				switch ($this->params->get('ui_source_cdn', 1))
				{
					case 1: $main_url = $default; break;
					case 2: $main_url = $this->cdn->google.'jqueryui/'; break;
					default: $main_url = $default;
				}

				if ($file_js)
				{
					$url_js = $main_url.$url_js_part;
					$url_js = $this->cdn_fallback('ui', $url_js, $url_js_local);
				}

				if ($file_css)
				{
					$url_css = $main_url.$url_css_part;
					$url_css = $this->cdn_fallback('ui', $url_css, $url_css_local);
				}
			}
			else // Source: Local
			{
				if ($file_js) { $url_js = $url_js_local; }
				if ($file_css) { $url_css = $url_css_local; }
			}
		}
		else // Use Custom UI Files
		{
			$url_js = $this->urls('js', $this->params->get('ui_custom_js'));
			$url_css = $this->urls('css', $this->params->get('ui_custom_css'));
		}

		//	--------------------------------------------------

		//	Load JS
		if ($url_js and $this->url->jq_lib) 
		{
			$this->url->ui_js = $url_js;
			$this->load('js', $url_js);
		}

		//	Load CSS
		if ($url_css)
		{
			$this->url->ui_css = $url_css;
			$this->css_order($url_css);
		}
	}

	//	--------------------------------------------------

	protected function twitter_bootstrap($version_default, $version_latest)
	{
		if (!$this->area('tb', 0))
		{
			return;
		}

		if ($this->params->get('tb_version_choice', 1) == 1) // Auto
		{
			$version = eorisis_jquery::auto_version('tb', $version_default, $version_latest, $this->J3);
		}
		else // Specific
		{
			$version = $this->clean_version('tb', $version_default);
		}

		$elem = $this->params->get('tb_elem', 'js');
		$dir = 'bootstrap/';
		$file_js = null;
		$file_css = null;
		$file_theme_css = null;

		if ($elem != 'css')
		{
			$file_js = 'bootstrap.min.js';
			$url_js_part = $version.'/js/'.$file_js;
			$url_js_local = $this->media_url.$dir.$url_js_part;
		}

		if ($elem != 'js')
		{
			if ($version == '2.3.2')
			{
				$file_css = 'bootstrap-combined.min.css';
			}
			else // >= 3.0.0
			{
				$file_css = 'bootstrap.min.css';

				if ($this->params->get('tb_theme', 0))
				{
					$file_theme_css = 'bootstrap-theme.min.css';
					$url_theme_css_part = $version.'/css/'.$file_theme_css;
					$url_theme_css_local = $this->media_url.$dir.$url_theme_css_part;
				}
			}

			$url_css_part = $version.'/css/'.$file_css;
			$url_css_local = $this->media_url.$dir.$url_css_part;
		}

		//	Source: CDN
		if ($this->params->get('tb_source', 1) == 1)
		{
			$default = $this->cdn->bootstrap_maxcdn;
			switch ($this->params->get('tb_source_cdn', 1))
			{
				case 1: $main_url = $default; break;
				case 2: $main_url = $this->cdn->bootstrap_netdna; break;
				default: $main_url = $default;
			}

			if ($file_js)
			{
				$url_js = $main_url.$url_js_part;
				$url_js = $this->cdn_fallback('tb', $url_js, $url_js_local);
			}

			if ($file_css)
			{
				$url_css = $main_url.$url_css_part;
				$url_css = $this->cdn_fallback('tb', $url_css, $url_css_local);

				if ($file_theme_css)
				{
					$url_theme_css = $main_url.$url_theme_css_part;
					$url_theme_css = $this->cdn_fallback('tb', $url_theme_css, $url_theme_css_local);
				}
			}
		}
		else // Source: Local
		{
			if ($file_js)
			{
				$url_js = $url_js_local;
			}

			if ($file_css)
			{
				$url_css = $url_css_local;

				if ($file_theme_css)
				{
					$url_theme_css = $url_theme_css_local;
				}
			}
		}

		//	--------------------------------------------------

		//	Load JS
		if ($file_js and $this->url->jq_lib)
		{
			$this->url->tb_js = $url_js;
			$this->load('js', $url_js);
		}

		//	Load CSS
		if ($file_css)
		{
			$this->url->tb_css = $url_css;
			$this->css_order($url_css);

			if ($file_theme_css)
			{
				$this->url->tb_theme_css = $url_theme_css;
				$this->css_order($url_theme_css);
			}
		}
	}

	//	--------------------------------------------------

	protected function chosen($version_default, $version_latest)
	{
		if (!$this->area('chosen', 0))
		{
			return;
		}

		if ($this->params->get('chosen_version_choice', 1) == 1) // Auto
		{
			$version = eorisis_jquery::auto_version('chosen', $version_default, $version_latest, $this->J3);
		}
		else // Specific
		{
			$version = $this->clean_version('chosen', $version_default);
		}

		$url_part = 'chosen/'.$version.'/chosen.jquery.min.js';
		$url_local = $this->media_url.'plugins/'.$url_part;

		//	Source: CDN
		if ($this->params->get('chosen_source', 1) == 1)
		{
			$url = $this->cdn->cloudflare.$url_part;
			$url = $this->cdn_fallback('chosen', $url, $url_local);
		}
		else // Source: Local
		{
			$url = $url_local;
		}

		$this->url->chosen_js = $url;
		$this->load('js', $url);
	}

	//	--------------------------------------------------

	protected function easing()
	{
		if (!$this->area('easing', 0))
		{
			return;
		}

		$file = 'jquery.easing.min.js';
		$url_local = $this->media_url.'plugins/easing/'.$file;

		//	Source: CDN
		if ($this->params->get('easing_source', 1) == 1)
		{
			$url = $this->cdn->cloudflare.'jquery-easing/1.3/'.$file;
			$url = $this->cdn_fallback('easing', $url, $url_local);
		}
		else // Source: Local
		{
			$url = $url_local;
		}

		$this->load('js', $url);
	}

	//	--------------------------------------------------

	protected function custom_media()
	{
		if (!$this->area('custom', 0))
		{
			return;
		}

		foreach (array('js', 'css') as $type)
		{
			if ($this->params->get('custom_'.$type, 0))
			{
				if ($url = $this->urls($type, $this->params->get('custom_'.$type.'_urls')))
				{
					$media = ($type == 'js') ? $this->load($type, $url) : $this->css_order($url);
				}
			}
		}
	}

	//	--------------------------------------------------

	protected function no_conflict()
	{
		$nc = $this->params->get('no_conflict', 1);
		if (!$nc or (($nc == 1) and !$this->url->jq_lib))
		{
			return;
		}

		$url = $this->media_url.'jquery-noconflict.js';
		$this->url->noconflict = $url;
		$this->load('js', $url);
		eorisis_jquery::set_loaded('noconflict', true);
	}

	//	--------------------------------------------------

	protected function area($field, $default)
	{
		$area = $this->params->get($field.'_area', $default);
		if (($area == 3) or
			(($area == 1) and !$this->is_admin) or
			(($area == 2) and $this->is_admin))
		{
			return true;
		}
	}

	//	--------------------------------------------------

	protected function head_isset($files, $jui)
	{
		foreach ($files as $file)
		{
			if (isset($this->scripts[$jui.'js/'.$file]))
			{
				return true;
			}
		}
	}

	//	--------------------------------------------------

	protected function head_reset($remove)
	{
		$changes = 0;
		$arr = array(
			'js' => 'scripts',
			'css' => 'styleSheets'
		);

		foreach ($arr as $type => $elem)
		{
			if (!empty($remove->$type))
			{
				$urls = $this->head[$elem];
				foreach ($remove->$type as $file)
				{
					unset($urls[$file]);
				}

				$this->head[$elem] = $urls;
				$changes++;
			}
		}

		if ($changes)
		{
			$this->doc->setHeadData($this->head);
		}
	}

	//	--------------------------------------------------

	protected function clean($data)
	{
		$data = trim($data);
		return filter_var($data, FILTER_SANITIZE_STRING);
	}

	//	--------------------------------------------------

	protected function valid_url($url)
	{
		if (filter_var($url, FILTER_VALIDATE_URL) !== false)
		{
			return $this->clean($url);
		}
	}

	//	--------------------------------------------------

	protected function valid_scheme($scheme, $default)
	{
		if (in_array($scheme, array('http', 'https')))
		{
			return $scheme;
		}

		return $default;
	}

	//	--------------------------------------------------

	protected function clean_version($field, $default)
	{
		$version = $this->params->get($field.'_version', $default);
		$version = preg_replace('/[^0-9a-zA-Z.-]+/', '', $version);
		$version = str_replace('..', '', $version);

		if ($version != '.')
		{
			return $version;
		}

		return '';
	}

	//	--------------------------------------------------

	protected function version_correction($version)
	{
		if (strlen($version) == 3)
		{
			$version .= '.0';
		}

		return $version;
	}

	//	--------------------------------------------------

	protected function cdn_fallback($field, $url, $url_local)
	{
		$fallback = false;
		if ($this->params->get($field.'_cdn_fallback', 0))
		{
			if (function_exists('curl_init'))
			{
				$cURL = curl_init($this->scheme.':'.$url);
				curl_setopt($cURL, CURLOPT_NOBODY, true);

				//	Send User-Agent with the request
				if ($this->params->get('curl_useragent', 0))
				{
					curl_setopt($cURL, CURLOPT_USERAGENT,
						$this->clean($this->params->get('curl_useragent_txt', 'CDN Fallback Check'))
					);
				}

				$result = curl_exec($cURL);
				if (!$result or
					($result and (curl_getinfo($cURL, CURLINFO_HTTP_CODE) !== 200)))
				{
					$fallback = true;
				}

				curl_close($cURL);
			}
		}

		if ($fallback)
		{
			return $url_local;
		}

		return $url;
	}

	//	--------------------------------------------------

	protected function custom_local_path($field, $filename)
	{
		if ($path = $this->params->get($field.'_custom_local_path'))
		{
			if (strpos($path, '.') === false)
			{
				$path = str_replace('\\', '/', $path);
				$path = trim($path, '/');

				$url = $path.'/'.$filename;
				if ($url = $this->clean_url($url))
				{
					if (!$this->external_url)
					{
						return $this->main_url.$url;
					}
				}
			}
		}
	}

	//	--------------------------------------------------

	protected function clean_scheme($url)
	{
		return str_replace(array('https://', 'http://'), '', $url);
	}

	//	--------------------------------------------------

	protected function scheme()
	{
		$scheme = $this->params->get('scheme', 'auto');
		$current = JURI::getInstance($this->web_root)->getScheme();

		if ($scheme == 'auto')
		{
			return $current;
		}

		return $this->valid_scheme($scheme, $current);
	}

	//	--------------------------------------------------

	protected function clean_domain($domain)
	{
		$domain = trim($domain, '/');
		$domain = filter_var($domain, FILTER_SANITIZE_URL);
		$domain = $this->clean_scheme($domain);

		if (strstr($domain, '/'))
		{
			$domain = substr($domain, 0, strpos($domain, '/'));
		}

		return strtolower($domain);
	}

	//	--------------------------------------------------

	protected function fqdn()
	{
		if ($this->params->get('domain_fqdn', 1) == 1)
		{
			return parse_url($this->web_root, PHP_URL_HOST);
		}

		return $this->clean_domain($this->params->get('domain_fqdn_custom'));
	}

	//	--------------------------------------------------

	protected function local_url()
	{
		$default = $this->web_root_relative; // Relative
		switch ($this->params->get('local_urls', 1))
		{
			case 1: return $default;
			case 2: return str_replace(array('https:', 'http:'), '', $this->web_root); // Scheme Relative
			case 3: return $this->web_root; // Absolute
			default: return $default;
		}
	}

	//	--------------------------------------------------

	protected function custom_cdn_url()
	{
		$default = '//'.$this->custom_cdn; // Scheme Relative
		switch ($this->params->get('custom_cdn_urls', 1))
		{
			case 1: return $default;
			case 2: return $this->scheme.'://'.$this->custom_cdn; // Absolute
			default: return $default;
		}
	}

	//	--------------------------------------------------

	protected function custom_cdn()
	{
		if ($this->params->get('custom_cdn', 0))
		{
			$url = trim($this->params->get('custom_cdn_url'), '/').'/';
			$url = $this->clean_scheme($url);

			if ($this->valid_url('http://'.$url)) // just a check
			{
				return $url;
			}
		}
	}

	//	--------------------------------------------------

	protected function main_url()
	{
		if ($this->custom_cdn = $this->custom_cdn())
		{
			return $this->custom_cdn_url();
		}

		return $this->local_url();
	}

	//	--------------------------------------------------

	protected function set_urls()
	{
		$this->web_root = JURI::root();
		$this->web_root_relative = rtrim(JURI::root(true), '/').'/';
		$this->scheme = $this->scheme();
		$host = $this->scheme.'://'.$this->fqdn();
		$this->web_root = $this->valid_url(strtolower($host).$this->web_root_relative);
		$this->main_url = $this->main_url();
		$this->media_url = $this->main_url.'media/eorisis-jquery/';

		//	CDN URLs
		$this->cdn = (object)array(
			'jquery'			=> '//code.jquery.com/', // jQuery
			'cloudflare'		=> '//cdnjs.cloudflare.com/ajax/libs/', // CDNJS (CloudFlare)
			'google'			=> '//ajax.googleapis.com/ajax/libs/', // Google Ajax API
			'bootstrap_maxcdn'	=> '//maxcdn.bootstrapcdn.com/bootstrap/', // Bootstrap CDN by MaxCDN
			'bootstrap_netdna'	=> '//netdna.bootstrapcdn.com/bootstrap/' // Bootstrap CDN by NetDNA
		);

		$this->url = (object)array(
			'jq_lib'		=> null,
			'migrate'		=> null,
			'noconflict'	=> null,
			'tb_js'			=> null,
			'tb_css'		=> null,
			'tb_theme_css'	=> null,
			'chosen_js'		=> null,
			'ui_js'			=> null,
			'ui_css'		=> null
		);
	}

	//	--------------------------------------------------

	protected function urls($type, $data)
	{
		switch ($type)
		{
			case 'js':  $chars = 3; break;
			case 'css': $chars = 4; break;
		}

		$urls = array();
		if ($strings = $this->extract_textarea($data))
		{
			foreach ($strings as $url)
			{
				if (($url = $this->clean_url($url)) and
					(substr($url, -$chars) == '.'.$type))
				{
					if ($this->external_url)
					{
						$urls[] = $url;
					}
					else
					{
						$urls[] = $this->main_url.ltrim($url, '/');
					}
				}
			}
		}

		if (!empty($urls))
		{
			return array_unique($urls);
		}
	}

	//	--------------------------------------------------

	protected function clean_url($url)
	{
		$url = str_replace(array(
		"\r\n",
		"\r",
		"\n",
		"\t",
		"\s",
		" ",
		"	",
		"*",
		"\"",
		"'",
		".."
		), '', $url);

		if ($this->external_url($url))
		{
			$this->external_url = true;
		}
		else
		{
			$this->external_url = false;
			$url = preg_replace('/[^a-zA-Z0-9\/._-]+/', '', $url);
		}

		$url = filter_var($url, FILTER_SANITIZE_URL);
		$url = $this->clean($url);

		return $url;
	}

	//	--------------------------------------------------

	protected function external_url($url)
	{
		if ((strpos($url, '//') === 0) or
			(strpos($url, 'https:') === 0) or
			(strpos($url, 'http:') === 0))
		{
			return true;
		}
	}

	//	--------------------------------------------------

	protected function remove_media()
	{
		$remove = (object)array(
			'js' => array(),
			'css' => array()
		);

		foreach (array('jq_lib', 'ui', 'tb', 'chosen') as $elem)
		{
			$plugin_css = false;
			$files_js_more = false;
			$files_css_main = false;
			$files_css_more = false;

			switch ($elem)
			{
				case 'jq_lib': // jQuery Library, Migrate
				{
					$plugin_main = $this->url->jq_lib;

					$plugin_js = array(
						$plugin_main,
						$this->url->migrate,
						$this->url->noconflict);

					$files_js_main = array(
						'jquery.min.js',
						'jquery.js');

					$files_js_more = array(
						'jquery-noconflict.js',
						'jquery-migrate.min.js',
						'jquery-migrate.js');

				} break;

				case 'ui':
				{
					$plugin_js = $this->url->ui_js;
					$plugin_css = $this->url->ui_css;

					$plugin_main = array(
						$plugin_js,
						$plugin_css);

					$files_js_main = array(
						'jquery.ui.core.min.js',
						'jquery.ui.core.js');

					$files_js_more = array(
						'jquery.ui.sortable.min.js',
						'jquery.ui.sortable.js');

				} break;

				case 'tb':
				{
					$plugin_js = $this->url->tb_js;

					$plugin_css = array(
						$this->url->tb_css,
						$this->url->tb_theme_css
					);

					$plugin_main = array(
						$this->url->tb_js,
						$this->url->tb_css);

					$files_js_main = array(
						'bootstrap.min.js',
						'bootstrap.js');

					$files_css_main = array(
						'bootstrap.min.css',
						'bootstrap.css');

					$files_css_more = array(
						'bootstrap-responsive.min.css',
						'bootstrap-responsive.css',
						'bootstrap-extended.css',
						'bootstrap-rtl.css');

				} break;

				case 'chosen':
				{
					$plugin_js = $this->url->chosen_js;
					$plugin_main = $plugin_js;

					$files_js_main = array(
						'chosen.jquery.min.js',
						'chosen.jquery.js');

				} break;
			}

			//	--------------------------------------------------

			$continue = false;
			if (is_array($plugin_main))
			{
				foreach ($plugin_main as $url)
				{
					if ($url)
					{
						$continue = true;
						break;
					}
				}
			}
			elseif ($plugin_main)
			{
				$continue = true;
			}

			//	--------------------------------------------------

			if ($continue)
			{
				$jui = $this->web_root_relative.'media/jui/';
				$load = true;

				if ($this->params->get($elem.'_state', 2) == 1) // Auto Load
				{
					if (!$this->head_isset($files_js_main, $jui))
					{
						$load = false;
						$remove->js  = $this->url_into_array($plugin_js, $remove->js);
						$remove->css = $this->url_into_array($plugin_css, $remove->css);
					}
				}

				if ($load)
				{
					$files_js = $this->merge($files_js_main, $files_js_more);
					$remove->js = $this->add_jui('js', $files_js, $remove->js, $jui);

					if ($files_css_main)
					{
						$files_css = $this->merge($files_css_main, $files_css_more);
						$remove->css = $this->add_jui('css', $files_css, $remove->css, $jui);
					}
				}
			}
		}

		return $remove;
	}

	//	--------------------------------------------------

	protected function remove_media_other($remove)
	{
		if ($this->area('remove', 1))
		{
			foreach (array('js', 'css') as $type)
			{
				if ($this->params->get('remove_'.$type))
				{
					$remove->$type = $this->remove_media_strings($type, $remove->$type, $this->textarea('remove_'.$type.'_urls'));
				}
			}
		}

		return $remove;
	}

	//	--------------------------------------------------

	protected function remove_media_strings($type, $arr, $strings)
	{
		if ($strings)
		{
			switch ($type)
			{
				case 'js':  $urls = $this->scripts; break;
				case 'css': $urls = $this->head['styleSheets']; break;
			}

			foreach ($urls as $url => $attr)
			{
				foreach ($strings as $string)
				{
					if ((strpos($url, $string) !== false) and !in_array($url, $this->loaded_media))
					{
						$arr[] = $url;
					}
				}
			}
		}

		return $arr;
	}

	//	--------------------------------------------------

	protected function css_media_type()
	{
		if ($this->css_media_type === false)
		{
			$media = $this->params->get('css_media_type', 'null');
			if ($media == 'null')
			{
				$this->css_media_type = null;
			}
			else
			{
				$valid = array('all', 'screen');
				if (in_array($media, $valid))
				{
					$this->css_media_type = $media;
				}
			}
		}

		return $this->css_media_type;
	}

	//	--------------------------------------------------

	protected function load($type, $path, $media = null)
	{
		if (is_array($path))
		{
			foreach ($path as $url)
			{
				$this->load($type, $url, $media);
			}
		}
		else
		{
			switch ($type)
			{
				case 'js' : $this->doc->addScript($path); break;
				case 'css': $this->doc->addStyleSheet($path, 'text/css', $media); break;
			}

			$this->loaded_media[] = $path;
		}
	}

	//	--------------------------------------------------

	protected function url_into_array($path, $arr)
	{
		if ($path)
		{
			if (is_array($path))
			{
				foreach ($path as $url)
				{
					$arr[] = $url;
				}
			}
			else
			{
				$arr[] = $path;
			}
		}

		return $arr;
	}

	//	--------------------------------------------------

	protected function textarea($field)
	{
		if ($data = $this->params->get($field))
		{
			if ($strings = $this->extract_textarea($data))
			{
				return array_unique($strings);
			}
		}
	}

	//	--------------------------------------------------

	protected function extract_textarea($data)
	{
		$strings = array();
		$lines = preg_split('/\s+/', $data); // line and gaps on each

		foreach ($lines as $string)
		{
			if ($string = trim($string))
			{
				$strings[] = $string;
			}
		}

		if (!empty($strings))
		{
			return $strings;
		}
	}

	//	--------------------------------------------------

	protected function merge($arr_a, $arr_b)
	{
		if ($arr_b)
		{
			return array_merge($arr_a, $arr_b);
		}

		return $arr_a;
	}

	//	--------------------------------------------------

	protected function add_jui($type, $files, $arr, $jui)
	{
		foreach ($files as $file)
		{
			$arr[] = $jui.$type.'/'.$file;
		}

		return $arr;
	}

	//	--------------------------------------------------

	protected function css_order($css)
	{
		if ($this->css_first === null)
		{
			$this->css_first = ($this->params->get('css_order', 1) == 1);
		}

		if ($this->css_first)
		{
			$this->load('css', $css, $this->css_media_type());
		}
		else
		{
			$this->css_last[] = $css;
		}
	}

	//	--------------------------------------------------

	protected function css_order_last()
	{
		if (!empty($this->css_last))
		{
			foreach ($this->css_last as $css)
			{
				$this->load('css', $css, $this->css_media_type());
			}
		}
	}
}
