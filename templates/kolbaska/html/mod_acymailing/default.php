<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.9.4
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>
    <?php
    $style = array();
    ?>

            <form id="<?php echo $formName; ?>" action="<?php echo JRoute::_('index.php'); ?>" onsubmit="return submitacymailingform('optin','<?php echo $formName;?>')" method="post" name="<?php echo $formName ?>" <?php if(!empty($fieldsClass->formoption)) echo $fieldsClass->formoption; ?> >
                <div class="form-group">
                    <input id="user_name_<?php echo $formName; ?>" <?php if(!empty($identifiedUser->userid)) echo 'readonly="readonly" ';  if(!$displayOutside){ ?> onfocus="if(this.value == '<?php echo $nameCaption;?>') this.value = '';" onblur="if(this.value=='') this.value='<?php echo $nameCaption?>';"<?php } ?> class="form-control" type="text" name="user[name]" placeholder="<?php if(!empty($identifiedUser->userid)) echo $identifiedUser->name; elseif(!$displayOutside) echo $nameCaption; ?>" value="<?php if(!empty($identifiedUser->userid)) echo $identifiedUser->name; ?>"/>
                </div>
                <div class="form-group">
                    <input id="user_email_<?php echo $formName; ?>" <?php if(!empty($identifiedUser->userid)) echo 'readonly="readonly" ';  if(!$displayOutside){ ?> onfocus="if(this.value == '<?php echo $emailCaption;?>') this.value = '';" onblur="if(this.value=='') this.value='<?php echo $emailCaption?>';"<?php } ?> class="form-control" type="text" name="user[email]" placeholder="<?php if(!empty($identifiedUser->userid)) echo $identifiedUser->email; elseif(!$displayOutside) echo $emailCaption; ?>" value="<?php if(!empty($identifiedUser->userid)) echo $identifiedUser->email; ?>"/>
                </div>
                <input class="btn btn-danger" type="submit" value="<?php $subtext = $params->get('subscribetextreg'); if(empty($identifiedUser->userid) OR empty($subtext)){ $subtext = $params->get('subscribetext',JText::_('SUBSCRIBECAPTION')); } echo $subtext;  ?>" name="Submit" onclick="try{ return submitacymailingform('optin','<?php echo $formName;?>'); }catch(err){alert('The form could not be submitted '+err);return false;}"/>
                <?php
                    if(!empty($fieldsClass->excludeValue)){
                        $js = "\n"."acymailing['excludeValues".$formName."'] = Array();";
                        foreach($fieldsClass->excludeValue as $namekey => $value){
                            $js .= "\n"."acymailing['excludeValues".$formName."']['".$namekey."'] = '".$value."';";
                        }
                        $js .= "\n";
                        $doc = JFactory::getDocument();
                        if($params->get('includejs','header') == 'header'){
                            $doc->addScriptDeclaration( $js );
                        }else{
                            echo "<script type=\"text/javascript\">
							<!--
							$js
							//-->
							</script>";
                        }
                    }
                    if(!empty($postText)) echo '<div class="acymailing_finaltext">'.$postText.'</div>';
                    $ajax = ($params->get('redirectmode') == '3') ? 1 : 0;?>
                    <input type="hidden" name="ajax" value="<?php echo $ajax; ?>" />
                    <input type="hidden" name="acy_source" value="<?php echo 'module_'.$module->id ?>" />
                    <input type="hidden" name="ctrl" value="sub"/>
                    <input type="hidden" name="task" value="notask"/>
                    <input type="hidden" name="redirect" value="<?php echo urlencode($redirectUrl); ?>"/>
                    <input type="hidden" name="redirectunsub" value="<?php echo urlencode($redirectUrlUnsub); ?>"/>
                    <input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT ?>"/>
                    <?php if(!empty($identifiedUser->userid)){ ?><input type="hidden" name="visiblelists" value="<?php echo $visibleLists;?>"/><?php } ?>
                    <input type="hidden" name="hiddenlists" value="<?php echo $hiddenLists;?>"/>
                    <input type="hidden" name="acyformname" value="<?php echo $formName; ?>" />
                    <?php if(JRequest::getCmd('tmpl') == 'component'){ ?>
                        <input type="hidden" name="tmpl" value="component" />
                        <?php if($params->get('effect','normal') == 'mootools-box' AND !empty($redirectUrl)){ ?>
                            <input type="hidden" name="closepop" value="1" />
                        <?php } } ?>
                    <?php $myItemId = $config->get('itemid',0); if(empty($myItemId)){ global $Itemid; $myItemId = $Itemid;} if(!empty($myItemId)){ ?><input type="hidden" name="Itemid" value="<?php echo $myItemId;?>"/><?php } ?>
            </form>


