<?php

namespace app\models;

use yii\base\Model;

class RegistrationForm extends Model
{
    public $login;
    public $email;
    public $password;


    public function rules()
    {
        return [
            [['login', 'password', 'email'], 'required'],
            ['email', 'email'],
        ];
    }
}
