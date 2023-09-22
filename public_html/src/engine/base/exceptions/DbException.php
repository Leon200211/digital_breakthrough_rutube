<?php


namespace engine\base\exceptions;


// Throwable является родительским интерфейсом для всех объектов, выбрасывающихся с помощью выражения throw,
// включая классы Error и Exception.
use Throwable;
use engine\base\controllers\BaseMethods;



/**
 * Class DbException класс для исключений связанных с Базой Данных
 * @package engine\base\exceptions
 */
class DbException extends \Exception
{

    protected $messages;

    use BaseMethods;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code);

        $this->messages = include 'messages.php';

        // создаем сообщение для логов
        $error = $this->getMessage() ?: $this->messages[$this->getCode()];
        $error .= "\r\n" . 'file' . $this->getFile() . "\r\n In line " . $this->getLine() . "\r\n";

        if($this->messages[$this->getCode()]){
            //$this->message = $this->messages[$this->getCode()];
        }

        // запись логов
        $this->writeLog($error, 'db_log.txt');

    }

}