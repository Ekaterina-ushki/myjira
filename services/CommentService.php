<?php

namespace app\services;
use app\models\Comment;
use Yii;
use yii\db\ActiveRecord;


class CommentService {
    public function addComment($task_id, $text) {
        $comment = new Comment();
        $comment->task_id = $task_id;
        $comment->author_id = Yii::$app->user->id;
        $comment->create_date = Yii::$app->formatter->asDateTime('now', 'yyyy-MM-dd H:i:s');

        if (!isset($text)) {
            $comment->text = "";
        }else {
            $comment->text = $text;
        }

        $comment->save();
    }

    public function findById($id) {
        return Comment::find()
            ->where(['id' => $id])
            ->one();
    }

    public function findByTaskId($id)
    {
        return Comment::find()
            ->where(['task_id' => $id])
            ->all();
    }
}