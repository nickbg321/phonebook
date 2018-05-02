<?php

/** @var $model \app\models\form\LoginForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Sign in';

$form = ActiveForm::begin([
    'validateOnBlur' => false,
]);
?>

<div class="login-form">
    <h3>Phonebook</h3>

    <div class="well well-md">
        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Username'])->label(false); ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label(false); ?>
        <?= $form->field($model, 'rememberMe')->checkbox(); ?>
        <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
