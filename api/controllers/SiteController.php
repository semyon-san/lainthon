<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $user = Yii::$app->user->getIdentity();
        return $this->renderPartial('index', ['user' => $user]);
    }

    public function behaviors()
    {
        return [
            'bearerAuth' => [
                'class' => HttpBearerAuth::class,
            ],
        ];
    }
}
