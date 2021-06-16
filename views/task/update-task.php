<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['id' => 'update-form']); ?>

<?= $form->field($model, 'title')->textInput(['autofocus' => true]) ?>

<?= $form->field($model, 'type')->dropDownList(
    ArrayHelper::map($types, 'id', 'name')
) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'executor')->dropDownList(
    ArrayHelper::map($users, 'id', 'login')
) ?>
<?= $form->field($model, 'status')->dropDownList(
    ArrayHelper::map($statuses, 'id', 'name')
) ?>
<?= $form->field($model, 'serviceClass')->dropDownList(
    ArrayHelper::map($serviceClasses, 'id', 'name')
) ?>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'upd-button']) ?>
</div>

<?php ActiveForm::end(); ?>
