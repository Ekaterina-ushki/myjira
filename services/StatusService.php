<?php

namespace app\services;
use app\models\Status;
use yii\db\ActiveRecord;

class StatusService {
    public function findById($id) {
        return Status::find()
            ->where(['id' => $id])
            ->one();
    }

    public function findByName($name)
    {
        return Status::find()
            ->where(['name' => $name])
            ->one();
    }
}