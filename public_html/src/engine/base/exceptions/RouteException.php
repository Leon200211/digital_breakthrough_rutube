<?php


namespace engine\base\exceptions;


// Throwable является родительским интерфейсом для всех объектов, выбрасывающихся с помощью выражения throw,
// включая классы Error и Exception.
use Throwable;
use engine\base\controllers\BaseMethods;


/**
 * Class RouteException ласс исключений
 * @package engine\base\exceptions
 */
class RouteException extends \Exception
{

    protected $messages;

    use BaseMethods;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->messages = include 'messages.php';


        // создаем сообщение для логов
        $error = $this->getMessage() ?: $this->messages[$this->getCode()];
        $error .= "\r\n" . 'file' . $this->getFile() . "\r\n In line " . $this->getLine() . "\r\n";


        // возвращаем сообщение для пользователя
        if($this->messages[$this->getCode()]){
            //$this->message = $this->messages[$this->getCode()];
        }

        // запись логов
        $this->writeLog($error);

    }

}