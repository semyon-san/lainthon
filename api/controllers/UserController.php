<?php

namespace app\controllers;

use \yii\rest\ActiveController;
use \yii\filters\auth\HttpBearerAuth;

class UserController extends ActiveController
{
    public $modelClass = '\app\models\User';

    public function behaviors()
    {
        return [
            'bearerAuth' => [
                'class' => HttpBearerAuth::class,
            ],
        ];
    }
}
