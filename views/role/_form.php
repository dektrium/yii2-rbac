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

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'enableAjaxValidation'   => true,
]) ?>

<?= $form->field($model, 'name')->hint(Yii::t('rbac', 'The name of the item.')) ?>

<?= $form->field($model, 'description')->hint(Yii::t('rbac', 'The item description (Optional).')) ?>

<?= $form->field($model, 'rule')->hint(Yii::t('rbac', 'Classname of the rule associated with this item')) ?>

<?= $form->field($model, 'children')->listBox($model->getUnassignedItems(), ['id' => 'children', 'multiple' => true]) ?>

<?= Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']) ?>

<?php ActiveForm::end() ?>