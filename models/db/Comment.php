<?php

namespace app\models\db;

use yii\db\ActiveRecord;

/**
 * ActiveRecord comment class.
 */
class Comment extends ActiveRecord
{
    /**
     * Method get comment id.
     *
     * @return Integer
     */
    public function getId()
    {
        return $this->id;
    }
}