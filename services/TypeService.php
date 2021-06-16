<?php

namespace app\services;
use app\models\db\Type;
use yii\db\ActiveRecord;
/**
 *
 * TypeService is the service for work with Type ActiveRecord.
 *
 */
class TypeService {
    /**
     * Find and return type by id from db.
     *
     * @param $id Integer
     *
     * @return Type|ActiveRecord
     */
    public function findById($id) {
        return Type::find()
            ->where(['id' => $id])
            ->one();
    }

    /**
     * Find and return type by name from db.
     *
     * @param $name String
     *
     * @return Type|ActiveRecord
     */
    public function findByName($name)
    {
        return Type::find()
            ->where(['name' => $name])
            ->one();
    }
}