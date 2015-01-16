<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dektrium\rbac;

use dektrium\rbac\components\DbManager;
use dektrium\rbac\components\ManagerInterface;
use yii\base\BootstrapInterface;

/**
 * Bootstrap class registers translations and needed application components.
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Bootstrap implements BootstrapInterface
{
    /** @inheritdoc */
    public function bootstrap($app)
    {
        // register auth manager
        if (\Yii::$app->authManager == null || (\Yii::$app->authManager instanceof ManagerInterface) == false) {
            \Yii::$app->set('authManager', [
                'class' => DbManager::className(),
            ]);
        }

        // register translations
        \Yii::$app->get('i18n')->translations['rbac*'] = [
            'class'    => 'yii\i18n\PhpMessageSource',
            'basePath' => __DIR__ . '/messages',
        ];
    }
}