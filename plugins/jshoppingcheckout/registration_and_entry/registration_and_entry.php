<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgJshoppingcheckoutRegistration_and_entry extends JPlugin{
	function onAfterRegister($user, $row, $_post, $useractivation){

		$jshopConfig = &JSFactory::getConfig();
		$mainframe =& JFactory::getApplication();
		
		$options = array();
		$options['remember'] = $this->params->get('remember');

		$credentials = array();
		$credentials['username'] = $user->username;
		$credentials['password'] = $user->password_clear;
		
		$error = $mainframe->login($credentials, $options);
		
		setNextUpdatePrices();
		
		if ($this->params->get('redirect')){
			$return = SEFLink($this->params->get('redirect'));
			
		}else{
            $session = JFactory::getSession();
            $return = $session->get('return');
            if ($return){
                $return = base64_decode($return);
            }
            if (!$return){
			    $return = SEFLink('index.php?option=com_jshopping');
            }
		}

		if ((!JError::isError($error)) && ($error !== FALSE)){
            if ( ! $return ) {
                $return = JURI::base();
            }                              
            $mainframe->redirect( $return );
			
        }else{
            $mainframe->redirect( SEFLink('index.php?option=com_jshopping&controller=user&task=login&return='.base64_encode($return),0,1,$jshopConfig->use_ssl) );
        }
		
		
	}
}
?>