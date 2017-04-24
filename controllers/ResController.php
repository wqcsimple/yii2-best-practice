<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 4/24/17
 * Time: 12:21 PM
 */
namespace app\controllers;

use dix\base\controller\BaseController;
use Endroid\QrCode\QrCode;

class ResController extends BaseController
{
    public function actionQr($code, $fc = 0x000000)
    {
        $code = urldecode($code);

        header('Content-type:image/png;');
        $qrCode = new QrCode();
        $qrCode
            ->setText($code)
            ->setSize(1000)
            ->setPadding(20)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->render()
        ;
        die();
    }
}