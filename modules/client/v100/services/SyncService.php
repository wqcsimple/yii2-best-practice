<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 2018/6/20
 * Time: 17:06
 */
namespace app\modules\client\v100\services;


use app\components\WxNotify;
use app\exceptions\ServiceSaveFailException;
use app\models\KyData;
use yii\helpers\ArrayHelper;

class SyncService {

    /**
     * http://dc/client/100/sync/role-sync?token=b298a2b98d567962a15a8b919eb30523&client=1&version=1&data=%7B%22ItemId%22%3A%22041D2F2200000000FFFF0000019071A4%22%2C%22ItemName%22%3A%22%E8%A7%92%E8%89%B2%3A%E5%AD%A4%E5%BD%B1%EE%A0%A2%E5%8D%8A%E5%9F%8E%22%2C%22SellerUin%22%3A%22573512964%22%2C%22SellerNick%22%3A%22%22%2C%22ItemPic%22%3A%22http%3A%2F%2Fvstatic.gtimg.com%2Fstatic%2Fimg%2Fgameapi%2Fapiitemimg%2Fffo%2F1.jpg%22%2C%22ItemPrice%22%3A%22110000%22%2C%22ItemLevel%22%3A%22100%22%2C%22UnitPrice%22%3A%220%22%2C%22ItemState%22%3A%222%22%2C%22PublicDuration%22%3A%22604800%22%2C%22SellDuration%22%3A%220%22%2C%22AddTime%22%3A%222018-06-11%2014%3A15%3A40%22%2C%22LastModifiedTime%22%3A%222018-06-18%2014%3A16%3A14%22%2C%22ClassId%22%3A%22303844%22%2C%22DetailProperty%22%3A%228%22%2C%22Property%22%3A%220%22%2C%22Extra1%22%3A%220%22%2C%22Extra2%22%3A%22%22%7D
     * @param $role
     * @param $data
     * @throws ServiceSaveFailException
     */
    public static function roleSync($role, $data)
    {
        $ky_data_origin = json_decode($data, true);

        $item_id = ArrayHelper::getValue($ky_data_origin, "ItemId", "");
        $item_name = ArrayHelper::getValue($ky_data_origin, "ItemName", "");
        $item_price = intval(ArrayHelper::getValue($ky_data_origin, "ItemPrice", 0));
        $item_level = intval(ArrayHelper::getValue($ky_data_origin, "ItemLevel", 0));
        $item_add_time = ArrayHelper::getValue($ky_data_origin, "AddTime", "");
        $item_modified_time = ArrayHelper::getValue($ky_data_origin, "LastModifiedTime", "");


        if (empty($item_id)) {
            return;
        }
        $ky_data = KyData::find()->where("weight >= 0")->andWhere(['role' => $role, "item_id" => $item_id])->one();
        if (!$ky_data) {
            $ky_data = new KyData();
        }

        $ky_data->role = $role;
        $ky_data->item_id = $item_id;
        $ky_data->item_name = $item_name;
        $ky_data->level = $item_level;
        $ky_data->price = $item_price;
        $ky_data->add_time = $item_add_time;
        $ky_data->modified_time = $item_modified_time;
        $ky_data->data = $data;

        /**
         * 如果发布时间是今天就通知
         */
        if (strtotime($ky_data->add_time) > strtotime('-3 day')) {
            WxNotify::send($item_name, "http://www.kuyoo.com/ffo/item_info.shtml?strItemId=" . $item_id);
        }

        if (!$ky_data->save())
        {
            throw new ServiceSaveFailException("save error", ['errors' => $ky_data->errors]);
        }
    }


}