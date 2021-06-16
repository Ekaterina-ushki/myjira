<?php

/* @var $this yii\web\View */

use app\models\db\Task;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

$this->title = 'Public tasks';
?>
<div>
    <a href="/task/add-task"><button type="button" class="btn btn-primary">Add task</button></a>
</div>
<div class="site-tasks">
    <h1><?= Html::encode($this->title) ?></h1>
        <?php

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                // Simple columns defined by the data contained in $dataProvider.
                // Data from the model's column will be used.
                [
                    'value' => function ($task) {
                        return Html::a(Html::encode($task->title), Url::to(['full-task', 'id' => $task->id]));
                    },
                    'label' => 'Title',
                    'format' => 'raw',
                    'attribute' => 'title'
                ],
                [
                    'class' => 'yii\grid\DataColumn',
                    'value' => function ($task) use ($types) {
                        return $types[$task->type - 1]->name;
                    },
                    'label' => 'Task type',
                    'attribute' => 'type',
                    'filter' => Html::activeDropDownList($searchModel, 'type', ArrayHelper::map($types, 'id', 'name'),['class'=>'form-control','prompt' => 'Select Type']),
                ],
                [
                    'value' => function ($task) use ($users){
                        return $users[$task->author_id - 1]->login;
                    },
                    'label' => 'Author',
                    'attribute' => 'author'
                ],
                [
                    'value' => function ($task) use ($users){
                        return $users[$task->executor_id - 1]->login;
                    },
                    'label' => 'Executor',
                    'attribute' => 'executor'
                ],
                [
                    'value' => function ($task) use ($statuses){
                        return $statuses[$task->status - 1]->name;
                    },
                    'label' => 'Status',
                    'attribute' => 'status',
                    'filter' => Html::activeDropDownList($searchModel, 'status', ArrayHelper::map($statuses, 'id', 'name'),['class'=>'form-control','prompt' => 'Select Status']),
                ],
                [
                    'value' => function ($task) use ($serviceClasses){
                        return $serviceClasses[$task->service_class - 1]->name;
                    },
                    'label' => 'Service Class',
                    'attribute' => 'service_class',
                    'filter' => Html::activeDropDownList($searchModel, 'service_class', ArrayHelper::map($serviceClasses, 'id', 'name'),['class'=>'form-control','prompt' => 'Select Service Class']),
                ],
                [
                    'value' => function ($task){
                        return $task->create_date;
                    },
                    'label' => 'Create date',
                    'attribute' => 'create_date'
                ],
            ],
        ]);
        ?>
</div>


