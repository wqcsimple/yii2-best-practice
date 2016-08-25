<?php
/**
 * Created by PhpStorm.
 * User: dd
 * Date: 5/27/15
 * Time: 15:33
 */
namespace app\modules\client\v100;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        defined('IS_IN_EXPRESS_CLIENT') or define('IS_IN_EXPRESS_CLIENT', true);
    }
}
