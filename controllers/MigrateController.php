<?php

namespace app\controllers;

use dix\base\controller\BaseController;

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