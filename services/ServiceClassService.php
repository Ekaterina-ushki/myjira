<?php

namespace app\services;
use app\models\db\ServiceClass;
use yii\db\ActiveRecord;

/**
 * ServiceClassService is the service for work with ServiceClass ActiveRecord.
 */
class ServiceClassService {
    /**
     * Find and return servce class by id from db.
     *
     * @param $id Integer
     *
     * @return ServiceClass|ActiveRecord
     */
    public function findById($id) {
        return ServiceClass::find()
            ->where(['id' => $id])
            ->one();
    }

    /**
     * Find and return servce class by id from db.
     *
     * @param $name String
     *
     * @return ServiceClass|ActiveRecord
     */
    public function findByName($name)
    {
        return ServiceClass::find()
            ->where(['name' => $name])
            ->one();
    }
}