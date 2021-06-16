<?php

namespace app\models\db;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * ActiveRecord user class.

 */
class User extends ActiveRecord implements IdentityInterface
{

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return User::find()
            ->where(['login' => $username])
            ->one();
    }

    public function validatePassword($password) {
        return $password == User::find()
                ->where(['login'=> $this->login])
                ->one()
                ->password;
    }

    /**
     * Method get user id.
     *
     * @return Integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Method get user login.
     *
     * @return String
     */
    public function getLogin()
    {
        return $this->login;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
}