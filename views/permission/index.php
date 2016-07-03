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
 * @var $filterModel  dektrium\rbac\models\Search
 */

use kartik\select2\Select2;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = Yii::t('rbac', 'Permissions');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $this->beginContent('@dektrium/rbac/views/layout.php') ?>

<?php Pjax::begin() ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'layout'       => "{items}\n{pager}",
    'columns'      => [
        [
            'attribute' => 'name',
            'header'    => Yii::t('rbac', 'Name'),
            'options'   => [
                'style' => 'width: 20%'
            ],
            'filter' => Select2::widget([
                'model'     => $filterModel,
                'attribute' => 'name',
                'data'      => $filterModel->getNameList(),
                'options'   => [
                    'placeholder' => Yii::t('rbac', 'Select permission'),
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]),
        ],
        [
            'attribute' => 'description',
            'header'    => Yii::t('rbac', 'Description'),
            'options'   => [
                'style' => 'width: 55%',
            ],
            'filterInputOptions' => [
                'class'       => 'form-control',
                'id'          => null,
                'placeholder' => Yii::t('rbac', 'Enter the description')
            ],
        ],
        [
            'attribute' => 'rule_name',
            'header'    => Yii::t('rbac', 'Rule name'),
            'options'   => [
                'style' => 'width: 20%'
            ],
            'filter' => Select2::widget([
                'model'     => $filterModel,
                'attribute' => 'rule_name',
                'data'      => $filterModel->getRuleList(),
                'options'   => [
                    'placeholder' => Yii::t('rbac', 'Select rule'),
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]),
        ],
        [
            'class'      => ActionColumn::className(),
            'template'   => '{update} {delete}',
            'urlCreator' => function ($action, $model) {
                return Url::to(['/rbac/permission/' . $action, 'name' => $model['name']]);
            },
            'options'   => [
                'style' => 'width: 5%'
            ],
        ]
    ],
]) ?>

<?php Pjax::end() ?>

<?php $this->endContent() ?>