<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/26/16
 * Time: 4:03 PM
 */
namespace app\modules\admin\v100\services;

use app\components\DXConst;
use app\components\DXUtil;
use app\models\Contact;
use dix\base\exception\ServiceErrorNotExistsException;
use dix\base\exception\ServiceErrorSaveException;

class ContactService
{
    public static function saveContact($contact_id, $content, $name, $phone, $email)
    {
        $db_contact = Contact::findById($contact_id);
        if (!$db_contact)
        {
            $db_contact = new Contact();
            $db_contact->weight = DXConst::WEIGHT_NORMAL;
        }

        $db_contact->content = $content;
        $db_contact->name = $name;
        $db_contact->phone = $phone;
        $db_contact->email = $email;
        if (!$db_contact->save())
        {
            throw new ServiceErrorSaveException('save error', ['errors' => $db_contact->errors]);
        }

        return $db_contact->id;
    }
    
    public static function getContactList($_user_id, $page, $begin_date, $end_date, $name)
    {
        $query = Contact::find()->where(' weight >= 0 ');
        if ($begin_date)
        {
            $query = $query->andWhere(['>=', 'create_time', strtotime($begin_date)]);
        }
        if ($end_date)
        {
            $query = $query->andWhere(['<=', 'create_time', strtotime($end_date)]);
        }
        if ($name)
        {
            $query = $query->andWhere(['or', ['like', 'first_name', $name], ['like', 'last_name', $name]]);
        }

        $page = $page < 1 ? 1 : intval($page);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $count = intval($query->count());
        $db_contact_list = $query->orderBy(['create_time' => SORT_DESC])->limit($limit)->offset($offset)->asArray()->all();
        $contact_list = DXUtil::formatModelList($db_contact_list, Contact::className());
        
        return [
            'begin_date' => $begin_date,
            'end_date' => $end_date,
            'name' => $name,
            'count' => $count,
            'list' => $contact_list
        ];
    }
    
    public static function removeContact($_user_id, $contact_id)
    {
        $db_contact = Contact::findById($contact_id);
        if (!$db_contact)
        {
            throw new ServiceErrorNotExistsException();
        }
        
        $db_contact->weight = DXConst::WEIGHT_DELETED;
        if (!$db_contact->save())
        {
            throw new ServiceErrorSaveException('save error', ['errors' => $db_contact->errors]);
        }
    }

    public static function getContactDetail($_user_id, $contact_id)
    {
        return Contact::processRaw(Contact::findById($contact_id));
    }
    
}