<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 7/28/16
 * Time: 12:13 PM
 */
namespace app\controllers;

use app\components\BaseApiController;
use app\components\DXConst;
use dix\base\exception\ServiceErrorNotExistsException;
use Upload\File;
use Upload\Storage\FileSystem;
use Upload\Validation\Mimetype;
use Upload\Validation\Size;
use yii\caching\DummyCache;

class ApiController extends BaseApiController
{
    public function beforeAction($action) 
    {
        $ok = parent::beforeAction($action);

        header("Access-Control-Allow-Origin: *");

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            {
                header("Access-Control-Allow-Methods: POST, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }

            exit(0);
        }
        
        return $ok;
    }
    
    public function actionImgUpload()
    {
        $data = ['code' => 0];
        
        $path = 'file/img';
        
        if (!file_exists($path))
        {
            throw new ServiceErrorNotExistsException();
        }
        
        $storage = new FileSystem($path);
        $file = new File('file', $storage);
        
        $file_name = $file->getName();
        
        $file->setName($this->makeFileName($file_name));
        
        $file->addValidations(
            [
                new Mimetype(['image/png', 'image/jpg', 'image/jpeg']),
                new Size('10M'),
            ]
        );
        
        $error = null;
        try 
        {
            $file->upload();
        }
        catch (\Exception $e) 
        {
            $error = $file->getErrors();
        }
        
        if ($error !== null)
        {
            $data['code'] = 1;
            $data['message'] = implode(';', $error);
        }
        else
        {
            $data['name'] = $file->getNameWithExtension();
        }
        
        $this->finish($data);
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