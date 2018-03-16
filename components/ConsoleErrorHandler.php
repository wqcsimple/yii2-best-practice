<?php
/**
 * Created by PhpStorm.
 * User: dd
 * Date: 6/15/16
 * Time: 15:40
 */

namespace app\components;


use dix\base\exception\ServiceException;
use Yii;
use yii\base\ErrorException;
use yii\base\UserException;
use yii\console\Exception;
use yii\helpers\Console;

class ConsoleErrorHandler extends \yii\console\ErrorHandler
{
    /**
     * Renders an exception using ansi format for console output.
     * @param \Exception $exception the exception to be rendered.
     */
    protected function renderException($exception)
    {
        if ($exception instanceof Exception && ($exception instanceof UserException || !YII_DEBUG)) {
            $message = $this->formatMessage($exception->getName() . ': ') . $exception->getMessage();
        } elseif (YII_DEBUG) {
            if ($exception instanceof Exception) {
                $message = $this->formatMessage("Exception ({$exception->getName()})");
            } elseif ($exception instanceof ErrorException) {
                $message = $this->formatMessage($exception->getName());
            } else {
                $message = $this->formatMessage('Exception');
            }

            $message .= $this->formatMessage(" '" . get_class($exception) . "'", [Console::BOLD, Console::FG_BLUE])
                . ' with message ' . $this->formatMessage("'{$exception->getMessage()}'", [Console::BOLD]) //. "\n"
                . "\n\nin " . dirname($exception->getFile()) . DIRECTORY_SEPARATOR . $this->formatMessage(basename($exception->getFile()), [Console::BOLD])
                . ':' . $this->formatMessage($exception->getLine(), [Console::BOLD, Console::FG_YELLOW]) . "\n";
            if ($exception instanceof \yii\db\Exception && !empty($exception->errorInfo)) {
                $message .= "\n" . $this->formatMessage("Error Info:\n", [Console::BOLD]) . print_r($exception->errorInfo, true);
            }

            if ($exception instanceof ServiceException)
            {
                $message .=  "\n" .DXUtil::jsonEncode($exception->getData()) . "\n";
            }

             $message .= "\n" . $this->formatMessage("Stack trace:\n", [Console::BOLD]) . $exception->getTraceAsString();
        } else {
            $message = $this->formatMessage('Error: ') . $exception->getMessage();
        }

        if (PHP_SAPI === 'cli') {
            Console::stderr($message . "\n");
        } else {
            echo $message . "\n";
        }
    }
}