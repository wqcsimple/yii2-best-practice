<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 3/28/17
 * Time: 6:15 PM
 */
namespace app\controllers;

use app\components\DXUtil;
use app\components\TxCosApi;
use app\exceptions\ServiceUploadException;
use dix\base\controller\BaseController;
use dix\base\exception\ServiceErrorNotExistsException;
use dix\base\exception\ServiceErrorWrongParamException;
use qcloudcos\Cosapi;

class CosController extends BaseController 
{
    public function actionUpload($app = 'disk01')
    {
        $bucket_name = TxCosApi::getBucket($app);
        if (!$bucket_name)
        {
            throw new ServiceErrorNotExistsException("app 不存在");
        }

        $filename = $this->uploadFile($bucket_name);

        $this->finishSuccess(['name' => $filename]);
    }

    private function uploadFile($bucket_name, $path_prefix = "file", $mime_types = [], $change_file_name = false)
    {
        $key = 'file';
        list($code, $error) = DXUtil::validateUploadFile($key, $mime_types, 1024 * 1024 * 20);
        if ($code !== 0)
        {
            throw new ServiceErrorWrongParamException('invalid file');
        }

        $path = $_FILES[$key]['tmp_name'];
        $name = $_FILES[$key]['name'];
        $mime_type = DXUtil::getFileMimeType($path);
        if (!$change_file_name) {
            $filename = date('YmdHis') . rand(1000, 9999) . "-" . $name;
        }
        else {
            $filename = $this->makeFileName($name);
        }
        
        TxCosApi::prepare();

   
        $result = Cosapi::upload($bucket_name, $path, $path_prefix . '/' . $filename);
        if (isset($result['code']) && $result['code'] != 0){
            if (isset($result['message'])) {
                
                $message = $result['message'];
                $parse_message = simplexml_load_string($message);
                throw new ServiceUploadException("上传失败", ['error' => $parse_message]);
            }
        }
        
        return $filename;
    }

    protected function makeFileName($name)
    {
        $ext = '';
        if (strrpos($name, '.') !== false)
        {
            $ext = strtolower(substr($name, strrpos($name, '.')));
        }

        return md5(rand(1, 1000000) . time() . $name) . $ext;
    }
}