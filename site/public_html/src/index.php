<?php
// все запросы по ссылкам
// навигационные запросы по сайту


// константа безопасности
define('VG_ACCESS', true);
//error_reporting(0);

header('Content-Type:text/html;charset=utf-8'); // в какой кодировки пользователь обрабатывает данные (первый заголовок)
session_start(); //стартуем сессию


// отключаем сообщение о предупреждениях
error_reporting(0);


require_once 'config.php';  // базовые настройки для хостинга
require_once 'engine/base/settings/internal_settings.php';  // фундаментальные настройки сайта


use engine\base\exceptions\RouteException;  // импортируем пространство имен для исключения
use engine\base\exceptions\DbException;  // импортируем пространство имен для исключения БД
use engine\base\controllers\RouteController;


if ($_SERVER['REQUEST_URI'] == '/uploadVideo') {
    $uploadVideo = new \engine\main\controllers\UploadPageController();
    $uploadVideo->uploadVideo();
}
if ($_SERVER['REQUEST_URI'] == '/checkVideo') {
    $uploadVideo = new \engine\main\controllers\UploadPageController();
    $uploadVideo->checkVideo();
}
if ($_SERVER['REQUEST_URI'] == '/loadVideo') {
    $uploadVideo = new \engine\main\controllers\UploadPageController();
    $uploadVideo->uploadVideoFromApi();
}

try{
    RouteController::getInstance()->route();
}
catch (RouteException $e){
    exit($e->getMessage());
}
catch (DbException $e){
    exit($e->getMessage());
}
