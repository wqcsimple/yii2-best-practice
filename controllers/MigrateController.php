<?php

namespace app\controllers;

use app\models\Project;
use app\modules\client\v100\services\ProjectMemberService;
use dix\base\component\BaseController;
use Yii;
use yii\web\NotFoundHttpException;


class MigrateController extends BaseController
{
    public function actionProjectMemberInit()
    {
        $project_list = Project::find()->where('weight >= 0')->asArray()->all();
        foreach ($project_list as $project)
        {
            try
            {
                ProjectMemberService::addProjectMember($project['user_id'], $project['org_root_id'], $project['id'], $project['user_id']);

            }
            catch (\Exception $e)
            {
                dump($e->getMessage());
                dump($project);
            }
        }

    }
}