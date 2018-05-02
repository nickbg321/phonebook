<?php

/** @var $contactForm \app\models\form\ContactForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Modal;

$form = ActiveForm::begin([
    'id' => 'contact-form',
    'validateOnBlur' => false,
    'validateOnChange' => false,
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
]);

Modal::begin([
    'header' => $contactForm->getContactData()->isNewRecord ?
        'Add new contact' : 'Edit contact ID ' . $contactForm->getContactData()->id,
]);
?>

<?= $form->field($contactForm, 'firstName')->textInput(); ?>
<?= $form->field($contactForm, 'lastName')->textInput(); ?>
<?= $form->field($contactForm, 'phoneNumber')->textInput(); ?>
<?= $form->field($contactForm, 'note')->textInput(); ?>

<?= Html::submitButton($contactForm->getContactData()->isNewRecord ? 'Create' : 'Save',
    ['class' => 'btn btn-primary', 'name' => 'create-button']) ?>

<?php
Modal::end();
ActiveForm::end();
