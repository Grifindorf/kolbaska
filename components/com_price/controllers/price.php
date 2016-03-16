<?php

defined('_JEXEC') or die;


class PriceControllerPrice extends JControllerForm
{


    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    protected function postSaveHook(JModelLegacy $model, $validData = array())
    {

        return;
    }

}
