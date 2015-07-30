<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var $this  yii\web\View
 * @var $model dektrium\rbac\models\Role
 */

use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'enableAjaxValidation'   => true,
]) ?>

<?= $form->field($model, 'name')->label(Yii::t('rbac', 'Name')) ?>

<?= $form->field($model, 'description')->label(Yii::t('rbac', 'Description')) ?>

<?= $form->field($model, 'rule')->label(Yii::t('rbac', 'Rule')) ?>

<?= $form->field($model, 'children')->label(Yii::t('rbac', 'Children'))->widget(Select2::className(), [
    'data' => $model->getUnassignedItems(),
    'options' => [
        'id' => 'children',
        'multiple' => true
    ],
]) ?>

<?= Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']) ?>

<?php ActiveForm::end() ?>