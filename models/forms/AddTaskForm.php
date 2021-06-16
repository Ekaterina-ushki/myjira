<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * TaskForm is the model behind the task form.
 */
class AddTaskForm extends Model
{
    public $title;
    public $type;
    public $description;
    public $executor;
    public $status;
    public $serviceClass;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['title', 'type', 'status', 'executor', 'serviceClass'], 'required'],
            ['description', 'string'],
        ];
    }
}
