<?php

namespace engine\main\controllers;

use engine\base\controllers\BaseController;
use engine\main\models\MainModel;

class UploadPageController extends BaseController
{

    private $_format = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'f4v', 'swf', 'mkv', 'webm'];

    public function index() : void
    {

    }

    public function outputData()
    {
        return $this->render($_SERVER['DOCUMENT_ROOT'] . '/engine/main/views/uploadVideoPage');
    }

    public function uploadVideo()
    {
        if (empty($_FILES['file']['name'])) {
            http_response_code(400);
            echo "Не подходящий формат файла";
            exit();
        }

        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if (!in_array($ext, $this->_format)) {
            http_response_code(400);
            echo "Не подходящий формат файла";
            exit();
        }

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

        $fileName = 'test_' . random_int(1, 1000000) . '.' . $ext;
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/files/uploads/" . $fileName;

        if (move_uploaded_file($_FILES['file']["tmp_name"], $targetPath)) {
            $this->model->add('upload_video', [
                'fields' => [
                    'video' => $fileName,
                    'name' => $_REQUEST['name'],
                    'description' => $_REQUEST['description'],
                    'quality' => $_REQUEST['quality'] ? 0 : 1,
                    'commentary' => $_REQUEST['commentary'] ? 0 : 1,
                    'is_processed' => 0
                ]
            ]);


            http_response_code(200);
            $result = [
                "id" => $idNewVideo + 1,
                "status" => 'success'
            ];
            echo json_encode($result);

            $curl = curl_init();
            $aPost = array(
                'id' => $idNewVideo + 1,
                'name' => $_REQUEST['name'],
                'description' => $_REQUEST['description'],
                'quality' => $_REQUEST['quality'] ? 0 : 1,
                'commentary' => $_REQUEST['commentary'] ? 0 : 1,
            );
            if ((version_compare(PHP_VERSION, '5.5') >= 0)) {
                $aPost['file'] = new \CURLFile($targetPath);
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
            } else {
                $aPost['file'] = "@".$targetPath;
            }
            curl_setopt($curl, CURLOPT_URL, SITE_URL . 'loadVideo/test');
            curl_setopt($curl, CURLOPT_TIMEOUT, 120);
            curl_setopt($curl, CURLOPT_BUFFERSIZE, 128);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $aPost);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);
            $sResponse = curl_exec ($curl);

            exit();
        } else {
            http_response_code(500);
            echo "Error";
            exit();
        }
    }


    public function uploadVideoFromApi()
    {
        if(!$this->model) $this->model = MainModel::getInstance();
        $videoDb = $this->model->read('upload_video', [
           'fields' => ['id', 'video'],
           'where' => ['id' => $_REQUEST['id']]
        ]);
        if (empty($videoDb)) {
            http_response_code(400);
            echo "Не корректные данные";
            exit();
        }

        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if (!in_array($ext, $this->_format)) {
            http_response_code(400);
            echo "Не подходящий формат файла";
            exit();
        }

        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/files/uploads/" . $videoDb[0]['video'];
        if (move_uploaded_file($_FILES['file']["tmp_name"], $targetPath)) {
            $this->model->update('upload_video', [
                'fields' => [
                    'is_processed' => 1,
                ],
                'where' => ['id' => $videoDb[0]['id']]
            ]);
            http_response_code(200);
            echo "success";
            exit();
        } else {
            http_response_code(500);
            echo "Error";
            exit();
        }

    }

    public function checkVideo(): void
    {
        if (empty($_REQUEST['id'])) {
            http_response_code(400);
            echo "Error 400";
            exit();
        }

        if(!$this->model) $this->model = MainModel::getInstance();
        $videoDb = $this->model->read('upload_video', [
            'fields' => ['id', 'is_processed', 'video'],
            'where' => ['id' => $_REQUEST['id']]
        ]);

        if (empty($videoDb)) {
            http_response_code(400);
            echo "Не корректные данные";
            exit();
        }

        file_put_contents(__DIR__ . '/test.log', print_r($videoDb, 1), FILE_APPEND);

        if ($videoDb[0]['is_processed'] == 1) {
            http_response_code(200);
            $result = [
                'is_processed' => 1,
                'video' => $videoDb[0]['video'],
            ];
            echo json_encode($result);
            file_put_contents(__DIR__ . '/test1.log', print_r($result, 1), FILE_APPEND);
        } else {
            http_response_code(200);
            $result = [
                'is_processed' => 0
            ];
            echo json_encode($result);
        }
        session_write_close();
        exit();
    }

}