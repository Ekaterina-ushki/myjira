<?php


namespace app\models;

use yii\db\ActiveRecord;

class ServiceClass extends ActiveRecord
{
    /**
     * @inheritdoc
     */

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}