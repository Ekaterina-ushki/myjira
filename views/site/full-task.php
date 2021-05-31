<?php

use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Описание задачи "' . $task->title . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <a href="/site/update-task?id=<?=$task->id?>"><button type="button" class="btn btn-primary">Update task</button></a>
</div>
<p>Name: <?=Html::encode($task->title)?></p>
<p>Task type: <?=Html::encode($type->name)?></p>
<p>Description: <?=Html::encode($task->description)?></p>
<p>Author: <?=Html::encode($author->login)?></p>
<p>Executor: <?=Html::encode($executor->login)?></p>
<p>Status: <?=Html::encode($status->name)?></p>
<p>Create date: <?=Html::encode($task->create_date)?></p>
<p>Service class: <?=Html::encode($serviceClass->name)?></p>

    <textarea id="comment" type="text" placeholder="Comment"></textarea>
    <script>
        function add_comment() {
            var id = <?=$task->id?>;
            var text = document.getElementById("comment").value;
            window.location.replace("/site/add-comment?id=" + id + "&text=" + text);
        }
    </script>
    <button class="btn btn-primary" onclick="add_comment()">Add</button>
    </br>
<div>
    <a href="/site/delete-task?id=<?=$task->id?>"><button type="button" class="btn btn-primary">Delete task</button></a>
</div>
<?php foreach ($comments as $comment) { ?>
    <div class="flex-container">
        <p><?=$users[Yii::$app->formatter->asInteger($comment->author_id) - 1]->login?></p>
        <p><?=$comment->text?></p>
        <p><?=$comment->create_date?></p>
    </div>
<?php } ?>