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
 * @var $this     yii\web\View
 * @var $content string
 */

use dektrium\rbac\widgets\Menu;

?>

<?= $this->render('/_alert', [
    'module' => Yii::$app->getModule('rbac'),
]) ?>

<?= Menu::widget() ?>

<div style="padding: 10px 0">
    <?= $content ?>
</div>