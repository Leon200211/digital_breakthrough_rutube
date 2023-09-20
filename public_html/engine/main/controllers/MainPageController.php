<?php 


namespace engine\main\controllers;


use engine\base\exceptions\RouteException;
use engine\base\settings\Settings;
use engine\base\controllers\BaseController;
use engine\main\models\MainModel;


class MainPageController extends BaseController
{

    public function index() : void 
    {


    }

    public function outputData()
    {
        return $this->render($_SERVER['DOCUMENT_ROOT'] . '/engine/main/views/mainPage');
    }


}
