<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['id' => 'reg-form']); ?>

<?= $form->field($model, 'login')->textInput(['autofocus' => true]) ?>

<?= $form->field($model, 'email')->textInput([]) ?>

<?= $form->field($model, 'password')->passwordInput([]) ?>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'reg-button']) ?>
    </div>


<?php ActiveForm::end(); ?>