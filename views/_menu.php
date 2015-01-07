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
 * @var $this yii\web\View
 */

use yii\bootstrap\Nav;
use yii\helpers\Html;

?>

<?= $this->render('/_alert', [
    'module' => Yii::$app->getModule('rbac'),
]) ?>

<?= Nav::widget([
    'options' => [
        'class' => 'nav nav-tabs',
    ],
    'items' => [
        [
            'label' => Yii::t('rbac', 'Roles'),
            'url'   => ['/rbac/role/index'],
        ],
        [
            'label' => Yii::t('rbac', 'Permissions'),
            'url'   => ['/rbac/permission/index'],
        ],
        [
            'label' => Yii::t('rbac', 'Rules'),
            'url'   => ['/rbac/rules/index'],
        ],
        [
            'label' => Yii::t('rbac', 'Assignments'),
            'url'   => ['/rbac/assignment/index'],
        ],
    ],
]) ?>
