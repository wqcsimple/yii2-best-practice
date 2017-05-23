<?php

namespace app\components;

use yii\web\Controller;

class BaseApiController extends Controller
{
    public $redis;
    public $params;

    public function checkParams($requiredParams)
    {
        foreach ($requiredParams as $p)
        {
            if (!isset($this->params[$p]))
            {
                $error = [];
                $error['code'] = DXConst::ERROR_PARAM_NOT_SET;
                $error['message'] = 'param ' . $p . ' not set';
                $this->finish($error);
            }
        }
    }

    public function finishError($code, $message, $extra = [])
    {
        $data['code'] = intval($code);
        $data['message'] = $message;
        if (is_array($extra))
        {
            $data = array_merge($data, $extra);
        }

        $this->finish($data);
    }

    public function finish($data)
    {
        header('Content-type:application/json;charset=UTF-8');
        die(json_encode($data));
    }

    


  

}