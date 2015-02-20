<?php

/* 
 * This file is part of the Dektrium project
 * 
 * (c) Dektrium project <http://github.com/dektrium>
 * 
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php if ($model->updated): ?>

<?= Alert::widget([
    'options' => [
        'class' => 'alert-success'
    ],
    'body' => Yii::t('rbac', 'Assignments have been updated'),
]) ?>

<?php endif ?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'enableAjaxValidation'   => false,
]) ?>

<?= Html::activeHiddenInput($model, 'user_id') ?>

<?= $form->field($model, 'items')->listBox($model->getAvailableItems(), ['id' => 'items', 'multiple' => true]) ?>

<?= Html::submitButton(Yii::t('rbac', 'Update assignments'), ['class' => 'btn btn-success btn-block']) ?>

<?php ActiveForm::end() ?>

