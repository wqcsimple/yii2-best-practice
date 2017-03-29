<?php
/**
 * Created by PhpStorm.
 * User: yangcheng
 * Date: 16/1/31
 * Time: 18:13
 */

namespace app\exceptions;

class ServiceUploadException extends ServiceException
{
    public function __construct($message = '上传失败', $data = [])
    {
        parent::__construct(ServiceException::ERROR_UPLOAD_FAIL, $message, $data);
    }

}