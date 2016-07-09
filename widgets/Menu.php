<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace dektrium\rbac\widgets;

use yii\bootstrap\Nav;

/**
 * Menu widget.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Menu extends Nav
{
    /**
     * @inheritdoc
     */
    public $options = [
        'class' => 'nav-tabs'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $userModuleClass       = 'dektrium\user\Module';
        $isUserModuleInstalled = \Yii::$app->getModule('user') instanceof $userModuleClass;

        $this->items = [
            [
                'label'   => \Yii::t('rbac', 'Users'),
                'url'     => ['/user/admin/index'],
                'visible' => $isUserModuleInstalled,
            ],
            [
                'label' => \Yii::t('rbac', 'Roles'),
                'url'   => ['/rbac/role/index'],
            ],
            [
                'label' => \Yii::t('rbac', 'Permissions'),
                'url'   => ['/rbac/permission/index'],
            ],
            [
                'label' => \Yii::t('rbac', 'Rules'),
                'url'   => ['/rbac/rule/index'],
            ],
            [
                'label' => \Yii::t('rbac', 'Create'),
                'items' => [
                    [
                        'label'   =>\ Yii::t('rbac', 'New user'),
                        'url'     => ['/user/admin/create'],
                        'visible' => $isUserModuleInstalled,
                    ],
                    [
                        'label' => \Yii::t('rbac', 'New role'),
                        'url'   => ['/rbac/role/create']
                    ],
                    [
                        'label' => \Yii::t('rbac', 'New permission'),
                        'url'   => ['/rbac/permission/create']
                    ],
                    [
                        'label' => \Yii::t('rbac', 'New rule'),
                        'url'   => ['/rbac/rule/create']
                    ]
                ]
            ],
        ];
    }
}