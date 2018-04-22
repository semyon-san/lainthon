<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use \yii\behaviors\TimestampBehavior;
//use yii\filters\RateLimitInterface; // TODO later

/**
 * This is the model class for table "User".
 *
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $password
 * @property string $access_token
 * @property int $token_expires_at
 * @property int $created_at
 * @property int $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const TOKEN_EXPIRE_TIME = 60*60*24*7;
    const TOKEN_LENGTH = 40; // sha1

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'User';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'name'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 60],
            [['access_token'], 'string', 'length' => self::TOKEN_LENGTH],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'name' => 'Name',
            'password' => 'Password',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => static::hashToken($token)]);
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @return string
     */
    public function refreshToken()
    {
        $token = static::generateToken();
        $this->access_token = static::hashToken($token);
        $this->token_expires_at = time() + self::TOKEN_EXPIRE_TIME;

        if ($this->update() === false) {
            throw new \yii\db\Exception('could not refresh token');
        }

        return $token;
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    protected static function generateToken()
    {
        return sha1(uniqid(Yii::$app->getSecurity()->generateRandomString(), true));
    }

    /**
     * @param string $token
     * @return string
     */
    protected static function hashToken($token)
    {
        return sha1($token);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findByUsername($username)
    {
        return static::findOne([
            'username' => $username,
        ]);
    }

    /**
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        }
        return false;
    }
}
