<?php

namespace app\controllers;

use app\components\DXConst;
use app\components\DXUtil;
use app\components\SinaScsApi;
use dix\base\controller\BaseController;
use dix\base\exception\ServiceErrorWrongParamException;

class StaticController extends BaseController
{
    public function actionUpload($app = 'disc01')
    {
        $bucket_name = SinaScsApi::getBucket($app);
        if (!$bucket_name)
        {
            throw new ServiceErrorWrongParamException('app 不存在');    
        }
        
        $filename = $this->uploadFile($bucket_name);

        $this->finishSuccess(['name' => $filename]);
    }
    
    private function uploadFile($bucket_name, $path_prefix = 'file', $mime_types = [])
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
        $filename = $this->makeFileName($name);
        
        SinaScsApi::prepare();
        \SCS::setExceptions(true);
        try
        {
//            $object = "$path_prefix/$filename";
            $object = $name;
            \SCS::putObject(\SCS::inputFile($path, false), $bucket_name, $object, \SCS::ACL_PUBLIC_READ);
        }
        catch (\SCSException $e)
        {
            echo $e->getMessage();
            die;
        }
        
        return $name;
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