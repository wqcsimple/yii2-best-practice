<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 2/17/17
 * Time: 10:36 AM
 */
namespace app\controllers;

use app\components\DXUtil;
use app\components\QiniuOssApi;
use dix\base\controller\BaseController;
use dix\base\exception\ServiceErrorNotExistsException;
use dix\base\exception\ServiceErrorWrongParamException;
use Qiniu\Storage\UploadManager;

class QiniuController extends BaseController
{
    public function actionUpload($app = 'simplelife')
    {
        $bucket_name = QiniuOssApi::getBucket($app);
        if (!$bucket_name)
        {
            throw new ServiceErrorNotExistsException("app 不存在");
        }
     
        $filename = $this->uploadFile($bucket_name);
        
        $this->finishSuccess(['name' => $filename]);
    }
    
    public function actionFileInfo($app = 'simplelife')
    {
        $file_name = app()->request->get('file_name');
        $bucket_name = QiniuOssApi::getBucket($app);
        if (!$bucket_name)
        {
            throw new ServiceErrorNotExistsException("app 不存在");
        }
        
        $res = QiniuOssApi::getObjectInfo($bucket_name, $file_name);
        
        dump($res);
    }
    
    public function actionFileList($app = 'simplelife')
    {
        $bucket_name = QiniuOssApi::getBucket($app);
        $file_list = QiniuOssApi::getObjectListByBucket($bucket_name, "", "", 10);
        dump($file_list);
    }

    private function uploadFile($bucket_name, $path_prefix = "file", $mime_types = [])
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
        
        $auth = QiniuOssApi::prepare();
        $upload_token = $auth->uploadToken($bucket_name);

        // 构建 UploadManager 对象
        $upload_manager = new UploadManager();
        
        list($result, $err) = $upload_manager->putFile($upload_token, $filename, $path);
        if ($err !== null) {
            dump($err);
            die;
        } else {
            
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