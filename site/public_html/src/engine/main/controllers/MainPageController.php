<?php 


namespace engine\main\controllers;


use engine\base\exceptions\RouteException;
use engine\base\settings\Settings;
use engine\base\controllers\BaseController;
use engine\main\models\MainModel;


class MainPageController extends BaseController
{
    public $videoData = [];

    public function index() : void 
    {
        if(!$this->model) $this->model = MainModel::getInstance();

        $this->videoData = $this->model->read('upload_video', [
            'fields' => ['id', 'video', 'name', 'description', 'date_create', 'quality', 'commentary', 'is_processed'],
            'order' => ['id'],
            'order_direction' => ['DESC'],
        ]);
    }

    public function outputData()
    {
        return $this->render($_SERVER['DOCUMENT_ROOT'] . '/engine/main/views/mainPage');
        #return $this->render($_SERVER['DOCUMENT_ROOT'] . '/engine/main/views/uploadVideoPage');
    }


}
