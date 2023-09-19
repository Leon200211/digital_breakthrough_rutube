<?php

namespace engine\main\controllers;

use engine\base\controllers\BaseController;
use engine\main\models\MainModel;

class uploadPageController extends BaseController
{
    public function index() : void
    {

    }

    public function outputData()
    {
        return $this->render($_SERVER['DOCUMENT_ROOT'] . '/engine/main/views/uploadVideoPage');
    }

    public function uploadVideo()
    {
        if(!$this->model) $this->model = MainModel::getInstance();


        $id = $this->model->read('upload_video', [
            'fields' => ['id'],
            'limit' => '1',
            'order' => ['id'],
            'order_direction' => ['DESC'],
        ]);

        if (!empty($id)) {
            $idNewVideo = $id[0]['id'];
        } else {
            $idNewVideo = 0;
        }

        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $fileName = 'test_' . random_int(1, 1000000) . '.' . $ext;
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/files/uploads/" . $fileName;

        if (move_uploaded_file($_FILES['avatar']["tmp_name"], $targetPath)) {
            $this->model->add('upload_video', [
                'fields' => [
                    'video' => $fileName,
                    'name' => 'test',
                    'description' => 'test',
                    'quality' => 1,
                    'commentary' => 1,
                    'is_processed' => 0
                ]
            ]);

            echo 1;
            exit();
        } else {
            echo "Error";
            exit();
        }
    }

}