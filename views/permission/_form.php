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

use dektrium\rbac\events\PermissionFormEvent;
use dektrium\rbac\RbacWebModule;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;


$event = new PermissionFormEvent();

?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'enableAjaxValidation'   => true,
]) ?>

<?= $form->field($model, 'name') ?>

<?= $form->field($model, 'description')->textarea() ?>

<?= $form->field($model, 'rule')->widget(Select2::className(), [
    'options'   => [
        'placeholder' => Yii::t('rbac', 'Select rule'),
    ],
    'pluginOptions' => [
        'ajax' => [
            'url'  => Url::to(['/rbac/rule/search']),
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
        'allowClear' => true,
    ],
]) ?>

<?php if ($model->dataCannotBeDecoded): ?>
    <div class="alert alert-info">
        <?= Yii::t('rbac', 'Data cannot be decoded') ?>
    </div>
<?php else: ?>
    <?= $form->field($model, 'data')->textarea([
        'rows' => 3
    ]) ?>
<?php endif ?>

<?= $form->field($model, 'children')->widget(Select2::className(), [
    'data' => $model->getUnassignedItems(),
    'options' => [
        'id' => 'children',
        'multiple' => true
    ],
]) ?>

<?php
$this->context->module->trigger(RbacWebModule::EVENT_PERMISSION_FORM, $event);

if (!empty($event->renderViews)) {
    foreach ($event->renderViews as $view) {
        echo $this->render($view, ['form' => $form, 'model' => $model]);
    }
}
?>

<?= Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']) ?>

<?php ActiveForm::end() ?>