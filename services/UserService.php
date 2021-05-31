<?php

namespace app\services;
use app\models\User;
use Yii;
use yii\db\ActiveRecord;

class UserService {
    public function findById($id)
    {
        return User::find()
                ->where(['id'=> $id])
                ->one();
    }

    public function addUser($login, $email, $password)
    {
        $user = new User();
        $user->login = $login;
        $user->email = $email;
        $user->password = $password;
        $user->create_date = Yii::$app->formatter->asDateTime('now', 'yyyy-MM-dd H:i:s');

        $user->save();
    }
}