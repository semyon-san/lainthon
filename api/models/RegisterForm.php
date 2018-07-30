<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RegisterForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class RegisterForm extends Model
{
    const MIN_PASSWORD_LENGTH = 8;

    public $username;
    public $password;
    public $name;

    private $user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'validateUsername'],
            ['password', 'validatePassword'],
            ['name', 'string', 'max' => 255],
        ];
    }

    public function validateUsername($attribute, $params)
    {
        $this->{$attribute} = trim($this->{$attribute});
        if ($this->{$attribute}) {
            if (User::find()->where(['username' => $this->{$attribute}])->exists()) {
                $this->addError($attribute, 'Username already taken');
            }
        } else {
            $this->addError($attribute, 'Invalid username');
        }
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (strlen($this->{$attribute}) < self::MIN_PASSWORD_LENGTH) {
                $this->addError(
                    $attribute,
                    'Password should be at least ' . self::MIN_PASSWORD_LENGTH . ' characters long.'
                );
            }
        }
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function register()
    {
        if ($this->validate()) {
            $user = new User;
            $user->username = $this->username;
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $user->name = $this->name;

            if (!$user->save()) {
                $this->addErrors($user->getErrors());
            }

            return true;
        }

        return false;
    }
}
