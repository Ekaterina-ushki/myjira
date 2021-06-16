<?php

namespace app\models\forms;

use yii\base\Model;

/**
 * RegistrationForm is the model behind the registration form.
 */
class RegistrationForm extends Model
{
    public $login;
    public $email;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['login', 'password', 'email'], 'required'],
            ['email', 'email'],
        ];
    }
}
