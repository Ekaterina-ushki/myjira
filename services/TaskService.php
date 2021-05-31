<?php

namespace app\services;
use app\models\Task;
use Yii;
use yii\db\ActiveRecord;

class TaskService {
    public function addTask($type, $title, $description, $status, $executor, $service_class) {
        $task = new Task();
        $task->type = $type;
        $task->title = $title;
        $task->status = $status;
        $task->author_id = Yii::$app->user->id;
        $task->executor_id = $executor;
        $task->create_date = Yii::$app->formatter->asDateTime('now', 'yyyy-MM-dd H:i:s');
        $task->service_class = $service_class;

        if (!isset($description)) {
            $task->description = "";
        }else {
            $task->description = $description;
        }

        $task->save();
    }

    public function updateTask($id,$type, $title, $description, $status, $executor, $service_class) {
        $task = $this->findById($id);
        $task->type = $type;
        $task->title = $title;
        $task->status = $status;
        $task->executor_id = $executor;
        $task->service_class = $service_class;

        if (!isset($description)) {
            $task->description = "";
        }else {
            $task->description = $description;
        }

        $task->save();
    }

    public function deleteTask($id) {
        $task = $this->findById($id);
        if (isset($task)) {
            $task->delete();
        }
    }

    public function findById($id) {
        return Task::find()
            ->where(['id' => $id])
            ->one();
    }

    public function findByTitle($title) {
        return Task::find()
            ->andWhere(['like', 'title', $title])
            ->all();
    }

    public function find_by($filter, $value) {
        return Task::find()
            ->where([$filter => $value])
            ->all();
    }
}