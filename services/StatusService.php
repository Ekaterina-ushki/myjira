<?php

namespace app\services;
use app\models\db\Status;
use yii\db\ActiveRecord;

/**
 * StatusService is the service for work with Status ActiveRecord.
 */
class StatusService {
    /**
     * Find and return status by id from db.
     *
     * @param $id Integer
     *
     * @return Status|ActiveRecord
     */
    public function findById($id) {
        return Status::find()
            ->where(['id' => $id])
            ->one();
    }
    /**
     * Find and return status by name from db.
     *
     * @param $name String
     *
     * @return Status|ActiveRecord
     */

    public function findByName($name)
    {
        return Status::find()
            ->where(['name' => $name])
            ->one();
    }
}