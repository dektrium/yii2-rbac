<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace dektrium\rbac;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Module extends \yii\base\Module
{
    /** @var bool Whether to show flash messages */
    public $enableFlashMessages = true;

    /** @var string */
    public $defaultRoute = 'role/index';

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        \Yii::$app->get('i18n')->translations['rbac*'] = [
            'class'    => 'yii\i18n\PhpMessageSource',
            'basePath' => __DIR__ . '/messages',
        ];
    }
}