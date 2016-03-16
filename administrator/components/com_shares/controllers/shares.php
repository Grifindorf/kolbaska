<?php

defined('_JEXEC') or die;


class SharesControllerShares extends JControllerAdmin
{


    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function publish()
    {

        $values = array('publish' => 1, 'unpublish' => 0);
        $task   = $this->getTask();
        $value  = JArrayHelper::getValue($values, $task, 0, 'int');

        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $ids    = $this->input->get('cid', array(), 'array');

        if (empty($ids))
        {
            JError::raiseWarning(500, JText::_('Не выбраны акции'));
        }
        else
        {
            // Get the model.
            $model = $this->getModel();

            // Change the state of the records.
            if (!$model->publish($ids,$value))
            {
                JError::raiseWarning(500, $model->getError());
            }
            else
            {
                if ($value==1) {
                    $ntext = 'Опубликовано';
                }else{
                    $ntext = 'Снято с публикации';
                }


                $this->setMessage(JText::plural($ntext, count($ids)));
            }
        }

        $this->setRedirect('index.php?option=com_shares&view=shares');
    }


    public function delete()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $ids    = $this->input->get('cid', array(), 'array');

        if (empty($ids))
        {
            JError::raiseWarning(500, JText::_('Не выбраны акции'));
        }
        else
        {
            // Get the model.
            $model = $this->getModel();

            // Change the state of the records.
            if (!$model->delete($ids))
            {
                JError::raiseWarning(500, $model->getError());
            }
            else
            {
                $ntext = 'Удалено';

                $this->setMessage(JText::plural($ntext, count($ids)));
            }
        }

        $this->setRedirect('index.php?option=com_shares&view=shares');
    }

    public function cancel()
    {
        $this->setRedirect('index.php?option=com_shares&view=shares');
    }

    public function apply()
    {
        /*var_dump($this->input->get('jform', array(), 'array'));
        die;*/
        $this->setRedirect('index.php?option=com_shares&view=shares');
    }



    public function getModel($name = 'Share', $prefix = 'SharesModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    function add()
    {
        JRequest::setVar('view', 	'shares');
        JRequest::setVar('layout', 	'edit');

        parent::display();
    }

    protected function postDeleteHook(JModelLegacy $model, $ids = null)
    {
    }
}
