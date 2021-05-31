<?php

namespace app\models;

use yii\db\ActiveRecord;

class Comment extends ActiveRecord
{
    public function getId()
    {
        return $this->id;
    }
}