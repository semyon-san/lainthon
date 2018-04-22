<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\LoginForm;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller
{
    /**
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function actionLogin()
    {
        $params = Yii::$app->request->post();

        $loginForm = new LoginForm;
        if ($loginForm->load($params, '')) {
            if ($token = $loginForm->login()) {
                return ['token' => $token];
            }
        }

        $errors = $loginForm->getFirstErrors();
        throw new UnauthorizedHttpException(reset($errors));
    }

    public function actionLogout()
    {

    }

    public function verbs()
    {
        return [
            'login' => ['post'],
            'logout' => ['post'],
        ];
    }
}