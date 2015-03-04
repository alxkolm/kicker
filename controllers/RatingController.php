<?php

namespace app\controllers;

class RatingController extends \yii\web\Controller
{
    public function actionIndex()
    {
//        select
//        user_id,
//        count(distinct game_id) as games,
//        sum(autogoal=0) as goals,
//        sum(autogoal=1) as autogoals
//        from goal
//        group by user_id
//        order by goals desc

        return $this->render('index');
    }

}
