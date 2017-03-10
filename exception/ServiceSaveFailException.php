<?php
/**
 * Created by PhpStorm.
 * User: yangcheng
 * Date: 16/1/31
 * Time: 18:13
 */

namespace app\exceptions;

use dix\base\exception\ServiceException;

class ServiceSaveFailException extends ServiceException
{
    public function __construct($message = '保存失败', $data)
    {
        parent::__construct(ServiceException::ERROR_SAVE_ERROR, $message, $data);
    }

}