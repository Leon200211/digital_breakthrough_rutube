<?php


namespace engine\base\controllers;


use engine\base\exceptions\RouteException;
use engine\base\settings\Settings;
use engine\main\notificationSystem\controllers\Notification;



/**
 * Class BaseController класс базового контроллера, от него будут наследоваться все другие контроллеры
 * @package engine\base\controllers
 */
abstract class BaseController
{

    // трейт с базовыми методами
    use BaseMethods;

    /**
     * @var string Заголовок страницы
     */
    protected $title = 'PskVesna';

    // поля для шаблонов
    protected $header;
    protected $content;
    protected $footer;
    protected $page;

    protected $errors;

    protected $controller;  // контроллеры
    protected $controllerAction;  // метод контроллера
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;  // параметры

    // поля для подключения шаблонов
    protected $template;
    protected $styles;
    protected $scripts;


    protected $model;
    protected $accessRightsChecker;
    protected $notificationSystem;

    // метод для переадресации
    public function route(){
        $controller = str_replace('/', '\\', $this->controller);

        try {
            // для проверок
            // Класс ReflectionMethod сообщает информацию о методах.
            $object = new \ReflectionMethod($controller, 'request');

            $args = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->inputMethod,
                'outputMethod' => $this->outputMethod
            ];

            $object->invoke(new $controller, $args);
        }catch (\ReflectionException $e){
            throw new RouteException($e->getMessage());
        }

    }


    // метод для работы с запросом
    public function request($args){
        $this->parameters = $args['parameters'];

        $inputData = $args['inputMethod'];
        $outputData = $args['outputMethod'];

        $data = $this->$inputData();

        if(method_exists($this, $outputData)){
            $page = $this->$outputData($data);
            if($page){
                $this->page = $page;
            }
        }elseif($data){
            $this->page = $data;
        }

        // логирование ошибок
        if($this->errors){
            $this->writeLog($this->errors);
        }

        $this->getPage();
    }


    // генератор шаблонов
    protected function render($path = '', $parameters = []){
        @extract($parameters);

        if(!$path){

            $class = new \ReflectionClass($this);
            // пространство имен
            $space = str_replace('\\', '/', $class->getNamespaceName() . '\\');
            // получение маршрутов
            $routes = Settings::get('routes');

            if($space === $routes['user']['path']){
                $template = TEMPLATE;
            }else{
                $template = ADMIN_TEMPLATE;
            }

            $path = $template . explode('controller', strtolower($class->getShortName()))[0];
        }


        // работа с буфером обмена
        ob_start();
        if(!@include_once $path . '.php') {
            throw new RouteException('Отсутствует шаблон - ' . $path);
        }
        // возвращаем данные из буфера обмена
        return ob_get_clean();

    }


    // отображение страницы
    protected function getPage(){

        if(is_array($this->page)){
            foreach ($this->page as $block){
                echo $block;
            }
        }else{
            echo $this->page;
        }

        exit();

    }


    // метод инициализации стилей и скриптов
    protected function init($admin = false){

        if(!$admin){
            if(USER_CSS_JS['style']){
                foreach (USER_CSS_JS['style'] as $item){
                    $this->styles[] = PATH . TEMPLATE . trim($item, '/');
                }
            }

            if(USER_CSS_JS['scripts']){
                foreach (USER_CSS_JS['scripts'] as $item){
                    $this->scripts[] = PATH . TEMPLATE . trim($item, '/');
                }
            }
        }else{
            if(ADMIN_CSS_JS['style']){
                foreach (ADMIN_CSS_JS['style'] as $item){
                    $this->styles[] = PATH . ADMIN_TEMPLATE . trim($item, '/');
                }
            }

            if(ADMIN_CSS_JS['scripts']){
                foreach (ADMIN_CSS_JS['scripts'] as $item){
                    $this->scripts[] = PATH . ADMIN_TEMPLATE . trim($item, '/');
                }
            }
        }
    }

}