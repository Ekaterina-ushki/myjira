<?php

namespace app\services;
use app\models\ServiceClass;

class ServiceClassService {
    public function findById($id) {
        return ServiceClass::find()
            ->where(['id' => $id])
            ->one();
    }

    public function findByName($name)
    {
        return ServiceClass::find()
            ->where(['name' => $name])
            ->one();
    }
}