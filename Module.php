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

use Yii;
use yii\base\Module as BaseModule;
use yii\filters\AccessControl;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Module extends BaseModule
{
    /** @var bool Whether to show flash messages */
    public $enableFlashMessages = true;

    /** @var string */
    public $defaultRoute = 'role/index';
    
    /** @var array */
    public $admins = [];
    
    /**
     * use for RBAC
     * @var string RBAC admin role name
     */
    public $adminRole = false;
    
    /**
     * use for RBAC
     * @var string RBAC user admin role name
     */
    public $userAdminRole = false;

    /**
     * use for RBAC
     * @var string RBAC user view role name
     */
    public $userViewRole = false;    
    
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->getIsAdmin();
                        },
                    ]
                ],
            ],
        ];
    }
}