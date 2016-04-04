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

jimport('joomla.form.formfield');

//	The class name must always be the same as the filename (in camel case)
class JFormFieldInfo extends JFormField
{
	protected $type = 'info'; // The field class must know its own type through the variable $type
	protected $xml;

	//	--------------------------------------------------

	public function getLabel() {}

	//	--------------------------------------------------

	protected function getInput()
	{
		$app = JFactory::getApplication();
		if (!$app->isAdmin())
		{
			return;
		}

		//	--------------------------------------------------

		$app_dir = 'eorisis_jquery';
		$app_url = 'eorisis-jquery';
		$app_type = 'plugin';

		//	--------------------------------------------------

		//	Joomla 3.2.4 showon fields
		//	Joomla 3.3.1 fieldset descriptions
		if (version_compare(JVERSION, '3.3.1', '<'))
		{
			$msg = JText::_('EO_JVERSION_OLD').' '.JVERSION.'. '.JText::_('EO_JUPGRADE');
			$app->enqueueMessage($msg, 'warning');
		}

		//	--------------------------------------------------

		$ext = 'plg_system_'.$app_dir;
		$l = JFactory::getLanguage();
		$l->load($ext, JPATH_ADMINISTRATOR, 'en-GB', true);
		$l->load($ext, JPATH_ADMINISTRATOR, null, true);

		//	--------------------------------------------------

		$doc = JFactory::getDocument();
		$app_path = JPATH_SITE.'/plugins/system/'.$app_dir.'/';
		$framework_admin = $app_path.'framework/admin/';

		//	--------------------------------------------------

		$doc->addStyleSheet('//fonts.googleapis.com/css?family=Open+Sans:300', 'text/css', 'all');
		$css = $this->read_file($framework_admin.'css/styles.css');

		if (version_compare(JVERSION, 3, '>='))
		{
			$css .= '.eo-hr { width:220px;border-top:1px solid #eee; }';
		}
		else
		{
			$css .= '.eo-hr { display:block;color:#ddd;background:#eee;border:0; }';
		}

		$css .= '.eo-hr { clear:both;height:1px;font-size:0;margin:5px 0; }';
		$css .= '.eo-help label { color:#ec8824; }';
		$doc->addStyleDeclaration($css);

		//	--------------------------------------------------

		if ($xml_file = $this->read_file($app_path.$app_dir.'.xml', true))
		{
			$xml = new SimpleXMLElement($xml_file);
			$this->xml = $xml;

			$js = $this->read_file($framework_admin.'js/script.js');
			$js = str_replace('|jquery_version|', (string)$xml->jquery_version, $js);
			$js = str_replace('|migrate_version|', (string)$xml->migrate_version, $js);
			$js = str_replace('|app_url|', $app_url, $js);
			$js = str_replace('|app_type|', $app_type, $js);
			$js = str_replace('|update_site_url|', JURI::getInstance(JURI::root())->getScheme().'://'.(string)$xml->author.'/updates', $js);
			$doc->addScriptDeclaration($js);

			//	--------------------------------------------------

			$html  = '<script type="text/javascript">var installed_version = "'.(string)$xml->version.'"</script>';
			$html .= '<div class="eo-info">';
			$html .=	'<h1>'.(string)$xml->title.'</h1>';
			$html .=	'<div id="eo-update-check">';
			$html .=		'<a href="#" class="hasTooltip" title="'.JText::_('EO_UPDATE_CHECK_TITLE').'"><h3>'.JText::_('EO_UPDATE_CHECK').'</a></h3>';
			$html .=	'</div>';
			$html .=	'<div id="server-version" class="ver">';
			$html .=		'<h3><span id="eo-version-no"></span></h3>';
			$html .=		'<span id="eo-latest"></span>';
			$html .=		'<span id="eo-dl-update"></span>';
			$html .=	'</div>';
			$html .=	'<div id="eo-release">';
			$html .=		'<span id="eo-release-date"></span>';
			$html .=		'<span id="eo-release-notes"></span>';
			$html .=		'<span id="eo-infourl"></span>';
			$html .=	'</div>';
			$html .=	'<hr>';
			$html .=	'<span class="group">';
			$html .=		JText::_('EO_VERSION').': '.(string)$xml->version.' ('.(string)$xml->creationDate.')<br />';
			$html .=		JText::_('EO_COMPATIBILITY').': '.(string)$xml->compatibility;
			$html .=	'</span>';
			$html .=	'<span class="group">';
			$html .=		$this->info_link(JText::_('EO_MAIN_PAGE'), '').' - '.
							$this->info_link(JText::_('EO_DOWNLOAD'), 'download').' - '.
							$this->info_link(JText::_('EO_DOC'), 'doc').' - '.
							$this->info_link(JText::_('EO_CHANGELOG'), 'changelog').'<br />';
			$html .=		JText::_('EO_SUPPORT').': <a href="mailto:'.(string)$xml->authorEmail.'" target="_top">'.(string)$xml->authorEmail.'</a><br />';
			$html .=		JText::_('EO_VOTE').' '.$this->info_link(JText::_('EO_JED'), 'jed').'<br />';
			$html .=		JText::_('EO_SUPPORT_DEV').': '.$this->info_link(JText::_('EO_DONATE'), 'donate', false).'<br />';
			$html .=		'<br />';
			$html .=		str_replace('(C)', '&copy', (string)$xml->copyright);
			$html .=	'</span>';
			$html .= '</div>';

			return $html;
		}
	}

	//	--------------------------------------------------

	protected function read_file($file, $check = false)
	{
		if (!$check)
		{
			return file_get_contents($file);
		}

		if (file_exists($file) === true)
		{
			return file_get_contents($file);
		}
	}

	//	--------------------------------------------------

	protected function info_link($text, $url, $extension = true)
	{
		$mail_url = (string)$this->xml->authorUrl.'/';
		if ($extension)
		{
			$mail_url .= (string)$this->xml->short_url;
			if ($url)
			{
				$url = '/'.$url;
			}
		}

		return '<a href="'.$mail_url.$url.'" target="_blank" class="hasTooltip" title="'.JText::_('JBROWSERTARGET_NEW').'">'.$text.'</a>';
	}
}
