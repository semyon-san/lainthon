<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\LoginForm;
use app\models\RegisterForm;
use yii\filters\auth\HttpBearerAuth;
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
        return ['success' => Yii::$app->user->logout()];
    }

    /**
     * @return array
     */
    public function actionRegister()
    {
        $params = Yii::$app->request->post();

        $success = false;
        $errors = [];

        $registerForm = new RegisterForm;
        if ($registerForm->load($params, '')) {
            $success = $registerForm->register();
            if (!$success) {
                $errors = $registerForm->getFirstErrors();
            }
        }

        return [
            'success' => $success,
            'errors' => $errors,
        ];
    }

    public function behaviors()
    {
        return [
            'bearerAuth' => [
                'class' => HttpBearerAuth::class,
                'optional' => ['login', 'register'],
            ],
        ];
    }

    public function verbs()
    {
        return [
            'login' => ['post'],
            'logout' => ['post'],
            'register' => ['post'],
        ];
    }
}