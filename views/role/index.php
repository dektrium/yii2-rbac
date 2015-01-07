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
 * @var $dataProvider array
 * @var $this         yii\web\View
 */

use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('rbac', 'Roles');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('/_menu.php') ?>

<div style="padding: 10px 0">
    <?= Html::a(Yii::t('rbac', 'Create new role'), ['/rbac/role/create'], ['class' => 'btn btn-success']) ?>

    <?= Html::a(Yii::t('rbac', 'Create new permission'), ['/rbac/permission/create'], ['class' => 'btn btn-success']) ?>

    <?= Html::a(Yii::t('rbac', 'Create new rule'), ['/rbac/rule/create'], ['class' => 'btn btn-success']) ?>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'      => [
        [
            'attribute' => 'name',
            'header'    => Yii::t('rbac', 'Name'),
        ],
        [
            'attribute' => 'description',
            'header'    => Yii::t('rbac', 'Description'),
        ],
        [
            'attribute' => 'ruleName',
            'header'    => Yii::t('rbac', 'Rule name'),
        ],
        [
            'class'      => ActionColumn::className(),
            'template'   => '{update} {delete}',
            'urlCreator' => function ($action, $model) {
                return Url::to(['/rbac/role/' . $action, 'name' => $model->name]);
            },
        ]
    ],
]) ?>