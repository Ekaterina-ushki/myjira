<?php

namespace app\models;

use Yii;
use yii\base\Model;

class AddTaskForm extends Model
{
    public $title;
    public $type;
    public $description;
    public $executor;
    public $status;
    public $serviceClass;


    public function rules()
    {
        return [
            [['title', 'type', 'status', 'executor', 'serviceClass'], 'required'],
            ['description', 'string'],
        ];
    }
}
