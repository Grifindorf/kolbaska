<?php

defined('_JEXEC') or die;


class UpdateControllerUpdate extends JControllerAdmin
{


    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    protected function postDeleteHook(JModelLegacy $model, $ids = null)
    {
    }
}
