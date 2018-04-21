<?php

namespace app\controllers;

use conquer\oauth2\TokenAuth;
use \yii\rest\ActiveController;
use \yii\behaviors\TimestampBehavior;

class UserController extends ActiveController
{
    public $modelClass = '\app\models\User';

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
            'tokenAuth' => [
                'class' => TokenAuth::class,
            ],
        ];
    }
}
