<?php


namespace engine\base\settings;


use engine\base\controllers\Singleton;


/**
 * Class Settings класс настроек
 * @package engine\base\settings
 */
class Settings
{

    use Singleton;

    // геттер для получения данных
    static public function get($property){
        return self::getInstance()->$property;
    }


    // настройки пути
    private $routes = [

        '/404' => [
            'controller' => 'exceptionalPages',
            'controllerPath' => '\engine\base\controllers\\',
            'action' => 'page404',
        ],

        '/' => [
            'controller' => 'mainPage',
            'controllerPath' => '\engine\main\controllers\\',
            'action' => 'index',
        ],
        '/index.php' => [
            'controller' => 'mainPage',
            'controllerPath' => '\engine\main\controllers\\',
            'action' => 'index',
        ],
        '/studio' => [
            'controller' => 'uploadPage',
            'controllerPath' => '\engine\main\controllers\\',
            'action' => 'index',
        ],
        '/uploadVideo' => [
            'controller' => 'uploadPage',
            'controllerPath' => '\engine\main\controllers\\',
            'action' => 'uploadVideo',
        ],
        '/loadVideo' => [
            'controller' => 'uploadPage',
            'controllerPath' => '\engine\main\controllers\\',
            'action' => 'uploadVideoFromApi',
        ],
        '/checkVideo' => [
            'controller' => 'uploadPage',
            'controllerPath' => '\engine\main\controllers\\',
            'action' => 'checkVideo',
        ],


    ];


}