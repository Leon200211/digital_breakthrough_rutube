<?php


namespace engine\base\controllers;


use engine\base\exceptions\RouteException;
use engine\base\settings\Settings;


/**
 * Class RouteController класс для роутинга
 * @package engine\base\controllers
 */
class RouteController extends BaseController
{

    use Singleton;

    protected $routes; // маршруты


    private function __construct(){

        $address_str = $_SERVER['REQUEST_URI'];

        // если есть аргументы GET
        if($_SERVER['QUERY_STRING']){
            $address_str = substr($address_str, 0, strpos($address_str, $_SERVER['QUERY_STRING']) - 1);
        }

        $path = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], 'index.php'));

        // PATH - константа из config.php
        if($path === PATH){

            // если символ / стоит в конце строки, то перенаправляем пользователя на страницу без этого /
            if(strrpos($address_str, '/') === strlen($address_str) - 1 and
                strrpos($address_str, '/') !== strlen(PATH) - 1){
                $this->redirect(rtrim($address_str, '/'), 301);
            }


            // получаем данные из класса Settings с помощью геттера
            // это нежно чтобы знать пути к частям сайта
            $this->routes = Settings::get('routes');

            // если мы не получили данные
            if(!$this->routes){
                throw new RouteException('Отсутствуют маршруты в базовых настройках', 1);
            }

            // обрезаем адресную строку и разбиваем путь
            $url = explode('/', substr($address_str, strlen(PATH)));

            if(!empty($this->routes[$address_str])){
                $this->controller = $this->routes[$address_str]['controllerPath'];
                $this->controller .= ucfirst($this->routes[$address_str]['controller'].'Controller');
                $this->inputMethod = $this->routes[$address_str]['action'];
                $this->outputMethod = 'outputData';
            }else{
                throw new RouteException("Не найден контроллер", 1);
            }


        }else{
            throw new RouteException("Не корректная директория сайта", 1);
        }

    }





}